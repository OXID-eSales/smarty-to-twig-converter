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
    private $conn = null;

    /** @var string */
    private $databasePath = __DIR__ . '/_datasets/init.db';

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

        $dataset = $this->conn->createDataSet(['table_a', 'table_b']);
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

        $dataset = $this->conn->createDataSet(['table_a', 'table_b']);
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
        if ($this->conn === null) {
            if (self::$pdo == null) {
                self::$pdo = new PDO("sqlite:$this->databasePath");
            }

            $this->conn = $this->createDefaultDBConnection(self::$pdo);
        }

        return $this->conn;
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
}
