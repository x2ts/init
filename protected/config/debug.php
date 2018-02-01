<?php
return [
    'component' => [
        'bus'       => [
            'class'     => x2ts\event\Bus::class,
            'singleton' => true,
            'conf'      => [],
        ],
        'logger'    => [
            'class'     => x2ts\Logger::class,
            'singleton' => true,
            'conf'      => [
                'name'     => 'app',
                'handlers' => [
                    \Monolog\Handler\StreamHandler::class => [
                        X_RUNTIME_ROOT . ($_SERVER['HTTP_DEBUG_KEY'] ?? 'app') . '.log',
                        (X_DEBUG || isset($_SERVER['HTTP_DEBUG_KEY'])) ? X_LOG_DEBUG : X_LOG_NOTICE,
                    ],
                    \x2ts\log\AmqpDelegateHandler::class  => [
                        [
                            'sock'              => 'unix:///var/run/amqp-delegate.sock',
                            'connectionTimeout' => 3,
                            'writeTimeout'      => 10,
                            'exchange'          => 'log',
                            'routingKey'        => 'log.' . X_PROJECT . '.{channel}.{level}',
                        ],
                        (X_DEBUG || isset($_SERVER['HTTP_DEBUG_KEY'])) ? X_LOG_DEBUG : X_LOG_NOTICE,
                        X_DEBUG,
                    ],
                ],
            ],
        ],
        'router'    => [
            'class'     => x2ts\route\Router::class,
            'singleton' => true,
            'conf'      => [
                'baseUri' => '',
                'rules'   => [
                    \x2ts\route\rule\Simple::class => [
                        'default' => '/index',
                    ],
                ],
            ],
        ],
        'db'        => [
            'class'     => x2ts\db\MySQL::class,
            'singleton' => true,
            'conf'      => [
                'host'       => 'mysql.localdomain',
                'port'       => 3306,
                'user'       => 'root',
                'password'   => 'password',
                'dbname'     => 'test',
                'charset'    => 'utf8',
                'persistent' => true,
            ],
        ],
        'model'     => [
            'class'     => x2ts\db\orm\Model::class,
            'singleton' => false,
            'conf'      => [
                'namespace'   => 'model',
                'tablePrefix' => '',
                'dbId'        => 'db',
                'schemaConf'  => [
                    'schemaCacheId'       => 'cc',
                    'useSchemaCache'      => true,
                    'schemaCacheDuration' => 300,
                ],
                'manager'     => [
                    'class' => x2ts\db\orm\DirectModelManager::class,
                    'conf'  => [],
                ],
            ],
        ],
        'cache'     => [
            'class'     => x2ts\cache\RCache::class,
            'singleton' => true,
            'conf'      => [
                'host'           => 'redis.localdomain',
                'port'           => 6379,
                'timeout'        => 0, //float, value in seconds, default is 0 meaning unlimited
                'persistent'     => false,
                'persistentHash' => 'cache',
                'database'       => 7,
                'auth'           => null,
                'keyPrefix'      => 'c_',
            ],
        ],
        'cc'        => [
            'class'     => x2ts\cache\CCache::class,
            'singleton' => true,
            'conf'      => [
                'cacheDir' => X_RUNTIME_ROOT . '/cache',
            ],
        ],
        'view'      => [
            'class'     => x2ts\view\Simple::class,
            'singleton' => true,
            'conf'      => [
                'tpl_dir'       => X_PROJECT_ROOT . '/protected/view',
                'tpl_ext'       => 'html',
                'compile_dir'   => X_RUNTIME_ROOT . '/compiled_template',
                'enable_clip'   => false,
                'cacheId'       => 'cc',
                'cacheDuration' => 60,
            ],
        ],
        'validator' => [
            'class'     => x2ts\validator\Validator::class,
            'singleton' => false,
            'conf'      => [
                'encoding' => 'UTF-8',
                'autoTrim' => true,
            ],
        ],
        'session'   => [
            'class'     => \x2ts\Session::class,
            'singleton' => false,
            'conf'      => [
                'saveComponentId' => 'cache',
                'saveKeyPrefix'   => 'session_',
                'tokenLength'     => 16,
                'autoSave'        => true,
                'expireIn'        => 604800,
                'cookie'          => [
                    'name'     => 'X_SESSION_ID',
                    'expireIn' => null,
                    'path'     => '/',
                    'domain'   => null,
                    'secure'   => null,
                    'httpOnly' => true,
                ],
            ],
        ],
    ],
];
