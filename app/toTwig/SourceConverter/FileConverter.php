<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\SourceConverter;

use ArrayIterator;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo as FinderSplFileInfo;
use toTwig\ConversionResult;
use toTwig\Converter\ConverterAbstract;
use toTwig\Finder\DefaultFinder;
use toTwig\Finder\FinderInterface;

/**
 * Class FileConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class FileConverter extends SourceConverter
{

    private $finder;
    private $dir;
    private $outputExtension;

    /**
     * FileConverter constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->finder = new DefaultFinder();
    }

    /**
     * @param bool                $dryRun
     * @param bool                $diff
     * @param ConverterAbstract[] $converters
     *
     * @return ConversionResult[]
     */
    public function convert(bool $dryRun, bool $diff, array $converters): array
    {
        $changed = [];
        foreach ($this->getFinder() as $file) {
            if ($file->isDir()) {
                continue;
            }

            $conversionResult = $this->convertTemplate(file_get_contents($file->getRealpath()), $diff, $converters);
            if ($conversionResult->hasAppliedConverters()) {
                if (!$dryRun) {
                    $filename = $this->getConvertedFilename($file);

                    file_put_contents($filename, $conversionResult->getConvertedTemplate());
                }

                if ($file instanceof FinderSplFileInfo) {
                    $changed[$file->getRelativePathname()] = $conversionResult;
                } else {
                    $changed[$file->getPathname()] = $conversionResult;
                }
            }
        }

        return $changed;
    }


    /**
     * @param Finder $finder
     *
     * @return FileConverter
     */
    public function setFinder(Finder $finder): self
    {
        $this->finder = $finder;

        return $this;
    }

    /**
     * @return Finder
     */
    public function getFinder(): Finder
    {
        if ($this->finder instanceof FinderInterface && $this->dir !== null) {
            $this->finder->setDir($this->dir);
        }

        return $this->finder;
    }

    /**
     * @param string $dir
     */
    public function setDir(string $dir): void
    {
        $this->dir = $dir;
    }

    /**
     * @param string $path
     *
     * @return FileConverter
     */
    public function setPath(string $path): self
    {
        if (is_file($path)) {
            $iterator = new ArrayIterator([new SplFileInfo($path)]);
            $this->setFinder(Finder::create()->files()->append($iterator));
        } else {
            $this->setDir($path);
        }

        return $this;
    }

    /**
     * @param string $outputExtension
     *
     * @return FileConverter
     */
    public function setOutputExtension(string $outputExtension): self
    {
        $this->outputExtension = $outputExtension;

        return $this;
    }

    /**
     * @param SplFileInfo $file
     *
     * @return string
     */
    public function getConvertedFilename(SplFileInfo $file)
    {
        $filename = $file->getRealpath();

        $ext = strrchr($filename, '.');
        if ($this->outputExtension) {
            $filename = substr($filename, 0, -strlen($ext)) . '.' . trim($this->outputExtension, '.');
        }

        return $filename;
    }
}
