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
        $content = $this->replaceOxifcontent($content);
        $content = $this->replaceEndOxifcontent($content);

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
    private function replaceEndOxifcontent($content)
    {
        // [{oxifcontent}]
        $search = "#\[\{\s*/oxifcontent\s*\}\]#";
        $replace = "{% endoxifcontent %}";

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
        $pattern = "#\[\{\s*oxifcontent\s*([^{}]+)?\}\]#i";

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];

            $attributes = $this->attributes($match);

            return sprintf("{%% oxifcontent %s %%}", $this->convertArrayToAssocTwigArray($attributes, []));
        }, $content);
    }
}
