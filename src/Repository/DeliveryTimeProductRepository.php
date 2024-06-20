<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Repository;

use Db;
use DbQuery;

class DeliveryTimeProductRepository {

    protected $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    public function getByProductId(int $productId): ?array
    {
        $query = (new DbQuery())
            ->from('webo_deliverytime_product', 'wdp')
            ->where('wdp.id_product = ' . $productId);

        return $this->db->getRow($query) ?: null;
    }
}