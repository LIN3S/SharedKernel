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

namespace Spec\LIN3S\SharedKernel\Domain\Model;

use Doctrine\Common\Collections\ArrayCollection;
use LIN3S\SharedKernel\Domain\Model\Collection\Collection;
use LIN3S\SharedKernel\Domain\Model\Collection\CollectionElementAlreadyAddedException;
use LIN3S\SharedKernel\Domain\Model\Collection\CollectionElementAlreadyRemovedException;
use LIN3S\SharedKernel\Domain\Model\Collection\CollectionElementInvalidException;
use LIN3S\SharedKernel\Domain\Model\DomainEvent;
use LIN3S\SharedKernel\Domain\Model\DomainEventCollection;
use PhpSpec\ObjectBehavior;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DomainEventCollectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DomainEventCollection::class);
    }

    function it_extends_collection()
    {
        $this->shouldHaveType(Collection::class);
    }

    function it_extends_doctrine_array_collection()
    {
        $this->shouldHaveType(ArrayCollection::class);
    }

    function it_creates_collection_with_an_invalid_element()
    {
        $this->beConstructedWith(['a-scalar-element']);
        $this->shouldThrow(CollectionElementInvalidException::class)->duringInstantiation();
    }

    function it_adds_to_collection_an_invalid_element()
    {
        $this->shouldThrow(CollectionElementInvalidException::class)->duringAdd('a-scalar-element');
    }

    function it_creates_collection_with_elements(DomainEvent $element, DomainEvent $element2)
    {
        $this->beConstructedWith([$element, $element2]);
        $this->toArray()->shouldReturn([$element, $element2]);
    }

    function it_adds_element_to_collection(DomainEvent $element)
    {
        $this->add($element);
        $this->toArray()->shouldReturn([$element]);
    }

    function it_removes_element_to_collection(DomainEvent $element)
    {
        $this->beConstructedWith([$element]);
        $this->removeElement($element);
        $this->toArray()->shouldReturn([]);
    }

    function it_does_not_add_already_added_element_from_collection(DomainEvent $element)
    {
        $this->beConstructedWith([$element]);
        $this->shouldThrow(CollectionElementAlreadyAddedException::class)->duringAdd($element);
    }

    function it_does_not_remove_already_removed_element_from_collection(DomainEvent $element)
    {
        $this->shouldThrow(CollectionElementAlreadyRemovedException::class)->duringRemoveElement($element);
    }
}
