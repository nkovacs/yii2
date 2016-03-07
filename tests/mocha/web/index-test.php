<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

require(__DIR__ . '/../../../framework/Yii.php');
$application = new yii\web\Application(require(__DIR__ . '/../config/config.php'));
$application->run();

/*require(__DIR__ . '/../../vendor/autoload.php');
require_once(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../codeception/config/acceptance.php');

$config['controllerNamespace'] = 'app\tests\mocha\controllers';
$config['viewPath'] = dirname(__DIR__) . '/mocha/views';
$config['defaultRoute'] = 'site/index';

(new yii\web\Application($config))->run();*/
