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

/**
 * NumberHelper displays culture information.
 *
 * @author Thomas Rabaix <thomas.rabaix@ekino.com>
 * @author Stefano Arlandini <sarlandini@alice.it>
 *
 * NEXT_MAJOR: Remove this class.
 *
 * @deprecated since sonata-project/intl-bundle 2.13, to be removed in version 3.0.
 */
class NumberHelper extends BaseHelper
{
    /**
     * @var array<string, int|float> The default attributes to apply to the \NumberFormatter instance
     */
    protected $attributes = [];

    /**
     * @var array<string, string> The default text attributes to apply to the \NumberFormatter instance
     */
    protected $textAttributes = [];

    /**
     * @var array<string, string> The symbols used by \NumberFormatter
     */
    protected $symbols = [];

    /**
     * @param string                   $charset        The output charset of the helper
     * @param LocaleDetectorInterface  $localeDetector A locale detector instance
     * @param array<string, int|float> $attributes     The default attributes to apply to the \NumberFormatter instance
     * @param array<string, string>    $textAttributes The default text attributes to apply to the \NumberFormatter instance
     * @param array<string, string>    $symbols        The default symbols to apply to the \NumberFormatter instance
     */
    public function __construct($charset, LocaleDetectorInterface $localeDetector, array $attributes = [], array $textAttributes = [], array $symbols = [])
    {
        parent::__construct($charset, $localeDetector);

        $this->attributes = $attributes;
        $this->textAttributes = $textAttributes;
        $this->symbols = $symbols;
    }

    /**
     * Formats a number as percent according to the specified locale and
     * \NumberFormatter attributes.
     *
     * @param string|float|int                  $number          The number to format
     * @param array<string, int|float>          $attributes      The attributes used by \NumberFormatter
     * @param array<string, string>             $textAttributes  The text attributes used by \NumberFormatter
     * @param array<string, string>|string|null $symbolsOrLocale
     *
     * @return string The formatted number
     */
    public function formatPercent($number, array $attributes = [], array $textAttributes = [], $symbolsOrLocale = null)
    {
        $methodArgs = array_pad(\func_get_args(), 5, null);

        [$locale, $symbols] = $this->normalizeMethodSignature($methodArgs[3], $methodArgs[4]);
        $number = $this->parseNumericValue($number);

        return $this->format($number, \NumberFormatter::PERCENT, $attributes, $textAttributes, $symbols, $locale);
    }

    /**
     * Formats a number as duration according to the specified locale and
     * \NumberFormatter attributes.
     *
     * @param string|float|int                  $number          The number to format
     * @param array<string, int|float>          $attributes      The attributes used by \NumberFormatter
     * @param array<string, string>             $textAttributes  The text attributes used by \NumberFormatter
     * @param array<string, string>|string|null $symbolsOrLocale
     *
     * @return string The formatted number
     */
    public function formatDuration($number, array $attributes = [], array $textAttributes = [], $symbolsOrLocale = null)
    {
        $methodArgs = array_pad(\func_get_args(), 5, null);

        [$locale, $symbols] = $this->normalizeMethodSignature($methodArgs[3], $methodArgs[4]);
        $number = $this->parseNumericValue($number);

        return $this->format($number, \NumberFormatter::DURATION, $attributes, $textAttributes, $symbols, $locale);
    }

    /**
     * Formats a number as decimal according to the specified locale and
     * \NumberFormatter attributes.
     *
     * @param string|float|int                  $number          The number to format
     * @param array<string, int|float>          $attributes      The attributes used by \NumberFormatter
     * @param array<string, string>             $textAttributes  The text attributes used by \NumberFormatter
     * @param array<string, string>|string|null $symbolsOrLocale
     *
     * @return string The formatted number
     */
    public function formatDecimal($number, array $attributes = [], array $textAttributes = [], $symbolsOrLocale = null)
    {
        $methodArgs = array_pad(\func_get_args(), 5, null);

        [$locale, $symbols] = $this->normalizeMethodSignature($methodArgs[3], $methodArgs[4]);
        $number = $this->parseNumericValue($number);

        return $this->format($number, \NumberFormatter::DECIMAL, $attributes, $textAttributes, $symbols, $locale);
    }

