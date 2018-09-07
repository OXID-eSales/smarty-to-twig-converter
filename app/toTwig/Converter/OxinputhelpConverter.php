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
    protected $name = 'oxinputhelp';
    protected $description = "Convert smarty {oxinputhelp} to twig function {{ oxinputhelp() }}";
    protected $priority = 100;

    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        // [{oxinputhelp ident="A_IDENT"}]
        $pattern = $this->getOpeningTagPattern('oxinputhelp');

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];
            $attributes = $this->attributes($match);
            $ident = $this->value($attributes['ident']);

            return "{{ oxinputhelp($ident) }}";
        }, $content);
    }
}