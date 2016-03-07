<?php
use app\assets\TestingAsset;
use yii\helpers\Html;

/**
 * @var \yii\web\View $this
 * @var string $content
 */
TestingAsset::register($this);

$this->registerJs("
    mocha.checkLeaks();
    mocha.globals(['jQuery', 'yii', 'validators']);
    mocha.run();
", \yii\web\View::POS_END);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title>Mocha Tests</title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
    <div id="mocha"></div>
    <?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
