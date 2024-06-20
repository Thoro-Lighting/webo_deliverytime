<?php

namespace WeboDeliveryTime\Grid\Filter;

use PrestaShop\PrestaShop\Core\Search\Filters;
use WeboDeliveryTime\Grid\Definition\DeliveryTimeShippingGridDefinitionFactory;

final class DeliveryTimeShippingFilters extends Filters
{
    protected $filterId = DeliveryTimeShippingGridDefinitionFactory::GRID_ID;

    /**
     * {@inheritdoc}
     */
    public static function getDefaults(): array
    {
        return [
            'limit' => 20,
            'offset' => 0,
            'orderBy' => 'id',
            'sortOrder' => 'ASC',
            'filters' => [],
        ];
    }
}