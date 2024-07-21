<?php

namespace atom\bootstrap;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class NavBar extends Widget
{
    public $collapseOptions = [];
    public $brandLabel = false;
    public $brandImage = false;
    public $brandUrl = false;
    public $brandOptions = [];
    public $screenReaderToggleText = 'Toggle navigation';
    public $togglerContent = '<span class="navbar-toggler-icon"></span>';
    public $togglerOptions = [];
    public $renderInnerContainer = true;
    public $innerContainerOptions = [];
    public $clientOptions = false;

    public function init()
    {
        parent::init();
        if (!isset($this->options['class']) || empty($this->options['class'])) {
            Html::addCssClass($this->options, [
                'widget' => 'navbar',
                'toggle' => 'navbar-expand-lg',
                'navbar-light',
                'bg-light'
            ]);
        } else {
            Html::addCssClass($this->options, ['widget' => 'navbar']);
        }
        $navOptions = $this->options;
        $navTag = ArrayHelper::remove($navOptions, 'tag', 'nav');
        $brand = '';
        if (!isset($this->innerContainerOptions['class'])) {
            Html::addCssClass($this->innerContainerOptions, ['panel' => 'container']);
        }
        if (!isset($this->collapseOptions['id'])) {
            $this->collapseOptions['id'] = "{$this->options['id']}-collapse";
        }
        if ($this->brandImage !== false) {
            $this->brandLabel = Html::img($this->brandImage);
        }
        if ($this->brandLabel !== false) {
            Html::addCssClass($this->brandOptions, ['widget' => 'navbar-brand']);
            if ($this->brandUrl === null) {
                $brand = Html::tag('span', $this->brandLabel, $this->brandOptions);
            } else {
                $brand = Html::a(
                    $this->brandLabel,
                    $this->brandUrl === false ? Yii::$app->homeUrl : $this->brandUrl,
                    $this->brandOptions
                );
            }
        }
        Html::addCssClass($this->collapseOptions, ['collapse' => 'collapse', 'widget' => 'navbar-collapse']);
        $collapseOptions = $this->collapseOptions;
        $collapseTag = ArrayHelper::remove($collapseOptions, 'tag', 'div');

        echo Html::beginTag($navTag, $navOptions) . "\n";
        if ($this->renderInnerContainer) {
            echo Html::beginTag('div', $this->innerContainerOptions) . "\n";
        }
        echo $brand . "\n";
        echo $this->renderToggleButton() . "\n";
        echo Html::beginTag($collapseTag, $collapseOptions) . "\n";
    }

    public function run()
    {
        $tag = ArrayHelper::remove($this->collapseOptions, 'tag', 'div');
        echo Html::endTag($tag) . "\n";
        if ($this->renderInnerContainer) {
            echo Html::endTag('div') . "\n";
        }
        $tag = ArrayHelper::remove($this->options, 'tag', 'nav');
        echo Html::endTag($tag);
    }

    protected function renderToggleButton()
    {
        $options = $this->togglerOptions;
        Html::addCssClass($options, ['widget' => 'navbar-toggler']);
        return Html::button(
            $this->togglerContent,
            ArrayHelper::merge($options, [
                'type' => 'button',
                'data' => [
                    'toggle' => 'collapse',
                    'target' => '#' . $this->collapseOptions['id'],
                ],
                'aria-controls' => $this->collapseOptions['id'],
                'aria-expanded' => 'false',
                'aria-label' => $this->screenReaderToggleText,
            ])
        );
    }

    public function setContainerOptions($collapseOptions)
    {
        $this->collapseOptions = $collapseOptions;
    }
}
