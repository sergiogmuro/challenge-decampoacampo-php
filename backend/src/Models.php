<?php
namespace Src;

use Src\database\Connection;

abstract class Models {
    protected Connection $pdo;

    public function __construct(Connection $pdo) {
        $this->pdo = $pdo;
    }
}
