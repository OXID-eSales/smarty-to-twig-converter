<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxevalConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxevalConverter extends ConverterAbstract
{
    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        // [{oxeval other stuff}]
        $pattern = '/\[\{\s*oxeval\s*([^{}]+)?\}\]/';
        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);

            $argumentsString = $this->convertArrayToAssocTwigArray($attributes, []);

            return "{{ oxeval($argumentsString) }}";
        }, $content);
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 100;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oxeval';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Convert smarty {oxeval} to twig function {{ oxeval() }}";
    }
}