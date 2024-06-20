<?php

namespace WeboDeliveryTime\Model;

use DbQuery;
use ObjectModel;

class DeliveryTimeProduct extends ObjectModel {

    public $id_product;
    public $delivery_time_on_stock;
    public $delivery_time_out_of_stock;

    public static $definition = [
        'table' => 'webo_deliverytime_product',
        'primary' => 'id_webo_deliverytime_product',
        'fields' => [
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isInt', 'size' => 3],
            'delivery_time_on_stock' => ['type' => self::TYPE_INT, 'validate' => 'isInt', 'size' => 3],
            'delivery_time_out_of_stock' => ['type' => self::TYPE_INT, 'validate' => 'isInt', 'size' => 3],
        ],
    ];

    public static function saveData(int $id_product, array $data){
        $id = self::getIdByProductId($id_product);
        $model = $id ? new DeliveryTimeProduct($id) : new DeliveryTimeProduct();
        $model->id_product = $id_product;

        $model->delivery_time_out_of_stock = $data['delivery_time_on_stock'];
        $model->delivery_time_on_stock = $data['delivery_time_out_of_stock'];

        return $model->save();
    }

    public static function getIdByProductId(int $productId): ?int
    {
        $query = (new DbQuery())
            ->select('id_webo_deliverytime_product')
            ->from('webo_deliverytime_product', 'wdp')
            ->where('wdp.id_product = ' . $productId);

        return (int) \Db::getInstance()->getValue($query) ?: null;
    }

    public static function getAllDataByProductId(int $productId): ?array
    {
        $query = (new DbQuery())
            ->from('webo_deliverytime_product', 'wdp')
            ->where('wdp.id_product = ' . $productId);

        return \Db::getInstance()->getRow($query) ?: null;
    }

    public static function getByProductsIds(array $productsIds): ?array
    {
        $query = (new DbQuery())
            ->from('webo_deliverytime_product', 'wdp')
            ->where('wdp.id_product IN (' . implode(',', $productsIds) . ')' );


        return \Db::getInstance()->executeS($query) ?: null;
    }
}