<?php

namespace atom\widgets;

use atom\grid\ActionColumn;
use atom\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\widgets\InputWidget;

class ArrayInput extends InputWidget
{
    public array $columns = [];

    public function init()
    {
        parent::init();

        if ($this->hasModel()) {
            if ($this->name === null) {
                $this->name = Html::getInputName($this->model, $this->attribute);
            }
            if ($this->value === null) {
                $this->value = Html::getAttributeValue($this->model, $this->attribute);
            }
        }

    }

    public function run()
    {
        ArrayInputAsset::register($this->view);

        $items = $this->value;

        $showHeader = false;
        $columns = [];
        foreach ($this->columns as $config) {
            if (is_string($config)) {
                $config = ['name' => $config];
            }

            if ($name = $config['name'] ?? null) {
                $header = $config['header'] ?? Inflector::camel2words($name, true);
                if ($header !== false) {
                    $showHeader = true;
                }

                $columns[] = [
                    'attribute' => $name,
                    'header' => $header ?: '',
                ];
            }
        }
        if (!$columns) {
            $columns = [[
                'attribute' => '-',
                'content' => [$this, 'cellTextInput'],
            ]];
            $items = array_map(fn ($v) => ['-' => $v], $items);
        }
        $columns[] = [
            'class' => ActionColumn::class,
            'template' => '{remove}',
            'buttons' => [
                'remove' => function($url, $model, $key) {
                    $options = ['class' => 'array-input-remove'];
                    if ($key === '-') {
                        Html::addCssClass($options, 'd-none');
                    }
                    return Html::a('<i class="fa-solid fa-trash-can"></i>', '#', $options);
                }
            ],
            'contentOptions' => ['class' => 'align-middle'],
        ];


        $items['-'] = [];
        // var_dump($items); die();

        echo GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $items,
                'pagination' => false,
            ]),
            'emptyText' => false,
            'layout' => '{items}',
            'showHeader' => $showHeader,
            'columns' => $columns,
            'options' => ['class' => 'array-input-container'],
        ]);



        // $options = $this->options;
        // Html::addCssClass($options, 'array-table-input');

        // $showHeading = false;
        // $columns = $this->columns ?: [['name' => 'test']];
        // // $columns = $this->columns ?: [['heading' => false]];
        // foreach ($columns as &$column) {
        //     if (!isset($column['heading'])) {
        //         $name = $column['name'] ?? null;
        //         $column['heading'] = $name ? Inflector::camel2words($name, true) : false;
        //     }
        //     if ($column['heading']) {
        //         $showHeading = true;
        //     }
        // }

        // echo Html::beginTag('table', $options);

        // if ($showHeading) {
        //     echo Html::beginTag('thead');
        //     foreach ($columns as $column) {
        //         echo Html::
        //     }
        //     echo Html::endTag('thead');
        // }

        // echo Html::endTag('table');
    }

    public function cellTextInput($model, $key, $index, $column)
    {
        $options = ['class' => 'form-control'];

        $attribute = $column->attribute;

        $name = "{$this->name}[{$key}]";
        if ($attribute != '-') {
            $name .= "[{$attribute}]";
        }
        if ($key === '-') {
            $options['data-name'] = $name;
            $name = '';
        }

        $value = $model[$attribute] ?? null;

        return Html::textInput($name, $value, $options);
    }
}
