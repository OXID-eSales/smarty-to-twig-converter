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
class ForConverter extends ConverterAbstract
{
	// Lookup tables for performing some token
	// replacements not addressed in the grammar.
	private $replacements = [
		'smarty\.foreach.*\.index' => 'loop.index0',
		'smarty\.foreach.*\.iteration' => 'loop.index'
	];

	public function convert(\SplFileInfo $file, $content)
	{
		$content = $this->replaceFor($content);
		$content = $this->replaceEndForEach($content);
		$content = $this->replaceForEachElse($content);

		foreach ($this->replacements as $k=>$v) {
			$content = preg_replace($k, $v, $content);
		}

		return $content;
	}

	public function getPriority()
	{
		return 50;
	}

	public function getName()
	{
		return 'for';
	}

	public function getDescription()
	{
		return 'Convert foreach/foreachelse to twig';
	}

	private function replaceEndForEach($content)
	{
		$search = "#\{/foreach\s*\}#";
		$replace = "{% endfor %}";
		return preg_replace($search,$replace,$content);
	}

	private function replaceForEachElse($content)
	{
		$search = "#\{foreachelse\s*\}#";
		$replace = "{% else %}";
		return preg_replace($search,$replace,$content);
	}

	private function replaceFor($content){

		// $pattern = "#\{foreach\b\s*(?:(?!}).)+?\}#";
		$pattern = "#\{foreach\b\s*([^{}]+)?\}#i";

		return preg_replace_callback($pattern, function($matches) {

			$match   = $matches[1];
			$search  = $matches[0];

			// {foreach $users as $user}
			if (preg_match("/(.*)(?:as)(.*)/i", $match,$mcs)) {

				$replace = "{% for ".$this->val($mcs[2])." in ".$this->val($mcs[1])." %}";
				$search = str_replace($search, $replace, $search);
			} else {

				$attr = $this->attributes($match);
				$replace = "{% for ";

				if ($attr['key']) {
					$replace .= $attr['key'].',';
				}
				$replace .= $this->val($attr['item'])." in ".$this->val($attr['from'])." %}";
				$search = str_replace($search, $replace, $search);

			}

			//{foreach from=$data item="entry"}
			return $search;

		}, $content);

	}

}
