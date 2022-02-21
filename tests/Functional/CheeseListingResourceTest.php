<?php

namespace App\Tests\Functional;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class CheeseListingResourceTest extends ApiTestCase
{

     use ReloadDatabaseTrait;

     public function testCreateCheeseListing()
     {
          $client = self::createClient();

          $client->request('POST', '/api/cheeses', [
               'headers' => [
                    'Content-Type' => 'application/json',
               ],
               'json' => [],
          ]);
          $this->assertResponseStatusCodeSame(401);

          $user = new User();
          $user->setEmail('cheeseplease@example.com');
          $user->setUsername('cheeseplease');
          $user->setPassword('$2y$13$S5umU89zN3aE.e9sc4MvgurnYbpPrHLLji.eQtP3WrnHEJfNmGm/u');

          $em = self::getContainer()->get(EntityManagerInterface::class);
          $em->persist($user);
          $em->flush();

          $client->request('POST', '/login', [
               'headers' => [
                    'Content-Type' => 'application/json',
               ],
               'json' => [
                    'email' => 'cheeseplease@example.com',
                    'password' => 'foo',
               ],
          ]);
          $this->assertResponseStatusCodeSame(204);
     }
}