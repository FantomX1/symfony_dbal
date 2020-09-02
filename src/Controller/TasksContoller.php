<?php


namespace App\Controller;
use App\Components\DateHelpers;
use App\Repository\TaskRepository;
use Doctrine\DBAL\Connection;
use fantomx1\datatables\plugins\queryExecutor\classes\SymfonyQueryExecutorPlugin;
use fantomx1\datatables\plugins\queryExecutor\classes\YiiQueryExecutorPlugin;
use fantomx1\datatables\widgets\DataTableWidget;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TasksContoller
 * @package App\Controller
 */
class TasksContoller extends AbstractController
{


    /**
     * TasksContoller constructor.
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->connection = $conn;
    }

    /**
     * @Route("/tasks/ping", name="tasks_ping")
     */
    public function actionPing(
        Connection $conn,
        SessionInterface $session
    )
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $tr = new TaskRepository($conn);

        $taskId = null;
        $status = "unknown";

        if ($task = $tr->getNewestTask()) {
            $taskId = $task['id'];
        }

        $last = $session->get('latest_task');
        //$last = $_SESSION['latest_task'] ?? null;

        // taskId is in db so it is int, can not be tampered by different val anyway, at most not being positive

        // decide prior last session value, and set flags, prior setting it again to session
        //if (!isset($last) || $last != $taskId || !is_int($last)) {
        if (isset($last) && is_numeric($taskId) && is_numeric($last) &&  $last == $taskId) {
            $status = "same";
        } elseif (is_numeric($taskId)) {
            $status = 'changed';
            $session->set('latest_task', $taskId);
        }

        if (!$task) {
            $status = 'NA';
        }

        $result = [
            'status'=> $status
        ];

        if ($task) {
            $result['task'] = $task;
        }

        $response->setContent(
            json_encode(
                $result
//                [
//                    'status' => $status,
//                    'task'   => $task
//                ]
            )
        );

        return $response;
    }



    /**
     * @Route("tasks/start/{id}", name="tasks_start", methods={"GET","POST"})
     */
    public function actionStartTask(
        $id,
        Request $request
    )
    {
        if (!$id) {
            $this->redirectToRoute("home");
        }

        $tr = new TaskRepository($this->connection);

        $tr->edit(
            $id,
            ['status' => TaskRepository::STATUS_IN_PROGRESS]
            //$request->get('task')
        );

        return $this->redirectToRoute('tasks_edit', ['id'=>$id]);

    }


    /**
     * @Route("tasks/edit/{id}", name="tasks_edit", methods={"GET","POST"})
     */
    public function actionEdit(
        $id,
        Request $request
    )
    {

        $tr = new TaskRepository($this->connection);

        if ($request->get('task_submit') ) {
            $tr->edit(
                $id, $request->get('task')
            );
        }



        $task = $tr->find($id);

//        var_dump($rep);

        return $this->render(
            'Tasks/edit.html.twig',
            [
                'dateHelper'=>new DateHelpers(),
                'data' => $task,
                // prevent accidentally calling something else on an entity/object with insert, update operations or scattered inside html passing full object
                'statuses' => $tr->getAvailableStatuses()
            ]
        );

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
