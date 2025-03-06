<?php

namespace atom;

use yii\base\Module;

abstract class BackendModule extends Module
{
    abstract public function menu(array &$items, string $baseRoute): void;
}
