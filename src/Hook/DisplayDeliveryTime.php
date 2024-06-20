<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Hook;

use Context;
use Module;
use WeboDeliveryTime\Presenter\DeliveryTimePresenter;

class DisplayDeliveryTime extends AbstractHook {

    private $deliveryTimePresenter;

    private const TEMPLATE_FILE = 'delivery_time.tpl';

    public function __construct(
        Module $module,
        Context $context,
        DeliveryTimePresenter $deliveryTimePresenter
    ) {
        parent::__construct($module, $context);

        $this->deliveryTimePresenter = $deliveryTimePresenter;
    }

    public function execute(array $params)
    {
        if(empty($params['carrier']) && empty($params['cart'])) {
            return;
        }

        $idCarrierReference = (int) $params['carrier']['id_reference'] ?? null;
        $cart = $params['cart'] ?? null;

        $this->assignTemplateVariables($idCarrierReference, $cart);

        return $this->module->fetch($this->getTemplateFullPath());
    }

    private function assignTemplateVariables($idCarrierReference = null, $cart = null): void {
        $deliveryTimeData = $this->deliveryTimePresenter->present($idCarrierReference, $cart);

        $this->context->smarty->assign([
            'deliveryTimeProducts' => $deliveryTimeData['carrierDeliveryTime'],
            'deliveryTimeCarrier' => $deliveryTimeData['cartProductsDeliveryTime'],
            'deliveryTimeSum' => $deliveryTimeData['deliveryTimeSum'],
        ]);
    }

    public function getTemplateFullPath(): string
    {
        return "module:{$this->module->name}/views/templates/hook/{$this->getTemplate()}";
    }

    protected function getTemplate(): string
    {
        return DisplayDeliveryTime::TEMPLATE_FILE;
    }
}