<?php
/**
 * Created by IntelliJ IDEA.
 * User: rek
 * Date: 2015/11/20
 * Time: 下午3:35
 */

define('X_PROJECT_ROOT', __DIR__);
define('X_RUNTIME_ROOT', X_PROJECT_ROOT . '/protected/runtime');
define('X_DEBUG', true);
define('X_ENV', 'debug');

require_once X_PROJECT_ROOT . '/vendor/autoload.php';
require_once X_PROJECT_ROOT . '/protected/common.php';

ini_set('display_errors', X_DEBUG ? 'On' : 'Off');

$environments = ['debug', 'test', 'release'];
foreach ($environments as $env) {
    X::conf(require X_PROJECT_ROOT . '/protected/config/' . $env . '.php');
    if ($env === X_ENV) {
        break;
    }
}

event\Setup::setup();
