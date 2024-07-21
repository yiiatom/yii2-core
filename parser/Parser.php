<?php

namespace atom\parser;

class Parser extends \yii\base\BaseObject
{
    protected Request $request;

    public function init()
    {
        parent::init();
        $this->request = new Request;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}
