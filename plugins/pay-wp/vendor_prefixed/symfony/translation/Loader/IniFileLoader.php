<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WPPayVendor\Symfony\Component\Translation\Loader;

/**
 * IniFileLoader loads translations from an ini file.
 *
 * @author stealth35
 */
class IniFileLoader extends \WPPayVendor\Symfony\Component\Translation\Loader\FileLoader
{
    /**
     * {@inheritdoc}
     */
    protected function loadResource(string $resource)
    {
        return \parse_ini_file($resource, \true);
    }
}
