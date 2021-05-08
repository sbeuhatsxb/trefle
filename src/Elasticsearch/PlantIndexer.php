<?php
namespace App\Elasticsearch;

use App\Entity\Plant;
use App\Repository\PlantRepository;
use Elastica\Client;
use Elastica\Document;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PlantIndexer
{
    private $client;
    private $plantRepository;
    private $router;

    public function __construct(Client $client, PlantRepository $plantRepository, UrlGeneratorInterface $router)
    {
        $this->client = $client;
        $this->plantRepository = $plantRepository;
        $this->router = $router;
    }

    public function buildDocument(Plant $plant)
    {
        return new Document(
            $plant->getId(), // Manually defined ID
            [
                'scientific_name' => $plant->getScientificName(),
                'common_name' => $plant->getCommonName(),
                'family_common_name' => $plant->getFamilyCommonName(),
                'synonyms' => $plant->getSynonyms(),
                'common_names' => $plant->getCommonNames(),
                'vegetable' => $plant->getVegetable(),
                'edible' => $plant->getEdible(),

                // Not indexed but needed for display
                //'url' => $this->router->generate('blog_post', ['slug' => $plantHandler->getScientificName()], UrlGeneratorInterface::ABSOLUTE_PATH),
//                'date' => $plantHandler->getPublishedAt()->format('M d, Y'),
            ],
            "plantHandler" // Types are deprecated, to be removed in Elastic 7
        );
    }

    public function indexAllDocuments($indexName)
    {
        $allPosts = $this->plantRepository->findAll();
        $index = $this->client->getIndex($indexName);

        $documents = [];
        foreach ($allPosts as $post) {
            $documents[] = $this->buildDocument($post);
        }

        $index->addDocuments($documents);
        $index->refresh();
    }
}