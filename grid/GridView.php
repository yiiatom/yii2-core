<?php

namespace atom\grid;

use yii\helpers\Html;

class GridView extends \yii\grid\GridView
{
    public $tableOptions = [
        'class' => 'table table-bordered table-sm',
    ];

    public $pager = [
        'class' => 'atom\widgets\LinkPager',
    ];

    public $responsive = true;

    public function renderItems()
    {
        $content = parent::renderItems();
        if ($this->responsive) {
            $content = Html::tag('div', $content, ['class' => 'table-responsive']);
        }
        return $content;
    }
}
