<?php


namespace App\Controller;

use App\Entity\Plant;
use Symfony\Component\Routing\Annotation\Route;

class ShowPlantController
{
    public function createPublication(Plant $data): Plant
    {
        return $this->handle($data);
    }

}