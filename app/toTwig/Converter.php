<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig;

use SebastianBergmann\Diff\Differ;
use Symfony\Component\Finder\Finder;
use toTwig\Config\ConfigInterface;
use toTwig\SourceConverter\SourceConverter;
use toTwig\Converter\ConverterAbstract;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Converter
{
    const VERSION = '0.1-DEV';

    /** @var ConverterAbstract[] */
    protected array $converters = [];

    /** @var ConfigInterface[] */
    protected array $configs = [];

    protected Differ $diff;
    private SourceConverter $sourceConverter;

    /**
     * Converter constructor.
     */
    public function __construct()
    {
        $this->diff = new Differ();
    }

    /**
     * registerBuiltInConverters
     */
    public function registerBuiltInConverters(): void
    {
        foreach (Finder::create()->files()->in(__DIR__ . '/Converter') as $file) {
            $class = 'toTwig\\Converter\\' . basename($file, '.php');
            if (!$this->isInstantiable($class)) {
                continue;
            }
            $this->addConverter(new $class());
        }
    }

    private function isInstantiable(string $class): bool
    {
        try {
            $reflectionClass = new \ReflectionClass($class);
            $isInstantiable = $reflectionClass->isInstantiable();
        } catch (\ReflectionException $exception) {
            $isInstantiable = false;
        }

        return $isInstantiable;
    }

    public function registerCustomConverters(array $converters): void
    {
        foreach ($converters as $converter) {
            $this->addConverter($converter);
        }
    }

    public function addConverter(ConverterAbstract $converter): void
    {
        $this->converters[] = $converter;
    }

    /**
     * @param string[] $converters
     */
    public function filterConverters(array $converters): void
    {
        $converters = array_map('trim', $converters);

        if (empty($converters) || $converters[0][0] == '-') {
            $converters = array_map(
                function ($converter) {
                    return ltrim($converter, '-');
                },
                $converters
            );

            $this->converters = array_filter(
                $this->converters,
                function (ConverterAbstract $converter) use ($converters) {
                    return !in_array($converter->getName(), $converters);
                }
            );
        } else {
            $this->converters = array_filter(
                $this->converters,
                function (ConverterAbstract $converter) use ($converters) {
                    return in_array($converter->getName(), $converters);
                }
            );
        }
    }

    /**
     * @return ConverterAbstract[]
     */
    public function getConverters(): array
    {
        $this->sortConverters();

        return $this->converters;
    }

    /**
     * registerBuiltInConfigs
     */
    public function registerBuiltInConfigs(): void
    {
        foreach (Finder::create()->files()->in(__DIR__ . '/Config') as $file) {
            $class = 'toTwig\\Config\\' . basename($file, '.php');

            if (class_exists($class)) {
                $this->addConfig(new $class());
            }
        }
    }

    public function addConfig(ConfigInterface $config): void
    {
        $this->configs[] = $config;
    }

    /**
     * @return ConfigInterface[]
     */
    public function getConfigs(): array
    {
        return $this->configs;
    }

    /**
     * Fixes all files for the given finder.convert()
     *
     * @param bool $dryRun Whether to simulate the changes or not
     * @param bool $diff   Whether to provide diff
     *
     * @return array
     */
    public function convert(bool $dryRun = false, bool $diff = false): array
    {
        $converters = $this->getConverters();

        return $this->sourceConverter->convert($dryRun, $diff, $converters);
    }

    /**
     * sortConverters
     */
    private function sortConverters(): void
    {
        usort(
            $this->converters,
            function (ConverterAbstract $a, ConverterAbstract $b) {
                if ($a->getPriority() == $b->getPriority()) {
                    return 0;
                }

                return $a->getPriority() > $b->getPriority() ? -1 : 1;
            }
        );
    }

    public function setSourceConverter(SourceConverter $converter): void
    {
        $this->sourceConverter = $converter;
    }
}
