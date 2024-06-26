<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Hook;

use Context;
use Doctrine\ORM\EntityManagerInterface;
use Module;
use WeboDeliveryTime\Presenter\DeliveryTimePresenter;
use WeboDeliveryTime\Repository\Doctrine\DeliveryTimeShippingRepository;

class ActionCarrierUpdate extends AbstractHook
{
    private $entityRepository;

    private $entityManager;

    public function __construct(
        Module $module,
        Context $context,
        DeliveryTimeShippingRepository $entityRepository,
        EntityManagerInterface $entityManager
    ) {
        parent::__construct($module, $context);

        $this->entityRepository = $entityRepository;
        $this->entityManager = $entityManager;
    }

    public function execute(array $params)
    {
        if(!empty($id_carrier = \Tools::getValue('id_carrier'))) {
            $deliveryTimeShippingItem = $this->entityRepository->findOneBy([
                'idCarrier' => (int)$id_carrier,
            ]);

            if ($deliveryTimeShippingItem !== null) {
                $this->entityManager->remove($deliveryTimeShippingItem);
                $this->entityManager->flush();
            }
        }
    }
}