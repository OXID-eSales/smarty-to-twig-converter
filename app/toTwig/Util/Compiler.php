<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\Util;

use Symfony\Component\Finder\Finder;

/**
 * The Compiler class compiles the Totwig Converter utility.
 *
 * @author sankar <sankar.suda@gmail.com>
 */
class Compiler
{
    public function compile($pharFile = 'toTwig.phar')
    {
        if (file_exists($pharFile)) {
            unlink($pharFile);
        }

        $phar = new \Phar($pharFile, 0, 'toTwig.phar');
        $phar->setSignatureAlgorithm(\Phar::SHA1);

        $phar->startBuffering();

        // CLI Component files
        foreach ($this->getFiles() as $file) {
            $path = str_replace(__DIR__.'/', '', $file);
            $phar->addFromString($path, file_get_contents($file));
        }
        $this->addStConverter($phar);

        // Stubs
        $phar->setStub($this->getStub());

        $phar->stopBuffering();

        // $phar->compressFiles(\Phar::GZ);

        unset($phar);

        chmod($pharFile, 0777);
    }

    /**
     * Remove the shebang from the file before add it to the PHAR file.
     *
     * @param \Phar $phar PHAR instance
     */
    protected function addStConverter(\Phar $phar)
    {
        $content = file_get_contents(__DIR__ . '/../../../toTwig');
        $content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);

        $phar->addFromString('toTwig', $content);
    }

    protected function getStub()
    {
        return "#!/usr/bin/env php\n<?php Phar::mapPhar('toTwig.phar'); require 'phar://toTwig.phar/toTwig'; __HALT_COMPILER();";
    }

    protected function getLicense()
    {
        return '
    /**
     * This file is part of the PHP ST utility.
     *
     * (c) Sankar suda <sankar.suda@gmail.com>
     *
     * This source file is subject to the MIT license that is bundled
     * with this source code in the file LICENSE.
     */';
    }

    protected function getFiles()
    {
        $iterator = Finder::create()->files()->exclude('tests')->name('*.php')->in(array('vendor', 'lib'));

        return array_merge(array('LICENSE'), iterator_to_array($iterator));
    }
}
