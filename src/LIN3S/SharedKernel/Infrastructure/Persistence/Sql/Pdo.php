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
    }

    public function exec($statement) : int
    {
        return $this->pdo->exec($statement);
    }

    public function beginTransaction() : void
    {
        $this->pdo->beginTransaction();
    }

    public function commit() : void
    {
        $this->pdo->commit();
    }

    public function rollback() : void
    {
        $this->pdo->rollBack();
    }

    public function execute($sql, array $parameters) : \PDOStatement
    {
        $statement = $this->pdo->prepare($sql);
        $result = $statement->execute($parameters);

        if (false === $result) {
            throw new PdoExecutionFailed($statement->errorInfo());
        }

        return $statement;
    }
}
