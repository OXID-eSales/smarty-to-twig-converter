<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace sankar\ST;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
abstract class ConverterAbstract
{
	const PSR0_LEVEL = 1;
	const PSR1_LEVEL = 3;
	const PSR2_LEVEL = 7;
	const ALL_LEVEL  = 15;

	/**
	 * Fixes a file.
	 *
	 * @param \SplFileInfo $file    A \SplFileInfo instance
	 * @param string       $content The file content
	 *
	 * @return string The fixed file content
	 */
	public function convert(\SplFileInfo $file, $content)
	{

	}

	/**
	 * Returns the priority of the converter.
	 *
	 * The default priority is 0 and higher priorities are executed first.
	 */
	public abstract function getPriority();

	/**
	 * Returns the name of the converter.
	 *
	 * The name must be all lowercase and without any spaces.
	 *
	 * @return string The name of the converter
	 */
	public abstract function getName();

	/**
	 * Returns the description of the converter.
	 *
	 * A short one-line description of what the converter does.
	 *
	 * @return string The description of the converter
	 */
	public abstract function getDescription();


    /**
     * Returns true if the file is supported by this fixer.
     *
     * @return Boolean true if the file is supported by this fixer, false otherwise
     */
    public function supports(\SplFileInfo $file)
    {
    	return true;
    }


	/**
	 * Returns the attribues array from string
	 *
	 * @param string $string
	 * @return array
	 */
	protected function attributes($string){

		$items = explode(' ',$string);
		$attr  = array();
		foreach ($items as $item) {
			$item = explode('=', $item);
			$attr[trim($item[0])] = trim($item[1]);
		}
		return $attr;
	}
	/**
	 * Sanitize value, remove $,' or " from string
	 * @param string $string
	 */
	protected function val($string)
	{
		return str_replace(array('$','"',"'"),'',trim($string));
	}
}
