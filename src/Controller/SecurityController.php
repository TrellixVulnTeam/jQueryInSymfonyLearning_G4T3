<?php

namespace App\Controller;

use ApiPlatform\Core\Api\IriConverterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
     #[Route('/login', name: 'app_login')]
     public function login(IriConverterInterface $iriConverterInterface)
     {
          if (!$this->isGranted('IS_AUTHENTICATED_FULLY')){
               return $this->json([
                    'error' => 'Invalid login request: check that the Content-Type header is "aplication/json"'
               ], 400);
          }


          return  new Response(null,  204, [
               'Location' => $iriConverterInterface->getIriFromItem($this->getUser()),
          ]);
     }

     #[Route('/logout', name:'app_logout')]
     public function logout()
     {
          throw new \Exception('should not be reached');
     }
}