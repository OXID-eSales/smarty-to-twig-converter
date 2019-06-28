<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\Config;

use toTwig\Converter\ConverterAbstract;
use toTwig\Finder\FinderInterface;
use toTwig\SourceConverter\FileConverter;
use toTwig\SourceConverter\SourceConverter;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class Config implements ConfigInterface
{

    /** @var string */
    protected $name;

    /** @var string */
    protected $description;

    /** @var FinderInterface */
    protected $finder;

    /** @var string[] */
    protected $converters = [];

    /** @var string */
    protected $dir;

    /** @var ConverterAbstract[] */
    protected $customConverters;

    /** @var bool */
    protected $dryRun = false;

    /** @var bool */
    protected $diff = false;

    /** @var SourceConverter */
    private $sourceConverter;

    /**
     * Config constructor.
     *
     * @param string $name
     * @param string $description
     */
    public function __construct(string $name = 'default', string $description = 'A default configuration')
    {
        $this->name = $name;
        $this->description = $description;
        $this->customConverters = [];
        $this->sourceConverter = new FileConverter();
    }

    /**
     * @return Config
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * @param string[] $converters
     *
     * @return $this
     */
    public function converters(array $converters): self
    {
        $this->converters = $converters;

        return $this;
    }

    /**
     * @return string[]
     */
    public function getConverters(): array
    {
        return $this->converters;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param ConverterAbstract $converter
     */
    public function addCustomConverter(ConverterAbstract $converter): void
    {
        $this->customConverters[] = $converter;
    }

    /**
     * @return ConverterAbstract[]
     */
    public function getCustomConverters(): array
    {
        return $this->customConverters;
    }

    /**
     * @param SourceConverter $converter
     *
     * @return ConfigInterface
     */
    public function setSourceConverter(SourceConverter $converter): ConfigInterface
    {
        $this->sourceConverter = $converter;

        return $this;
    }

    /**
     * @return SourceConverter
     */
    public function getSourceConverter(): SourceConverter
    {
        return $this->sourceConverter;
    }

    /**
     * @return bool
     */
    public function isDiff(): bool
    {
        return $this->diff;
    }

    /**
     * @param bool $diff
     *
     * @return Config
     */
    public function diff($diff = true): self
    {
        $this->diff = $diff;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDryRun(): bool
    {
        return $this->dryRun;
    }

    /**
     * @param bool $dryRun
     *
     * @return Config
     */
    public function dryRun($dryRun = true): self
    {
        $this->dryRun = $dryRun;

        return $this;
    }
}
