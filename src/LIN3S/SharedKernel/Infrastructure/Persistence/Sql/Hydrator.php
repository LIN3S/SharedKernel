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

use LIN3S\SharedKernel\Exception\Exception;

/**
 * @author Rubén García <ruben@lin3s.com>
 */
final class Hydrator
{
    public function constructObjectWith(string $class, array $values)
    {
        $reflectionClass = new \ReflectionClass($class);
        $object = $reflectionClass->newInstanceWithoutConstructor();
        $this->setValuesToObject($reflectionClass, $object, $values);

        Return $object;
    }

    private function setValuesToObject(\ReflectionClass $reflectionClass, $object, array $values)
    {
        foreach ($values as $key => $value) {
            if (!$reflectionClass->hasProperty($key)) {
                throw new Exception(
                    'Property ' . $key . ' doesn\'t exist in construction of object ' . $reflectionClass->getName()
                );
            }
            $refProperty = $reflectionClass->getProperty($key);
            if (!$refProperty->getDeclaringClass()->getName() === $reflectionClass->getName()) {
                throw new Exception(
                    'Property has declaring class '
                    . $refProperty->getDeclaringClass()->getName()
                    . ' but insert '
                    . $reflectionClass->getName()
                );
            }
            $refProperty->setAccessible(true);
            $refProperty->setValue($object, $value);
            unset($values[$key]);
        }
        if ($reflectionClass->getParentClass()) {
            $object = $this->setValuesToObject($reflectionClass->getParentClass(), $object, $values);
        }
        return $object;
    }
}
