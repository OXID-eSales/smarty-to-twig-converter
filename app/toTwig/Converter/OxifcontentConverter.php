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
     * @param string $content
     *
     * @return string
     */
    private function replaceEndOxifcontent($content)
    {
        // [{/oxifcontent}]
        $search = $this->getClosingTagPattern('oxifcontent');
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
        $pattern = $this->getOpeningTagPattern('oxifcontent');

        return preg_replace_callback($pattern, function ($matches) {
            $match = $matches[1];

            $attributes = $this->attributes($match);

            return sprintf("{%% oxifcontent %s %%}", $this->convertArrayToAssocTwigArray($attributes, []));
        }, $content);
    }
}
