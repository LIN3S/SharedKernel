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

namespace LIN3S\SharedKernel\Domain\Model\Collection;

use Doctrine\Common\Collections\ArrayCollection;
use LIN3S\SharedKernel\Domain\Model\Identity\Id;

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

    public function add($element) : void
    {
        $this->validate($element);

        if ($this->contains($element)) {
            throw new CollectionElementAlreadyAddedException();
        }
        parent::add($element);
    }

    public function removeElement($element) : void
    {
        if (!$this->contains($element)) {
            throw new CollectionElementAlreadyRemovedException();
        }
        if ($element instanceof Id) {
            foreach ($this->toArray() as $key => $el) {
                if ($element->equals($el)) {
                    $this->remove($key);
                }
            }

            return;
        }
        parent::removeElement($element);
    }

    public function contains($element) : bool
    {
        if ($element instanceof Id) {
            foreach ($this->toArray() as $el) {
                if ($element->equals($el)) {
                    return true;
                }
            }

            return false;
        }

        return parent::contains($element);
    }

    private function validate($element) : void
    {
        if (is_scalar($element)
            || false === (is_subclass_of($element, $this->type())
                || (get_class($element) === $this->type())
            )
        ) {
            throw new CollectionElementInvalidException();
        }
    }
}
