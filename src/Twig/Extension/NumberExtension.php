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

namespace Sonata\IntlBundle\Twig\Extension;

use Sonata\IntlBundle\Helper\NumberFormatterInterface;
use Sonata\IntlBundle\Templating\Helper\NumberHelper as TemplatingNumberHelper;
use Sonata\IntlBundle\Twig\NumberRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * NumberExtension extends Twig with some filters to format numbers according
 * to locale and/or custom options.
 *
 * @author Thomas Rabaix <thomas.rabaix@ekino.com>
 * @author Stefano Arlandini <sarlandini@alice.it>
 */
class NumberExtension extends AbstractExtension
{
    /**
     * @var NumberFormatterInterface|TemplatingNumberHelper The instance of the NumberHelper helper
     */
    protected $helper;

    private NumberRuntime $numberRuntime;

    /**
     * NEXT_MAJOR: Remove this constructor.
     *
     * @param NumberFormatterInterface|TemplatingNumberHelper $helper
     */
    public function __construct(object $helper)
    {
        if ($helper instanceof TemplatingNumberHelper) {
            @trigger_error(
                sprintf('The use of %s is deprecated since 2.13, use %s instead.', TemplatingNumberHelper::class, NumberFormatterInterface::class),
                \E_USER_DEPRECATED
            );
        } elseif (!$helper instanceof NumberFormatterInterface) {
            throw new \TypeError(sprintf('Helper must be an instanceof %s, instanceof %s given', NumberFormatterInterface::class, \get_class($helper)));
        }
        $this->helper = $helper;
        $this->numberRuntime = new NumberRuntime($this->helper);
    }

    /**
     * @return TwigFilter[]
     */
    public function getFilters()
    {
        return [
            new TwigFilter('number_format_currency', [$this, 'formatCurrency'], ['is_safe' => ['html']]), // NEXT_MAJOR: Remove this line
            new TwigFilter('sonata_number_format_currency', [NumberRuntime::class, 'formatCurrency'], ['is_safe' => ['html']]),
            new TwigFilter('number_format_decimal', [$this, 'formatDecimal'], ['is_safe' => ['html']]), // NEXT_MAJOR: Remove this line
            new TwigFilter('sonata_number_format_decimal', [NumberRuntime::class, 'formatDecimal'], ['is_safe' => ['html']]),
            new TwigFilter('number_format_scientific', [$this, 'formatScientific'], ['is_safe' => ['html']]), // NEXT_MAJOR: Remove this line
            new TwigFilter('sonata_number_format_scientific', [NumberRuntime::class, 'formatScientific'], ['is_safe' => ['html']]),
            new TwigFilter('number_format_spellout', [$this, 'formatSpellout'], ['is_safe' => ['html']]), // NEXT_MAJOR: Remove this line
            new TwigFilter('sonata_number_format_spellout', [NumberRuntime::class, 'formatSpellout'], ['is_safe' => ['html']]),
            new TwigFilter('number_format_percent', [$this, 'formatPercent'], ['is_safe' => ['html']]), // NEXT_MAJOR: Remove this line
            new TwigFilter('sonata_number_format_percent', [NumberRuntime::class, 'formatPercent'], ['is_safe' => ['html']]),
            new TwigFilter('number_format_duration', [$this, 'formatDuration'], ['is_safe' => ['html']]), // NEXT_MAJOR: Remove this line
            new TwigFilter('sonata_number_format_duration', [NumberRuntime::class, 'formatDuration'], ['is_safe' => ['html']]),
            new TwigFilter('number_format_ordinal', [$this, 'formatOrdinal'], ['is_safe' => ['html']]), // NEXT_MAJOR: Remove this line
            new TwigFilter('sonata_number_format_ordinal', [NumberRuntime::class, 'formatOrdinal'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * NEXT_MAJOR: remove this method.
     *
     * @deprecated since sonata-project/intl-bundle 2.8, to be removed in version 3.0.
     *
     * @return string
     */
    public function getName()
    {
        return 'sonata_intl_number';
    }

    /**
     * NEXT_MAJOR: remove this method.
     *
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
        @trigger_error(
            'The number_format_currency filter is deprecated since 2.12 and will be removed on 3.0. '.
            'Use sonata_number_format_currency instead.',
            \E_USER_DEPRECATED
        );

        return $this->numberRuntime->formatCurrency(...\func_get_args());
    }

    /**
     * NEXT_MAJOR: remove this method.
     *
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
        @trigger_error(
            'The number_format_decimal filter is deprecated since 2.12 and will be removed on 3.0. '.
            'Use sonata_number_format_decimal instead.',
            \E_USER_DEPRECATED
        );

        return $this->numberRuntime->formatDecimal(...\func_get_args());
    }

    /**
     * NEXT_MAJOR: remove this method.
     *
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
        @trigger_error(
            'The number_format_scientific filter is deprecated since 2.12 and will be removed on 3.0. '.
            'Use sonata_number_format_decimal instead.',
            \E_USER_DEPRECATED
        );

        return $this->numberRuntime->formatScientific(...\func_get_args());
    }

    /**
     * NEXT_MAJOR: remove this method.
     *
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
        @trigger_error(
            'The number_format_spellout filter is deprecated since 2.12 and will be removed on 3.0. '.
            'Use sonata_number_format_spellout instead.',
            \E_USER_DEPRECATED
        );

        return $this->numberRuntime->formatSpellout(...\func_get_args());
    }

    /**
     * NEXT_MAJOR: remove this method.
     *
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
        @trigger_error(
            'The number_format_percent filter is deprecated since 2.12 and will be removed on 3.0. '.
            'Use sonata_number_format_percent instead.',
            \E_USER_DEPRECATED
        );

        return $this->numberRuntime->formatPercent(...\func_get_args());
    }

    /**
     * NEXT_MAJOR: remove this method.
     *
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
        @trigger_error(
            'The number_format_duration filter is deprecated since 2.12 and will be removed on 3.0. '.
            'Use sonata_number_format_duration instead.',
            \E_USER_DEPRECATED
        );

        return $this->numberRuntime->formatDuration(...\func_get_args());
    }

    /**
     * NEXT_MAJOR: remove this method.
     *
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
        @trigger_error(
            'The number_format_ordinal filter is deprecated since 2.12 and will be removed on 3.0. '.
            'Use sonata_number_format_ordinal instead.',
            \E_USER_DEPRECATED
        );

        return $this->numberRuntime->formatOrdinal(...\func_get_args());
    }
}
