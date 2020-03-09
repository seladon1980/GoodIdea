<?php

namespace App\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\PDOPgSql\Driver as PDOPgSqlDriver;
use Doctrine\ORM\NativeQuery;

class PgSqlNativeQueryCursor
{
    const DIRECTION_NEXT         = 'NEXT';
    const DIRECTION_PRIOR        = 'PRIOR';
    const DIRECTION_FIRST        = 'FIRST';
    const DIRECTION_LAST         = 'LAST';
    const DIRECTION_ABSOLUTE     = 'ABSOLUTE'; // with count
    const DIRECTION_RELATIVE     = 'RELATIVE'; // with count
    const DIRECTION_FORWARD      = 'FORWARD'; // with count
    const DIRECTION_FORWARD_ALL  = 'FORWARD ALL';
    const DIRECTION_BACKWARD     = 'BACKWARD'; // with count
    const DIRECTION_BACKWARD_ALL = 'BACKWARD ALL';

    /**
     * @var NativeQuery
     */
    private $query;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var bool
     */
    private $isOpen = false;

    /**
     * @var string
     */
    private $cursorName;


    public function __construct(NativeQuery $query, $sessionId)
    {
        $this->query = $query;
        $this->connection = $query->getEntityManager()->getConnection();
        $this->cursorName = $sessionId;

        assert($this->connection->getDriver() instanceof PDOPgSqlDriver);
    }

    public function __destruct()
    {
        if ($this->isOpen) {
            $this->close();
        }
    }

    public function getFetchQuery(int $count = 1, string $direction = self::DIRECTION_FORWARD): NativeQuery
    {
        if (!$this->isOpen) {
            $this->openCursor();
        }

        $query = clone $this->query;
        $query->setParameters([]);
        if (
            $direction == self::DIRECTION_ABSOLUTE
            || $direction == self::DIRECTION_RELATIVE
            || $direction == self::DIRECTION_FORWARD
            || $direction == self::DIRECTION_BACKWARD
        ) {
            $query->setSQL(sprintf(
                'FETCH %s %d FROM %s',
                $direction,
                $count,
                $this->connection->quoteIdentifier($this->cursorName)
            ));
        } else {
            $query->setSQL(sprintf(
                'FETCH %s FROM %s',
                $direction,
                $this->connection->quoteIdentifier($this->cursorName)
            ));
        }

        return $query;
    }

    public function close()
    {
        if (!$this->isOpen) {
            return;
        }

        $this->connection->exec('CLOSE ' . $this->connection->quoteIdentifier($this->cursorName));
        $this->isOpen = false;
    }

    private function openCursor()
    {
        if ($this->query->getEntityManager()->getConnection()->getTransactionNestingLevel() === 0) {
            throw new \BadMethodCallException('Cursor must be used inside a transaction');
        }

        $query = clone $this->query;
        $query->setSQL(sprintf(
            'DECLARE %s CURSOR FOR (%s)',
            $this->connection->quoteIdentifier($this->cursorName),
            $this->query->getSQL()
        ));
        $query->execute($this->query->getParameters());

        $this->isOpen = true;
    }
}