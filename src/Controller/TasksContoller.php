<?php


namespace App\Controller;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Connection;
use fantomx1\datatables\plugins\queryExecutor\classes\SymfonyQueryExecutorPlugin;
use fantomx1\datatables\plugins\queryExecutor\classes\YiiQueryExecutorPlugin;
use fantomx1\datatables\widgets\DataTableWidget;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TasksContoller extends AbstractController
{

    /**
     * @Route("/tasks/ping", name="tasks_ping")
     */
    public function actionPing(Connection $conn)
    {
        $response = new Response();

        $tr = new TaskRepository($conn);
        $tr->getNewestTask();

        $response->setContent(
            json_encode(
                $tr->getNewestTask()
//                [
//                'data' => "123",
//                ]
            )
        );

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    /**
     * @TODO:
     * @Route("tasks/edit/{id}", name="tasks_edit", methods={"GET","POST"})
     */
    public function actionEdit()
    {

    }


    /**
     * @Route("tasks/list", name="tasks_list")
     */
    public function actionList(Connection $conn, SymfonyQueryExecutorPlugin $symfony)
    {

        $dt = new DataTableWidget();

        $queryBuilder = $conn->createQueryBuilder();

        $tr = new TaskRepository($conn);
        $data = $tr->getList();

//        $data = $queryBuilder
//            ->select('*')
//            ->from('tasks');

        $dt->setQuery(
            $tr->getListDtableQuery()
//            $data
        );



        return $this->render(
            'Tasks/list.html.twig',
            [
                'dt' => $dt,
                //'data'=> $data
            ]
        );

    }

}
