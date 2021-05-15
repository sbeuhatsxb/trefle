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

        // We build our index settings and mapping
        //$index->create($settings, true);
        $index->create(array(
            'settings' =>
                array(
                    'index' =>
                        array(
                            'number_of_shards' => 1,
                            'number_of_replicas' => 0,
                            'refresh_interval' => '60s',
                        ),
                )
            ),
 true);

        $mapping = new \Elastica\Mapping();
        $mapping->setProperties(
                array(
                    'dynamic' => false,
                    'properties' =>
                        array(
                            'scientific_name' =>
                                array(
                                    'type' => 'text',
                                ),
                            'common_name' =>
                                array(
                                    'type' => 'text',
                                    'analyzer' => 'english',
                                ),
                            'family_common_name' =>
                                array(
                                    'type' => 'text',
                                ),
                            'synonyms' =>
                                array(
                                    'type' => 'text',
                                ),
                            'common_names' =>
                                array(
                                    'type' => 'text',
                                ),
                        ),
                ),
        );

        $mapping->send();
        return $index;
    }
}