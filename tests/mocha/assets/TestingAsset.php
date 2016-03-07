<?php

namespace app\assets;

class TestingAsset extends \yii\web\AssetBundle
{
    public $depends = [
        'app\assets\MochaAsset',
        'app\assets\ChaiAsset',
        'app\assets\LecheAsset',
        'app\assets\SinonAsset',
    ];
}
