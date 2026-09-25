<?php

namespace App\Models;

use Src\database\Connection;
use Src\Models;

class Product extends Models
{
    public function __construct(Connection $pdo) {
        parent::__construct($pdo);
    }

    public function getAll() {
         return $this->pdo->getConnection()->query("SELECT * FROM productos")->fetchAll();
    }
}
