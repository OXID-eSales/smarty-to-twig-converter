<?php

namespace toTwig\Converter;

use toTwig\ConverterAbstract;

/**
 * Class OxhasrightsConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class OxhasrightsConverter extends ConverterAbstract
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
        return 'oxhasrights';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'Convert oxhasrights to twig';
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceEndOxhasrights($content)
    {
        $search = "#\[\{\s*/oxhasrights\s*\}\]#";
        $replace = "{% endoxhasrights %}";

        return preg_replace($search, $replace, $content);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function replaceOxhasrights($content)
    {
        $pattern = "#\[\{\s*oxhasrights\s*([^{}]+)?\}\]#i";

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];

            $attr = $this->attributes($match);
            $ident = $this->value($attr['ident']);

            return sprintf("{%% oxhasrights %s %%}", $ident);
        }, $content);
    }
}
