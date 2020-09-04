<?php

class Controller {


/**
* @Route("tasks/list", name="tasks_list")
*/
public function actionList(Connection $conn, SymfonyQueryExecutorPlugin $symfony)
{
//        $queryBuilder = $conn->createQueryBuilder();


//        $data = $tr->getList();

//        $data = $queryBuilder
//            ->select('*')
//            ->from('tasks');
//        $dt->setQuery(
//            $tr->getListDtableQuery()
////            $data
//        );

//        return $this->render(
//            'Tasks/list.html.twig',
//            [
//                //'dt' => $dt,
//                //'data'=> $data
//            ]

//##################


// rename var $dt single row
//        $dt = new DataTableWidget();
//        $tr = new TaskRepository($conn);
//        $dt->setQuery(
//            $tr->getListDtableQuery()
//        );
//

//        $dt->setQuery(
//            $tr->getListDtableQuery()
//        );
//        return $this->render(
//            'Tasks/list.html.twig',
//            [
//                //'dt' => $dt,
//            ]


$dt = new DataTableWidget();
$tr = new TaskRepository($conn);

$dt->setQuery(
$tr->getListDtableQuery()
);

$dt->setQuery(
$tr->getListDtableQuery()
);



TaskRepository::setConnection($conn);

DataTableWidget::setQuery(
TaskRepository::getListDtableQuery()
);

DataTableWidget::setQuery(
TaskRepository::getListDtableQuery()
);

DataTableWidget::setQuery(
TaskRepository::getListDtableQuery()
);



$dt = new DataTableWidget();
$tr = new TaskRepository($conn);

$dt->setQuery(
TaskRepository::getListDtableQuery()
);

$dt->setQuery(
TaskRepository::getListDtableQuery()
);




return $this->render(
'Tasks/list.html.twig'
);

}


}
