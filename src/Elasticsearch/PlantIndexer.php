<?php
namespace App\Elasticsearch;

use App\Entity\Plant;
use App\Repository\PlantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Client;
use Elastica\Document;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PlantIndexer
{
    private $client;
    private $plantRepository;
    private $entityManager;

    public function __construct(Client $client, PlantRepository $plantRepository, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->plantRepository = $plantRepository;
        $this->entityManager = $entityManager;
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
            "_doc" // Types are deprecated, to be removed in Elastic 7
        );
    }

    public function indexAllDocuments($indexName)
    {
        //docker exec -it symfony php -d memory_limit=4096M bin/console elastic:reindex --no-debug --env=prod
        $this->entityManager->getConnection()->getConfiguration()->setSQLLogger(null);
        //$allPlant = $this->plantRepository->findByOffsetLimit(1, 500);

        //$allPlant = $this->plantRepository->findAll();

        $total = $this->plantRepository->createQueryBuilder('a')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();

        $index = $this->client->getIndex($indexName);
        if($index->exists()){
            $documents = [];
            //        foreach ($allPlant as $plant) {
            //            $documents[] = $this->buildDocument($plant);
            //            $this->entityManager->clear();
            //        }
            //
            $offset = 0;
            $limit = 500;
            $stopper = $limit;
            for($i = 0; $i < $total; $i+=$stopper) {
                if ($i + $limit > $total) {
                    $limit = $total - $i;
                }
                $plants = $this->plantRepository->findByOffsetLimit($offset, $limit);
                foreach ($plants as $plant) {
                    $documents[] = $this->buildDocument($plant);
                }
                $index->addDocuments($documents);
                $index->refresh();
                $documents = [];
                $this->entityManager->clear();
                $offset += $limit;
            }

        }

    }
}