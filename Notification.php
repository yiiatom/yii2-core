<?php

namespace atom;

class Notification
{
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_DANGER = 'danger';

    public static function add($type, $title, $content)
    {
        $model = new \atom\cms\models\Notification([
            'type' => $type,
            'title' => $title,
            'content' => $content,
        ]);
        $model->save(false);
    }
}
