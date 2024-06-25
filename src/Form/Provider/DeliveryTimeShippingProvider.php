<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Form\Provider;

use Doctrine\ORM\EntityRepository;
use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\DataProvider\FormDataProviderInterface;

class DeliveryTimeShippingProvider implements FormDataProviderInterface
{
    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(
        EntityRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function getData($id)
    {
        $deliveryTimeShippingItem = $this->repository->findOneById((int) $id);
        $itemData = [];

        $itemData['id_carrier'] = $deliveryTimeShippingItem->getIdCarrier();
        $itemData['delivery_time'] = $deliveryTimeShippingItem->getDeliveryTime();

       return $itemData;
    }

    public function getDefaultData()
    {
        return [
            "id_carrier" => '',
            "delivery_time" => [],
        ];
    }
}