<?php

namespace Main\Interfaces;

interface ServiceProviderInterface
{
    public static function register();
    public function boot();
    public function get(): ServiceInterface;
}