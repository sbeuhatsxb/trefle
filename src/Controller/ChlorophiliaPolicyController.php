<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class ChlorophiliaPolicyController extends AbstractController
{
    /**
     * @Route("/chlorophilia/app/policy/en")
     */
    public function enPolicy() :Response
    {
        return $this->render('chlorophilia_en.html.twig');
    }
}