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
     *
     */
    const STATUS_OPEN        = 1;
    /**
     *
     */
    const STATUS_IN_PROGRESS = 2;
    /**
     *
     */
    const STATUS_RESOLVED = 3;


    /**
     *
     */
    const RESULT_NOT_SOLVED =0;
    /**
     *
     */
    const RESULT_RESOLVED =1;
    /**
     *
     */
    const RESULT_WONT_FIX =2;
    /**
     *
     */
    const RESULT_REJECTED =3;


    /**
     * @var array|false
     */
    private $columns;

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


        // // PDO doenst allow columns binding, therefore check if the column exists, get existing columns
        $results = $connection->fetchAll("SELECT * , count(*) as _count from (SELECT * FROM tasks LIMIT 1) a")[0];
        unset($results['_count']);

        // fill values with ones1, values are not important for columns names but isset fails with value null
        // but is faster than array_key_exists
        $this->columns = array_combine(array_keys($results), array_fill(0, count($results), 1));

        /** @var Connection $connection */
        $this->connection = $connection;
    }



    /**
     * @return array
     */
    public  function getAvailableStatuses()
    {
        return $this->getConstArrayFromClass(__CLASS__, 'STATUS_');
    }

    //private static function getConstArrayFromClass($class, $prefix = '')

    /**
     * @param $class
     * @param string $prefix
     * @return array
     * @throws \ReflectionException
     */
    private function getConstArrayFromClass($class, $prefix = '')
    {
        $class = new \ReflectionClass($class);
        $constArray = [];
        $consts =  $class->getConstants();
        foreach ($consts as $constKey => $constValue) {
            //if ($prefix != '' && strpos($constKey, $prefix) === false) {
            if (strpos($constKey, $prefix) === false) {
                continue;
            }
            //if ($prefix != '') {
            $constKey = substr($constKey, strlen($prefix));
            //}
            $constArray[$constKey] = $constValue;
        }
        return array_flip($constArray);
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
     * @param $array
     */
    public function edit($id, $array)
    {

        if (!is_numeric($id) || $id<1) {
            return;
        }

        if (!$array) {
            return;
        }

        // array_map(function($item) {return addslashes($item); });
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder->update('tasks');

        // query builde ronly prepares string, or stringifies it, connection has also executeds and fetchAll also executes
        // SELECT * , count(*) as _count from (".$query." LIMIT 1) a perhaps not needed to get columns, but can be added @TODO


        $binds = [];
        foreach ($array as $col => $val) {


            // defense against SQL injection, replace not allowed chars
            $col = preg_replace("/[^a-zA-Z0-9\-_]+/", "", $col);

            // PDO doenst allow columns binding, therefore check if the column exists
            if (isset($this->columns[$col])) {
                $queryBuilder->set($col, ":".$col);
            }
//            $queryBuilder->set(":col_".$col, ":".$col);
//            $binds["col_".$col] = $col;
            $binds[$col] = $val;
        }

        $queryBuilder->getSQL();


        // $queryBuilder->execute(); and setParams
        //  @TODO: setParams via queryBuilder wrapper


        $this->connection->executeQuery(
            $queryBuilder->getSQL(),
            $binds
        );
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
