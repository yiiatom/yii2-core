<?php

namespace atom\bootstrap;

use yii\helpers\ArrayHelper;

class Html extends \yii\helpers\Html
{
    protected static function booleanInput($type, $name, $checked = false, $options = [])
    {
        // 'checked' option has priority over $checked argument
        if (!isset($options['checked'])) {
            $options['checked'] = (bool) $checked;
        }
        if (!isset($options['class'])) {
            $options['class'] = 'form-check-input';
        }
        $value = array_key_exists('value', $options) ? $options['value'] : '1';
        if (isset($options['uncheck'])) {
            // add a hidden field so that if the checkbox is not selected, it still submits a value
            $hiddenOptions = [];
            if (isset($options['form'])) {
                $hiddenOptions['form'] = $options['form'];
            }
            // make sure disabled input is not sending any value
            if (!empty($options['disabled'])) {
                $hiddenOptions['disabled'] = $options['disabled'];
            }
            $hidden = static::hiddenInput($name, $options['uncheck'], $hiddenOptions);
            unset($options['uncheck']);
        } else {
            $hidden = '';
        }
        $content = static::input($type, $name, $value, $options);
        if (isset($options['label'])) {
            $labelText = $options['label'];
            $labelOptions = isset($options['labelOptions']) ? $options['labelOptions'] : [];
            if (!isset($labelOptions['class'])) {
                $labelOptions['class'] = 'form-check-label';
            }
            unset($options['label'], $options['labelOptions']);
            $label = static::label($labelText, $options['id'] ?? null, $labelOptions);

            $containerOptions = ArrayHelper::remove($options, 'containerOptions', []);
            $containerTag = ArrayHelper::remove($containerOptions, 'tag', 'div');
            if (!isset($containerOptions['class'])) {
                $containerOptions['class'] = 'form-check';
            }
            $content = static::tag($containerTag, $content . $label, $containerOptions);
        }

        return $hidden . $content;
    }
}
