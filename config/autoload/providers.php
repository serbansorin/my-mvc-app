<?php

enum Providers: string
{
    case Database = 'database';
    case Mail = 'mail';
    case Queue = 'queue';
    case View = 'view';
    case Session = 'session';
    case Cache = 'cache';
}

class Provider
{
    public static function get(Providers $provider): ServiceProviderInterface
    {
        return match ($provider) {
            Providers::Database => new \Providers\DatabaseProvider(),
            Providers::Mail => new \Providers\MailProvider(),
            Providers::Queue => new \Providers\QueueProvider(),
            Providers::View => new \Providers\ViewProvider(),
            Providers::Session => new \Providers\SessionProvider(),
            Providers::Cache => new \Providers\CacheProvider(),

            default => new \Providers\DatabaseProvider()
        };
    }

    public static function toArray(): array
    {
        return [
            Providers::Database,
            Providers::Mail,
            Providers::Queue,
            Providers::View,
            Providers::Session,
            Providers::Cache
        ];
    }

    function __toString()
    {
        return json_encode($this);
    }

    function __wakeup()
    {
        return \Swoole\Serialize::unpack($this);
    }

    function __sleep()
    {
        return [];
    }

    function __unserialize($data)
    {
        return \Swoole\Serialize::unpack($data);
    }

    function __serialize()
    {
        return \Swoole\Serialize::pack($this);
    }
}