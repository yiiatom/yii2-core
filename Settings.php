<?php

namespace atom;

use yii\base\Model;

class Settings extends Model
{
    public static function get($name, $default = null)
    {
        $model = \atom\cms\models\Settings::findOne(['name' => $name]);
        $value = @unserialize($model->value);
        if (!$model || ($value === false && $model->value != 'b:0;')) {
            $value = $default;
        }
        return $value;
    }

    public static function set($name, $value)
    {
        $model = \atom\cms\models\Settings::findOne(['name' => $name]);
        if (!$model) {
            $model = new \atom\cms\models\Settings([
                'name' => $name,
            ]);
        }
        $model->value = serialize($value);
        return $model->save(false);
    }
}
