<?php

namespace atom\bootstrap;

class ActiveForm extends \yii\widgets\ActiveForm
{
    public $enableClientValidation = false;
    public $fieldClass = 'atom\bootstrap\ActiveField';
    public $errorCssClass = 'is-invalid';
    public $validateOnBlur = false;
    public $validateOnChange = false;
    public $validateOnSubmit = false;
    public $validationStateOn = self::VALIDATION_STATE_ON_INPUT;
}
