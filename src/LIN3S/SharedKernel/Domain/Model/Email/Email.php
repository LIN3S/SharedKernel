<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016 LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LIN3S\SharedKernel\Domain\Model\Email;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class Email
{
    private $email;
    private $domain;
    private $localPart;

    public function __construct($anEmail)
    {
        if (!filter_var($anEmail, FILTER_VALIDATE_EMAIL)) {
            throw new EmailInvalidException();
        }
        $this->email = $anEmail;
        $this->localPart = implode(explode('@', $this->email, -1), '@');
        $this->domain = str_replace($this->localPart . '@', '', $this->email);
    }

    public function email()
    {
        return $this->email;
    }

    public function localPart()
    {
        return $this->localPart;
    }

    public function domain()
    {
        return $this->domain;
    }

    public function equals(Email $anEmail)
    {
        return strtolower((string) $this) === strtolower((string) $anEmail);
    }

    public function __toString()
    {
        return (string) $this->email;
    }
}
