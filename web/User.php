<?php

namespace atom\web;

class User extends \yii\web\User
{
    public $enableAutoLogin = true;

    public $identityClass = 'atom\cms\models\User';
}
