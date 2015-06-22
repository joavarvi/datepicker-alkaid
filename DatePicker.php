<?php
/**
 * Created by PhpStorm.
 * User: joaquin
 * Date: 22/06/15
 * Time: 12:38 PM
 */

namespace alkaid\datepicker;

use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\widgets\InputWidget;

class DatePicker extends InputWidget{

    public $options = ['class' => 'form-control'];
    public $clientOptions;
    public $clientEvents;

    public $type = 'input';

    public $toModel = false;
    public $toAttribute = false;
    public $toName = false;
    public $toValue = false;
    private $_assetBundle;

    public function init(){
        $this->registerAssetBundle();
        $this->registerLocate();
        $this->registerScript();
        $this->registerEvent();
    }

    public function registerAssetBundle()
    {
        $this->_assetBundle = DatePickerAsset::register($this->getView());
    }

    public function registerLocate()
    {
        $locate = ArrayHelper::getValue($this->clientOptions, 'language', false);
        if ($locate) {
            $locateAsset = 'js/locales/bootstrap-datepicker.' . $locate . '.js';
            if (file_exists(Yii::getAlias($this->assetBundle->sourcePath . '/' . $locateAsset))) {
                $this->assetBundle->js[] = $locateAsset;
            } else {
                ArrayHelper::remove($this->clientOptions, 'language');
            }
        }
    }

    public function registerScript()
    {
        $configure = empty($this->clientOptions) ? '' : Json::encode($this->clientOptions);
        $js = "jQuery('#{$this->selector}').datepicker({$configure});";
        $this->getView()->registerJs($js);
    }

    public function registerEvent()
    {
        if (!empty($this->clientEvents)) {
            $js = [];
            foreach ($this->clientEvents as $event => $handle) {
                $js[] = "jQuery('#{$this->selector}').on('{$event}',{$handle});";
            }
            $this->getView()->registerJs(implode(PHP_EOL, $js));
        }
    }

    public function getSelector()
    {
        return empty($this->type) ? $this->id : $this->type . '_' . $this->id;
    }

    public function getAssetBundle()
    {
        if (!($this->_assetBundle instanceof AssetBundle)) {
            $this->registerAssetBundle();
        }
        return $this->_assetBundle;
    }

    public function renderEembedded()
    {
        echo Html::tag('div', '', ['id' => $this->selector]);
    }

    public function renderInput()
    {
        $this->options['id'] = $this->selector;
        if ($this->hasModel()) {
            echo Html::activeInput('text', $this->model, $this->attribute, $this->options);
        } else {
            echo Html::input('text', $this->name, $this->value, $this->options);
        }
    }

    public function renderComponent()
    {
        echo Html::beginTag('div', ['class' => 'input-group date', 'id' => $this->selector]);
        if ($this->hasModel()) {
            echo Html::activeInput('text', $this->model, $this->attribute, $this->options);
        } else {
            echo Html::input('text', $this->name, $this->value, $this->options);
        }
        echo Html::beginTag('span', ['class' => 'input-group-addon']);
        echo Html::tag('i', '', ['class' => 'glyphicon glyphicon-calendar']);
        echo Html::endTag('span');
        echo Html::endTag('div');
    }

    public function renderRange()
    {
        echo Html::beginTag('div', ['class' => 'input-daterange input-group', 'id' => $this->selector]);
        if ($this->hasModel()) {
            $this->toModel = ($this->toModel) ? $this->toModel : $this->model;
            echo Html::activeInput('text', $this->model, $this->attribute, $this->options);
            echo Html::tag('span', 'to', ['class' => 'input-group-addon']);
            echo Html::activeInput('text', $this->toModel, $this->toAttribute, $this->options);
        } else {
            echo Html::input('text', $this->name, $this->value, $this->options);
            echo Html::tag('span', 'to', ['class' => 'input-group-addon']);
            echo Html::input('text', $this->toName, $this->toValue, $this->options);
        }
        echo Html::endTag('div');
    }

    public function run()
    {
        switch ($this->type) {
            case 'component':
                $this->renderComponent();
                break;
            case 'embedded':
                $this->renderEembedded();
                break;
            case 'range':
                $this->renderRange();
                break;
            default:
                $this->renderInput();
                break;
        }
    }

}