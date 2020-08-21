<?php

namespace App\Controller;

use App\Components\FlashMessage;
use App\Repository\CountryRepository;
use App\Services\FlashMessageService;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 */
class   HomeController extends AbstractController
{


    // @TODO: prior action process logic, always possibly redirect to the single place
    // on other side disadvantage that must be sent exact action to distingush edit from create , even when redirect to the same
    // rename vars or generic , and specialize custom, sometimes to redirect new, sometimes to say, update
    //

    /**
     * @Route("/home", name="home")
     */
    public function index(Connection $conn):Response
    {
        $queryBuilder = $conn->createQueryBuilder();

        $data = $queryBuilder
            ->select('*')
            ->from('countries')
            ->execute()
            ->fetchAll();


        return $this->render(
            'Home/index.html.twig',
            [
                'data' => $data
            ]
        );
    }



    /**
     * @Route("edit/{id}", name="country_edit", methods={"GET","POST"})
     */
    public function edit($id , Request $request, Connection $connection, FlashMessageService $flashMessage)
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

        // hs_ hiddenSignal_ prefix
        if ($request->get('new_country')) {

            //FlashMessage::add("Item added");
            // @TODO: still duplicated

            //  Service  not found: even though it exists in the app's container, the container inside "App\Controller\HomeController" is a smaller service locator that only knows about the
            //$this->get('flashMessage')->add("Item added");
            $flashMessage->add("Item added");

            $rep = new CountryRepository($connection);

            $rep->insert(
                $request->get('name'),
                $request->get('population')
            );

            return $this->redirectToRoute('home');

        }




        return $this->render(
            'Home/new.html.twig',
            []
        );
    }
}
