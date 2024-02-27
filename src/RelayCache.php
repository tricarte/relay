<?php

namespace Tricarte\Relay;

use Relay\Relay;
use CacheWerk\Relay\Psr\SimpleCache\RelayCache as RCache;

class RelayCache
{
    /**
     * @var RCache
     */
    public static $instance = null;

    public static function start($worker) {
        self::setInstance();
    }

    /**
     * @return void
     */
    public static function setInstance()
    {
        $relay = new Relay;
        // TODO: support connection names if there is such a thing.
        // TODO: The plugin has also its own redis.php configuration.
        // TODO: Throw exception when connection error occurs
        $config = config('redis.default');
        $relay->connect($config['host'], $config['port']);
        self::$instance = new RCache($relay);
    }

    /**
     * @return RCache
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public static function __callStatic($name, $arguments)
    {
        return static::getInstance()->{$name}(...$arguments);
    }
}
