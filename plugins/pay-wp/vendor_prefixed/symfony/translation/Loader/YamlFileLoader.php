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

use WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException;
use WPPayVendor\Symfony\Component\Translation\Exception\LogicException;
use WPPayVendor\Symfony\Component\Yaml\Exception\ParseException;
use WPPayVendor\Symfony\Component\Yaml\Parser as YamlParser;
use WPPayVendor\Symfony\Component\Yaml\Yaml;
/**
 * YamlFileLoader loads translations from Yaml files.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class YamlFileLoader extends \WPPayVendor\Symfony\Component\Translation\Loader\FileLoader
{
    private $yamlParser;
    /**
     * {@inheritdoc}
     */
    protected function loadResource(string $resource)
    {
        if (null === $this->yamlParser) {
            if (!\class_exists(\WPPayVendor\Symfony\Component\Yaml\Parser::class)) {
                throw new \WPPayVendor\Symfony\Component\Translation\Exception\LogicException('Loading translations from the YAML format requires the Symfony Yaml component.');
            }
            $this->yamlParser = new \WPPayVendor\Symfony\Component\Yaml\Parser();
        }
        try {
            $messages = $this->yamlParser->parseFile($resource, \WPPayVendor\Symfony\Component\Yaml\Yaml::PARSE_CONSTANT);
        } catch (\WPPayVendor\Symfony\Component\Yaml\Exception\ParseException $e) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException(\sprintf('The file "%s" does not contain valid YAML: ', $resource) . $e->getMessage(), 0, $e);
        }
        if (null !== $messages && !\is_array($messages)) {
            throw new \WPPayVendor\Symfony\Component\Translation\Exception\InvalidResourceException(\sprintf('Unable to load file "%s".', $resource));
        }
        return $messages ?: [];
    }
}
