<?php

namespace App\Elasticsearch;

use App\Entity\Plant;
use App\Repository\PlantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Client;
use Elastica\Document;
use Elasticsearch\ClientBuilder;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Yaml\Yaml;

class PlantIndexer
{
    private $plantRepository;
    private $entityManager;
    private $client;

    public function __construct(ClientBuilder $client, PlantRepository $plantRepository, EntityManagerInterface $entityManager)
    {
        $this->plantRepository = $plantRepository;
        $this->entityManager = $entityManager;
        $this->client = $client;
    }

    private function setDocument(Plant $plant): array
    {
            return $params = [
                'index' => [
                    '_index' => 'plantapi',
                    '_type' => 'plant',
                    '_id' => $plant->getId(),
                    'body' => [
                        'scientific_name' => $plant->getScientificName(),
                        'common_name' => $plant->getCommonName(),
                        'synonyms' => $plant->getSynonyms(),
                        'common_names' => $plant->getCommonNames(),
                    ]
                ]
            ];
    }

    public function indexAllDocuments()
    {
        //docker exec -it symfony php -d memory_limit=4096M bin/console elastic:reindex --no-debug --env=prod
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);

        $host[] = getenv('SCALINGO_ELASTICSEARCH_URL');
        $client = $this->client->setHosts($host)->build();

        $deleteParams = [
            'index' => 'plantapi'
        ];
        if($client->indices()->exists($deleteParams)){
            $response = $client->indices()->delete($deleteParams);
            echo "Deleting index\n";
            print_r($response);
        }

        $params = [
            'index' => 'plantapi',
            'body' => [
                'settings' => [
                    'number_of_shards' => 1,
                    'number_of_replicas' => 0
                ]
            ]
        ];

        echo "Creating index\n";
        $response = $client->indices()->create($params);
        print_r($response);

        $total = $this->plantRepository->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();


        echo "Parsing documents... this may take a while\n";
        $offset = 0;
        $limit = 1000;
        $stopper = $limit;
        for ($i = 0; $i < $total; $i += $stopper) {
            if ($i + $limit > $total) {
                $limit = $total - $i;
            }
            $plants = $this->plantRepository->findByOffsetLimit($offset, $limit);
            foreach ($plants as $plant) {
                $params[] = $this->setDocument($plant);
            }
            $client->bulk($params);
            $params = [];
            $this->entityManager->clear();
            $offset += $limit;
        }
    }
}