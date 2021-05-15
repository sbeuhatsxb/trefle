<?php

namespace App\Controller;


use App\Elasticsearch\IndexBuilder;
use App\Elasticsearch\PlantIndexer;
use App\Entity\Plant;
use App\Entity\Token;
use App\Repository\TokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexPlantController extends AbstractController
{

    private $indexBuilder;
    private $plantIndexer;

    public function __construct(IndexBuilder $indexBuilder, PlantIndexer $plantIndexer)
    {
        $this->indexBuilder = $indexBuilder;
        $this->plantIndexer = $plantIndexer;
    }

    /**
     * @Route("/api/v1/indexplants/token={token}", methods={"GET"})
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function search(Request $request, EntityManagerInterface $entityManager, Client $client, $token): Response
    {
        $tokenRepo = $entityManager->getRepository(Token::class);
        $token = $tokenRepo->findOneBy(['token' => $token]);
        if($token != null){

            $index = $this->indexBuilder->create();

            $this->plantIndexer->indexAllDocuments($index->getName());

        } else {
            $array = array('401' => 'Valid token requested');
            $response = new Response(json_encode($array), 401);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

    }

}