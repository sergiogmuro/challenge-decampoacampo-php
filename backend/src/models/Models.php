<?php

namespace Src\models;

use Src\database\Connection;

abstract class Models implements ModelsInterface
{
    protected Connection $pdo;

    public function __construct(Connection $pdo)
    {
        $this->pdo = $pdo;

        return $this;
    }

    static function getBaseQuery(): string
    {
        throw new \Exception('Not implemented method [getBaseQuery]');
    }

    public function getAll(): array
    {
        $query = $this->getBaseQuery();

        return $this->pdo->getAll($query);
    }

    public function find($id): array
    {
        $queryBase = $this->getBaseQuery();
        $queryParts[] = $queryBase;

        $whereExists = false;
        if (strpos($queryBase, 'WHERE') !== false) {
            $whereExists = true;
        }

        $queryParts[] = $whereExists ? 'AND' : 'WHERE';
        $queryParts[] = "id = :id";

        $query = join(PHP_EOL, $queryParts);

        return $this->pdo->first($query, ['id' => $id]) ?? [];
    }

    public function insert($params): array
    {
        $tableName = $this->getTableName();

        ['columns' => $columns, 'values' => $values, 'bind' => $bind] = $this->processColumnAndValues($params);

        $bind = join(',', $bind);
        $columns = join(',', $columns);
        $query = "INSERT INTO `{$tableName}` ({$columns}) VALUES ({$bind})";

        $id = $this->pdo->insert($query, $values);

        return $this->find($id);
    }

    public function update(int $id, array $params): array
    {
        $tableName = $this->getTableName();

        ['columns' => $columns, 'values' => $values] = $this->processColumnAndValues($params);
        $binders = [];
        foreach ($columns as $column) {
            $binders[] = "{$column}=:{$column}";
        }
        $values['id'] = $id;
        $binders = join(',', $binders);

        $query = "UPDATE `{$tableName}` SET {$binders} WHERE id = :id";

        if (!$this->pdo->query($query, $values)) {
            throw new \Exception('Product could not be updated');
        }

        return $this->find($id);
    }

    public function delete(int $id, bool $softDelete = true)
    {
        $tableName = $this->getTableName();
        $values['id'] = $id;

        $query = "UPDATE `{$tableName}` SET deleted_at=current_time WHERE id = :id";

        if (!$softDelete) {
            $query = "DELETE FROM `{$tableName}` WHERE id = :id";
        }

        if (!$this->pdo->query($query, $values)) {
            throw new \Exception('Product could not be removed');
        }

        return $this->find($id);
    }

    private function pluralize($value)
    {
        return $value . 's';
    }

    private function getTableName()
    {
        if (isset($this->tableName)) {
            return $this->tableName;
        }

        $modelName = new \ReflectionClass(get_called_class())->getShortName();
        $model = $this->pluralize(strtolower($modelName));
        return $model;
    }

    private function processColumnAndValues(array $params = [])
    {
        $columns = array_keys($params);
        $columnValues = array_values($params);
        $bind = [];
        $values = [];
        foreach ($columns as $i => $column) {
            $value = $columnValues[$i];
            switch (gettype($value)) {
                case 'string':
                    $bind[] = ":{$column}";
                    $values[$column] = "{$value}";
                    break;
                case 'double':
                default:
                    $bind[] = ":{$column}";
                    $values[$column] = $value;
                    break;
            }
        }

        return [
            'values' => $values,
            'columns' => $columns,
            'bind' => $bind,
        ];
    }
}
