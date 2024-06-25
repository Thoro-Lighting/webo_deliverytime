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
        $idCarrier = $this->context->cart->id_carrier ?? null;
        $cart = $this->context->cart ?? null;

        if (!empty($params['carrier'] && !empty($params['carrier']['id']))) {
            $idCarrier = (int) $params['carrier']['id'];
        }

        if (!empty($params['cart'])) {
            $cart = $params['cart'];
        }

        $this->assignTemplateVariables($idCarrier, $cart);

        return $this->module->fetch($this->getTemplateFullPath());
    }

    private function assignTemplateVariables($idCarrier = null, $cart = null): void {
        $deliveryTimeData = $this->deliveryTimePresenter->present($idCarrier, $cart);

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