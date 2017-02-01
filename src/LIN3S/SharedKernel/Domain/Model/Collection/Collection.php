<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-2017 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Domain\Model\Collection;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
abstract class Collection extends ArrayCollection
{
    abstract protected function type();

    public function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            $this->validate($element);
        }
        parent::__construct($elements);
    }

    public function add($element)
    {
        if ($this->contains($this->validate($element))) {
            throw new CollectionElementAlreadyAddedException();
        }
        parent::add($element);
    }

    public function removeElement($element)
    {
        if (!$this->contains($element)) {
            throw new CollectionElementAlreadyRemovedException();
        }
        parent::removeElement($element);
    }

    private function validate($element)
    {
        if (is_scalar($element)
            || false === (is_subclass_of($element, $this->type())
                || (get_class($element) === $this->type())
            )
        ) {
            throw new CollectionElementInvalidException();
        }

        return $element;
    }
}
