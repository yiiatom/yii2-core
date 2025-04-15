<?php

namespace atom\bootstrap;

use atom\widgets\ArrayInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ActiveField extends \yii\widgets\ActiveField
{
    public $options = ['class' => 'field mb-3'];
    public $inputOptions = ['class' => 'form-control'];
    public $selectOptions = ['class' => 'form-select'];
    public $errorOptions = ['class' => 'invalid-feedback'];
    public $labelOptions = ['class' => 'form-label'];
    public $hintOptions = ['class' => 'form-text'];
    public $tableOptions = ['class' => 'table'];

    public $checkboxTemplate = "{input}\n{label}\n{hint}\n{error}";
    public $checkboxContainerClass = 'form-check';
    public $checkboxOptions = ['class' => 'form-check-input'];
    public $checkboxLabelOptions = ['class' => 'form-check-label'];

    public function textInput($options = [])
    {
        if (isset($options['prependText'])) {
            $options['prepend'] = Html::tag('span', $options['prependText'], ['class' => 'input-group-text']);
            unset($options['prependText']);
        }
        if (isset($options['appendText'])) {
            $options['append'] = Html::tag('span', $options['appendText'], ['class' => 'input-group-text']);
            unset($options['appendText']);
        }

        $prepend = ArrayHelper::remove($options, 'prepend', '');
        $append = ArrayHelper::remove($options, 'append', '');

        parent::textInput($options);

        if ($prepend || $append) {
            $content = $prepend . $this->parts['{input}'] . $append;
            $this->parts['{input}'] = Html::tag('div', $content, ['class' => 'input-group']);
        }

        return $this;
    }

    public function fileInput($options = [])
    {
        if ($this->inputOptions === ['class' => 'form-control']) {
            $options = array_merge($this->inputOptions, $options);
        }

        return parent::fileInput($options);
    }

    public function radio($options = [], $enclosedByLabel = false)
    {
        $this->template = $this->checkboxTemplate;
        Html::addCssClass($this->options, $this->checkboxContainerClass);
        $options = array_merge($this->checkboxOptions, $options);
        $this->labelOptions = $this->checkboxLabelOptions;
        return parent::radio($options, $enclosedByLabel);
    }

    public function checkbox($options = [], $enclosedByLabel = false)
    {
        $this->template = $this->checkboxTemplate;
        Html::addCssClass($this->options, $this->checkboxContainerClass);
        $options = array_merge($this->checkboxOptions, $options);
        $this->labelOptions = $this->checkboxLabelOptions;
        return parent::checkbox($options, $enclosedByLabel);
    }

    public function dropDownList($items, $options = [])
    {
        $this->inputOptions = $this->selectOptions;
        return parent::dropDownList($items, $options);
    }

    public function array(array $columns = [], array $options = [])
    {
        $options = array_merge($this->tableOptions, $options);

        $this->parts['{input}'] = ArrayInput::widget([
            'model' => $this->model,
            'attribute' => $this->attribute,
            'columns' => $columns,
            'options' => $options,
        ]);

        return $this;
    }
}
