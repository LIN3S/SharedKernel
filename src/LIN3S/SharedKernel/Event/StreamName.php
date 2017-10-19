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

    public static function fromName(string $name) : self
    {
        list($name, $aggregateId) = explode('-', $name, 2);

        return new self($aggregateId, $name);
    }

    public static function from(Id $aggregateId, string $name) : self
    {
        return new self($aggregateId->id(), $name);
    }

    private function __construct(string $aggregateId, string $name)
    {
        $this->setName($name);
        $this->aggregateId = $aggregateId;
    }

    private function setName(string $name) : void
    {
        $this->checkNameIsValid($name);
        $this->name = $name;
    }

    private function checkNameIsValid(string $name) : void
    {
        if ('' === $name) {
            throw new StreamNameIsEmpty();
        }
    }

    public function name() : string
    {
        return sprintf('%s-%s', $this->name, $this->aggregateId);
    }

    public function aggregateId() : string
    {
        return $this->aggregateId;
    }

    public function __toString() : string
    {
        return (string) $this->name();
    }
}
