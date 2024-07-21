<?php

namespace atom\pdf;

use yii\base\BaseObject;

class BasePdf extends BaseObject
{
    protected $pdf;

    public function init()
    {
        parent::init();
        $this->pdf = new Pdf;
    }

    public function process()
    {
    }
}
