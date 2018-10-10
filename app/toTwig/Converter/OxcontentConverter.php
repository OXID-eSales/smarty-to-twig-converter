<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxcontentConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxcontentConverter extends ConverterAbstract
{

    protected $name = 'oxcontent';
    protected $description = "Convert smarty {oxcontent} to twig function {{ oxcontent() }}";
    protected $priority = 100;

    /**
     * @param \SplFileInfo $file
     * @param string       $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        // [{oxcontent other stuff}]
        $pattern = $this->getOpeningTagPattern('oxcontent');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $match = $matches[1];
                $attributes = $this->attributes($match);

                $key = null;
                if (isset($attributes['ident'])) {
                    $key = 'ident';
                } elseif (isset($attributes['oxid'])) {
                    $key = 'oxid';
                }

                $value = $this->rawString($this->value($attributes[$key]));

                $templateName = "content::$key::$value";

                $parameters = array_map(
                    function ($attribute) {
                        return $this->rawString($this->value($attribute));
                    },
                    array_filter(
                        $attributes,
                        function ($attribute) {
                            return !in_array($attribute, ['ident', 'oxid', 'assign']);
                        },
                        ARRAY_FILTER_USE_KEY
                    )
                );

                if (!empty($parameters)) {
                    $templateName .= '?' . http_build_query($parameters);
                }

                // Different approaches for syntax with and without assignment
                $assignVar = $this->extractAssignVariableName($attributes);
                if ($assignVar) {
                    $twigTag = "{% set $assignVar = include('$templateName') %}";
                } else {
                    $twigTag = "{% include '$templateName' %}";
                }

                return $twigTag;
            },
            $content
        );
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    private function extractAssignVariableName($attributes)
    {
        $assignVar = null;
        if (isset($attributes['assign'])) {
            $assignVar = $this->variable($attributes['assign']);
        }

        return $assignVar;
    }
}
