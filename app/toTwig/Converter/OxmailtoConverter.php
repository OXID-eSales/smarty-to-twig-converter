<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxmailtoConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxmailtoConverter extends ConverterAbstract
{
    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        // [{oxmailto other stuff}]
        $pattern = '/\[\{\s*oxmailto\s*([^{}]+)?\}\]/';
        $string = '{{ oxmailto(:parameters) }}';

        return preg_replace_callback($pattern, function ($matches) use ($string) {

            $match = $matches[1];
            $attributes = $this->attributes($match);

            $parameters = $this->value($attributes['address']);
            if ($twigArray = $this->convertArrayToAssocTwigArray($attributes, ['address'])) {
                $parameters .= ", " . $twigArray;
            }

            $replaced = str_replace(':parameters', $parameters, $string);
            return $replaced;

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
        return 'oxmailto';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Convert smarty {oxmailto} to twig function {{ oxmailto() }}";
    }
}
