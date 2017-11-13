<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace LIN3S\SharedKernel\Infrastructure\Persistence\Sql;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
final class Pdo
{
    public const DATE_FORMAT = 'Y-m-d H:i:s';

    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute($this->pdo::ATTR_ERRMODE, $this->pdo::ERRMODE_EXCEPTION);
    }

    public function query(string $sql, array $parameters = []) : array
    {
        return $this->execute($sql, $parameters)->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function count(string $sql, array $parameters = []) : int
    {
        return (int)$this->execute($sql, $parameters)->fetchColumn();
    }

    public function insert(string $table, array $parameters) : void
    {
        if (!is_array($parameters[array_keys($parameters)[0]])) {
            $parameters = [$parameters];
        }

        $values = [];
        foreach ($parameters as $parameter) {
            $values = array_merge($values, array_values($parameter));
        }
        $numberOfInsertions = count($parameters);
        $columns = array_keys($parameters[array_keys($parameters)[0]]);
        $rowPlaces = '(' . implode(', ', array_fill(0, count($columns), '?')) . ')';
        $allPlaces = implode(', ', array_fill(0, $numberOfInsertions, $rowPlaces));

        $sql = 'INSERT INTO ' . $table . ' (' . implode(', ', $columns) . ') VALUES ' . $allPlaces;

        $this->execute($sql, $values);
    }

    public function update(string $table, array $parameters) : void
    {
        if (!is_array($parameters[array_keys($parameters)[0]])) {
            $parameters = [$parameters];
        }

        foreach ($parameters as $columns) {
            $sql = 'UPDATE ' . $table . ' SET ';
            $updates = 0;

            foreach ($columns as $column => $value) {
                if ($updates !== 0) {
                    $sql .= ',';
                }
                $sql .= $column . '= ?';
                $updates++;
            }
            $sql .= ' WHERE ' . array_keys($columns)[0] . '= ?';
            $values = array_values($columns);
            $values[] = $values[0];

            $this->execute($sql, $values);
        }
    }

    public function executeAtomically(callable $function)
    {
        $this->pdo->beginTransaction();

        try {
            $return = call_user_func($function, $this);

            $this->pdo->commit();

            return $return ?: true;
        } catch (\Exception | \Throwable $exception) {
            $this->pdo->rollback();

            throw $exception;
        }
    }

    public function execute(string $sql, array $parameters) : \PDOStatement
    {
        $statement = $this->pdo->prepare($sql);
        $statement->execute($parameters);

        return $statement;
    }
}
