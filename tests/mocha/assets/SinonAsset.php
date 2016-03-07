<?php

namespace app\assets;

class SinonAsset extends \yii\web\AssetBundle
{
    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN,
    ];

    public $sourcePath = '@app/assets/vendor';
    public $js = [ 'sinon.js', 'mocha-sinon.js' ];
    public $depends = [ 'app\assets\MochaAsset' ];
}
