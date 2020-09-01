<?php


namespace App\Repository;


use Doctrine\DBAL\Connection;

/**
 * Class TaskRepository
 * @package App\Repository
 */
class TaskRepository
{



    const STATUS_OPEN = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_RESOLVED = 3;


    const RESULT_NOT_SOLVED=0;
    const RESULT_RESOLVED=1;
    const RESULT_WONT_FIX=2;
    const RESULT_REJECTED=3;


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
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder
            ->select('*')
            ->from('tasks')
            ->where("id=".$id)
            ->execute()
            ->fetch();
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
