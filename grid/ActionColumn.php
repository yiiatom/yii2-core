<?php

namespace atom\grid;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $template = '{update} {delete}';

    public $icons = [
        'eye-open' => '<i class="fa-solid fa-eye"></i>',
        'pencil' => '<i class="fa-solid fa-pencil"></i>',
        'trash' => '<i class="fa-solid fa-trash-can"></i>',
    ];
}
