<?php

namespace app\assets;

class LecheAsset extends \yii\web\AssetBundle
{
    public $jsOptions = [
        'position' => \yii\web\View::POS_BEGIN,
    ];

    public $sourcePath = '@app/assets/vendor';
    public $js = [ 'leche.js' ];
    public $depends = [ 'app\assets\MochaAsset' ];
}
