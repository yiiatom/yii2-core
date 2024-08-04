<?php

namespace atom\widgets;

class ListView extends \yii\widgets\ListView
{
    public $pager = [
        'class' => 'atom\widgets\LinkPager',
    ];
}
