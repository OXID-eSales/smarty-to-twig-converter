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

/**
 * @author sankar <sankar.suda@gmail.com>
 */
interface ConfigInterface
{

    /**
     * Returns the name of the configuration.
     *
     * The name must be all lowercase and without any spaces.
     *
     * @return string The name of the configuration
     */
    public function getName(): string;

    /**
     * Returns the description of the configuration.
     *
     * A short one-line description for the configuration.
     *
     * @return string The description of the configuration
     */
    public function getDescription(): string;

    /**
     * Returns an iterator of files to scan.
     *
     * @return \Traversable A \Traversable instance that returns \SplFileInfo instances
     */
    public function getFinder(): \Traversable;

    /**
     * Returns the converters to run.
     *
     * @return array|integer A level or a list of converter names
     */
    public function getConverters();

    /**
     * Sets the root directory of the project.
     *
     * @param string $dir The project root directory
     */
    public function setDir(string $dir): void;

    /**
     * Returns the root directory of the project.
     *
     * @return string The project root directory
     */
    public function getDir(): string;

    /**
     * Adds an instance of a custom converter.
     *
     * @param ConverterAbstract $converter
     */
    public function addCustomConverter(ConverterAbstract $converter): void;

    /**
     * Returns the custom converters to use.
     *
     * @return ConverterAbstract[]
     */
    public function getCustomConverters(): array;
}
