<?php

/**
 * @extends Swoole\Http\Request
 */
class Request extends Facades
{
	protected static function getFacadeAccessor()
	{
		return 'request';
	}

    public static function all()
    {
        return $_REQUEST;
    }
}