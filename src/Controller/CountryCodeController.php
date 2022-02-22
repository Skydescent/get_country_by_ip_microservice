<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CountryCodeController extends AbstractController
{

    /**
     * @Route("/v1/get_country_code/{ip}")
     */
    public function getCountryCodeByIp($ip): JsonResponse
    {

        return new JsonResponse(['your_country_code' => 'COUNTRY' . $ip]);
    }
}
