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
 * @author Beñat Espiña <bespina@lin3s.com>
 */
final class ConnectionFactory
{
    private $driver;
    private $dbName;
    private $host;
    private $port;
    private $username;
    private $password;

    public function __construct($driver, $dbName, $host, $port, $username, $password)
    {
        $this->driver = $driver;
        $this->dbName = $dbName;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
    }

    public function createConnection()
    {
        $dsn = sprintf('%s:dbname=%s;host=%s;port=%s', $this->driver, $this->dbName, $this->host, $this->port);

        return new \PDO($dsn, $this->username, $this->password);
    }
}
