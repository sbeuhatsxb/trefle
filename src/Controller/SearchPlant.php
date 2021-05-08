<?php

namespace App\Controller;

use App\Entity\Plant;
use Elastica\Client;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MultiMatch;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchPlant extends AbstractController
{
    /**
     * @Route("/api/{slug}", methods={"GET","HEAD"})
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function search(Request $request, Client $client, $slug): Response
    {

        $foundPlants = $client->getIndex('plant')->search($slug);

        $results = [];

        foreach ($foundPlants->getResults() as $result) {

            $results[] = [
                'score' => $result->getScore(),
                'id' => htmlspecialchars($result->getId(), ENT_COMPAT | ENT_HTML5),
                'scientific_name' => htmlspecialchars($result->getData()["scientific_name"], ENT_COMPAT | ENT_HTML5),
                'common_name' => htmlspecialchars($result->getData()["common_name"], ENT_COMPAT | ENT_HTML5),
                'family_common_name' => htmlspecialchars($result->getData()["family_common_name"], ENT_COMPAT | ENT_HTML5),
                'common_names' => htmlspecialchars($result->getData()["common_names"], ENT_COMPAT | ENT_HTML5),
                'vegetable' => htmlspecialchars($result->getData()["vegetable"], ENT_COMPAT | ENT_HTML5),
                'edible' => htmlspecialchars($result->getData()["edible"], ENT_COMPAT | ENT_HTML5),
            ];
        }

        return $this->json($results);
    }

}