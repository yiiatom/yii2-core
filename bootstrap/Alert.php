<?php

namespace atom\bootstrap;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class Alert extends Widget
{
    public $body;
    public $closeButton = ['class' => 'btn-close'];

    public function init()
    {
        parent::init();

        $this->initOptions();

        echo Html::beginTag('div', $this->options) . "\n";
    }

    public function run()
    {
        echo "\n" . $this->renderBodyEnd();
        echo "\n" . Html::endTag('div');
    }

    protected function renderBodyEnd()
    {
        return $this->body . "\n" .  $this->renderCloseButton() . "\n";
    }

    protected function renderCloseButton()
    {
        if (($closeButton = $this->closeButton) !== false) {
            $closeButton['data-bs-dismiss'] = 'alert';
            $closeButton['aria-label'] = 'Close';
            $tag = ArrayHelper::remove($closeButton, 'tag', 'button');
            if ($tag === 'button' && !isset($closeButton['type'])) {
                $closeButton['type'] = 'button';
            }

            return Html::tag($tag, '', $closeButton);
        } else {
            return null;
        }
    }

    protected function initOptions()
    {
        Html::addCssClass($this->options, ['widget' => 'alert']);

        if ($this->closeButton !== false) {
            $this->closeButton = array_merge([
                'data-dismiss' => 'alert',
                'class' => ['widget' => 'close'],
            ], $this->closeButton);

            Html::addCssClass($this->options, ['toggle' => 'alert-dismissible']);
        }
        if (!isset($this->options['role'])) {
            $this->options['role'] = 'alert';
        }
    }
}
