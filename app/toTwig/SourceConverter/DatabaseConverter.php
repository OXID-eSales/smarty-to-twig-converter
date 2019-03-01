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
    private $columns;

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

        $sm = $this->connection->getSchemaManager();
        $primaryKey = $sm->listTableDetails($table)->getPrimaryKey()->getColumns()[0];

        $qb = $this->connection->createQueryBuilder();
        $qb
            ->select($primaryKey, $column)
            ->from($table)
            ->where($qb->expr()->notLike($column, '""'));

        $rows = $qb->execute()->fetchAll();

        $changed = [];
        foreach ($rows as $key => $row) {
            $conversionResult = $this->convertTemplate($row[$column], $diff, $converters);

            if ($conversionResult->hasAppliedConverters()) {
                if (!$dryRun) {
                    $this->updateRow($table, $column, $primaryKey, $row[$primaryKey], $conversionResult->getConvertedTemplate());
                }

                $id = sprintf("%s.%s(%s:%s)", $table, $column, $primaryKey, $row[$primaryKey]);

                $changed[$id] = $conversionResult;
            }
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
        $qb = $this->connection->createQueryBuilder();

        $qb
            ->update($table)
            ->set($column, ':newValue')
            ->where($qb->expr()->eq($primaryKey, ':primaryKeyValue'))
            ->setParameter('newValue', $newValue)
            ->setParameter('primaryKeyValue', $primaryKeyValue);

        $qb->execute();
    }

    /**
     * @param string[] $columns
     */
    public function setColumns(array $columns): void
    {
        $this->columns = $columns;
    }
}
