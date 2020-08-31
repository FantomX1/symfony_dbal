<?php

namespace App\Controller;

use App\Components\FlashMessage;
use App\Repository\CountryRepository;
use App\Repository\TaskRepository;
use App\Services\FlashMessageService;
use Doctrine\DBAL\Connection;

use fantomx1\datatables\widgets\DataTableWidget;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{


    /**
     * @Route("/")
     * @Route("/home")
     * @Route(name="home")
     */
    public function index(Connection $conn):Response
    {

        $tasks = new TaskRepository($conn);
        $tasks->add("somethin new", "hoppa");

        //$dt = new DataTableWidget();
        //$dt->run();


        $rep = new CountryRepository($conn);
        $list = $rep->getList();

        return $this->render(
            'Home/index.html.twig',
            [
                'data' => $list
            ]
        );
    }



    /**
     * @Route("edit/{id}", name="country_edit", methods={"GET","POST"})
     */
    public function edit(
        $id ,
        Request $request,
        Connection $connection,
        FlashMessageService $flashMessage
    )
    {

        $rep = new CountryRepository($connection);


        if ($request->get('new_country')) {


            $flashMessage->add("Item edited");

            $rep = new CountryRepository($connection);

            // or sent an entire array
            $rep->edit(
                $id,
                $request->get('name'),
                $request->get('population')
            );

            // prevent resubmission
            //return $this->redirectToRoute('country_edit', [ 'id' => $id ] );
            //return $this->redirectToRoute('home');
        }

        $item = $rep->find($id);

        return $this->render(
            'Home/edit.html.twig',
            [
                'data' => $item
            ]
        );

    }

    /**
     * @Route("/home/new", name="home/new")
     */
    public function new(
        Request $request,
        Connection $connection,
        FlashMessageService $flashMessage
    )
    {
        // if ($request->get('new_country')) {
        // moved to events

        return $this->render(
            'Home/new.html.twig',
            []
        );
    }


// at this stage Request service not available
//    public function setContainer(ContainerInterface $container = null, Request $request): ?ContainerInterface
//    {
//        return parent::setContainer($container);
//        //$this->containerInitialized();
//    }



}
