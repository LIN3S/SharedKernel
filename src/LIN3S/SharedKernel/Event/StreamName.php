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

namespace LIN3S\SharedKernel\Event;

use LIN3S\SharedKernel\Domain\Model\Identity\Id;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class StreamName
{
    private $name;
    private $aggregateId;

    public function __construct(Id $aggregateId, $name)
    {
        $this->setName($name);
        $this->aggregateId = $aggregateId;
    }

    private function setName($name)
    {
        $this->checkNameIsValid($name);
        $this->name = $name;
    }

    private function checkNameIsValid($name)
    {
        if ('' === $name) {
            throw new StreamNameIsEmpty();
        }
    }

    public function name()
    {
        return sprintf('%s-%s', $this->name, $this->aggregateId()->id());
    }

    public function aggregateId()
    {
        return $this->aggregateId;
    }

    public function __toString()
    {
        return (string) $this->name();
    }
}
