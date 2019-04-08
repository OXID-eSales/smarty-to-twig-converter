<?php

namespace toTwig\Converter;

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
     * @param string $content
     *
     * @return string
     */
    public function convert(string $content): string
    {
        $assignVar = null;
        $openingPattern = $this->getOpeningTagPattern('oxifcontent');
        if (preg_match($openingPattern, $content, $matches)) {
            $attributes = $this->getAttributes($matches);
            if (isset($attributes['assign'])) {
                $assignVar = $this->sanitizeVariableName($attributes['assign']);
            }
        }

        $content = $this->replaceOxifcontent($openingPattern, $content);
        $content = $this->replaceEndOxifcontent($content);

        return ($assignVar ? "{% set $assignVar %}" : '') . $content . ($assignVar ? "{% endset %}" : '');
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceEndOxifcontent(string $content): string
    {
        $search = $this->getClosingTagPattern('oxifcontent');
        $replace = "{% endifcontent %}";

        return preg_replace($search, $replace, $content);
    }

    /**
     * @param string $pattern
     * @param string $content
     *
     * @return string
     */
    private function replaceOxifcontent(string $pattern, string $content): string
    {
        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $attributes = $this->getAttributes($matches);
                $key = isset($attributes['ident']) ? 'ident' : 'oxid';
                $value = ($key == 'ident') ? $attributes['ident'] : $attributes['oxid'];
                $set = $attributes['object'] ? (' set ' . $this->rawString($attributes['object'])) : '';

                return sprintf("{%% ifcontent %s %s%s %%}", $key, $this->sanitizeValue($value), $set);
            },
            $content
        );
    }
}
