<?php

namespace sankar\ST\Tests\Config;

use toTwig\Config\Config;
use PHPUnit\Framework\TestCase;
use toTwig\Converter\AssignConverter;
use toTwig\Converter\VariableConverter;
use toTwig\SourceConverter\DatabaseConverter;
use toTwig\SourceConverter\SourceConverter;

/**
 * Class ConfigTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class ConfigTest extends TestCase
{

    /**
     * @covers \toTwig\Config\Config::diff
     * @covers \toTwig\Config\Config::isDiff
     */
    public function testDiff()
    {
        $config = new Config();
        $this->assertFalse($config->isDiff());

        $config->diff();
        $this->assertTrue($config->isDiff());

        $config->diff(false);

        $this->assertFalse($config->isDiff());
    }

    /**
     * @covers \toTwig\Config\Config::getDescription
     */
    public function testGetDescription()
    {
        $config = new Config('default', 'Custom description');

        $this->assertEquals('Custom description', $config->getDescription());
    }

    /**
     * @covers \toTwig\Config\Config::dryRun
     * @covers \toTwig\Config\Config::isDryRun
     */
    public function testDryRun()
    {
        $config = new Config();
        $this->assertFalse($config->isDryRun());

        $config->dryRun();
        $this->assertTrue($config->isDryRun());

        $config->dryRun(false);
        $this->assertFalse($config->isDryRun());
    }

    /**
     * @covers \toTwig\Config\Config::create
     */
    public function testCreate()
    {
        $config = Config::create();
        $this->assertInstanceOf(Config::class, $config);
    }

    /**
     * @covers \toTwig\Config\Config::getCustomConverters
     * @covers \toTwig\Config\Config::addCustomConverter
     */
    public function testCustomConverters()
    {
        $config = new Config();

        $variableConverter = new VariableConverter();
        $config->addCustomConverter($variableConverter);
        $this->assertEquals([$variableConverter], $config->getCustomConverters());

        $assignConverter = new AssignConverter();
        $config->addCustomConverter($assignConverter);
        $this->assertEquals([$variableConverter, $assignConverter], $config->getCustomConverters());
    }

    /**
     * @covers \toTwig\Config\Config::getSourceConverter
     * @covers \toTwig\Config\Config::setSourceConverter
     */
    public function testSourceConverter()
    {
        $config = new Config();
        $databaseConverter = new DatabaseConverter('sqlite://:memory:');
        $config->setSourceConverter($databaseConverter);

        $this->assertEquals($databaseConverter, $config->getSourceConverter());
    }

    /**
     * @covers \toTwig\Config\Config::__construct
     */
    public function test__construct()
    {
        $config = new Config();

        $this->assertEquals('default', $config->getName());
        $this->assertNotEmpty($config->getDescription());
        $this->assertEmpty($config->getConverters());
        $this->assertEmpty($config->getCustomConverters());
        $this->assertInstanceOf(SourceConverter::class, $config->getSourceConverter());

        $config = new Config('myConfig', 'Custom config');
        $this->assertEquals('myConfig', $config->getName());
    }

    /**
     * @covers \toTwig\Config\Config::converters
     * @covers \toTwig\Config\Config::getConverters
     */
    public function testConverters()
    {
        $config = new Config();
        $config->converters(['variable', 'assign']);

        $this->assertEquals(['variable', 'assign'], $config->getConverters());
    }

    /**
     * @covers \toTwig\Config\Config::getName
     */
    public function testGetName()
    {
        $config = new Config('custom' );

        $this->assertEquals('custom', $config->getName());
    }
}
