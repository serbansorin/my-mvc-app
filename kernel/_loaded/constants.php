<?php

$constsDefined ??= false;

if ($constsDefined) {
    return [$route, $app, $bootstrap];
}

const APP_DIR = ROOT_DIR . '/app';
const CONFIG_DIR = ROOT_DIR . '/config';
const PUBLIC_DIR = ROOT_DIR . '/public';
const RESOURCES_DIR = ROOT_DIR . '/resources';
const ROUTES_DIR = ROOT_DIR . '/routes';
const STORAGE_DIR = ROOT_DIR . '/storage';
const VENDOR_DIR = ROOT_DIR . '/vendor';
const VIEW_DIR = APP_DIR . '/views';
const KERNEL_DIR = ROOT_DIR . '/boot';

$constsDefined = true;
