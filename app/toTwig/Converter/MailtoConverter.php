<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class MailtoConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class MailtoConverter extends ConverterAbstract
{
    protected $name = 'mailto';
    protected $description = "Convert smarty {mailto} to twig function {{ mailto() }}";
    protected $priority = 100;

    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(\SplFileInfo $file, $content)
    {
        $pattern = '/\[\{mailto\b\s*([^{}]+)?\}\]/';
        $string = '{{ mailto(:parameters) }}';

        return preg_replace_callback($pattern, function ($matches) use ($string) {

            $match = $matches[1];
            $attributes = $this->attributes($match);

            $address = $this->value($attributes['address']);
            unset($attributes['address']);

            $parameters = [];
            foreach ($attributes as $name => $value) {
                $parameters[] = $this->variable($name) . ": " . $this->value($value);
            }

            if (empty($parameters)) {
                $parameters = $address;
            } else {
                $parameters = $address . ", { " . implode(", ", $parameters) . " }";
            }

            $replaced = str_replace(':parameters', $parameters, $string);
            return $replaced;

        }, $content);
    }
}
