<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Grid\Definition;

use PrestaShop\PrestaShop\Core\Grid\Action\Row\RowActionCollection;
use PrestaShop\PrestaShop\Core\Grid\Action\Row\Type\LinkRowAction;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ActionColumn;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\Factory\AbstractGridDefinitionFactory;

class DeliveryTimeShippingGridDefinitionFactory extends AbstractGridDefinitionFactory {

    public const GRID_ID = 'webo_deliverytime_shipping';

    /**
     * {@inheritdoc}
     */
    protected function getId()
    {
        return self::GRID_ID;
    }

    protected function getName()
    {
        return $this->trans('Shipping delivery tine form grid', [], 'Modules.Webodeliverytime.Admin');
    }

    protected function getColumns()
    {
        return (new ColumnCollection())
            ->add(
                (new DataColumn('id'))
                    ->setName($this->trans('Id', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'id',
                    ])
            )
            ->add(
                (new DataColumn('carrier_name'))
                    ->setName($this->trans('Metoda wysyłki', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'carrier_name',
                    ])
            )
            ->add(
                (new DataColumn('delivery_time'))
                    ->setName($this->trans('Czas dostawy', [], 'Admin.Global'))
                    ->setOptions([
                        'field' => 'delivery_time',
                    ])
            )
            ->add(
                (new ActionColumn('actions'))
                    ->setName($this->trans('Actions', [], 'Admin.Actions'))
                    ->setOptions([
                        'actions' => $this->getRowActions(),
                    ])
            );
    }

    private function getRowActions()
    {
        return (new RowActionCollection())
            ->add(
                (new LinkRowAction('edit'))
                    ->setName($this->trans('Edit', [], 'Admin.Actions'))
                    ->setOptions([
                        'route' => 'webo_delivery_time_form_create_edit',
                        'route_param_name' => 'id',
                        'route_param_field' => 'id',
                    ])
                    ->setIcon('edit')
            )
            ->add(
                (new LinkRowAction('delete'))
                    ->setName($this->trans('Remove', [], 'Admin.Actions'))
                    ->setOptions([
                        'route' => 'webo_delivery_time_form_delete',
                        'route_param_name' => 'id',
                        'route_param_field' => 'id',
                    ])
                    ->setIcon('remove')
            );
    }
}