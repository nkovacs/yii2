<?php

namespace app\tests;

class ValidationTestAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@app/tests/js';
    public $js = [
        'yii.validation.js',
    ];
    public $depends = [
        'yii\validators\ValidationAsset',
    ];
}
