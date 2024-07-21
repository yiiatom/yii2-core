<?php

namespace atom\jui;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

class AutoComplete extends \yii\jui\AutoComplete
{
    public $options = ['class' => 'form-control'];
    public $url;
    public $label;
    public $valueKey = 'value';
    public $labelKey;

    public function init()
    {
        parent::init();
        if ($this->labelKey === null) {
            $this->labelKey = $this->valueKey;
        }
        if ($this->url) {
            $this->clientOptions['source'] = new JsExpression('function(request, response) {
                $.get("' . Url::to($this->url) . '", request, function(data) {
                    response(data.map((i) => ({label: i.' . $this->labelKey . ', value: i.' . $this->labelKey . ', data: i})));
                }, "json");
            }');
        }
        $this->clientOptions['select'] = new JsExpression('function(e, ui) {
            e.target.dataset.label = ui.item.label;
            $("#" + e.target.id + "_value").val(ui.item.data.' . $this->valueKey .');
        }');
        $this->options['onblur'] = new JsExpression('this.value = this.dataset.label || ""');
        if ($this->label) {
            $this->options['value'] = $this->label;
            $this->options['data-label'] = $this->label;
        }
    }

    public function renderWidget()
    {
        $options = $this->options;
        $hidden = '';
        if ($this->valueKey != $this->labelKey) {
            $options['name'] = false;
            $hiddenOptions = ['id' => $options['id'] . '_value'];

            if ($this->hasModel()) {
                $hidden = Html::activeHiddenInput($this->model, $this->attribute, $hiddenOptions);
            } else {
                $hidden = Html::hiddenInput($this->name, $this->value, $hiddenOptions);
            }
        }

        if ($this->hasModel()) {
            $input = Html::activeTextInput($this->model, $this->attribute, $options);
        } else {
            $input = Html::textInput($this->name, $this->value, $options);
        }

        return $input . $hidden;



        $content = parent::renderWidget();



        if ($this->valueKey != $this->labelKey) {
            $id = $this->options['id'];
            return parent::renderWidget();
        }

        if ($this->hasModel()) {
            $hidden = Html::activeHiddenInput($this->model, $this->attribute, ['id' => $this->options['id']]);
        } else {
            $hidden = Html::hiddenInput($this->name, $this->value, ['id' => $this->options['id']]);
        }

        $this->options['id'] .= '_autocomplete';
        return $hidden . Html::textInput(null, $this->value, $this->options);
    }
}
