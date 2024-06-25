<?php

namespace WeboDeliveryTime\Hook;

use PrestaShopException;
use Product;
use WeboDeliveryTime\Model\DeliveryTimeProduct;
use Tools;

class ActionAdminProductsControllerSaveAfter extends AbstractHook
{
    /** @param array{return: Product|false} $params */
    public function execute(array $params)
    {
        if(!empty(Tools::getValue('weboDeliveryTimeUpdateAfter'))) {
            $product = $params['return'];

            $delivery_time_on_stock = Tools::getValue('delivery_time_on_stock');
            $delivery_time_out_of_stock =Tools::getValue('delivery_time_out_of_stock');

            $this->validate($delivery_time_on_stock, $delivery_time_out_of_stock);

            $data = [
                'delivery_time_on_stock' =>  (int) $delivery_time_on_stock,
                'delivery_time_out_of_stock' => (int) $delivery_time_out_of_stock
            ];

            return DeliveryTimeProduct::saveData($product->id, $data);
        }

        return;
    }

    private function validate($delivery_time_on_stock, $delivery_time_out_of_stock)
    {
        if((!empty($delivery_time_on_stock) && !is_numeric($delivery_time_on_stock)) || (!empty($delivery_time_out_of_stock) && !is_numeric($delivery_time_out_of_stock))){
            throw new PrestaShopException('Oba pola muszą być liczbą');
        }

        if($delivery_time_on_stock < 1 || $delivery_time_out_of_stock < 1){
            throw new PrestaShopException('Oba pola muszą być większe niż 0');
        }
    }
}
