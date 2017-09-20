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

namespace LIN3S\SharedKernel\Infrastructure\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use LIN3S\SharedKernel\Infrastructure\Persistence\Sql\Event\SqlEventStore;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Version20170920174845 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql(SqlEventStore::createSchema());
    }

    public function down(Schema $schema) : void
    {
        $this->addSql(SqlEventStore::removeSchema());
    }
}
