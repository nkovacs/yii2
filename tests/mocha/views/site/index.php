<?php

use yii\web\View;
use app\tests\ValidationTestAsset;

$model = new \yii\base\DynamicModel(['attribute' => '']);

$numberValidator = Yii::createObject(\yii\validators\NumberValidator::className(), []);
$integerValidator = Yii::createObject(\yii\validators\NumberValidator::className(), ['integerOnly' => true]);

$wrapValidator = function($validator) use ($model) {
    return 'function(value, messages) {' . $validator->clientValidateAttribute($model, 'attribute', $this) . '}';
};

$this->registerJs('
    var validators = {};

    validators.number = ' . $wrapValidator($numberValidator) . ';
    validators.integer = ' . $wrapValidator($integerValidator) . ';
', View::POS_BEGIN);

ValidationTestAsset::register($this);
