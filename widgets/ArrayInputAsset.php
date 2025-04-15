<?php

namespace atom\widgets;

use yii\web\AssetBundle;

class ArrayInputAsset extends AssetBundle
{
    public $sourcePath = '@atom/assets';

    public $js = [
        'atom.arrayInput.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
