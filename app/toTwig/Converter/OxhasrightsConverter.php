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

    protected $name = 'oxhasrights';
    protected $description = 'Convert oxhasrights to twig';
    protected $priority = 50;

    /**
     * @param \SplFileInfo $file
     * @param string       $content
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
     * @param string $content
     *
     * @return string
     */
    private function replaceEndOxhasrights($content)
    {
        // [{/oxhasrights}]
        $search = $this->getClosingTagPattern('oxhasrights');
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
        // [{oxhasrights other stuff}]
        $pattern = $this->getOpeningTagPattern('oxhasrights');

        return preg_replace_callback(
            $pattern,
            function ($matches) {
                $match = $matches[1];

                $attr = $this->attributes($match);
                $ident = $this->value($attr['ident']);

                return sprintf("{%% oxhasrights %s %%}", $ident);
            },
            $content
        );
    }
}
