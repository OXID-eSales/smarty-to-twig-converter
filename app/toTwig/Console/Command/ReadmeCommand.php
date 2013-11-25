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
class ReadmeCommand extends Command
{
	/**
	 * @see Command
	 */
	protected function configure()
	{
		$this
			->setName('readme')
			->setDescription('Generates the README content, based on the convert command help')
		;
	}

	/**
	 * @see Command
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$header = <<<EOF
PHP Smarty to Twig Converter
==========================
toTwig is an utility to convert smarty template engine to twig template engine.

Installation
------------

### Locally

Download the
[`toTwig.phar`](https://raw.github.com/sankarsuda/toTwig/master/toTwig.phar) file and
store it somewhere on your computer.

### Globally (manual)

You can run these commands to easily acces `toTwig` from anywhere on your system:

	\$ sudo wget https://raw.github.com/sankarsuda/toTwig/master/toTwig.phar -O /usr/local/bin/toTwig

or with curl:

	\$ sudo curl https://raw.github.com/sankarsuda/toTwig/master/toTwig.phar -o /usr/local/bin/toTwig

then:

	\$ sudo chmod a+x /usr/local/bin/toTwig

Then, just run `toTwig`

Update
------

### Locally

The `self-update` command tries to update toTwig itself:

	\$ php toTwig.phar self-update

### Globally (manual)

You can update toTwig through this command:

	\$ sudo toTwig self-update

Usage
-----

EOF;

		$footer = <<<EOF

Contribute
----------

The tool comes with quite a few built-in converters and finders, but everyone is
more than welcome to [contribute](https://github.com/sankarsuda/toTwig) more
of them.

### Converter

A *converter* is a class that tries to convert one tag (a `Converter` class must
extends `ConverterAbstract`).

### Configs

A *config* knows about the files and directories that must be
scanned by the tool when run in the directory of your project. It is useful
for projects that follow a well-known directory structures (like for Symfony
projects for instance).

EOF;

		$command = $this->getApplication()->get('convert');
		$help = $command->getHelp();
		$help = str_replace('%command.full_name%', 'toTwig.phar '.$command->getName(), $help);
		$help = str_replace('%command.name%', $command->getName(), $help);
		$help = preg_replace('#</?(comment|info)>#', '`', $help);
		$help = preg_replace('#^(\s+)`(.+)`$#m', '$1$2', $help);
		$help = preg_replace('#^ \* `(.+)`#m', ' * **$1**', $help);

		$output->writeln($header);
		$output->writeln($help);
		$output->write($footer);

		$content = $header."\n";
		$content .= $help ."\n";
		$content .= $footer;

		@file_put_contents('README.md', $content);
	}
}
