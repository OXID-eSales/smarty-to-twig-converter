<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\Console;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;
use toTwig\Console\Command\ConvertCommand;

/**
 * Class ConvertCommandOutputTest
 */
class ConvertCommandOutputTest extends TestCase
{
    /**
     * @param array<string, mixed> $options
     */
    private function runCommand(array $options, bool $verbose): string
    {
        $commandTester = new CommandTester(new ConvertCommand());
        $commandTester->execute(
            array_merge(
                [
                    '--path'       => __DIR__ . '/../SourceConverter/_templates',
                    '--ext'        => '.html.twig',
                    '--dry-run'    => true,
                    '--converters' => 'variable',
                ],
                $options
            ),
            ['verbosity' => $verbose ? OutputInterface::VERBOSITY_VERBOSE : OutputInterface::VERBOSITY_NORMAL]
        );

        return $commandTester->getDisplay();
    }

    /**
     * @covers \toTwig\Console\Command\ConvertCommand::outputTxt
     */
    public function testTxtListsAppliedConvertersWhenVerbose(): void
    {
        $this->assertStringContainsString('1) example.tpl (variable)', $this->runCommand([], true));
    }

    /**
     * @covers \toTwig\Console\Command\ConvertCommand::outputTxt
     */
    public function testTxtOmitsAppliedConvertersWhenNotVerbose(): void
    {
        $display = $this->runCommand([], false);

        $this->assertStringContainsString('1) example.tpl', $display);
        $this->assertStringNotContainsString('(variable)', $display);
    }

    /**
     * @covers \toTwig\Console\Command\ConvertCommand::outputTxt
     */
    public function testTxtShowsDiffWithoutVerbose(): void
    {
        $this->assertStringContainsString('---------- begin diff ----------', $this->runCommand(['--diff' => true], false));
    }

    /**
     * @covers \toTwig\Console\Command\ConvertCommand::outputXml
     */
    public function testXmlVerboseWithDiff(): void
    {
        $display = $this->runCommand(['--format' => 'xml', '--diff' => true], true);

        $this->assertStringContainsString('<applied_converter name="variable"/>', $display);
        $this->assertStringContainsString('<diff><![CDATA[', $display);
        $this->assertStringContainsString('{{ var }}', $display);
    }
}
