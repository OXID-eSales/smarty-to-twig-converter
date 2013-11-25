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

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class SelfUpdateCommand extends Command
{
	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this
			->setName('self-update')
			->setDescription('Update toTwig.phar to the latest version.')
			->setHelp(<<<EOT
The <info>self-update</info> command replace your toTwig.phar
by the latest version from cs.sensiolabs.org.

<info>php toTwig.phar self-update</info>

EOT
			)
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$remoteFilename = "https://raw.github.com/sankarsuda/toTwig/master/toTwig.phar";
		$localFilename  = $_SERVER['argv'][0];
		$tempFilename   = basename($localFilename, '.phar').'-temp.phar';

		try {
			copy($remoteFilename, $tempFilename);
			chmod($tempFilename, 0777 & ~umask());

			// test the phar validity
			$phar = new \Phar($tempFilename);
			// free the variable to unlock the file
			unset($phar);
			rename($tempFilename, $localFilename);
		} catch (\Exception $e) {
			if (!$e instanceof \UnexpectedValueException && !$e instanceof \PharException) {
				throw $e;
			}
			unlink($tempFilename);
			$output->writeln('<error>The download is corrupt ('.$e->getMessage().').</error>');
			$output->writeln('<error>Please re-run the self-update command to try again.</error>');
		}

		$output->writeln("<info>toTwig updated.</info>");
	}
}
