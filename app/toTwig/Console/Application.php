<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\Console;

use Symfony\Component\Console\Application as BaseApplication;
use toTwig\Console\Command\ConvertCommand;
use toTwig\Converter;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Application extends BaseApplication
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        error_reporting(E_ALL ^ E_NOTICE);

        parent::__construct('PHP Smarty to Twig Converter', Converter::VERSION);

        $this->add(new ConvertCommand());
    }

    /**
     * @return string
     */
    public function getLongVersion(): string
    {
        return parent::getLongVersion() . ' by <comment>sankar</comment>';
    }
}
