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

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class DefaultFinder extends Finder implements FinderInterface
{

    /**
     * DefaultFinder constructor.
     */
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
            ->filter(
                function (\SplFileInfo $file) use ($files) {
                    return !in_array($file->getRelativePathname(), $files);
                }
            );
    }

    /**
     * @param string $dir
     */
    public function setDir(string $dir): void
    {
        $this->in($this->getDirs($dir));
    }

    /**
     * Gets the directories that needs to be scanned for files to validate.
     *
     * @param string $dir
     *
     * @return array
     */
    protected function getDirs(string $dir): array
    {
        return array($dir);
    }

    /**
     * Excludes files because modifying them would break (mainly useful for fixtures in unit tests).
     *
     * @return array
     */
    protected function getFilesToExclude(): array
    {
        return array();
    }
}
