<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sonata\IntlBundle\Tests\Timezone;

use PHPUnit\Framework\TestCase;
use Sonata\IntlBundle\Locale\LocaleDetectorInterface;
use Sonata\IntlBundle\Timezone\LocaleBasedTimezoneDetector;

/**
 * @author Alexander <iam.asm89@gmail.com>
 *
 * NEXT_MAJOR: remove this class.
 *
 * @deprecated since sonata-project/intl-bundle 2.13, to be removed in version 3.0.
 */
final class LocaleBasedTimezoneDetectorTest extends TestCase
{
    public function testDetectsTimezoneForLocale(): void
    {
        $localeDetector = $this->createMock(LocaleDetectorInterface::class);
        $localeDetector
            ->method('getLocale')
            ->willReturn('fr');

        $timezoneDetector = new LocaleBasedTimezoneDetector($localeDetector, ['fr' => 'Europe/Paris']);
        static::assertSame('Europe/Paris', $timezoneDetector->getTimezone());
    }

    public function testTimezoneNotDetected(): void
    {
        $localeDetector = $this->createMock(LocaleDetectorInterface::class);
        $localeDetector
            ->method('getLocale')
            ->willReturn('de');

        $timezoneDetector = new LocaleBasedTimezoneDetector($localeDetector, ['fr' => 'Europe/Paris']);
        static::assertNull($timezoneDetector->getTimezone());
    }
}
