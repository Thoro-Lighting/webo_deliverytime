<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Presenter;

use StockAvailable;
use WeboDeliveryTime\Model\DeliveryTimeProduct;
use WeboDeliveryTime\Repository\Doctrine\DeliveryTimeShippingRepository;

class DeliveryTimePresenter {
    private $entityRepository;

    private $carrierDeliveryTime = 0;
    private $cartProductsDeliveryTime = 0;

    private $cachedTimeProducts = [];
    public function __construct(DeliveryTimeShippingRepository $entityRepository) {
            $this->entityRepository = $entityRepository;
    }

    public function present($idCarrier = null, $cart = null): array
    {
        if (empty($idCarrier) && empty($cart)) {
            return [
                'carrierDeliveryTime' => null,
                'cartProductsDeliveryTime' => null,
                'deliveryTimeSum' => null
            ];
        }

        $carrierDeliveryTime = null;
        $cartProductsDeliveryTime = null;

        if (!empty($idCarrier)) {
            $carrierDeliveryTime = $this->getCarrierDeliveryTime($idCarrier);
        }

        if (!empty($cart)) {
            $cartProductsDeliveryTime = $this->getMaxDeliveryTimeByProducts($cart['products']);
        }

        $deliveryTimeSum = null;
        if ($carrierDeliveryTime !== null && $cartProductsDeliveryTime !== null) {
            $deliveryTimeSum = $carrierDeliveryTime + $cartProductsDeliveryTime;
        }

        return [
            'carrierDeliveryTime' => $carrierDeliveryTime,
            'cartProductsDeliveryTime' => $cartProductsDeliveryTime,
            'deliveryTimeSum' => $deliveryTimeSum,
        ];
    }


    private function getCarrierDeliveryTime(int $idCarrierReference)
    {
        $deliveryTimeShippingItem = $this->entityRepository->findOneBy([
            'idCarrier' => $idCarrierReference,
        ]);

        if ($deliveryTimeShippingItem !== null) {
            return (int) $deliveryTimeShippingItem->getDeliveryTime();
        }

        return 0;
    }

    private function getMaxDeliveryTimeByProducts(array $products): int {
        $maxDeliveryTime = 0;

        $productIds = array_map(function ($product) {
            return $product->id;
        }, $products);

        $cacheKey = implode('|', $productIds);

        if(empty($this->cachedTimeProducts[$cacheKey])) {
            $maxWhenOnStockData = DeliveryTimeProduct::getByProductsIds($productIds);

            if(!empty($maxWhenOnStockData)) {
                foreach ($products as $product) {
                    $onStock = StockAvailable::getQuantityAvailableByProduct($product->id, $product->id_product_attribute) > 0;

                    foreach ($maxWhenOnStockData as $data) {
                        if ($data['id_product'] == $product->id) {
                            $deliveryTimeData = $data;
                            break;
                        }
                    }

                    if ($deliveryTimeData) {
                        if ($onStock) {
                            $maxDeliveryTime = max($maxDeliveryTime, (int)$deliveryTimeData['delivery_time_on_stock']);
                        } else {
                            $maxDeliveryTime = max($maxDeliveryTime, (int)$deliveryTimeData['delivery_time_out_of_stock']);
                        }
                    }
                }

                $this->cachedTimeProducts[$cacheKey] = $maxDeliveryTime;
            }

            return $maxDeliveryTime;
        }

        return $this->cachedTimeProducts[$cacheKey];
    }
}