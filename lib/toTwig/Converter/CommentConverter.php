<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * @author sankara <sankar.suda@gmail.com>
 */
class CommentConverter extends ConverterAbstract
{

	public function convert(\SplFileInfo $file, $content)
	{
		return str_replace(array('{*','*}'), array('{#','#}'), $content);
	}

	public function getPriority()
	{
		return 52;
	}

	public function getName()
	{
		return 'comment';
	}

	public function getDescription()
	{
		return 'Convert smarty comments {* *} to twig {# #}';
	}

}
