<?php

namespace App\Controller;


use App\Entity\Plant;
use App\Entity\Token;
use Doctrine\ORM\EntityManagerInterface;
use Elasticsearch\ClientBuilder;
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
     * @param ClientBuilder $cb
     * @return Response
     */
    public function search(Request $request, EntityManagerInterface $entityManager, ClientBuilder $cb, $slug, $token): Response
    {
        $host = $this->getParameter('es_host');
        $client = $cb->setHosts([$host["host"]])->build();

        $tokenRepo = $entityManager->getRepository(Token::class);
        $plantRepo = $entityManager->getRepository(Plant::class);

        $token = $tokenRepo->findOneBy(['token' => $token]);
        if($token != null){


            $common_names = $client->search($this->getParams($slug, 'common_names'));
            $common_name = $client->search($this->getParams($slug, 'common_name'));
            $synonyms = $client->search($this->getParams($slug,'synonyms'));
            $scientific_name = $client->search($this->getParams($slug, 'scientific_name'));

            $resultsIds = [];

            $result = false;
            if($common_names["hits"]["total"] != 0){
                $result = true;
                foreach($common_names["hits"]["hits"] as $hit){
                    $resultsIds[] = $hit["_id"];
                }
            }
            if($common_name["hits"]["total"] != 0){
                $result = true;
                foreach($common_name["hits"]["hits"] as $hit){
                    $resultsIds[] = $hit["_id"];
                }
            }
            if($scientific_name["hits"]["total"] != 0){
                $result = true;
                foreach($scientific_name["hits"]["hits"] as $hit){
                    $resultsIds[] = $hit["_id"];
                }
            }
            if($synonyms["hits"]["total"] != 0){
                $result = true;
                foreach($synonyms["hits"]["hits"] as $hit){
                    $resultsIds[] = $hit["_id"];
                }
            }

            if(!$result){
                $array = array('plants' => []);
                $response = new Response(json_encode($array), 200);
                $response->headers->set('Content-Type', 'application/json');
                return $response;
            }

            foreach ($resultsIds as $id) {
                /** @var Plant $plant */
                $plant = $plantRepo->find($id);
                $jsonResponse[] = [
                            'id' => htmlspecialchars($plant->getId(), ENT_COMPAT | ENT_HTML5),
                            'scientific_name' => htmlspecialchars($plant->getScientificName(), ENT_COMPAT | ENT_HTML5),
                            'family_common_name' => htmlspecialchars($plant->getFamilyCommonName(), ENT_COMPAT | ENT_HTML5),
                            'common_name' => htmlspecialchars($plant->getCommonName(), ENT_COMPAT | ENT_HTML5),
                            'genus' => htmlspecialchars($plant->getGenus()->getName(), ENT_COMPAT | ENT_HTML5),
                            'family' => htmlspecialchars($plant->getFamily()->getName(), ENT_COMPAT | ENT_HTML5),
                            'common_names' => htmlspecialchars($plant->getCommonNames(), ENT_COMPAT | ENT_HTML5),
                            'vegetable' => htmlspecialchars($plant->getVegetable(), ENT_COMPAT | ENT_HTML5),
                            'edible' => htmlspecialchars($plant->getEdible(), ENT_COMPAT | ENT_HTML5),
                            'image_url' => htmlspecialchars($plant->getImageUrl(), ENT_COMPAT | ENT_HTML5),
                    ];
            }

            $array = ['plants' => $jsonResponse];
            $response = new Response(json_encode($array), 200);
            $response->headers->set('Content-Type', 'application/json');

            return $response;

        } else {
            $array = array('401' => 'Valid token requested');
            $response = new Response(json_encode($array), 401);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

    }
    
    private function getParams($slug, $field){
        $query = '{
                "query" : {
                    "match" : {
                        "'.$field.'" : "'.$slug.'"
                    }
                }
            }';

        return $params = [
            'index' => 'plantapi',
            'type' => 'plant',
            'body'  => $query
        ];
    }

}