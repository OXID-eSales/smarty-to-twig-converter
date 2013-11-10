<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig;

use SebastianBergmann\Diff;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo as FinderSplFileInfo;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
class Converter
{
	const VERSION = '0.1-DEV';

	protected $converter = array();
	protected $configs = array();
	protected $diff;

	public function __construct()
	{
		$this->diff = new Diff();
	}

	public function registerBuiltInConverters()
	{
		foreach (Finder::create()->files()->in(__DIR__.'/Converter') as $file) {
			$class = 'toTwig\\Converter\\'.basename($file, '.php');
			$this->addConverter(new $class());
		}
	}

	public function registerCustomConverters($converter)
	{
		foreach ($converter as $convert) {
			$this->addConverter($convert);
		}
	}

	public function addConverter(ConverterAbstract $convert)
	{
		$this->converters[] = $convert;
	}

	public function getConverters()
	{
		$this->sortConverters();

		return $this->converters;
	}

	public function registerBuiltInConfigs()
	{
		foreach (Finder::create()->files()->in(__DIR__.'/Config') as $file) {
			$class = 'toTwig\\Config\\'.basename($file, '.php');
			$this->addConfig(new $class());
		}
	}

	public function addConfig(ConfigInterface $config)
	{
		$this->configs[] = $config;
	}

	public function getConfigs()
	{
		return $this->configs;
	}

	/**
	 * Fixes all files for the given finder.
	 *
	 * @param ConfigInterface $config A ConfigInterface instance
	 * @param Boolean         $dryRun Whether to simulate the changes or not
	 * @param Boolean         $diff   Whether to provide diff
	 */
	public function convert(ConfigInterface $config, $dryRun = false, $diff = false, $outputExt='')
	{
		$this->sortConverters();

		$converter = $this->prepareConverters($config);
		$changed = array();
		foreach ($config->getFinder() as $file) {
			if ($file->isDir()) {
				continue;
			}

			if ($fixInfo = $this->conVertFile($file, $converter, $dryRun, $diff, $outputExt)) {
				if ($file instanceof FinderSplFileInfo) {
					$changed[$file->getRelativePathname()] = $fixInfo;
				} else {
					$changed[$file->getPathname()] = $fixInfo;
				}
			}
		}

		return $changed;
	}

	public function conVertFile(\SplFileInfo $file, array $converter, $dryRun, $diff, $outputExt)
	{
		$new = $old = file_get_contents($file->getRealpath());
		$appliedConverters = array();

		foreach ($converter as $convert) {
			if (!$convert->supports($file)) {
				continue;
			}

			$new1 = $convert->convert($file, $new);
			if ($new1 != $new) {
				$appliedConverters[] = $convert->getName();
			}
			$new = $new1;
		}

		if ($new != $old) {
			if (!$dryRun) {
				
				$filename = $file->getRealpath();

				$ext = strrchr($filename, '.');
				if ($outputExt) {
					$filename = rtrim($filename,$ext).'.'.trim($outputExt,'.');
				}

				file_put_contents($filename, $new);
			}

			$fixInfo = array('appliedConverters' => $appliedConverters);

			if ($diff) {
				$fixInfo['diff'] = $this->stringDiff($old, $new);
			}

			return $fixInfo;
		}
	}

	protected function stringDiff($old, $new)
	{
		$diff = $this->diff->diff($old, $new);

		$diff = implode(PHP_EOL, array_map(function ($string) {
			$string = preg_replace('/^(\+){3}/', '<info>+++</info>', $string);
			$string = preg_replace('/^(\+){1}/', '<info>+</info>', $string);

			$string = preg_replace('/^(\-){3}/', '<error>---</error>', $string);
			$string = preg_replace('/^(\-){1}/', '<error>-</error>', $string);

			$string = str_repeat(' ', 6) . $string;

			return $string;
		}, explode(PHP_EOL, $diff)));

		return $diff;
	}

	private function sortConverters()
	{
		usort($this->converters, function ($a, $b) {
			if ($a->getPriority() == $b->getPriority()) {
				return 0;
			}

			return $a->getPriority() > $b->getPriority() ? -1 : 1;
		});
	}

	private function prepareConverters(ConfigInterface $config)
	{
		$converter = $config->getConverters();

		foreach ($converter as $convert) {
			if ($convert instanceof ConfigAwareInterface) {
				$convert->setConfig($config);
			}
		}

		return $converter;
	}
}
