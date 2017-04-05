<?php

/*
 * This file is part of the Shared Kernel library.
 *
 * Copyright (c) 2016-present LIN3S <info@lin3s.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Spec\LIN3S\SharedKernel\Domain\Model\Locale;

use LIN3S\SharedKernel\Domain\Model\Locale\InvalidLocaleException;
use PhpSpec\ObjectBehavior;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class LocaleSpec extends ObjectBehavior
{
    function it_does_not_build_invalid_locale()
    {
        $this->beConstructedWith('not_valid-locale');
        $this->shouldThrow(InvalidLocaleException::class)->duringInstantiation();
    }

    function it_gets_es_ES_locale()
    {
        $this->beConstructedWith('es_ES');
        $this->locale()->shouldReturn('es_ES');
        $this->countryCode()->shouldReturn('ES');
        $this->languageCode()->shouldReturn('es');
        $this->country()->shouldReturn('Spain');
        $this->language()->shouldReturn('Spanish; Castilian');
        $this->__toString()->shouldReturn('es_ES');
    }

    function it_gets_en_US_locale()
    {
        $this->beConstructedWith('en_US');
        $this->locale()->shouldReturn('en_US');
        $this->countryCode()->shouldReturn('US');
        $this->languageCode()->shouldReturn('en');
        $this->country()->shouldReturn('United States');
        $this->language()->shouldReturn('English');
        $this->__toString()->shouldReturn('en_US');
    }

    function it_gets_eu_locale()
    {
        $this->beConstructedWith('eu');
        $this->locale()->shouldReturn('eu');
        $this->countryCode()->shouldReturn(null);
        $this->languageCode()->shouldReturn('eu');
        $this->country()->shouldReturn(null);
        $this->language()->shouldReturn('Basque');
        $this->__toString()->shouldReturn('eu');
    }
}
