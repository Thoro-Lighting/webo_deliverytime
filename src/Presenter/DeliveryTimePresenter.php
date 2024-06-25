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
            if(is_object($cart)) {
                $products = $cart->getProducts();
            } else {
                $products = $cart['products'];
            }

            $cartProductsDeliveryTime = $this->getMaxDeliveryTimeByProducts($products);
        }

        $deliveryTimeSum = 0;

        if ($carrierDeliveryTime !== null) {
            $deliveryTimeSum = $carrierDeliveryTime;
        }

        if( $cartProductsDeliveryTime !== null) {
            $deliveryTimeSum += $cartProductsDeliveryTime;
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
            return is_object($product) ? $product->id : $product['id_product'];
        }, $products);

        $cacheKey = implode('|', $productIds);

        if(empty($this->cachedTimeProducts[$cacheKey])) {
            $maxWhenOnStockData = DeliveryTimeProduct::getByProductsIds($productIds);

            if(!empty($maxWhenOnStockData)) {
                foreach ($products as $product) {
                    $productId = is_object($product) ? $product->id : $product['id_product'];
                    $productIdAttribute = is_object($product) ? $product->id_attribute : $product['id_product_attribute'];

                    $onStock = StockAvailable::getQuantityAvailableByProduct($productId, $productIdAttribute) > 0;

                    foreach ($maxWhenOnStockData as $data) {
                        if ($data['id_product'] == $productId) {
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