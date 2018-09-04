<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxinputhelpConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxinputhelpConverter extends ConverterAbstract
{
    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        $pattern = '/\[\{oxinputhelp\b\s*([^{}]+)?\}\]/';

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);
            $ident = $this->value($attributes['ident']);

            return "{{ oxinputhelp($ident) }}";
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
        return 'oxinputhelp';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Convert smarty {oxinputhelp} to twig function {{ oxinputhelp() }}";
    }
}