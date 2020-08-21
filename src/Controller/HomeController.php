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

class   HomeController extends AbstractController
{
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

//        // todo reference as singleton in bootstrap, nope: kernel not available from outside
//        $this->get('twig')->addGlobal(
//            'flashMessages',
//            FlashMessage::get()
//        );

        return $this->render(
            'Home/index.html.twig',
            [
                'data' => $data
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


//        // todo reference as singleton in bootstrap
//        $this->get('twig')->addGlobal(
//            'flashMessages',
//            FlashMessage::get()
//        );

        // add repository class

//        $queryBuilder = $connection->createQueryBuilder();
//        $queryBuilder->insert("countries")
//            ->values(
//                [
//                    "id"         => "''",
//                    "name"       => "'" . $request->get('name') . "'",
//                    "population" => "'" . $request->get('population') . "'",
//                ]
//            )->execute();


        return $this->render(
            'Home/new.html.twig',
            []
        );
    }
}
