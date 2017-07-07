<?php

namespace Group\Async;

use Config;
use \Group\Async\Pool\MysqlProxy;
use \Group\Async\Client\Mysql;

class AsyncMysql
{   
    protected static $timeout = 1;

    public static function setTimeout($timeout)
    {
        self::$timeout = $timeout;
    }

    public static function query($sql, $userPool = true)
    {   
        if ($userPool) {
            $pool = app('mysqlPool');
            $mysql = new MysqlProxy($pool);
        } else {
            $mysql = new Mysql();
            $mysql->setTimeout(self::$timeout); 
        }

        $mysql->query($sql);
        $res = (yield $mysql);
        if ($res && $res['response']) {
            yield $res['response'];
        }

        yield false;
    }
}