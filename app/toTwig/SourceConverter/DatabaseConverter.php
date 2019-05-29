<?php

namespace toTwig\SourceConverter;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use toTwig\Converter\ConverterAbstract;

/**
 * Class DatabaseConverter
 *
 * @author Tomasz Kowalewski (t.kowalewski@createit.pl)
 */
class DatabaseConverter extends SourceConverter
{

    /** @var Connection */
    private $connection;

    /** @var string[] */
    private $columns = [
        'oxactions.OXLONGDESC',
        'oxactions.OXLONGDESC_1',
        'oxcontents.OXCONTENT',
        'oxcontents.OXCONTENT_1'
    ];

    /**
     * DatabaseConverter constructor.
     *
     * @param string $databaseUrl
     */
    public function __construct(string $databaseUrl)
    {
        parent::__construct();

        $this->connection = DriverManager::getConnection(['url' => $databaseUrl]);
    }

    /**
     * @param bool                $dryRun
     * @param bool                $diff
     * @param ConverterAbstract[] $converters
     *
     * @return array
     */
    public function convert(bool $dryRun, bool $diff, array $converters): array
    {
        $changed = [];

        foreach ($this->columns as $column) {
            $changed += $this->convertColumn($column, $dryRun, $diff, $converters);
        }

        return $changed;
    }

    /**
     * @param string              $column
     * @param bool                $dryRun
     * @param bool                $diff
     * @param ConverterAbstract[] $converters
     *
     * @return array
     */
    private function convertColumn(string $column, bool $dryRun, bool $diff, array $converters): array
    {
        list($table, $column) = explode('.', $column, 2);

        $schemaManager = $this->connection->getSchemaManager();
        $primaryKey = $schemaManager->listTableDetails($table)->getPrimaryKey()->getColumns()[0];

        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select($primaryKey, $column)
            ->from($table)
            ->where($queryBuilder->expr()->notLike($column, '""'));

        $rows = $queryBuilder->execute()->fetchAll();

        $changed = [];
        foreach ($rows as $key => $row) {
            $changed += $this->convertRow($table, $column, $primaryKey, $row, $dryRun, $diff, $converters);
        }

        return $changed;
    }

    /**
     * @param string              $table
     * @param string              $column
     * @param string              $primaryKey
     * @param array               $row
     * @param bool                $dryRun
     * @param bool                $diff
     * @param ConverterAbstract[] $converters
     *
     * @return array
     */
    private function convertRow(string $table, string $column, string $primaryKey, array $row, bool $dryRun, bool $diff, array $converters): array
    {
        $changed = [];

        $conversionResult = $this->convertTemplate($row[$column], $diff, $converters);

        if ($conversionResult->hasAppliedConverters()) {
            if (!$dryRun) {
                $this->updateRow($table, $column, $primaryKey, $row[$primaryKey], $conversionResult->getConvertedTemplate());
            }

            $id = sprintf("%s.%s(%s:%s)", $table, $column, $primaryKey, $row[$primaryKey]);

            $changed[$id] = $conversionResult;
        }

        return $changed;
    }

    /**
     * @param string $table
     * @param string $column
     * @param string $primaryKey
     * @param string $primaryKeyValue
     * @param string $newValue
     */
    private function updateRow(string $table, string $column, string $primaryKey, string $primaryKeyValue, string $newValue): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->update($table)
            ->set($column, ':newValue')
            ->where($queryBuilder->expr()->eq($primaryKey, ':primaryKeyValue'))
            ->setParameter('newValue', $newValue)
            ->setParameter('primaryKeyValue', $primaryKeyValue);

        $queryBuilder->execute();
    }

    /**
     * @param string[] $columns
     */
    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }

    /**
     * @param string[] $columns
     *
     * @return mixed
     */
    public function filterColumns(array $columns): mixed
    {
        $columns = array_map('trim', $columns);

        if (empty($columns) || $columns[0][0] == '-') {
            $columns = array_map(
                function ($column) {
                    return ltrim($column, '-');
                },
                $columns
            );

            $this->columns = array_filter(
                $this->columns,
                function ($column) use ($columns) {
                    return !in_array($column, $columns);
                }
            );
        } else {
            $this->columns = array_filter(
                $this->columns,
                function ($column) use ($columns) {
                    return in_array($column, $columns);
                }
            );
        }
    }
}
