<?php
/**
 * Created by IntelliJ IDEA.
 * User: rek
 * Date: 2017/4/22
 * Time: PM3:35
 */

namespace event;

use X;
use x2ts\route\event\PostRouteEvent;
use x2ts\Toolkit;

class Setup {
    public static function setupTidewaysProfiler(PostRouteEvent $ev) {
        if (class_exists('Tideways\Profiler')) {
            $action = $ev->action;
            $host = $action->header('Host');
            $subDomain = substr($host, 0, strpos($host, '.'));
            \Tideways\Profiler::setCustomVariable('subdomain', $subDomain);
            \Tideways\Profiler::setTransactionName(
                get_class($action) . '::' .
                Toolkit::toCamelCase('http ' . strtolower($action->server('REQUEST_METHOD')))
            );
        }
    }

    public static function setup() {
        X::bus()->on(PostRouteEvent::name(), [Setup::class, 'setupTidewaysProfiler']);
    }
}