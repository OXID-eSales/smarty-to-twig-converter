<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use toTwig\Util\Compiler;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class CompileCommand extends Command
{
	/**
	 * @see Command
	 */
	protected function configure()
	{
		$this
			->setName('compile')
			->setDescription('Compiles the converter as a phar file')
		;
	}

	/**
	 * @see Command
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$compiler = new Compiler();
		$compiler->compile();
	}
}
