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
        $this->client->connect();
        $response = new Response(json_encode([
            $this->client->getConnection()->getUsername(),
            $this->client->getConnection()->getHost(),
            $this->client->getConnection()->getPath(),
            $this->client->getConnection()->getParams(),
            $this->client->getConnection()->getAuthType(),
            $this->client->getConnection()->getPassword(),
            ]), 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

}