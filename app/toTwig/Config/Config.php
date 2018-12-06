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

use toTwig\ConverterAbstract;
use toTwig\FinderInterface;
use toTwig\ConfigInterface;
use toTwig\Finder\DefaultFinder;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class Config implements ConfigInterface
{

    protected $name;
    protected $description;
    protected $finder;
    protected $converter;
    protected $dir;
    protected $customConverter;

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
        $this->converter = ConverterAbstract::ALL_LEVEL;
        $this->finder = new DefaultFinder();
        $this->customConverter = array();
    }

    /**
     * @return Config
     */
    public static function create(): self
    {
        return new static();
    }

    /**
     * @param string $dir
     */
    public function setDir(string $dir): void
    {
        $this->dir = $dir;
    }

    /**
     * @return string
     */
    public function getDir(): string
    {
        return $this->dir;
    }

    /**
     * @param \Traversable $finder
     *
     * @return $this
     */
    public function finder(\Traversable $finder): self
    {
        $this->finder = $finder;

        return $this;
    }

    /**
     * @return DefaultFinder|FinderInterface|\Traversable
     */
    public function getFinder(): \Traversable
    {
        if ($this->finder instanceof FinderInterface && $this->dir !== null) {
            $this->finder->setDir($this->dir);
        }

        return $this->finder;
    }

    /**
     * @param ConverterAbstract[] $converter
     *
     * @return $this
     */
    public function converters(array $converter): self
    {
        $this->converter = $converter;

        return $this;
    }

    /**
     * @return ConverterAbstract[]|int
     */
    public function getConverters(): array
    {
        return $this->converter;
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
        $this->customConverter[] = $converter;
    }

    /**
     * @return ConverterAbstract[]
     */
    public function getCustomConverters(): array
    {
        return $this->customConverter;
    }
}
