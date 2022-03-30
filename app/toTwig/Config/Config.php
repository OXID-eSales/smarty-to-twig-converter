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
    protected string $name;
    protected string $description;
    protected FinderInterface $finder;
    protected string $dir;
    protected bool $dryRun = false;
    protected bool $diff = false;
    private SourceConverter $sourceConverter;

    /** @var string[] */
    protected array $converters = [];

    /** @var ConverterAbstract[] */
    protected array $customConverters;

    /**
     * Config constructor.
     */
    public function __construct(string $name = 'default', string $description = 'A default configuration')
    {
        $this->name = $name;
        $this->description = $description;
        $this->customConverters = [];
        $this->sourceConverter = new FileConverter();
    }

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

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

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

    public function setSourceConverter(SourceConverter $converter): ConfigInterface
    {
        $this->sourceConverter = $converter;

        return $this;
    }

    public function getSourceConverter(): SourceConverter
    {
        return $this->sourceConverter;
    }

    public function isDiff(): bool
    {
        return $this->diff;
    }

    public function diff(bool $diff = true): self
    {
        $this->diff = $diff;

        return $this;
    }

    public function isDryRun(): bool
    {
        return $this->dryRun;
    }

    public function dryRun(bool $dryRun = true): self
    {
        $this->dryRun = $dryRun;

        return $this;
    }
}
