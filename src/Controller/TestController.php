<?php

namespace App\Controller;


use App\Elasticsearch\IndexBuilder;
use App\Elasticsearch\PlantIndexer;
use App\Entity\Plant;
use App\Entity\Token;
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

    /**
     * @Route("/test", methods={"GET"})
     * @return Response
     */
    public function test(Client $client): Response
    {
        $this->client = $client;

        $response = new Response(json_encode([$this->client->getIndex('plantapi')->getStats()->getData()]), 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

}