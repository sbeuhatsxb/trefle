<?php

namespace App\Controller;


use App\Entity\Token;
use App\Repository\TokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchPlantController extends AbstractController
{
    //QpgagD7ElrQRNVQNaAqTCye4NkKl_OLTk4RQMmBobzE
    /**
     * @Route("/api/plants/token={token}/q={slug}", methods={"GET"})
     * @param Request $request
     * @param Client $client
     * @return Response
     */
    public function search(Request $request, EntityManagerInterface $entityManager, Client $client, $slug, $token): Response
    {
        $tokenRepo = $entityManager->getRepository(Token::class);
        $token = $tokenRepo->findOneBy(['token' => $token]);
        if($token != null){
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
        } else {
            $array = array('401' => 'Valid token requested');
            $response = new Response(json_encode($array), 401);
            $response->headers->set('Content-Type', 'application/json');

            return $response;
        }

    }

}