<?php
/**
 * Created by PhpStorm.
 * User: jskoczek
 * Date: 11/09/18
 * Time: 11:29
 */

namespace sankar\ST\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;
use toTwig\Console\Command\ConvertCommand;

class AcceptanceTest extends TestCase
{
    private $tplPath;
    private $twigPath;

    public function setUp()
    {
        parent::setUp();
        $this->tplPath = dirname(__FILE__) . '/order.tpl';
        $this->twigPath = dirname(__FILE__) . '/order.twig';
        if(file_exists($this->twigPath)) {
            unlink($this->twigPath);
        }
    }

    public function test()
    {
        $command = new ConvertCommand();
        $commandTester = new CommandTester($command);
        $commandTester->execute(['path' => $this->tplPath, '--ext' => 'twig']);

        $this->assertTrue(file_exists($this->twigPath));
    }

    public function tearDown()
    {
        parent::tearDown();
        unlink($this->twigPath);
    }
}
