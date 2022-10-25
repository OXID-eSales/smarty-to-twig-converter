<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class VeparseConverter
 */
class VeparseConverter extends ConverterAbstract
{
    protected string $name = 'veparse';
    protected string $description = 'Convert veparse to twig';
    protected int $priority = 20;

    public function convert(string $content): string
    {
        $content = $this->replaceVeparse($content);
        $content = $this->replaceEndVeparse($content);

        return $content;
    }

    private function replaceVeparse(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('veparse');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $attr = $this->getAttributes($matches);

                $vars = [];
                foreach ($attr as $key => $value) {
                    $vars[$key] = $key . '=' . $value;
                }

                $replace['vars'] = implode(' ', $vars);
                $string = $this->replaceNamedArguments('{%veparse :vars%}', $replace);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }

    private function replaceEndVeparse(string $content): string
    {
        $search = $this->getClosingTagPattern('veparse');
        $replace = "{%endveparse%}";

        return preg_replace($search, $replace, $content);
    }

}
