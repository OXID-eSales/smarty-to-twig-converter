<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\Finder;

use Symfony\Component\Finder\Finder;
use toTwig\FinderInterface;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class DefaultFinder extends Finder implements FinderInterface
{
    public function __construct()
    {
        parent::__construct();

        $files = $this->getFilesToExclude();

        $this
            ->files()
            ->name('*.tpl')
            ->ignoreDotFiles(true)
            ->ignoreVCS(true)
            ->exclude('vendor')
            ->filter(function (\SplFileInfo $file) use ($files) {
                return !in_array($file->getRelativePathname(), $files);
            })
        ;
    }

    public function setDir($dir)
    {
        $this->in($this->getDirs($dir));
    }

    /**
     * Gets the directories that needs to be scanned for files to validate.
     *
     * @return array
     */
    protected function getDirs($dir)
    {
        return array($dir);
    }

    /**
     * Excludes files because modifying them would break (mainly useful for fixtures in unit tests).
     *
     * @return array
     */
    protected function getFilesToExclude()
    {
        return array();
    }
}
