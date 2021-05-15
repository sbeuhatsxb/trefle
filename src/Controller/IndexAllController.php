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

class IndexAllController extends AbstractController
{
    /**
     * @Route("/api/v1/species/token={token}/indexall", methods={"GET"})
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    private $indexBuilder;

    public function search(Request $request, EntityManagerInterface $entityManager, Client $client, $token, PlantIndexer $plantIndexer): Response
    {
        $tokenRepo = $entityManager->getRepository(Token::class);
        $plantRepo = $entityManager->getRepository(Plant::class);

        $token = $tokenRepo->findOneBy(['token' => $token]);
        if($token != null){

            $index = $client->getIndex('plantapi');
            $settings = Yaml::parse(
                file_get_contents(
                    __DIR__.'/../../config/elasticsearch/plant_mapping.yaml'
                )
            );
            $index->create($settings, true);

            $allPlant = $plantRepo->findAll();

            $documents = [];
            foreach ($allPlant as $plant) {
                $documents[] = $this->buildDocument($plant);
            }

            $index->addDocuments($documents);
            $index->refresh();

            $response = new Response('OK', 200);
//            $response = new Response(json_encode($array), 401);
//            $response->headers->set('Content-Type', 'application/json');

            return $response;
        } else {
            $array = array('401' => 'Valid token requested');
            $response = new Response(json_encode($array), 401);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

    }

    public function buildDocument(Plant $plant)
    {
        return new Document(
            $plant->getId(), // Manually defined ID
            [
                'scientific_name' => $plant->getScientificName(),
                'common_name' => $plant->getCommonName(),
                'synonyms' => $plant->getSynonyms(),
                'common_names' => $plant->getCommonNames(),
            ],
            "plantapi" // Types are deprecated, to be removed in Elastic 7
        );
    }

}