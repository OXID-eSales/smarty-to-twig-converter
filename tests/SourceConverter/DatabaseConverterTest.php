<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace toTwig\Tests\SourceConverter;

use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\Tests\DbalFunctionalTestCase;
use toTwig\ConversionResult;
use toTwig\Converter\VariableConverter;
use toTwig\SourceConverter\DatabaseConverter;

/**
 * Class DatabaseConverterTest
 */
class DatabaseConverterTest extends DbalFunctionalTestCase
{
    const DATABASE_PATH = __DIR__ . '/_datasets/init.db';
    const FIXTURES_PATH = __DIR__ . '/_datasets/fixtures.sql';

    private AbstractSchemaManager $schemaManager;

    /**
     * Performs operation returned by getSetUpOperation().
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->schemaManager = $this->connection->getSchemaManager();
        $this->prepareDatabase();
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if (file_exists(self::DATABASE_PATH)) {
            unlink(self::DATABASE_PATH);
        }
    }

    private function prepareDatabase(): void
    {
        $this->schemaManager->dropAndCreateDatabase(self::DATABASE_PATH);
        $this->connection->executeStatement(file_get_contents(self::FIXTURES_PATH));
    }

    /**
     * @covers \toTwig\SourceConverter\DatabaseConverter::convert
     */
    public function testConvert(): void
    {
        $databaseConverter = new DatabaseConverter($this->connection);

        $databaseConverter->setColumns(['table_a.column_a', 'table_a.column_b', 'table_b.column_c']);
        $changed = $databaseConverter->convert(false, false, [new VariableConverter()]);

        $expected = $this->convertProviderExpectedConversionResults();

        // Compare ConversionResult objects
        $this->assertEquals($expected, $changed);

        // Check table_a
        $result = $this->connection->fetchAllAssociative("SELECT * FROM table_a");
        $expected = [
            [
                'id' => '1',
                'column_a' => 'Plain text A',
                'column_b' => 'Plain text B',
                'column_c' => 'Plain text C'
            ],
            [
                'id' => '2',
                'column_a' => '{{ varA }}',
                'column_b' => '{{ varB }}',
                'column_c' => '[{$varC}]'
            ],
            [
                'id' => '3',
                'column_a' => '{{ varA }}',
                'column_b' => null,
                'column_c' => '[{$varC}]'
            ],
        ];
        $this->assertEquals($expected, $result);

        // Check table_b
        $result = $this->connection->fetchAllAssociative("SELECT * FROM table_b");
        $expected = [
            [
                'id' => '2',
                'column_a' => '[{$varA}]',
                'column_b' => '[{$varB}]',
                'column_c' => '{{ varC }}'
            ],
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * @return ConversionResult[]
     */
    private function convertProviderExpectedConversionResults(): array
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

        return [
            'table_a.column_a(id:2)' => $tableAcolumnAid2,
            'table_a.column_a(id:3)' => $tableAcolumnAid3,
            'table_a.column_b(id:2)' => $tableAcolumnBid2,
            'table_b.column_c(id:2)' => $tableBcolumnCid2
        ];
    }

    /**
     * @covers \toTwig\SourceConverter\DatabaseConverter::convert
     */
    public function testConvertDiff(): void
    {
        $databaseConverter = new DatabaseConverter($this->connection);

        $databaseConverter->setColumns(['table_a.column_a']);
        $changed = $databaseConverter->convert(true, true, [new VariableConverter()]);

        $expected = $this->convertDiffProviderExpectedConversionResults();

        $this->assertEquals($expected, $changed);
    }

    /**
     * @return ConversionResult[]
     */
    private function convertDiffProviderExpectedConversionResults(): array
    {
        $columnAid2 = new ConversionResult();
        $columnAid2
            ->setOriginalTemplate('[{$varA}]')
            ->setConvertedTemplate('{{ varA }}')
            ->setDiff(
                <<<'DIFF'
      <error>---</error> Original
      <info>+++</info> New
      @@ @@
      <error>-</error>[{$varA}]
      <info>+</info>{{ varA }}
      
DIFF
            )
            ->addAppliedConverter('variable');

        $columnAid3 = new ConversionResult();
        $columnAid3
            ->setOriginalTemplate('[{$varA}]')
            ->setConvertedTemplate('{{ varA }}')
            ->setDiff(
                <<<'DIFF'
      <error>---</error> Original
      <info>+++</info> New
      @@ @@
      <error>-</error>[{$varA}]
      <info>+</info>{{ varA }}
      
DIFF
            )
            ->addAppliedConverter('variable');

        return [
            'table_a.column_a(id:2)' => $columnAid2,
            'table_a.column_a(id:3)' => $columnAid3,
        ];
    }

    /**
     * @covers \toTwig\SourceConverter\DatabaseConverter::convert
     */
    public function testConvertDryRun(): void
    {
        $databaseConverter = new DatabaseConverter($this->connection);

        $databaseConverter->setColumns(['table_a.column_a']);
        $changed = $databaseConverter->convert(true, false, [new VariableConverter()]);

        $expected = $this->convertDryRunProviderExpectedConversionResults();

        // Compare ConversionResult objects
        $this->assertEquals($expected, $changed);

        // Make sure db stays unaffected
        $result = $this->connection->fetchAllAssociative("SELECT table_a.id, table_a.column_a FROM table_a");
        $expected = [
            [
                'id' => '1',
                'column_a' => 'Plain text A'
            ],
            [
                'id' => '2',
                'column_a' => '[{$varA}]'
            ],
            [
                'id' => '3',
                'column_a' => '[{$varA}]'
            ],
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * @return ConversionResult[]
     */
    private function convertDryRunProviderExpectedConversionResults(): array
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

        return [
            'table_a.column_a(id:2)' => $columnAid2,
            'table_a.column_a(id:3)' => $columnAid3,
        ];
    }
}
