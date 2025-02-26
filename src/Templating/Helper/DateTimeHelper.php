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

namespace Sonata\IntlBundle\Templating\Helper;

use Sonata\IntlBundle\Locale\LocaleDetectorInterface;
use Sonata\IntlBundle\Timezone\TimezoneDetectorInterface;

/**
 * DateHelper displays culture information. More information here
 * http://userguide.icu-project.org/formatparse/datetime.
 *
 * NEXT_MAJOR: Remove all \DateTime hints from PHPDoc
 *
 * @author Thomas Rabaix <thomas.rabaix@ekino.com>
 * @author Alexander <iam.asm89@gmail.com>
 *
 * NEXT_MAJOR: Remove this class.
 *
 * @deprecated since sonata-project/intl-bundle 2.13, to be removed in version 3.0.
 */
class DateTimeHelper extends BaseHelper
{
    /**
     * @var TimezoneDetectorInterface
     */
    protected $timezoneDetector;

    /**
     * @var \ReflectionClass<\IntlDateFormatter>|null
     */
    protected static $reflection = null;

    /**
     * @param string $charset
     */
    public function __construct(TimezoneDetectorInterface $timezoneDetector, $charset, LocaleDetectorInterface $localeDetector)
    {
        parent::__construct($charset, $localeDetector);

        $this->timezoneDetector = $timezoneDetector;
    }

    /**
     * @param \DateTime|\DateTimeInterface|string|int $date
     * @param string|null                             $locale
     * @param string|null                             $timezone
     * @param int|null                                $dateType See \IntlDateFormatter::getDateType
     *
     * @return string
     */
    public function formatDate($date, $locale = null, $timezone = null, $dateType = null)
    {
        $date = $this->getDatetime($date, $timezone);

        $formatter = self::createInstance([
            'locale' => $locale ?? $this->localeDetector->getLocale(),
            'dateType' => $dateType ?? \IntlDateFormatter::MEDIUM,
            'timeType' => \IntlDateFormatter::NONE,
            'timezone' => $timezone ?? $this->timezoneDetector->getTimezone(),
            'calendar' => \IntlDateFormatter::GREGORIAN,
        ]);

        return $this->process($formatter, $date);
    }

    /**
     * @param \DateTime|\DateTimeInterface|string|int $datetime
     * @param string|null                             $locale
     * @param string|null                             $timezone
     * @param int|null                                $dateType See \IntlDateFormatter::getDateType
     * @param int|null                                $timeType See \IntlDateFormatter::getTimeType
     *
     * @return string
     */
    public function formatDateTime($datetime, $locale = null, $timezone = null, $dateType = null, $timeType = null)
    {
        $date = $this->getDatetime($datetime, $timezone);

        $formatter = self::createInstance([
            'locale' => $locale ?? $this->localeDetector->getLocale(),
            'dateType' => $dateType ?? \IntlDateFormatter::MEDIUM,
            'timeType' => $timeType ?? \IntlDateFormatter::MEDIUM,
            'timezone' => $timezone ?? $this->timezoneDetector->getTimezone(),
            'calendar' => \IntlDateFormatter::GREGORIAN,
        ]);

        return $this->process($formatter, $date);
    }

    /**
     * @param \DateTime|\DateTimeInterface|string|int $time
     * @param string|null                             $locale
     * @param string|null                             $timezone
     * @param int|null                                $timeType See \IntlDateFormatter::getTimeType
     *
     * @return string
     */
    public function formatTime($time, $locale = null, $timezone = null, $timeType = null)
    {
        $date = $this->getDatetime($time, $timezone);

        $formatter = self::createInstance([
            'locale' => $locale ?? $this->localeDetector->getLocale(),
            'dateType' => \IntlDateFormatter::NONE,
            'timeType' => $timeType ?? \IntlDateFormatter::MEDIUM,
            'timezone' => $timezone ?? $this->timezoneDetector->getTimezone(),
            'calendar' => \IntlDateFormatter::GREGORIAN,
        ]);

        return $this->process($formatter, $date);
    }

    /**
     * @param \DateTime|\DateTimeInterface|string|int $datetime
     * @param string                                  $pattern
     * @param string|null                             $locale
     * @param string|null                             $timezone
     *
     * @return string
     */
    public function format($datetime, $pattern, $locale = null, $timezone = null)
    {
        $date = $this->getDatetime($datetime, $timezone);

        $formatter = self::createInstance([
            'locale' => $locale ?? $this->localeDetector->getLocale(),
            'dateType' => \IntlDateFormatter::FULL,
            'timeType' => \IntlDateFormatter::FULL,
            'timezone' => $timezone ?? $this->timezoneDetector->getTimezone(),
            'calendar' => \IntlDateFormatter::GREGORIAN,
            'pattern' => $pattern,
        ]);

        return $this->process($formatter, $date);
    }

    /**
     * NEXT_MAJOR: Change to $date to \DateTimeInterface.
     *
     * @return string
     */
    public function process(\IntlDateFormatter $formatter, \DateTime $date)
    {
        return $this->fixCharset($formatter->format($date->getTimestamp()));
    }

    /**
     * Gets a date time instance by a given data and timezone.
     *
     * @param \DateTime|\DateTimeInterface|string|int $data     Value representing date
     * @param string|null                             $timezone Timezone of the date
     *
     * @return \DateTime
     */
    public function getDatetime($data, $timezone = null)
    {
        if ($data instanceof \DateTime) {
            return $data;
        }

        if ($data instanceof \DateTimeImmutable) {
            return \DateTime::createFromFormat(\DateTime::ATOM, $data->format(\DateTime::ATOM));
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
     * @return string
     */
    public function getName()
    {
        return 'sonata_intl_datetime';
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

        $instance = self::$reflection->newInstanceArgs($args);

        self::checkInternalClass($instance, \IntlDateFormatter::class, $args);

        return $instance;
    }
}
