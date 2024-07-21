<?php

namespace atom\widgets;

class LinkPager extends \yii\widgets\LinkPager
{
    public $disabledListItemSubTagOptions = ['class' => 'page-link'];

    public $linkOptions = ['class' => 'page-link'];

    public $maxButtonCount = 5;

    public $nextPageCssClass = 'page-item';

    public $pageCssClass = 'page-item';

    public $prevPageCssClass = 'page-item';
}
