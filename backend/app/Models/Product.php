<?php

namespace App\Models;

use Src\models\Models;

class Product extends Models
{
    protected string $tableName = 'productos';

    static function getBaseQuery(): string
    {
        return "SELECT
            id,
            nombre,
            descripcion,
            precio,
            created_at,
            updated_at
        FROM productos
        WHERE deleted_at IS NULL";
    }
}
