<?php

namespace app\assets;

class MochaVendorAsset extends \yii\web\AssetBundle
{
    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN,
    ];

    public $sourcePath = '@bower/mocha';
    public $css = [ 'mocha.css' ];
    public $js = [ 'mocha.js' ];
}
