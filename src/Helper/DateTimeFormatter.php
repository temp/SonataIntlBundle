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

namespace Sonata\IntlBundle\Helper;

use Sonata\IntlBundle\Timezone\TimezoneDetectorInterface;

/**
 * DateHelper displays culture information. More information here
 * http://userguide.icu-project.org/formatparse/datetime.
 *
 * @author Thomas Rabaix <thomas.rabaix@ekino.com>
 * @author Alexander <iam.asm89@gmail.com>
 */
final class DateTimeFormatter extends BaseHelper implements DateTimeFormatterInterface
{
    private TimezoneDetectorInterface $timezoneDetector;

    /**
     * @var \ReflectionClass<\IntlDateFormatter>|null
     */
    private static ?\ReflectionClass $reflection = null;

    public function __construct(TimezoneDetectorInterface $timezoneDetector, string $charset)
    {
        parent::__construct($charset);

        $this->timezoneDetector = $timezoneDetector;
    }

    public function formatDate($date, ?string $locale = null, ?string $timezone = null, ?int $dateType = null): string
    {
        $date = $this->getDatetime($date, $timezone);

        $formatter = self::createInstance([
            'locale' => $locale ?? $this->getLocale(),
            'dateType' => $dateType ?? \IntlDateFormatter::MEDIUM,
            'timeType' => \IntlDateFormatter::NONE,
            'timezone' => $timezone ?? $this->timezoneDetector->getTimezone(),
            'calendar' => \IntlDateFormatter::GREGORIAN,
        ]);

        return $this->process($formatter, $date);
    }

    public function formatDateTime($datetime, ?string $locale = null, ?string $timezone = null, ?int $dateType = null, ?int $timeType = null): string
    {
        $date = $this->getDatetime($datetime, $timezone);

        $formatter = self::createInstance([
            'locale' => $locale ?? $this->getLocale(),
            'dateType' => $dateType ?? \IntlDateFormatter::MEDIUM,
            'timeType' => $timeType ?? \IntlDateFormatter::MEDIUM,
            'timezone' => $timezone ?? $this->timezoneDetector->getTimezone(),
            'calendar' => \IntlDateFormatter::GREGORIAN,
        ]);

        return $this->process($formatter, $date);
    }

    public function formatTime($time, ?string $locale = null, ?string $timezone = null, ?int $timeType = null): string
    {
        $date = $this->getDatetime($time, $timezone);

        $formatter = self::createInstance([
            'locale' => $locale ?? $this->getLocale(),
            'dateType' => \IntlDateFormatter::NONE,
            'timeType' => $timeType ?? \IntlDateFormatter::MEDIUM,
            'timezone' => $timezone ?? $this->timezoneDetector->getTimezone(),
            'calendar' => \IntlDateFormatter::GREGORIAN,
        ]);

        return $this->process($formatter, $date);
    }

    public function format($datetime, string $pattern, ?string $locale = null, ?string $timezone = null): string
    {
        $date = $this->getDatetime($datetime, $timezone);

        $formatter = self::createInstance([
            'locale' => $locale ?? $this->getLocale(),
            'dateType' => \IntlDateFormatter::FULL,
            'timeType' => \IntlDateFormatter::FULL,
            'timezone' => $timezone ?? $this->timezoneDetector->getTimezone(),
            'calendar' => \IntlDateFormatter::GREGORIAN,
            'pattern' => $pattern,
        ]);

        return $this->process($formatter, $date);
    }

    public function getDatetime($data, ?string $timezone = null): \DateTimeInterface
    {
        if ($data instanceof \DateTimeInterface) {
            return $data;
        }

        // the format method accept array or integer
        if (is_numeric($data)) {
            $data = (int) $data;
        }

        if (\is_string($data)) {
            $data = strtotime($data);
        }

        $date = new \DateTime();
        $date->setTimestamp($data);
        $date->setTimezone(new \DateTimeZone($timezone ?? $this->timezoneDetector->getTimezone()));

        return $date;
    }

    /**
     * @param mixed[] $args
     *
     * @return \IntlDateFormatter
     */
    protected static function createInstance(array $args = [])
    {
        if (null === self::$reflection) {
            self::$reflection = new \ReflectionClass(\IntlDateFormatter::class);
        }

        /**
         * @var \IntlDateFormatter $instance
         */
        $instance = self::$reflection->newInstanceArgs($args);

        self::checkInternalClass($instance, \IntlDateFormatter::class, $args);

        return $instance;
    }

    private function process(\IntlDateFormatter $formatter, \DateTimeInterface $date): string
    {
        return $this->fixCharset($formatter->format($date));
    }
}
