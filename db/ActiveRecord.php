<?php

namespace atom\db;

use yii\data\ActiveDataProvider;

class ActiveRecord extends \yii\db\ActiveRecord
{
    public function getDataProvider($config = [])
    {
        if (!isset($config['query'])) {
            $config['query'] = static::find();
        }
        return new ActiveDataProvider($config);
    }
}
