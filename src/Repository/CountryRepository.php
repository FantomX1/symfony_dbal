<?php


namespace App\Repository;


use Doctrine\DBAL\Connection;

/**
 * Class CountryRepository
 * @package App\Repository
 */
class CountryRepository
{


    /**
     * @var Connection $connection
     */
    private $connection;


    /**
     * CountryRepository constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {

        /** @var Connection $connection */
        $this->connection = $connection;

    }


    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder
            ->select('*')
            ->from('countries')
            ->where("id=".$id)
            ->execute()
            ->fetch();
    }

    /**
     * @param $id
     * @param $name
     * @param $population
     * @return \Doctrine\DBAL\Driver\ResultStatement|int
     */
    public function edit($id, $name, $population)
    {

        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder
            ->update("countries")
            ->set('name',"'".$name."'")
            ->set('population',"'".$population."'")
            ->where("id=".$id)
            ->execute();
    }


    /**
     * @param $name
     * @param $population
     */
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
