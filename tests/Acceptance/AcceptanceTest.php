<?php

namespace sankar\ST\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use toTwig\Console\Command\ConvertCommand;

class AcceptanceTest extends TestCase
{

    private $templateNames;

    public function setUp()
    {
        parent::setUp();
        $this->templateNames = ['basket', 'order', 'payment', 'thankyou', 'user'];
        $this->unlinkConvertedFiles();
    }

    public function testConvert()
    {
        foreach ($this->templateNames as $templateName) {
            $this->convert($templateName);
        }
    }

    private function convert($templateName)
    {
        $command = new ConvertCommand();
        $commandTester = new CommandTester($command);
        $commandTester->execute(['--path' => $this->getSmartyTemplatePath($templateName), '--ext' => 'twig']);

        $this->assertTrue(file_exists($this->getTwigTemplatePath($templateName)));
        $actualTemplate = file_get_contents($this->getTwigTemplatePath($templateName));
        $expectedTemplate = file_get_contents($this->getExpectedTwigTemplatePath($templateName));

        $this->assertEquals($expectedTemplate, $actualTemplate);
    }

    private function getTwigTemplatePath($templateName)
    {
        return dirname(__FILE__) . '/' . $templateName . '.twig';
    }

    private function getSmartyTemplatePath($templateName)
    {
        return dirname(__FILE__) . '/' . $templateName . '.tpl';
    }

    private function getExpectedTwigTemplatePath($templateName)
    {
        return dirname(__FILE__) . '/' . $templateName . '-expected.twig';
    }

    private function unlinkConvertedFiles()
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
