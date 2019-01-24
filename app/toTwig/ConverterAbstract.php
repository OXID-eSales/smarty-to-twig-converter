<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig;

/**
 * @author sankar <sankar.suda@gmail.com>
 */
abstract class ConverterAbstract
{

    const ALL_LEVEL = 15;

    /**
     * @var string Name of the converter.
     *
     * The name must be all lowercase and without any spaces.
     */
    protected $name;

    /**
     * @var string Description of the converter.
     *
     * A short one-line description of what the converter does.
     */
    protected $description;

    /**
     * @var int Priority of the converter.
     *
     * The default priority is 0 and higher priorities are executed first.
     */
    protected $priority = 0;

    /**
     * Fixes a file.
     *
     * @param \SplFileInfo $file    A \SplFileInfo instance
     * @param string       $content The file content
     *
     * @return string The fixed file content
     */
    public function convert(\SplFileInfo $file, string $content): string
    {
        return $content;
    }

    /**
     * @return int
     */
    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }


    /**
     * Returns true if the file is supported by this converter.
     *
     * @param \SplFileInfo $file
     *
     * @return Boolean true if the file is supported by this converter, false otherwise
     */
    public function supports(\SplFileInfo $file): bool
    {
        return true;
    }

    /**
     * Get opening tag pattern: [{tagName other stuff}]
     *
     * @param string $tagName
     *
     * @return string
     */
    protected function getOpeningTagPattern(string $tagName): string
    {
        return sprintf("#\[\{\s*%s\b\s*((?:(?!\[\{|\}\]).(?<!\[\{)(?<!\}\]))+)?\}\]#is", preg_quote($tagName, '#'));
    }

    /**
     * Get closing tag pattern: [{\tagName}]
     *
     * @param string $tagName
     *
     * @return string
     */
    protected function getClosingTagPattern(string $tagName): string
    {
        return sprintf("#\[\{\s*/%s\s*\}\]#i", preg_quote($tagName, '#'));
    }

    /**
     * Method to extract key/value pairs out of a string with xml style attributes
     *
     * @param   string $string String containing xml style attributes
     *
     * @return  array   Key/Value pairs for the attributes
     */
    protected function attributes(string $string): array
    {
        //Initialize variables
        $attr = $pairs = [];
        $pattern = '/(?:([\w:-]+)\s*=\s*)?((?:".*?"|\'.*?\'|(?:[$\w->():]+))(?:[\|]?(?:\'\s+\'|"\s+"|[^\s}])*))/';

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
     *
     * @param string $string
     *
     * @return mixed
     */
    protected function variable(string $string): string
    {
        return trim(trim($string), '$"\'');
    }

    /**
     * Sanitize value, remove $,' or " from string
     *
     * @param string $string
     *
     * @return string
     */
    protected function value(string $string): string
    {
        $string = trim($string);

        if (empty($string)) {
            return $string;
        }

        // Handle "..." and '...'
        if (preg_match("/^\"[^\"]*\"$/", $string) ||  preg_match("/^'[^']*'$/", $string)) {
            return $string;
        }

        // Handle operators
        switch ($string) {
            case '&&':              return 'and';
            case '||':              return 'or';
            case '===':             return '==';
            case 'eq':              return '==';
            case 'ne':case 'neq':   return '!=';
            case 'gt':              return '>';
            case 'lt':              return '<';
            case 'gte':case 'ge':   return '>=';
            case 'lte':case 'le':   return '<=';
            case 'mod':             return '%';
        }

        // Handle non-quoted strings
        if (preg_match("/^[a-zA-Z]\w+$/", $string)) {
            if (!in_array($string, ["true", "false", "and", "or"])) {
                return "\"" . $string . "\"";
            }
        }

        // Handle "($var"
        if ($string[0] == "(") {
            return "(" . $this->value(ltrim($string, "("));
        }

        // Handle "!$var"
        if (preg_match("/(?<=[(\\s]|^)!(?!=)(?=[$]?\\w*)/", $string)) {
            return "not " . $this->value(ltrim($string, "!"));
        }

        $string = ltrim($string, '$');
        $string = str_replace("->", ".", $string);

        $string = $this->convertFunctionArguments($string);
        $string = $this->convertFilters($string);

        return $string;
    }

    /**
     * Handle function arguments (arg1, arg2, arg3)
     *
     * @param string $string
     *
     * @return string
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
                    $part = $this->value($part);
                }

                return sprintf("(%s)", implode(", ", $parts));
            },
            $string
        );
    }

    /**
     * Handle filters [{$var|filter:$var->from:'to'}]
     *
     * @param string $string
     *
     * @return string
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
                    $part = $this->value($part);
                }

                $convertedFilterName = FilterNameMap::getConvertedFilterName($value);

                return "|$convertedFilterName" . (!empty($parts) ? ("(" . implode(", ", $parts) . ")") : "");
            },
            $string
        );
    }

    /**
     * Explodes expression to parts and converts them separately
     *
     * @param string $expression
     *
     * @return string
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
            $part = $this->value($part);
        }

        return implode(" ", $parts);
    }

    /**
     * Replace named args in string
     *
     * @param  string $string
     * @param  array  $args
     *
     * @return string         Formated string
     */
    protected function vsprintf(string $string, array $args): string
    {
        $pattern = '/:([a-zA-Z0-9_-]+)/';

        return preg_replace_callback(
            $pattern,
            function ($matches) use ($args) {
                if (isset($args[$matches[1]])) {
                    return str_replace($matches[0], $args[$matches[1]], $matches[0]);
                }
            },
            $string
        );
    }

    /**
     * Converts associative php array to twig array
     * ['a' => 1, 'b' => 2]  ==>>  "{ a: 1, b: 2 }"
     *
     * @param array $array
     * @param array $skippedKeys
     *
     * @return string
     */
    protected function convertArrayToAssocTwigArray(array $array, array $skippedKeys): string
    {
        $pairs = [];
        foreach ($array as $key => $value) {
            if (in_array($key, $skippedKeys)) {
                continue;
            }
            $pairs[] = $this->variable($key) . ": " . $this->value($value);
        }

        // If array is empty, return nothing
        if (empty($pairs)) {
            return "";
        }

        return sprintf("{ %s }", implode(", ", $pairs));
    }

    /**
     * @param string $string
     *
     * @return string
     */
    protected function rawString(string $string): string
    {
        return trim($string, '\'"');
    }
}
