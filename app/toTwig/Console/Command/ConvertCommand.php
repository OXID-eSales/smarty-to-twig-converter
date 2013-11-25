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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use toTwig\Converter;
use toTwig\ConverterAbstract;
use toTwig\Config\Config;
use toTwig\ConfigInterface;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class ConvertCommand extends Command
{
	protected $converter;
	protected $defaultConfig;

	/**
	 * @param Converter           $converter
	 * @param ConfigInterface $config
	 */
	public function __construct(Converter $converter = null, ConfigInterface $config = null)
	{
		$this->converter = $converter ?: new Converter();
		$this->converter->registerBuiltInConverters();
		$this->converter->registerBuiltInConfigs();
		$this->defaultConfig = $config ?: new Config();

		parent::__construct();
	}

	/**
	 * @see Command
	 */
	protected function configure()
	{
		$this
			->setName('convert')
			->setDefinition(array(
				new InputArgument('path', InputArgument::REQUIRED, 'The path'),
				new InputOption('config', '', InputOption::VALUE_REQUIRED, 'The configuration name', null),
				new InputOption('converters', '', InputOption::VALUE_REQUIRED, 'A list of converters to run'),
				new InputOption('ext', '', InputOption::VALUE_REQUIRED, 'To output files with other extension'),
				new InputOption('diff', '', InputOption::VALUE_NONE, 'Also produce diff for each file'),
				new InputOption('dry-run', '', InputOption::VALUE_NONE, 'Only shows which files would have been modified'),
				new InputOption('format', '', InputOption::VALUE_REQUIRED, 'To output results in other formats', 'txt')
			))
			->setDescription('Convert a directory or a file')
			->setHelp(<<<EOF
The <info>%command.name%</info> command tries to fix as much coding standards
problems as possible on a given file or directory:

	<info>php %command.full_name% /path/to/dir</info>
	<info>php %command.full_name% /path/to/file</info>

The <comment>--converters</comment> option lets you choose the exact converters to
apply (the converter names must be separated by a comma):

	<info>php %command.full_name% /path/to/dir --converters=for,if,misc</info>

You can also blacklist the converters you don't want if this is more convenient,
using <comment>-name</comment>:

	<info>php %command.full_name% /path/to/dir --converters=-for,-if</info>

A combination of <comment>--dry-run</comment>, <comment>--verbose</comment> and <comment>--diff</comment> will
display summary of proposed changes, leaving your files unchanged.

All converters apply by default.

Choose from the list of available converters:

{$this->getConvertersHelp()}

The <comment>--config</comment> option customizes the files to analyse, based
on some well-known directory structures:

	<comment># For the Symfony 2.1 branch</comment>
	<info>php %command.full_name% /path/to/sf21 --config=sf21</info>

Choose from the list of available configurations:

{$this->getConfigsHelp()}
The <comment>--dry-run</comment> option displays the files that need to be
fixed but without actually modifying them:

	<info>php %command.full_name% /path/to/code --dry-run</info>

Instead of using command line options to customize the converter, you can save the
configuration in a <comment>.php_st</comment> file in the root directory of
your project. The file must return an instance of
`toTwig\ConfigInterface`, which lets you configure the converters, the files,
and directories that need to be analyzed:

	<?php

	\$finder = toTwig\Finder\DefaultFinder::create()
		->exclude('somefile')
		->in(__DIR__)
	;

	return toTwig\Config\Config::create()
		->converters(array('if', 'for'))
		->finder(\$finder)
	;
EOF
			);
	}

	/**
	 * @see Command
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$path = $input->getArgument('path');
		$filesystem = new Filesystem();
		if (!$filesystem->isAbsolutePath($path)) {
			$path = getcwd().DIRECTORY_SEPARATOR.$path;
		}

		$addSuppliedPathFromCli = true;

		if ($input->getOption('config')) {
			$config = null;
			foreach ($this->converter->getConfigs() as $c) {
				if ($c->getName() == $input->getOption('config')) {
					$config = $c;
					break;
				}
			}

			if (null === $config) {
				throw new \InvalidArgumentException(sprintf('The configuration "%s" is not defined', $input->getOption('config')));
			}
		} elseif (file_exists($file = $path.'/.php_st')) {
			$config = include $file;
			$addSuppliedPathFromCli = false;
		} else {
			$config = $this->defaultConfig;
		}

		if ($addSuppliedPathFromCli) {
			if (is_file($path)) {
				$config->finder(new \ArrayIterator(array(new \SplFileInfo($path))));
			} else {
				$config->setDir($path);
			}
		}

		// register custom converters from config
		$this->converter->registerCustomConverters($config->getCustomConverters());

		$allConverters = $this->converter->getConverters();

		$converters = array();
		// remove/add converters based on the converters option
		if (preg_match('{(^|,)-}', $input->getOption('converters'))) {
			foreach ($converters as $key => $converter) {
				if (preg_match('{(^|,)-'.preg_quote($converter->getName()).'}', $input->getOption('converters'))) {
					unset($converters[$key]);
				}
			}
		} elseif ($input->getOption('converters')) {
			$names = array_map('trim', explode(',', $input->getOption('converters')));

			foreach ($allConverters as $converter) {
				if (in_array($converter->getName(), $names) && !in_array($converter, $converters)) {
					$converters[] = $converter;
				}
			}
		} else {
			$converters = $allConverters;
		}

		$config->converters($converters);

		$changed = $this->converter->convert($config, $input->getOption('dry-run'), $input->getOption('diff'), $input->getOption('ext'));

		$i = 1;
		switch ($input->getOption('format')) {
			case 'txt':
				foreach ($changed as $file => $fixResult) {
					$output->write(sprintf('%4d) %s', $i++, $file));
					if ($input->getOption('verbose')) {
						$output->write(sprintf(' (<comment>%s</comment>)', implode(', ', $fixResult['appliedConverters'])));
						if ($input->getOption('diff')) {
							$output->writeln('');
							$output->writeln('<comment>      ---------- begin diff ----------</comment>');
							$output->writeln($fixResult['diff']);
							$output->writeln('<comment>      ---------- end diff ----------</comment>');
						}
					}
					$output->writeln('');
				}
				break;
			case 'xml':
				$dom = new \DOMDocument('1.0', 'UTF-8');
				$dom->appendChild($filesXML = $dom->createElement('files'));
				foreach ($changed as $file => $fixResult) {
					$filesXML->appendChild($fileXML = $dom->createElement('file'));

					$fileXML->setAttribute('id', $i++);
					$fileXML->setAttribute('name', $file);
					if ($input->getOption('verbose')) {
						$fileXML->appendChild($appliedConvertersXML = $dom->createElement('applied_converters'));
						foreach ($fixResult['appliedConverters'] as $appliedConverter) {
							$appliedConvertersXML->appendChild($appliedConverterXML = $dom->createElement('applied_converter'));
							$appliedConverterXML->setAttribute('name', $appliedConverter);
						}

						if ($input->getOption('diff')) {
							$fileXML->appendChild($diffXML = $dom->createElement('diff'));

							$diffXML->appendChild($dom->createCDATASection($fixResult['diff']));
						}
					}
				}

				$dom->formatOutput = true;
				$output->write($dom->saveXML());
				break;
			default:
				throw new \InvalidArgumentException(sprintf('The format "%s" is not defined.', $input->getOption('format')));
		}

		return empty($changed) ? 0 : 1;
	}

	protected function getConvertersHelp()
	{
		$converters = '';
		$maxName = 0;
		foreach ($this->converter->getConverters() as $converter) {
			if (strlen($converter->getName()) > $maxName) {
				$maxName = strlen($converter->getName());
			}
		}

		$count = count($this->converter->getConverters()) - 1;
		foreach ($this->converter->getConverters() as $i => $converter) {
			$chunks = explode("\n", wordwrap(sprintf('%s', $converter->getDescription()), 72 - $maxName, "\n"));
			$converters .= sprintf(" * <comment>%s</comment>%s %s\n", $converter->getName(), str_repeat(' ', $maxName - strlen($converter->getName())), array_shift($chunks));
			while ($c = array_shift($chunks)) {
				$converters .= str_repeat(' ', $maxName + 4).$c."\n";
			}

			if ($count != $i) {
				$converters .= "\n";
			}
		}

		return $converters;
	}

	protected function getConfigsHelp()
	{
		$configs = '';
		$maxName = 0;
		foreach ($this->converter->getConfigs() as $config) {
			if (strlen($config->getName()) > $maxName) {
				$maxName = strlen($config->getName());
			}
		}

		$count = count($this->converter->getConfigs()) - 1;
		foreach ($this->converter->getConfigs() as $i => $config) {
			$chunks = explode("\n", wordwrap($config->getDescription(), 72 - $maxName, "\n"));
			$configs .= sprintf(" * <comment>%s</comment>%s %s\n", $config->getName(), str_repeat(' ', $maxName - strlen($config->getName())), array_shift($chunks));
			while ($c = array_shift($chunks)) {
				$configs .= str_repeat(' ', $maxName + 4).$c."\n";
			}

			if ($count != $i) {
				$configs .= "\n";
			}
		}

		return $configs;
	}
}
