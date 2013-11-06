<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace sankar\ST\Converter;

use sankar\ST\ConverterAbstract;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class VariableConverter extends ConverterAbstract
{

	public function convert(\SplFileInfo $file, $content)
	{
		return str_replace(array('{*','*}'), array('{#','#}'), $content);
	}

	public function getPriority()
	{
		return 100;
	}

	public function getName()
	{
		return 'variable';
	}

	public function getDescription()
	{
		return 'Convert smarty variable {$var.name} to twig {{var.name}}';
	}

	private function arrays($content)
	{
		$pattern = "";
	}

}
