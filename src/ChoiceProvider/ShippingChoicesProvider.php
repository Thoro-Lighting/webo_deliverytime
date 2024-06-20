<?php

declare(strict_types=1);

namespace WeboDeliveryTime\ChoiceProvider;

use Carrier;
use WeboDeliveryTime\Repository\Doctrine\DeliveryTimeShippingRepository;

class ShippingChoicesProvider {
    private $deliveryTimeShippingRepository;

    public function __construct(
        DeliveryTimeShippingRepository $deliveryTimeShippingRepository
    ) {
        $this->deliveryTimeShippingRepository = $deliveryTimeShippingRepository;
    }

    public function getShippingChoices(int $idLang): array
    {
        $excludedCarriers = $this->deliveryTimeShippingRepository->findAll();

        $carriesChoices = [];

        $carriers = Carrier::getCarriers($idLang, false , false, false, null, Carrier::ALL_CARRIERS);

        foreach ($carriers as $carrier) {
            $carriesChoices[$carrier['name']] = (int) $carrier['id_reference'];
        }

        return $carriesChoices;
    }
}
