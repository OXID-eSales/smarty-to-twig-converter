<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 28/08/18
 * Time: 12:34
 */

namespace toTwig\Converter;

class InsertConverter extends IncludeConverter
{
    //[{ capture append="var" }]
    protected $pattern = '/\[\{\s*insert\b\s*([^{}]+)?\s*\}\]/';
    protected $string = '{% include :template :with :vars %}';
    protected $attrName = 'name';

    /**
     * @return string
     */
    public function getName()
    {
        return 'insert';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Convert smarty insert to twig include';
    }
}