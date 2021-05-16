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
        $index = $this->client->getIndex('plantapi');
        $this->plantRepository = $plantRepository;
        $this->client = $client;
        $this->client->connect();
        $total = $this->plantRepository->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        
        $documents = [];
        $offset = 0;
        $limit = 10;
        $stopper = $limit;
        for ($i = 0; $i < $total; $i += $stopper) {
            if ($i + $limit > $total) {
                $limit = $total - $i;
            }
            $plants = $this->plantRepository->findByOffsetLimit($offset, $limit);
            foreach ($plants as $plant) {
                $documents[] = $this->buildDocument($plant);
            }
            $index->addDocuments($documents);
            $index->refreshAll();
            $documents = [];
            $offset += $limit;
        }

        $response = new Response(json_encode([
            $this->client->hasConnection(),
            ]), 200);
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

}