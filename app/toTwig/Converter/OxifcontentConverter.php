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
    /**
     * @param \SplFileInfo $file
     * @param string $content
     *
     * @return string
     */
    public function convert(\SplFileInfo $file, $content)
    {
        $content = $this->replaceOxhasrights($content);
        $content = $this->replaceEndOxhasrights($content);

        return $content;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return 50;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'oxifcontent';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Convert oxifcontent to twig';
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceEndOxhasrights($content)
    {
        $search = "#\[\{/oxifcontent\s*\}\]#";
        $replace = "{% endoxifcontent %}";

        return preg_replace($search, $replace, $content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceOxhasrights($content)
    {
        $pattern = "#\[\{oxifcontent\b\s*([^{}]+)?\}\]#i";

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];

            $attributes = $this->attributes($match);

            $arguments = [];
            foreach ($attributes as $key => $value) {
                $arguments[] = $this->variable($key) . ": " . $this->value($value);
            }

            return sprintf("{%% oxifcontent { %s } %%}", implode(", ", $arguments));
        }, $content);
    }
}
