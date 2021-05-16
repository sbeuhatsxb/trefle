<?php

namespace App\Controller;


use App\Elasticsearch\IndexBuilder;
use App\Elasticsearch\PlantIndexer;
use App\Entity\Plant;
use App\Entity\Token;
use App\Repository\PlantRepository;
use App\Repository\TokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Client;
use Elastica\Document;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController extends AbstractController
{
    private $client;
    private $plantRepository;

    /**
     * @Route("/test", methods={"GET"})
     * @return Response
     */
    public function test(PlantRepository $plantRepository, Client $client): Response
    {
        $client = new Client();

        $path = '_aliases?pretty';

        $response = $client->request($path, Request::METHOD_GET);
        $responseArray = $response->getData();
        $response = new Response(json_encode($responseArray), 401);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
}