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
 * @author sankar <sankar.suda@gmail.com>
 */
class IfConverter extends ConverterAbstract
{
	private $alt = array(
			'gt'  => '>',
			'lt'  => '<',
			'eq'  => '==',
			'neq' => '!=',
			'ne'  => '!=',
			'not' => '!',
			'mod' => '%',
			'or'  => '||',
			'and' => '&&'
	);

	public function convert(\SplFileInfo $file, $content)
	{
		// Replace {if }
		$content = $this->replaceIf($content);
		// Replace {elseif }
		$content = $this->replaceElseIf($content);
		// Replace {else}
		$content = preg_replace('#\{/if\s*\}#', "{% endif %}", $content);
		// Replace {/if}
		$content = preg_replace('#\{else\s*\}#', "{% else %}", $content);

		return $content;
	}

	public function getPriority()
	{
		return 50;
	}

	public function getName()
	{
		return 'if';
	}

	public function getDescription()
	{
		return 'Convert smarty if/else/elseif to twig';
	}

	private function replaceIf($content){

		$pattern = "#\{if\b\s*([^{}]+)?\}#i";

		return preg_replace_callback($pattern, function($matches) {

			$match   = $matches[1];
			$search  = $matches[0];

			foreach ($this->alt as $key => $value) {
				$match = str_replace(" $key ", " $value ", $match);
			}

			$replace = "{% if ";
			$replace .= $match;
			$replace .= " %}";
			$search = str_replace($search, $replace, $search);

			return $search;

		}, $content);

	}

	private function replaceElseIf($content){

		$pattern = "#\{elseif\b\s*([^{}]+)?\}#i";

		return preg_replace_callback($pattern, function($matches) {

			$match   = $matches[1];
			$search  = $matches[0];

			foreach ($this->alt as $key => $value) {
				$match = str_replace(" $key ", " $value ", $match);
			}

			$replace = "{% elseif ";
			$replace .= $match;
			$replace .= " %}";
			$search = str_replace($search, $replace, $search);

			return $search;

		}, $content);

	}
}
