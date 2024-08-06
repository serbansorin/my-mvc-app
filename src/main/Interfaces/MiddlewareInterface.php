<?php

namespace Main\Interfaces;

interface MiddlewareInterface
{
    public function handle();
    public function terminate();
    public function next();
}