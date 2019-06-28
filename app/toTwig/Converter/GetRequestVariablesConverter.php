<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Converter;

/**
 * Class GetRequestVariablesConverter
 *
 * @package toTwig\Converter
 * @author  Jędrzej Skoczek
 */
class GetRequestVariablesConverter extends ConverterAbstract
{

    protected $name = 'get_request_variables';
    protected $description = 'Access php request variables using getters. Only $_COOKIE and $_GET are accessible';
    protected $priority = 50;

    /**
     * @param string $content
     *
     * @return string
     */
    public function convert(string $content): string
    {
        $convertedCookieGetter = $this->convertCookie($content);
        $convertedGetters = $this->convertGet($convertedCookieGetter);

        return $convertedGetters;
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function convertCookie(string $content): string
    {
        $pattern = '/\$smarty\.cookies\.([a-zA-Z0-9]+)/';
        $getterName = 'get_global_cookie';

        return $this->convertGetter($content, $pattern, $getterName);
    }

    /**
     * @param string $content
     *
     * @return string
     */
    private function convertGet(string $content): string
    {
        $pattern = '/\$smarty\.get\.([a-zA-Z0-9]+)/';
        $getterName = 'get_global_get';

        return $this->convertGetter($content, $pattern, $getterName);
    }

    /**
     * @param string $content
     * @param string $pattern
     * @param string $getterName
     *
     * @return string
     */
    private function convertGetter(string $content, string $pattern, string $getterName): string
    {
        return preg_replace_callback(
            $pattern,
            function ($matches) use ($getterName) {
                $search = str_replace($matches[0], $getterName . '("' . $matches[1] . '")', $matches[0]);

                return $search;
            },
            $content
        );
    }
}
