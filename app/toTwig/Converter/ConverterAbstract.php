<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\Converter;

use SplFileInfo;
use toTwig\FilterNameMap;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
abstract class ConverterAbstract
{
    /**
     * @var string Name of the converter.
     *
     * The name must be all lowercase and without any spaces.
     */
    protected string $name;

    /**
     * @var string Description of the converter.
     *
     * A short one-line description of what the converter does.
     */
    protected string $description;

    /**
     * @var int Priority of the converter.
     *
     * The default priority is 0 and higher priorities are executed first.
     */
    protected int $priority = 0;

    /**
     * Fixes a file.
     *
     * @param string $content The file content
     *
     * @return string The fixed file content
     */
    public function convert(string $content): string
    {
        return $content;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Returns true if the file is supported by this converter.
     *
     * @param SplFileInfo $file
     *
     * @return Boolean true if the file is supported by this converter, false otherwise
     */
    public function supports(SplFileInfo $file): bool
    {
        return true;
    }

    /**
     * Get opening tag patterns like:
     *   [{tagName other stuff}]
     *   [{foreach $myColors as $color}]
     *
     * Matching this pattern will give these results:
     *   $matches[0] contains a string with full matched tag i.e.'[{tagName foo="bar" something="somevalue"}]'
     *   $matches[1] should contain a string with all other configuration coming with a tag i.e.
     *   'foo = "bar" something="somevalue"'
     */
    protected function getOpeningTagPattern(string $tagName): string
    {
        return sprintf("#\[\{\s*%s\b\s*((?:(?!\[\{|\}\]).(?<!\[\{)(?<!\}\]))+)?\}\]#is", preg_quote($tagName, '#'));
    }

    /**
     * Get closing tag pattern: [{/tagName}]
     */
    protected function getClosingTagPattern(string $tagName): string
    {
        return sprintf("#\[\{\s*/%s\s*\}\]#i", preg_quote($tagName, '#'));
    }

    /**
     * Method to extract key/value pairs out of a string with xml style attributes
     *
     * @param string $string String containing xml style attributes
     *
     * @return  array   Key/Value pairs for the attributes
     */
    protected function extractAttributes(string $string): array
    {
        //Initialize variables
        $attr = $pairs = [];
        $pattern = '/(?:([\w:\-]+)\s*=\s*)?'
                   . '((?:".*?"|\'.*?\'|(?:[$\w\->():]+))(?:[\|]?(?:\'\s+\'|"\s+"|[^\s}]|(}(?!])))*))/';

        // Lets grab all the key/value pairs using a regular expression
        preg_match_all($pattern, $string, $attr);
        if (is_array($attr)) {
            $numPairs = count($attr[1]);

            for ($i = 0; $i < $numPairs; $i++) {
                $value = trim($attr[2][$i]);
                $key = ($attr[1][$i]) ? trim($attr[1][$i]) : trim(trim($value, '"'), "'");

                $pairs[$key] = $value;
            }
        }

        return $pairs;
    }

    /**
     * Sanitize variable, remove $,' or " from string
     */
    protected function sanitizeVariableName(string $string): string
    {
        return trim(trim($string), '$"\'');
    }

    /**
     * Sanitize value, remove $,' or " from string
     */
    protected function sanitizeValue(string $string): string
    {
        $string = trim($string);

        if (empty($string)) {
            return $string;
        }

        // Handle "..." and '...'
        if (preg_match("/^\"[^\"]*\"$/", $string) || preg_match("/^'[^']*'$/", $string)) {
            return $string;
        }

        // Handle operators
        switch ($string) {
            case '&&':
                return 'and';
            case '||':
                return 'or';
            case 'mod':
                return '%';
            case 'gt':
                return '>';
            case 'lt':
                return '<';

            // '===' is converted separately by IdenticalComparisonConverter
            case 'eq':
                return '==';

            case 'ne':
            case 'neq':
            case '!==':
                return '!=';

            case 'gte':
            case 'ge':
                return '>=';

            case 'lte':
            case 'le':
                return '<=';
        }

        // Handle non-quoted strings
        if (preg_match("/^[a-zA-Z]\w+$/", $string)) {
            if (!in_array($string, ["true", "false", "and", "or", "not"])) {
                return "\"" . $string . "\"";
            }
        }

        // Handle "($var"
        if ($string[0] == "(") {
            return "(" . $this->sanitizeValue(ltrim($string, "("));
        }

        // Handle "!$var"
        if (preg_match("/(?<=[(\\s]|^)!(?!=)(?=[$]?\\w*)/", $string)) {
            return "not " . $this->sanitizeValue(ltrim($string, "!"));
        }

        $string = ltrim($string, '$');
        $string = str_replace("->", ".", $string);

        $string = $this->convertFunctionArguments($string);
        $string = $this->convertFilters($string);

        return $string;
    }

    /**
     * Handles a case when smarty variable is passed to a function as a parameter
     * For example:
     *   smarty: [{ foo($bar)}]
     *   twig:   {{ foo(bar) }]
     */
    private function convertFunctionArguments(string $string): string
    {
        return preg_replace_callback(
            "/\([^)]*\)/",
            function ($matches) {
                $expression = $matches[0];
                $expression = rtrim(ltrim($expression, "("), ")");

                $parts = explode(",", $expression);
                foreach ($parts as &$part) {
                    $part = $this->sanitizeValue($part);
                }

                return sprintf("(%s)", implode(", ", $parts));
            },
            $string
        );
    }

    /**
     * Handle translation of filters
     * For example:
     *   smarty: [{ "foo"|smarty_bar) }]
     *   twig:   {{ "foo"|twig_bar }}
     */
    private function convertFilters(string $string): string
    {
        return preg_replace_callback(
            '/\|@?(?:\w+)(?:\:|\b)(?:"\s+"|\'\s+\'|[^\s}|])*/',
            function ($matches) {
                $expression = $matches[0];
                $expression = ltrim($expression, "|");

                $parts = explode(":", $expression);

                $value = array_shift($parts);
                $value = ltrim($value, "@");

                foreach ($parts as &$part) {
                    $part = $this->sanitizeValue($part);
                }

                $convertedFilterName = FilterNameMap::getConvertedFilterName($value);

                return "|$convertedFilterName" . (!empty($parts) ? ("(" . implode(", ", $parts) . ")") : "");
            },
            $string
        );
    }

    /**
     * Explodes expression to parts to converts them separately
     * For example:
     *  input:  ($a+$b)
     *  output: ($a + $b)
     *
     * Matching input pattern will give these results:
     *   $matches[0] contains a string with full matched tag i.e.'[{($a+$b)}]'
     *   $matches[1] should contain a string with first part of an expression i.e. $a
     *   $matches[2] should contain a string with one of following characters: +, -, >, <, *, /, %, &&, ||
     *   $matches[3] should contain a string with second part of an expression i.e. $b
     */
    protected function convertExpression(string $expression): string
    {
        $expression = $this->convertFilters($expression);

        $expression = preg_replace_callback(
            "/(\S+)(\+|-(?!>)|\*|\/|%|&&|\|\|)(\S+)/",
            function ($matches) {
                return $matches[1] . " " . $matches[2] . " " . $matches[3];
            },
            $expression
        );

        $parts = explode(" ", $expression);
        foreach ($parts as &$part) {
            $part = $this->sanitizeValue($part);
        }

        return implode(" ", $parts);
    }

    /**
     * Replace named args in string
     * For example:
     *   $string = '{% set :key = :value %}'
     *   $args = ['key' => 'foo', 'value' => 'bar']
     *
     * return '{% set 'foo' = 'bar' %}'
     */
    protected function replaceNamedArguments(string $string, array $args): string
    {
        $pattern = '/:([a-zA-Z0-9_-]+)/';
        $string = preg_replace_callback(
            $pattern,
            function ($matches) use ($args) {
                if (isset($args[$matches[1]])) {
                    return str_replace($matches[0], $args[$matches[1]], $matches[0]);
                }
            },
            $string
        );

        return $this->removeMultipleSpaces($string);
    }

    /**
     * Replace multiple spaces in string with single space
     */
    protected function removeMultipleSpaces(string $string): string
    {
        return preg_replace('! +!', ' ', $string);
    }

    /**
     * Converts associative php array to twig array
     * ['a' => 1, 'b' => 2]  ==>>  "{ a: 1, b: 2 }"
     */
    protected function convertArrayToAssocTwigArray(array $array, array $skippedKeys): string
    {
        $pairs = [];
        foreach ($array as $key => $value) {
            if (in_array($key, $skippedKeys)) {
                continue;
            }
            $pairs[] = $this->sanitizeVariableName($key) . ": " . $this->sanitizeValue($value);
        }

        // If array is empty, return nothing
        if (empty($pairs)) {
            return "";
        }

        return sprintf("{ %s }", implode(", ", $pairs));
    }

    protected function rawString(string $string): string
    {
        return trim($string, '\'"');
    }

    protected function convertFileExtension(string $templateName): string
    {
        return preg_replace('/\.tpl/', '.html.twig', $templateName);
    }

    protected function getAttributes(array $matches): array
    {
        $match = $this->getPregReplaceCallbackMatch($matches);
        return $this->extractAttributes($match);
    }

    protected function getPregReplaceCallbackMatch(array $matches): string
    {
        if (!isset($matches[1]) && $matches[0]) {
            $match = $matches[0];
        } else {
            $match = $matches[1];
        }

        return $match;
    }

    /**
     * Used in InsertTrackerConverter nad IncludeConverter
     */
    protected function getOptionalReplaceVariables(array $attr): string
    {
        $vars = [];
        foreach ($attr as $key => $value) {
            $vars[] = $this->sanitizeVariableName($key) . ": " . $this->sanitizeValue($value);
        }

        return '{' . implode(', ', $vars) . '}';
    }
}
