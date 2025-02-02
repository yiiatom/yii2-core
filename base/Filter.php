<?php

namespace atom\base;

use yii\base\Model;
use yii\data\DataProviderInterface;

abstract class Filter extends Model
{
    /**
     * @var string
     */
    public $formName = '';

    /**
     * @inheritdoc
     */
    public function formName(): string
    {
        return $this->formName;
    }

    /**
     * Get data provider
     * @param array $config
     * @return DataProviderInterface
     */
    abstract public function getDataProvider(array $config = []): DataProviderInterface;
}
