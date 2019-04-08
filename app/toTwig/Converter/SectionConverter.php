<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 28/08/18
 * Time: 12:34
 */

namespace toTwig\Converter;

/**
 * Class SectionConverter
 */
class SectionConverter extends ConverterAbstract
{

    protected $name = 'section';
    protected $description = 'Convert smarty {section} to twig {for}';
    protected $priority = 20;

    /**
     * Function converts smarty {section} tags to twig {for}
     *
     * @param string $content
     *
     * @return null|string|string[]
     */
    public function convert(string $content): string
    {
        $contentReplacedOpeningTag = $this->replaceSectionOpeningTag($content);
        $content = $this->replaceSectionClosingTag($contentReplacedOpeningTag);

        return $content;
    }

    /**
     * Function converts opening tag of smarty {section} to twig {for}
     *
     * @param string $content
     *
     * @return null|string|string[]
     */
    private function replaceSectionOpeningTag(string $content): string
    {
        $pattern = $this->getOpeningTagPattern('section');
        $string = '{% for :name in :start..:loop %}';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($string) {
                $replacement = $this->getAttributes($matches);
                $replacement['start'] = isset($replacement['start']) ? $replacement['start'] : 0;
                $replacement['name'] = $this->sanitizeVariableName($replacement['name']);
                $string = $this->replaceNamedArguments($string, $replacement);

                return str_replace($matches[0], $string, $matches[0]);
            },
            $content
        );
    }

    /**
     * Function converts closing tag of smarty {section} to twig {for}
     *
     * @param string $content
     *
     * @return null|string|string[]
     */
    private function replaceSectionClosingTag(string $content): string
    {
        $search = $this->getClosingTagPattern('section');
        $replace = '{% endfor %}';

        return preg_replace($search, $replace, $content);
    }
}
