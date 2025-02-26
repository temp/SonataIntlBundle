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

use Symfony\Contracts\Translation\LocaleAwareInterface;

/**
 * BaseHelper provides charset conversion.
 *
 * The php Intl extension will always output UTF-8 encoded strings [1]. If the
 * framework is configured to use another encoding than UTF-8 this will lead to
 * garbled text. The BaseHelper provides functionality to the other helpers to
 * convert from UTF-8 to the kernel charset.
 *
 * [1] http://www.php.net/manual/en/intl.examples.basic.php
 *
 * @author Alexander <iam.asm89@gmail.com>
 */
abstract class BaseHelper implements LocaleAwareInterface
{
    protected string $charset = 'UTF-8';

    /**
     * @var string
     */
    protected $locale;

    /**
     * @param string $charset The output charset of the helper
     */
    public function __construct(string $charset)
    {
        $this->setCharset($charset);
    }

    /**
     * Sets the default charset.
     */
    public function setCharset(string $charset): void
    {
        $this->charset = $charset;
    }

    /**
     * Gets the default charset.
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @static
     */
    public static function getICUDataVersion(): string
    {
        if (\defined('INTL_ICU_VERSION')) {
            return \INTL_ICU_VERSION;
        }

        ob_start();
        phpinfo();
        $content = ob_get_clean();

        $info = explode("\n", $content);

        if ('cli' === \PHP_SAPI) {
            foreach ($info as $line) {
                $results = [];

                if (preg_match('/(ICU Data version|ICU version) => (.*)/', $line, $results)) {
                    return $results[2];
                }
            }
        } else {
            foreach ($info as $line) {
                $results = [];

                if (preg_match('/(ICU Data version).*/', $line, $results)) {
                    return trim(strtolower(strip_tags($results[0])), 'ICU Data version');
                }

                if (preg_match('/(ICU version).*/', $line, $results)) {
                    return trim(strtolower(strip_tags($results[0])), 'icu version');
                }
            }
        }

        throw new \RuntimeException('Could not extract ICU data version information');
    }

    /**
     * Fixes the charset by converting a string from an UTF-8 charset to the
     * charset of the kernel.
     *
     * Precondition: the kernel charset is not UTF-8
     *
     * @param string $string The string to fix
     *
     * @return string A string with the %kernel.charset% encoding
     */
    protected function fixCharset(string $string): string
    {
        if ('UTF-8' !== $this->getCharset()) {
            $string = mb_convert_encoding($string, $this->getCharset(), 'UTF-8');
        }

        return $string;
    }

    /**
     * https://wiki.php.net/rfc/internal_constructor_behaviour.
     *
     * @param mixed   $instance
     * @param mixed[] $args
     */
    protected static function checkInternalClass($instance, string $class, array $args = []): void
    {
        if (null !== $instance) {
            return;
        }

        $messages = [];
        foreach ($args as $name => $value) {
            $messages[] = sprintf('%s => %s', $name, $value);
        }

        throw new \RuntimeException(sprintf(
            'Unable to create internal class: %s, with params: %s',
            $class,
            implode(', ', $messages)
        ));
    }
}
