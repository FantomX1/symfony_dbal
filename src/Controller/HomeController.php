<?php

namespace App\Controller;

use App\Repository\CountryRepository;
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
    public function new(Request $request, Connection $connection)
    {




        if ($request->get('new_country')) {
            $rep = new CountryRepository($connection);

            $rep->insert(
                $request->get('name'),
                $request->get('population')
            );


            return $this->redirectToRoute('home');

        }







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
            [
            ]
        );
    }
}
