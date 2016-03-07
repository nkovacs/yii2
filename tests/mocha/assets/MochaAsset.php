<?php

namespace app\assets;

class MochaAsset extends \yii\web\AssetBundle
{
    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN,
    ];

    public $sourcePath = '@app/assets/js';
    public $js = [ 'init.js' ];
    public $depends = [ 'app\assets\MochaVendorAsset' ];
}
