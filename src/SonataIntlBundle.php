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

namespace Sonata\IntlBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SonataIntlBundle extends Bundle
{
    /**
     * Returns a cleaned version number.
     *
     * @static
     *
     * @param string $version
     *
     * @return string
     */
    public static function getSymfonyVersion($version)
    {
        return implode('.', \array_slice(array_map(
            static fn ($val) => (int) $val,
            explode('.', $version)
        ), 0, 3));
    }

    /**
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
    }
}