    /**
     * Formats a number as spellout according to the specified locale and
     * \NumberFormatter attributes.
     *
     * @param string|float|int                  $number          The number to format
     * @param array<string, int|float>          $attributes      The attributes used by \NumberFormatter
     * @param array<string, string>             $textAttributes  The text attributes used by \NumberFormatter
     * @param array<string, string>|string|null $symbolsOrLocale
     *
     * @return string The formatted number
     */
    public function formatSpellout($number, array $attributes = [], array $textAttributes = [], $symbolsOrLocale = null)
    {
        $methodArgs = array_pad(\func_get_args(), 5, null);

        [$locale, $symbols] = $this->normalizeMethodSignature($methodArgs[3], $methodArgs[4]);

        return $this->format($number, \NumberFormatter::SPELLOUT, $attributes, $textAttributes, $symbols, $locale);
    }

    /**
     * Formats a number as currency according to the specified locale and
     * \NumberFormatter attributes.
     *
     * @param string|float|int                  $number          The number to format
     * @param string                            $currency        The currency in which format the number
     * @param array<string, int|float>          $attributes      The attributes used by \NumberFormatter
     * @param array<string, string>             $textAttributes  The text attributes used by \NumberFormatter
     * @param array<string, string>|string|null $symbolsOrLocale
     *
     * @return string The formatted number
     */
    public function formatCurrency($number, $currency, array $attributes = [], array $textAttributes = [], $symbolsOrLocale = null)
    {
        $methodArgs = array_pad(\func_get_args(), 6, null);

        [$locale, $symbols] = $this->normalizeMethodSignature($methodArgs[4], $methodArgs[5]);

        $formatter = $this->getFormatter($locale ?? $this->localeDetector->getLocale(), \NumberFormatter::CURRENCY, $attributes, $textAttributes, $symbols);
        $number = $this->parseNumericValue($number);

        return $this->fixCharset($formatter->formatCurrency($number, $currency));
    }

    /**
     * Formats a number in scientific notation according to the specified
     * locale and \NumberFormatter attributes.
     *
     * @param string|float|int                  $number          The number to format
     * @param array<string, int|float>          $attributes      The attributes used by \NumberFormatter
     * @param array<string, string>             $textAttributes  The text attributes used by \NumberFormatter
     * @param array<string, string>|string|null $symbolsOrLocale
     *
     * @return string The formatted number
     */
    public function formatScientific($number, array $attributes = [], array $textAttributes = [], $symbolsOrLocale = null)
    {
        $methodArgs = array_pad(\func_get_args(), 5, null);

        [$locale, $symbols] = $this->normalizeMethodSignature($methodArgs[3], $methodArgs[4]);
        $number = $this->parseNumericValue($number);

        return $this->format($number, \NumberFormatter::SCIENTIFIC, $attributes, $textAttributes, $symbols, $locale);
    }

    /**
     * Formats a number as ordinal according to the specified locale and
     * \NumberFormatter attributes.
     *
     * @param string|float|int                  $number          The number to format
     * @param array<string, int|float>          $attributes      The attributes used by \NumberFormatter
     * @param array<string, string>             $textAttributes  The text attributes used by \NumberFormatter
     * @param array<string, string>|string|null $symbolsOrLocale
     *
     * @return string The formatted number
     */
    public function formatOrdinal($number, array $attributes = [], array $textAttributes = [], $symbolsOrLocale = null)
    {
        $methodArgs = array_pad(\func_get_args(), 5, null);

        [$locale, $symbols] = $this->normalizeMethodSignature($methodArgs[3], $methodArgs[4]);
        $number = $this->parseNumericValue($number);

        return $this->format($number, \NumberFormatter::ORDINAL, $attributes, $textAttributes, $symbols, $locale);
    }

    /**
     * Formats a number according to the specified locale and \NumberFormatter
     * attributes.
     *
     * @param string|float|int                  $number          The number to format
     * @param int                               $style
     * @param array<string, int|float>          $attributes      The attributes used by \NumberFormatter
     * @param array<string, string>             $textAttributes  The text attributes used by \NumberFormatter
     * @param array<string, string>|string|null $symbolsOrLocale
     *
     * @return string
     */
    public function format($number, $style, array $attributes = [], array $textAttributes = [], $symbolsOrLocale = null)
    {
        $methodArgs = array_pad(\func_get_args(), 6, null);

        [$locale, $symbols] = $this->normalizeMethodSignature($methodArgs[4], $methodArgs[5]);

        $formatter = $this->getFormatter($locale ?? $this->localeDetector->getLocale(), $style, $attributes, $textAttributes, $symbols);

        return $this->fixCharset($formatter->format($number));
    }

