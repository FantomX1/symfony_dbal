<?php


namespace App\Repository;


use Doctrine\DBAL\Connection;

class CountryRepository
{



    private $connection;


    /**
     * CountryRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {

        /** @var TYPE_NAME $connection */
        $this->connection = $connection;
    }


    public function insert($name, $population)
    {

        $queryBuilder = $this->connection->createQueryBuilder();
        // @TODO: check if dbal somehow restricts sql injection, handle name
        $queryBuilder->insert("countries")
            ->values(
                [
                    "id"         => "''",
                    "name"       => "'" . $name. "'",
                    "population" => "'" . (int)$population . "'",
                ]
            )->execute();

    }

}
