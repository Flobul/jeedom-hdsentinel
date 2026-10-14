<?php
function __(mixed $message, mixed $file = null): mixed {}
function init(mixed $key, mixed $default = ''): mixed {}
function isConnect(mixed $profile = ''): bool {}
function include_file(mixed $folder, mixed $file, mixed $type, mixed $plugin = ''): void {}
function sendVarToJS(mixed $name, mixed $value): void {}
function displayException(mixed $exception): string {}

class sshmanager
{
    public const CONFIG_HOST = 'host';
    public const CONFIG_USERNAME = 'username';
    public const CONFIG_PASSWORD = 'password';

    public static function executeCmds(mixed $hostId, mixed $commands): mixed {}
    public static function getFile(mixed $hostId, mixed $local, mixed $remote): mixed {}
    public static function sendFile(mixed $hostId, mixed $local, mixed $remote): mixed {}
}
