<?php

namespace alkaid\datepicker;

use yii\web\AssetBundle;

class DatePickerAsset extends AssetBundle{

    public $sourcePath = '@vendor/alkaid/yii2-datepicker/assets';
    public $depends = ['yii\bootstrap\BootstrapPluginAsset'];
    public $css = ['css/bootstrap-datepicker.min.css', 'css/bootstrap-datepicker.standalone.min.css', 'css/bootstrap-datepicker3.min.css', 'css/bootstrap-datepicker3.standalone.min.css'];
    public $js = ['js/bootstrap-datepicker.js'];

}