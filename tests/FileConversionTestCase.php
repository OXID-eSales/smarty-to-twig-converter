<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace sankar\ST\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use toTwig\Console\Command\ConvertCommand;

/**
 * Class FileConversionTestCase
 *
 * @package sankar\ST\Tests
 */
abstract class FileConversionTestCase extends TestCase
{

    /** @var array */
    protected $templateNames;

    /** @var string */
    protected $templateDirectory;

    public function setUp()
    {
        parent::setUp();
        $this->unlinkConvertedFiles();
    }

    /**
     * Convert list of smarty templates, check if twig templates were created
     * and compare converted template to expected one.
     */
    public function testConvert()
    {
        foreach ($this->templateNames as $templateName) {
            $this->convert($templateName);

            $this->assertTrue(file_exists($this->getTwigTemplatePath($templateName)));
            $actualTemplate = file_get_contents($this->getTwigTemplatePath($templateName));
            $expectedTemplate = file_get_contents($this->getExpectedTwigTemplatePath($templateName));

            $this->assertEquals($expectedTemplate, $actualTemplate);
        }
    }

    /**
     * @param string $templateName
     */
    protected function convert(string $templateName)
    {
        $command = new ConvertCommand();
        $commandTester = new CommandTester($command);
        $commandTester->execute($this->getCommandParameters($templateName));
    }

    /**
     * @param string $templateName
     *
     * @return array(string)
     */
    protected function getCommandParameters(string $templateName): array
    {
        return ['--path' => $this->getSmartyTemplatePath($templateName)];
    }

    /**
     * @param string $templateName
     *
     * @return string
     */
    protected function getTwigTemplatePath(string $templateName): string
    {
        return $this->templateDirectory . $templateName . '.html.twig';
    }

    /**
     * @param string $templateName
     *
     * @return string
     */
    protected function getSmartyTemplatePath(string $templateName): string
    {
        return $this->templateDirectory . $templateName . '.tpl';
    }

    /**
     * @param string $templateName
     *
     * @return string
     */
    protected function getExpectedTwigTemplatePath(string $templateName): string
    {
        return $this->templateDirectory . $templateName . '-expected.html.twig';
    }

    /**
     * Remove template created during conversion
     */
    protected function unlinkConvertedFiles()
    {
        foreach ($this->templateNames as $templateName) {
            $twigTemplatePath = $this->getTwigTemplatePath($templateName);
            if (file_exists($twigTemplatePath)) {
                unlink($twigTemplatePath);
            }
        }
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->unlinkConvertedFiles();
    }
}
