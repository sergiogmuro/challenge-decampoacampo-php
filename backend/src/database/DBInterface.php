<?php

namespace Src\database;

interface DBInterface
{
    public static function getInstance();
    public function getConnection();
    public function __wakeup();
}
