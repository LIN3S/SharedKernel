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
final class ConnectionFactory
{
    private $driver;
    private $dbName;
    private $host;
    private $port;
    private $username;
    private $password;
    private $charset;

    public function __construct(
        string $driver,
        string $dbName,
        string $host,
        ?string $port,
        string $username,
        ?string $password,
        string $charset = 'utf8'
    ) {
        $this->driver = $driver;
        $this->dbName = $dbName;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        $this->charset = $charset;
    }

    public function createConnection() : PDO
    {
        $dsn = sprintf(
            '%s:dbname=%s;host=%s;port=%s;charset=%s',
            $this->driver,
            $this->dbName,
            $this->host,
            $this->port,
            $this->charset
        );

        return new Pdo(
            new \PDO($dsn, $this->username, $this->password)
        );
    }
}