    /**
     * Normalizes the given arguments according to the new function signature.
     * It asserts if neither the new nor old signature matches. This function
     * is public just to prevent code duplication inside the Twig Extension.
     *
     * @param mixed $symbols The symbols used by the formatter
     * @param mixed $locale  The locale
     *
     * @throws \BadMethodCallException If the arguments does not match any signature
     *
     * @return array{string|null, array<string, string>} Arguments list normalized to the new method signature
     *
     * @internal
     */
    public function normalizeMethodSignature($symbols, $locale)
    {
        $oldSignature = (null === $symbols || \is_string($symbols)) && null === $locale;
        $newSignature = \is_array($symbols) && (\is_string($locale) || null === $locale);

        // Confirm possible signatures
        if (!$oldSignature && !$newSignature) {
            throw new \BadMethodCallException('Neither new nor old signature matches, please provide the correct arguments');
        }

        if ($oldSignature) {
            // If the old signature matches, we pass an empty array as symbols
            // argument and the symbols value as the locale argument.
            return [$symbols, []];
        }

        return [$locale, $symbols];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sonata_intl_number';
    }

    /**
     * Gets an instance of \NumberFormatter set with the given attributes and
     * style.
     *
     * @param string                   $culture        The culture used by \NumberFormatter
     * @param int                      $style          The style used by \NumberFormatter
     * @param array<string, int|float> $attributes     The attributes used by \NumberFormatter
     * @param array<string, string>    $textAttributes The text attributes used by \NumberFormatter
     * @param array<string, string>    $symbols        The symbols used by \NumberFormatter
     *
     * @return \NumberFormatter
     */
    protected function getFormatter($culture, $style, $attributes = [], $textAttributes = [], $symbols = [])
    {
        $attributes = $this->parseAttributes(array_merge($this->attributes, $attributes));
        $textAttributes = $this->parseAttributes(array_merge($this->textAttributes, $textAttributes));
        $symbols = $this->parseAttributes(array_merge($this->symbols, $symbols));
        $formatter = new \NumberFormatter($culture, $style);

        self::checkInternalClass($formatter, \NumberFormatter::class, [
            'culture' => $culture,
            'style' => $style,
        ]);

        foreach ($attributes as $attribute => $value) {
            $formatter->setAttribute($attribute, $value);
        }

        foreach ($textAttributes as $attribute => $value) {
            $formatter->setTextAttribute($attribute, $value);
        }

        foreach ($symbols as $attribute => $value) {
            $formatter->setSymbol($attribute, $value);
        }

        return $formatter;
    }

    /**
     * Converts keys of attributes array to values of \NumberFormatter constants.
     *
     * @param array<string, mixed> $attributes The list of attributes
     *
     * @throws \InvalidArgumentException If any attribute does not match any constant
     *
     * @return array<int, mixed> List of \NumberFormatter constants
     *
     * @phpstan-template T of mixed
     * @phpstan-param array<string, T> $attributes
     * @phpstan-return array<int, T>
     */
    protected function parseAttributes(array $attributes)
    {
        $result = [];

        foreach ($attributes as $attribute => $value) {
            $result[$this->parseConstantValue($attribute)] = $value;
        }

        return $result;
    }

    /**
     * Parse the given value trying to get a match with a \NumberFormatter constant.
     *
     * @param string $attribute The constant's name
     *
     * @throws \InvalidArgumentException If the value does not match any constant
     *
     * @return int The \NumberFormatter constant
     */
    protected function parseConstantValue($attribute)
    {
        $attribute = strtoupper($attribute);
        $constantName = 'NumberFormatter::'.$attribute;

        if (!\defined($constantName)) {
            throw new \InvalidArgumentException(sprintf('NumberFormatter has no constant "%s".', $attribute));
        }

        return \constant($constantName);
    }

    /**
     * @param string|int|float $number
     *
     * @return int|float
     */
    private function parseNumericValue($number)
    {
        if (\is_float($number) || \is_int($number)) {
            return $number;
        }

        if (is_numeric($number)) {
            return (float) $number;
        }

        throw new \TypeError('Number must be either a float, an integer or a numeric string');
    }
}
