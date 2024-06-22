<?php

namespace WeboDeliveryTime\Hook;

use Tools;

class ActionAdminControllerSetMedia extends AbstractHook
{
    public function execute(array $params)
    {
        if ('AdminProducts' === Tools::getValue('controller')) {
            $this->context->controller->addJs([
                $this->module->getPathUri() . 'views/js/admin/adminProductExtra.js'
            ]);
        }
    }
}
