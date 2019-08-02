<?php

/**
 * This file is part of the PHP ST utility.
 *
 * (c) Sankar suda <sankar.suda@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace toTwig\SourceConverter;

use SebastianBergmann\Diff\Differ;
use SplFileInfo;
use toTwig\ConversionResult;
use toTwig\Converter\ConverterAbstract;

/**
 * Class SourceConverter
 */
abstract class SourceConverter
{

    /** @var Differ */
    private $diff;

    /**
     * SourceConverter constructor.
     */
    public function __construct()
    {
        $this->diff = new Differ();
    }

    /**
     * @param bool                $dryRun
     * @param bool                $diff
     * @param ConverterAbstract[] $converters
     *
     * @return ConversionResult[]
     */
    abstract public function convert(bool $dryRun, bool $diff, array $converters): array;

    /**
     * @param string              $templateToConvert
     * @param bool                $diff
     * @param ConverterAbstract[] $converters
     *
     * @return ConversionResult
     */
    protected function convertTemplate(string $templateToConvert, bool $diff, array $converters): ConversionResult
    {
        $convertedTemplate = $templateToConvert;
        $result = new ConversionResult();
        $result->setOriginalTemplate($templateToConvert);

        foreach ($converters as $converter) {
            $new1 = $converter->convert($convertedTemplate);
            if ($new1 != $convertedTemplate) {
                $result->addAppliedConverter($converter->getName());
            }
            $convertedTemplate = $new1;
        }

        $result->setConvertedTemplate($convertedTemplate);

        if ($diff) {
            $result->setDiff($this->stringDiff($templateToConvert, $convertedTemplate));
        }

        return $result;
    }

    /**
     * @param string $old
     * @param string $new
     *
     * @return string
     */
    protected function stringDiff(string $old, string $new): string
    {
        $diff = $this->diff->diff($old, $new);

        $diff = implode(
            PHP_EOL,
            array_map(
                function ($string) {
                    $string = preg_replace('/^(\+){3}/', '<info>+++</info>', $string);
                    $string = preg_replace('/^(\+){1}/', '<info>+</info>', $string);

                    $string = preg_replace('/^(\-){3}/', '<error>---</error>', $string);
                    $string = preg_replace('/^(\-){1}/', '<error>-</error>', $string);

                    $string = str_repeat(' ', 6) . $string;

                    return $string;
                },
                explode(PHP_EOL, $diff)
            )
        );

        return $diff;
    }
}
