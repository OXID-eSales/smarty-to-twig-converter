<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxifcontentConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxifcontentConverter extends ConverterAbstract
{

    protected $name = 'oxifcontent';
    protected $description = 'Convert oxifcontent to twig';
    protected $priority = 50;

    /**
     * @param \SplFileInfo $file
     * @param string       $content
     *
     * @return string
     */
    public function convert(\SplFileInfo $file, $content)
    {
        $assignVar = null;
        $openingPattern = $this->getOpeningTagPattern('oxifcontent');
        if (preg_match($openingPattern, $content, $matches)) {
            $attributes = $this->attributes($matches[1]);
            if (isset($attributes['assign'])) {
                $assignVar = $this->variable($attributes['assign']);
            }
        }

        $content = $this->replaceOxifcontent($content);
        $content = $this->replaceEndOxifcontent($content);

        return ($assignVar ? "{% set $assignVar %}" : '') . $content . ($assignVar ? "{% endset %}" : '');
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceEndOxifcontent($content)
    {
        // [{/oxifcontent}]
        $search = $this->getClosingTagPattern('oxifcontent');
        $replace = "{% endifcontent %}";

        return preg_replace($search, $replace, $content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceOxifcontent($content)
    {
        // [{oxifcontent other stuff}]
        $pattern = $this->getOpeningTagPattern('oxifcontent');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $match = $matches[1];

                $attributes = $this->attributes($match);
                $key = isset($attributes['ident']) ? 'ident' : 'oxid';
                $value = ($key == 'ident') ? $attributes['ident'] : $attributes['oxid'];
                $set = $attributes['object'] ? (' set ' . $this->rawString($attributes['object'])) : '';

                return sprintf("{%% ifcontent %s %s%s %%}", $key, $this->value($value), $set);
            },
            $content
        );
    }
}
