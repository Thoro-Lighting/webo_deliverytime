<?php

namespace WeboDeliveryTime\Hook;

use WeboDeliveryTime\Model\DeliveryTimeProduct;

class DisplayAdminProductsExtra extends AbstractHook
{
    public function execute(array $params): string
    {
        $deliveryTimeProduct = DeliveryTimeProduct::getAllDataByProductId(
            $params['id_product']
        );

        $this->context->smarty->assign([
            'delivery_time_on_stock' => $deliveryTimeProduct['delivery_time_on_stock'] ?? null,
            'delivery_time_out_of_stock' => $deliveryTimeProduct['delivery_time_out_of_stock'] ?? null,
        ]);

        return $this->module->fetch("module:{$this->module->name}/views/templates/hook/admin-products-extra-delivery-product-time.tpl");
    }
}
