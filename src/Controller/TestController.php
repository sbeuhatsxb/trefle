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
    /**
     * @Route("/test", methods={"GET"})
     * @return Response
     */
    public function test(): Response
    {
        $client = new Client();

        $index = $client->getIndex('test');
        $index->create(array(), true);
        $index->addDocument(new Document(1, array('username' => 'ruflin'), "_plantapi"));
        $index->refresh();

        $query = '{"query":{"query_string":{"query":"ruflin"}}}';

        $path = $index->getName() . '/_plantapi/_search';

        $response = $client->request($path, Request::METHOD_GET, $query);
        $responseArray = $response->getData();


        $response = new Response(json_encode($responseArray), 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

}