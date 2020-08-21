<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class   HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Connection $conn):JsonResponse
    {

        $queryBuilder = $conn->createQueryBuilder();

        $data = $queryBuilder->select('*')->from('countries')->execute()->fetchAll();

        return $this->json([
            'data' => $data
        ]);
    }
}
