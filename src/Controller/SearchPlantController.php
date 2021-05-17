<?php

namespace App\Controller;


use App\Entity\Plant;
use App\Entity\Token;
use App\Repository\TokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elasticsearch\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchPlantController extends AbstractController
{
    /**
     * @Route("/api/v1/species/token={token}/q={slug}", methods={"GET"})
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function search(Request $request, EntityManagerInterface $entityManager, Client $client, $slug, $token): Response
    {
        $tokenRepo = $entityManager->getRepository(Token::class);
        $plantRepo = $entityManager->getRepository(Plant::class);

        $token = $tokenRepo->findOneBy(['token' => $token]);
        if($token != null){

            $params = [
                'index' => 'plantapi'
            ];

            $response = $client->get($params);
            dd($response);

            $params = [
                'index' => 'plantapi',
                'body'  => [
                    'query' => [
                        'match' => [
                            'scientific_name' => $slug,
                            'common_name' => $slug,
                            'common_names' => $slug,
                            'synonyms' => $slug,
                        ]
                    ]
                ]
            ];

            $response = $client->search($params);
            print_r($response);

            $foundPlants = $client->getIndex('plantapi')->search($slug);

            $results = [];

            foreach ($foundPlants->getResults() as $result) {
                /** @var Plant $plant */
                $plant = $plantRepo->find($result->getId());
                $results[] = [
                            'score' => $result->getScore(),
                            'id' => htmlspecialchars($result->getId(), ENT_COMPAT | ENT_HTML5),
                            'scientific_name' => htmlspecialchars($result->getData()["scientific_name"], ENT_COMPAT | ENT_HTML5),
                            'family_common_name' => htmlspecialchars($plant->getFamilyCommonName(), ENT_COMPAT | ENT_HTML5),
                            'common_name' => htmlspecialchars($result->getData()["common_name"], ENT_COMPAT | ENT_HTML5),
                            'genus' => htmlspecialchars($plant->getGenus()->getName(), ENT_COMPAT | ENT_HTML5),
                            'family' => htmlspecialchars($plant->getFamily()->getName(), ENT_COMPAT | ENT_HTML5),
                            'common_names' => htmlspecialchars($result->getData()["common_names"], ENT_COMPAT | ENT_HTML5),
                            'vegetable' => htmlspecialchars($plant->getVegetable(), ENT_COMPAT | ENT_HTML5),
                            'edible' => htmlspecialchars($plant->getEdible(), ENT_COMPAT | ENT_HTML5),
                            'image_url' => htmlspecialchars($plant->getImageUrl(), ENT_COMPAT | ENT_HTML5),
                    ];
            }

            return $this->json(['plants' => $results]);
        } else {
            $array = array('401' => 'Valid token requested');
            $response = new Response(json_encode($array), 401);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

    }

}