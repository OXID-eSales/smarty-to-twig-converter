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

use DOMDocument;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use toTwig\Config\ConfigInterface;
use toTwig\ConversionResult;
use toTwig\Converter;
use toTwig\Config\Config;
use toTwig\SourceConverter\DatabaseConverter;
use toTwig\SourceConverter\SourceConverter;
use toTwig\SourceConverter\FileConverter;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class ConvertCommand extends Command
{

    protected $converter;
    protected $defaultConfig;

    /**
     * @param Converter       $converter
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
    protected function configure(): void
    {
        $this
            ->setName('convert')
            ->setDefinition(
                [
                    new InputOption('path', '', InputOption::VALUE_OPTIONAL, 'The path'),
                    new InputOption('database', '', InputOption::VALUE_REQUIRED, 'Database parameters'),
                    new InputOption('database-columns', '', InputOption::VALUE_REQUIRED, 'Database columns to convert'),
                    new InputOption('config', '', InputOption::VALUE_REQUIRED, 'The configuration name', null),
                    new InputOption('config-path', '', InputOption::VALUE_REQUIRED, 'The configuration file path'),
                    new InputOption('converters', '', InputOption::VALUE_REQUIRED, 'A list of converters to run'),
                    new InputOption('ext', '', InputOption::VALUE_REQUIRED, 'To output files with other extension', '.html.twig'),
                    new InputOption('diff', '', InputOption::VALUE_NONE, 'Also produce diff for each file'),
                    new InputOption('dry-run', '', InputOption::VALUE_NONE, 'Only shows which files would have been modified'),
                    new InputOption('format', '', InputOption::VALUE_REQUIRED, 'To output results in other formats', 'txt')
                ]
            )
            ->setDescription('Convert a directory, file or database entities.')
            ->setHelp(
                <<<EOF
           
The convert command tries to fix as much coding standards problems as possible on a given file, directory or database.
Converter can work with files and directories:

	<info>php toTwig convert --path=/path/to/dir</info>
	<info>php toTwig convert --path=/path/to/file</info>
	
<comment>--ext</comment>
By default files with .html.twig extension will be created. To specify different extensions use --ext parameter:

    <info>php toTwig convert --path=/path/to/dir --ext=.js.twig</info>
    
<comment>--database</comment>
The --database parameter gets database doctrine-like URL to convert database entries.

    <info>php toTwig convert --database="mysql://user:password@localhost/db"</info>

<comment>--database-columns</comment>
The --database-columns option lets you choose tables columns to be converted
(the table column names has to be specified in table_a.column_b format and separated by comma):

    <info>php toTwig convert --database="..." --database-columns=oxactions.OXLONGDESC,oxcontents.OXCONTENT</info>
    
You can also blacklist the table columns you don't want using "-" before column name

    <info>php toTwig convert --database="..." --database-columns=-oxactions.OXLONGDESC_1,-oxcontents.OXCONTENT_1</info>

<comment>--converters</comment>
The --converters option lets you choose which converters to apply (the converter names must be separated by a comma):

    <info>php toTwig convert --path=/path/to/dir --ext=.html.twig --converters=for,if,misc</info>

You can also blacklist the converters you don't want if this is more convenient, using "-" before converter name:

    <info>php toTwig convert --path=/path/to/dir --ext=.html.twig --converters=-for,-if</info>

<comment>--dry-run</comment>
The --dry-run option displays the files that need to be fixed but without actually modifying them:

<comment>--diff</comment>
Will create diff for all files

<comment>--config-path</comment>
Instead of building long line commands it is possible to inject PHP configuration code. Two example files are included
in main directory: config_file.php and config_database.php. To include config file use --config-path parameter:

    <info>php toTwig convert --config-path=config_file.php</info>

For more information check README.md in installation directory

EOF
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     *
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        var_dump($input->getOptions());

        try {
            $this->checkInputConstraints($input);
        } catch (InvalidArgumentException $exception) {
            $output->writeln($this->getHelp());
            throw $exception;
        }

        $config = $this->getConfig($input);

        $this->converter->setSourceConverter($config->getSourceConverter());
        // register custom converters from config
        $this->converter->registerCustomConverters($config->getCustomConverters());

        if ($input->getOption('converters')) {
            $this->converter->filterConverters(explode(',', $input->getOption('converters')));
        }

        $changed = $this->converter->convert($config->isDryRun(), $config->isDiff());

        switch ($input->getOption('format')) {
            case 'txt':
                $this->outputTxt($input, $output, $changed);
                break;
            case 'xml':
                $this->outputXml($input, $output, $changed);
                break;
            default:
                throw new InvalidArgumentException(sprintf('The format "%s" is not defined.', $input->getOption('format')));
        }

        return empty($changed) ? 0 : 1;
    }

    /**
     * @param InputInterface $input
     */
    private function checkInputConstraints(InputInterface $input)
    {
        if ($input->getOption('path') && $input->getOption('database')) {
            throw new InvalidOptionException("Only one of '--path' or '--database' options should be defined.");
        }

        if (
            $input->getOption('path') == null &&
            $input->getOption('database') == null &&
            $input->getOption('config-path') == null
        ) {
            throw new InvalidOptionException("One of '--path', '--database' or '--config-path' options should be defined.");
        }
    }

    /**
     * @param InputInterface $input
     *
     * @return ConfigInterface
     */
    private function getConfig(InputInterface $input): ConfigInterface
    {
        if ($input->getOption('config')) {
            $config = null;
            foreach ($this->converter->getConfigs() as $config) {
                if ($config->getName() == $input->getOption('config')) {
                    return $config;
                }
            }

            throw new InvalidOptionException(sprintf('The configuration "%s" is not defined', $input->getOption('config')));
        } elseif ($configPath = $input->getOption('config-path')) {
            if (!file_exists($configPath)) {
                throw new InvalidOptionException("The configuration filepath is incorrect. File doesn't exist.");
            }

            return include $configPath;
        } else {
            return $this->buildConfig($input);
        }
    }

    /**
     * @param InputInterface $input
     *
     * @return Config
     */
    private function buildConfig(InputInterface $input)
    {
        $config = $this->defaultConfig;

        /** @var SourceConverter */
        $sourceConverter = null;

        if ($input->getOption('path')) {
            $sourceConverter = new FileConverter();

            $path = $input->getOption('path');

            if ($path) {
                $filesystem = new Filesystem();
                if (!$filesystem->isAbsolutePath($path)) {
                    $path = getcwd() . DIRECTORY_SEPARATOR . $path;
                }
            }

            $sourceConverter
                ->setPath($path)
                ->setOutputExtension($input->getOption('ext'));
        } elseif ($databaseUrl = $input->getOption('database')) {
            $sourceConverter = new DatabaseConverter($databaseUrl);
            if ($input->getOption('database-columns')) {
                $sourceConverter->filterColumns(explode(',', $input->getOption('database-columns')));
            }
        }

        $config
            ->dryRun($input->getOption('dry-run'))
            ->diff($input->getOption('diff'))
            ->setSourceConverter($sourceConverter);

        return $config;
    }

    /**
     * @param InputInterface     $input
     * @param OutputInterface    $output
     * @param ConversionResult[] $changed
     */
    private function outputTxt(InputInterface $input, OutputInterface $output, array $changed): void
    {
        $i = 1;
        foreach ($changed as $id => $conversionResult) {
            $output->write(sprintf('%4d) %s', $i++, $id));
            if ($input->hasOption('verbose')) {
                //var_dump($input->hasOption('verbose'));
                $output->write(sprintf(' (<comment>%s</comment>)', implode(', ', $conversionResult->getAppliedConverters())));
                if ($input->getOption('diff')) {
                    $output->writeln('');
                    $output->writeln('<comment>      ---------- begin diff ----------</comment>');
                    $output->writeln($conversionResult->getDiff());
                    $output->writeln('<comment>      ---------- end diff ----------</comment>');
                }
            }
            $output->writeln('');
        }
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param array           $changed
     */
    private function outputXml(InputInterface $input, OutputInterface $output, array $changed): void
    {
        $dom = new DOMDocument('1.0', 'UTF-8');
        $dom->appendChild($filesXML = $dom->createElement('files'));
        $i = 1;
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
    }

    /**
     * @return string
     */
    protected function getConvertersHelp(): string
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
                $converters .= str_repeat(' ', $maxName + 4) . $c . "\n";
            }

            if ($count != $i) {
                $converters .= "\n";
            }
        }

        return $converters;
    }

    /**
     * @return string
     */
    protected function getConfigsHelp(): string
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
                $configs .= str_repeat(' ', $maxName + 4) . $c . "\n";
            }

            if ($count != $i) {
                $configs .= "\n";
            }
        }

        return $configs;
    }
}
