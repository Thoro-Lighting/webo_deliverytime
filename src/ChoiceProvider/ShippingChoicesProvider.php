<?php

declare(strict_types=1);

namespace WeboDeliveryTime\ChoiceProvider;

use Carrier;
use WeboDeliveryTime\Repository\Doctrine\DeliveryTimeShippingRepository;

class ShippingChoicesProvider {
    public function getShippingChoices(int $idLang): array
    {
        $carriesChoices = [];

        $carriers = Carrier::getCarriers($idLang, false , false, false, null, Carrier::ALL_CARRIERS);

        foreach ($carriers as $carrier) {
            $carriesChoices[$carrier['name']] = (int) $carrier['id_carrier'];
        }

        return $carriesChoices;
    }
}
