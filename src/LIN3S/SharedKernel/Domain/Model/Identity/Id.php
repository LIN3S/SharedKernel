<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-2017 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Domain\Model\Identity;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
abstract class Id implements BaseId
{
    protected $id;

    public static function generate($id = null)
    {
        return new static($id);
    }

    protected function __construct($id = null)
    {
        if ($id !== null && !is_scalar($id)) {
            throw new InvalidIdException();
        }
        $this->id = null === $id ? Uuid::generate() : $id;
    }

    public function id()
    {
        return $this->id;
    }

    public function equals(Id $id)
    {
        return $this->id === $id->id();
    }

    public function __toString()
    {
        return (string)$this->id;
    }
}
