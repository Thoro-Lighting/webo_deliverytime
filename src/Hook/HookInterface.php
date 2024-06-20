<?php

declare(strict_types=1);

namespace WeboDeliveryTime\Hook;

interface HookInterface
{
    public function execute(array $params);
}
