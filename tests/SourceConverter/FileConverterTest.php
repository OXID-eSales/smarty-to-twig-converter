<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\SourceConverter;

use SplFileInfo;
use Symfony\Component\Finder\Finder;
use toTwig\ConversionResult;
use toTwig\Converter\VariableConverter;
use toTwig\Finder\DefaultFinder;
use toTwig\SourceConverter\FileConverter;
use PHPUnit\Framework\TestCase;

/**
 * Class FileConverterTest
 */
class FileConverterTest extends TestCase
{

    /**
     * @covers \toTwig\SourceConverter\FileConverter::__construct
     */
    public function testConstruct()
    {
        $fileConverter = new FileConverter();

        $this->assertInstanceOf(DefaultFinder::class, $fileConverter->getFinder());
    }

    /**
     * @covers \toTwig\SourceConverter\FileConverter::setDir
     */
    public function testSetDir()
    {
        $directoryName = uniqid() . "/" . uniqid();

        $fileConverter = new FileConverter();
        $fileConverter->setDir($directoryName);

        $this->expectExceptionMessage("The \"$directoryName\" directory does not exist.");

        $fileConverter->getFinder()->getIterator();
    }

    /**
     * @covers \toTwig\SourceConverter\FileConverter::setOutputExtension
     */
    public function testSetOutputExtension()
    {
        $fileConverter = new FileConverter();

        $fileConverter->setOutputExtension(".html.twig");

        $file = new SplFileInfo(__DIR__ . '/_templates/example.tpl');
        $filename = basename($fileConverter->getConvertedFilename($file));

        $this->assertEquals('example.html.twig', $filename);

        $fileConverter->setOutputExtension("html.twig");

        $this->assertEquals('example.html.twig', $filename);
    }

    /**
     * @covers \toTwig\SourceConverter\FileConverter::getConvertedFilename
     */
    public function testGetConvertedFilename()
    {
        $fileConverter = new FileConverter();

        $fileConverter->setOutputExtension(".html.twig");

        $file = new SplFileInfo(__DIR__ . '/_templates/example.tpl');
        $filename = basename($fileConverter->getConvertedFilename($file));

        $this->assertEquals('example.html.twig', $filename);
    }

    /**
     * @covers \toTwig\SourceConverter\FileConverter::setFinder
     */
    public function testSetFinder()
    {
        $finder = new Finder();
        $fileConverter = new FileConverter();
        $fileConverter->setFinder($finder);

        $this->assertEquals($finder, $fileConverter->getFinder());
    }

    /**
     * @covers \toTwig\SourceConverter\FileConverter::convert
     */
    public function testConvertDryRun()
    {
        $fileConverter = new FileConverter();

        $fileConverter->setDir(__DIR__ . '/_templates');
        $fileConverter->setOutputExtension('.html.twig');
        $changed = $fileConverter->convert(true, false, [new VariableConverter()]);

        $expectedResult = new ConversionResult();
        $expectedResult
            ->setOriginalTemplate('[{$var}]')
            ->setConvertedTemplate('{{ var }}')
            ->addAppliedConverter('variable')
        ;

        $this->assertEquals(['example.tpl' => $expectedResult], $changed);
    }

    /**
     * @covers \toTwig\SourceConverter\FileConverter::convert
     */
    public function testConvert()
    {
        $fileConverter = new FileConverter();

        $fileConverter->setDir(__DIR__ . '/_templates');
        $fileConverter->setOutputExtension('.html.twig');
        $changed = $fileConverter->convert(false, false, [new VariableConverter()]);

        $expectedResult = new ConversionResult();
        $expectedResult
            ->setOriginalTemplate('[{$var}]')
            ->setConvertedTemplate('{{ var }}')
            ->addAppliedConverter('variable')
        ;

        $this->assertEquals(['example.tpl' => $expectedResult], $changed);
        $this->assertFileExists(__DIR__ . '/_templates/example.html.twig');

        unlink(__DIR__ . '/_templates/example.html.twig');
    }

    /**
     * @covers \toTwig\SourceConverter\FileConverter::convert
     */
    public function testConvertDiff()
    {
        $fileConverter = new FileConverter();

        $fileConverter->setDir(__DIR__ . '/_templates');
        $fileConverter->setOutputExtension('.html.twig');
        $changed = $fileConverter->convert(true, true, [new VariableConverter()]);

        $expectedDiff = "      <error>---</error> Original
      <info>+++</info> New
      @@ @@
      <error>-</error>[{\$var}]
      <info>+</info>{{ var }}
      ";

        $expectedResult = new ConversionResult();
        $expectedResult
            ->setOriginalTemplate('[{$var}]')
            ->setConvertedTemplate('{{ var }}')
            ->setDiff($expectedDiff)
            ->addAppliedConverter('variable')
        ;

        $this->assertEquals(['example.tpl' => $expectedResult], $changed);
    }

    /**
     * @covers \toTwig\SourceConverter\FileConverter::setPath
     */
    public function testSetPath()
    {
        $fileConverter = new FileConverter();

        $fileConverter->setPath(__DIR__ . '/_templates/example.tpl');

        /** @var SplFileInfo $file */
        $file = $fileConverter->getFinder()->getIterator()->current();
        $this->assertEquals('example.tpl', $file->getFilename());
    }

    /**
     * @covers \toTwig\SourceConverter\FileConverter::getFinder
     */
    public function testGetFinder()
    {
        $fileConverter = new FileConverter();

        $fileConverter->getFinder();

        $this->assertInstanceOf(DefaultFinder::class, $fileConverter->getFinder());

        $finder = new Finder();
        $fileConverter->setFinder($finder);

        $this->assertEquals($finder, $fileConverter->getFinder());
    }
}
