<?php

namespace App\Controller;

use App\Entity\IpAddress;
use App\Service\GetCountryCodeByIpService;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CountryCodeController extends AbstractController
{

    public function __construct(
        private GetCountryCodeByIpService $countryCodeService,
        private CacheItemPoolInterface $pool,
        private ValidatorInterface $validator
    )
    {}

    /**
     * @Route("/v1/get_country_code", methods="GET")
     */
    public function getCountryCodeByIp(Request $request): Response
    {
        $ip = $request->get('ip');

        $idAddress = new IpAddress($ip);
        $errors = $this->validator->validate($idAddress);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string) $errors], 400);
        }

        $cachedRespons = $this->pool->get('ip=' . $ip, function (ItemInterface $item) use ($ip) {
            $item->expiresAfter($this->getParameter('expires_country_code_time'));
            return $this->countryCodeService->get($ip);
        });

        if (!empty($cachedRespons['errors'])) {
            return new JsonResponse($cachedRespons);
        }

        return new JsonResponse(['country_code' => $cachedRespons]);
    }
}
