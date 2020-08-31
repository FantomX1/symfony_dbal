<?php


namespace App\Repository;


use Doctrine\DBAL\Connection;

/**
 * Class TaskRepository
 * @package App\Repository
 */
class TaskRepository
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
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function getListDtableQuery()
    {
        $conn = $this->connection;
        $queryBuilder = $conn->createQueryBuilder();

        return $queryBuilder
            ->select('*')
            ->from('tasks');

    }


    /**
     * @return mixed
     */
    public function getNewestTask()
    {
        $conn = $this->connection;
        $queryBuilder = $conn->createQueryBuilder();

        $data = $queryBuilder
            ->select('*')
            ->from('tasks')
            ->orderBy("id","DESC")
            ->setMaxResults(1)
            ->execute()
            ->fetch();


        return $data;

    }


    /**
     * @return mixed[]
     */
    public function getList()
    {

        $conn = $this->connection;
        $queryBuilder = $conn->createQueryBuilder();

        $data = $queryBuilder
            ->select('*')
            ->from('tasks')
            ->execute()
            ->fetchAll();


        return $data;

    }


    /**
     * @param $title
     * @param $description
     */
    public function add($title, $description)
    {

        $vars = get_defined_vars();
        $vars['id'] = '';
        $vars= array_map(function($item) { return  "'".$item."'";}, $vars);
        $vars['createdTs'] = 'CURRENT_TIMESTAMP()';


        $queryBuilder = $this->connection->createQueryBuilder();
        // @TODO: check if dbal somehow restricts sql injection, handle name
        $queryBuilder->insert("tasks")
            ->values(
//                [
//                    "id"         => "''",
//                    "name"       => "'" . $title . "'",
//                    "population" => "'" . $text . "'",
//                ]
                $vars
            )->execute();
    }

}
