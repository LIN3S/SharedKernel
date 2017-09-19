<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Infrastructure\Application\Tactician\Middlewares;

use League\Tactician\Middleware;
use LIN3S\SharedKernel\Infrastructure\Persistence\Sql\Pdo;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class PdoTransactionMiddleware implements Middleware
{
    private $pdo;

    public function __construct(Pdo $pdo)
    {
        $this->pdo = $pdo;
    }

    public function execute($command, callable $next)
    {
        $this->pdo->beginTransaction();

        try {
            $returnValue = $next($command);

            $this->pdo->commit();
        } catch (\Exception $exception) {
            $this->pdo->rollBack();

            throw $exception;
        } catch (\Throwable $exception) {
            $this->pdo->rollBack();

            throw $exception;
        }

        return $returnValue;
    }
}
