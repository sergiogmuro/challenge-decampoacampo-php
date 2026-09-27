<?php

namespace Src\models;

interface ModelsInterface
{
    static function getBaseQuery(): string;
}
