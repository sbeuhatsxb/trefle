<?php
namespace App\Elasticsearch;

use Elastica\Client;
use Symfony\Component\Yaml\Yaml;

class IndexBuilder
{
    //docker exec -it symfony php -d memory_limit=4096M bin/console elastic:reindex --no-debug --env=prod
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create()
    {
        // We name our index "plant"
        $index = $this->client->getIndex('plantapi');
//        $settings = Yaml::parse(
//            file_get_contents(
//                __DIR__.'/../../config/elasticsearch/plant_mapping.yaml'
//            )
//        );
//        // We build our index settings and mapping
//        $index->create($settings, true);

        return $index;
    }
}