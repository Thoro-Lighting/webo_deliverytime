<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Installer;

class ModuleInstaller
{
    const HOOKS_LIST = [
        'actionFrontControllerSetMedia',
        'displayAdminProductsExtra',
        'actionAdminProductsControllerSaveAfter',
        'displayDeliveryTime',
        'actionCarrierUpdate'
    ];

    /**
     * @var \Module
     */
    private $module;

    public function __construct(\Module $module)
    {
        $this->module = $module;
    }

    public function install(): bool
    {
        return $this->installHooks() && $this->installDb();
    }

    public function uninstall(): bool
    {
        return $this->uninstallDb();
    }

    private function installHooks(): bool
    {
        $result = true;

        foreach (self::HOOKS_LIST as $hook) {
            $result = $this->module->registerHook($hook) && $result;
        }

        return $result;
    }

    private function installDb(): bool
    {
        $sql = [
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'webo_deliverytime_product` (
                `id_webo_deliverytime_product` int(11) NOT NULL AUTO_INCREMENT,
                `id_product` int(11) NOT NULL,
                `delivery_time_on_stock` int (3) DEFAULT NULL,
                `delivery_time_out_of_stock` int(3) DEFAULT NULL,
                PRIMARY KEY (`id_webo_deliverytime_product`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;',
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'webo_deliverytime_shipping` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `id_carrier_reference` int(11) NOT NULL,
                `delivery_time` int (3) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;'
        ];

        foreach ($sql as $query) {
            if (\Db::getInstance()->execute($query) == false) {
                return false;
            }
        }

        return true;
    }

    private function uninstallDb(): bool
    {
        $success = true;

        $sql = [
            'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'webo_deliverytime_product`',
            'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'webo_deliverytime_shipping`',
        ];

        foreach ($sql as $query) {
            if (!\Db::getInstance()->execute($query)) {
                $success = false;
            }
        }

        return $success;
    }
}
