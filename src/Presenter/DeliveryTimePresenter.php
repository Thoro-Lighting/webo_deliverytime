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

    public function present($idCarrierReference = null, $cart = null):array {

        if(empty($idCarrierReference) && empty($cart)) {
            return [];
        }

        if(!empty($idCarrierReference)) {
            $this->carrierDeliveryTime = $this->getCarrierDeliveryTime($idCarrierReference);
        }

        if(!empty($cart)) {
            $this->cartProductsDeliveryTime = $this->getMaxDeliveryTimeByProducts($cart['products']);
        }

        return [
            'carrierDeliveryTime' => $this->carrierDeliveryTime,
            'cartProductsDeliveryTime' => $this->cartProductsDeliveryTime,
            'deliveryTimeSum' => $this->carrierDeliveryTime + $this->cartProductsDeliveryTime
        ];
    }

    private function getCarrierDeliveryTime(int $idCarrierReference)
    {
        $deliveryTimeShippingItem = $this->entityRepository->findOneBy([
            'idCarrierReference' => $idCarrierReference,
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