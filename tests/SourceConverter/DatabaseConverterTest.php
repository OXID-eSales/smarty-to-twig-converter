<?php

namespace sankar\ST\Tests\SourceConverter;

use PDO;
use PHPUnit\DbUnit\Database\Connection;
use PHPUnit\DbUnit\DataSet\IDataSet;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\Framework\TestCase;
use toTwig\ConversionResult;
use toTwig\Converter\VariableConverter;
use toTwig\SourceConverter\DatabaseConverter;

/**
 * Class DatabaseConverterTest
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class DatabaseConverterTest extends TestCase
{

    use TestCaseTrait;

    /** @var PDO */
    static private $pdo = null;

    /** @var Connection */
    private $connection = null;

    /** @var string */
    private $databasePath = __DIR__ . '/_datasets/init.db';

    private $databasePathBackup = __DIR__ . '/_datasets/init.db.bak';

    /**
     * Performs operation returned by getSetUpOperation().
     */
    protected function setUp()
    {
        parent::setUp();

        if (!file_exists($this->databasePathBackup)) {
            copy($this->databasePath, $this->databasePathBackup);
        }

        $this->databaseTester = null;

        $this->getDatabaseTester()->setSetUpOperation($this->getSetUpOperation());
        $this->getDatabaseTester()->setDataSet($this->getDataSet());
        $this->getDatabaseTester()->onSetUp();
    }

    /**
     * @covers \toTwig\SourceConverter\DatabaseConverter::convert
     */
    public function testConvert()
    {
        $databaseConverter = new DatabaseConverter("sqlite:///$this->databasePath");

        $databaseConverter->setColumns(['table_a.column_a', 'table_a.column_b', 'table_b.column_c']);
        $changed = $databaseConverter->convert(false, false, [new VariableConverter()]);

        $expected = $this->convertProviderExpectedConversionResults();

        // Compare ConversionResult objects
        $this->assertEquals($expected, $changed);

        $dataset = $this->connection->createDataSet(['table_a', 'table_b']);
        $expectedDataset = $this->createXMLDataSet(__DIR__.'/_datasets/expectedConvert.xml');

        // Compare database sets
        $this->assertDataSetsEqual($expectedDataset, $dataset);
    }

    /**
     * @return ConversionResult[]
     */
    private function convertProviderExpectedConversionResults()
    {
        $tableAcolumnAid2 = new ConversionResult();
        $tableAcolumnAid2
            ->setOriginalTemplate('[{$varA}]')
            ->setConvertedTemplate('{{ varA }}')
            ->addAppliedConverter('variable');

        $tableAcolumnAid3 = new ConversionResult();
        $tableAcolumnAid3
            ->setOriginalTemplate('[{$varA}]')
            ->setConvertedTemplate('{{ varA }}')
            ->addAppliedConverter('variable');

        $tableAcolumnBid2 = new ConversionResult();
        $tableAcolumnBid2
            ->setOriginalTemplate('[{$varB}]')
            ->setConvertedTemplate('{{ varB }}')
            ->addAppliedConverter('variable');

        $tableBcolumnCid2 = new ConversionResult();
        $tableBcolumnCid2
            ->setOriginalTemplate('[{$varC}]')
            ->setConvertedTemplate('{{ varC }}')
            ->addAppliedConverter('variable');

        $expected = [
            'table_a.column_a(id:2)' => $tableAcolumnAid2,
            'table_a.column_a(id:3)' => $tableAcolumnAid3,
            'table_a.column_b(id:2)' => $tableAcolumnBid2,
            'table_b.column_c(id:2)' => $tableBcolumnCid2
        ];

        return $expected;
    }

    /**
     * @covers \toTwig\SourceConverter\DatabaseConverter::convert
     */
    public function testConvertDiff()
    {
        $databaseConverter = new DatabaseConverter("sqlite:///$this->databasePath");

        $databaseConverter->setColumns(['table_a.column_a']);
        $changed = $databaseConverter->convert(true, true, [new VariableConverter()]);

        $expected = $this->convertDiffProviderExpectedConversionResults();

        $this->assertEquals($expected, $changed);
    }

    /**
     * @return ConversionResult[]
     */
    private function convertDiffProviderExpectedConversionResults()
    {
        $columnAid2 = new ConversionResult();
        $columnAid2
            ->setOriginalTemplate('[{$varA}]')
            ->setConvertedTemplate('{{ varA }}')
            ->setDiff('      <error>---</error> Original
      <info>+++</info> New
      @@ @@
      <error>-</error>[{$varA}]
      <info>+</info>{{ varA }}
      ')
            ->addAppliedConverter('variable');

        $columnAid3 = new ConversionResult();
        $columnAid3
            ->setOriginalTemplate('[{$varA}]')
            ->setConvertedTemplate('{{ varA }}')
            ->setDiff('      <error>---</error> Original
      <info>+++</info> New
      @@ @@
      <error>-</error>[{$varA}]
      <info>+</info>{{ varA }}
      ')
            ->addAppliedConverter('variable');


        $expected = [
            'table_a.column_a(id:2)' => $columnAid2,
            'table_a.column_a(id:3)' => $columnAid3,
        ];

        return $expected;
    }

    /**
     * @covers \toTwig\SourceConverter\DatabaseConverter::convert
     */
    public function testConvertDryRun()
    {
        $databaseConverter = new DatabaseConverter("sqlite:///$this->databasePath");

        $databaseConverter->setColumns(['table_a.column_a']);
        $changed = $databaseConverter->convert(true, false, [new VariableConverter()]);

        $expected = $this->convertDryRunProviderExpectedConversionResults();

        // Compare ConversionResult objects
        $this->assertEquals($expected, $changed);

        $dataset = $this->connection->createDataSet(['table_a', 'table_b']);
        $expectedDataset = $this->createXMLDataSet(__DIR__.'/_datasets/initial.xml');

        // Compare database sets
        $this->assertDataSetsEqual($expectedDataset, $dataset);
    }

    /**
     * @return ConversionResult[]
     */
    private function convertDryRunProviderExpectedConversionResults()
    {
        $columnAid2 = new ConversionResult();
        $columnAid2
            ->setOriginalTemplate('[{$varA}]')
            ->setConvertedTemplate('{{ varA }}')
            ->addAppliedConverter('variable');

        $columnAid3 = new ConversionResult();
        $columnAid3
            ->setOriginalTemplate('[{$varA}]')
            ->setConvertedTemplate('{{ varA }}')
            ->addAppliedConverter('variable');


        $expected = [
            'table_a.column_a(id:2)' => $columnAid2,
            'table_a.column_a(id:3)' => $columnAid3,
        ];

        return $expected;
    }

    /**
     * Returns the test database connection.
     *
     * @return Connection
     */
    protected function getConnection()
    {
        if ($this->connection === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO("sqlite:$this->databasePath");
            }

            $this->connection = $this->createDefaultDBConnection(self::$pdo);
        }

        return $this->connection;
    }

    /**
     * Returns the test dataset.
     *
     * @return IDataSet
     */
    protected function getDataSet()
    {
        $dataSet = $this->createXMLDataSet(__DIR__.'/_datasets/initial.xml');
        return $dataSet;
    }
    /**
     * Performs operation returned by getTearDownOperation().
     */
    protected function tearDown()
    {
        parent::tearDown();

        if(file_exists($this->databasePathBackup)) {
            copy($this->databasePathBackup, $this->databasePath);
            unlink($this->databasePathBackup);
        }

        $this->getDatabaseTester()->setTearDownOperation($this->getTearDownOperation());
        $this->getDatabaseTester()->setDataSet($this->getDataSet());
        $this->getDatabaseTester()->onTearDown();

        /*
         * Destroy the tester after the test is run to keep DB connections
         * from piling up.
         */
        $this->databaseTester = null;
    }
}
