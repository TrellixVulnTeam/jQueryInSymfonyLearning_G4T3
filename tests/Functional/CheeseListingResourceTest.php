<?php

namespace App\Tests\Functional;

use App\Entity\CheeseListing;
use App\Test\CustomApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class CheeseListingResourceTest extends CustomApiTestCase
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

          $user = 'cheeseplease@example.com';
          $pass = 'foo'; 
          $usuario = $this->createUserAndLogin($client, $user, $pass);

          $client->request('POST', '/api/cheeses', [
               'json' => [],
          ]);
          $this->assertResponseStatusCodeSame(422);
     }

     public function testUpdateCheeseListing()
     {
          $client = self::createClient();
          $user1 = $this->createUser('user1@example.com', 'foo');
          $user2 = $this->createUser('user2@example.com', 'foo');

          $cheeseListing = new CheeseListing('Block of cheddar');
          $cheeseListing->setOwner($user1);
          $cheeseListing->setPrice(1000);
          $cheeseListing->setDescription('mmmm');

          $em = $this->getEntityManager();
          $em->persist($cheeseListing);
          $em->flush();

          $this->logIn($client, 'user2@example.com', 'foo');
          $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
               'json' => [
                    'title' => 'updated',
                    'owner' => 'api/users/'.$user2->getId()
               ]
          ]);
          $this->assertResponseStatusCodeSame(403);
          // var_dump($client->getResponse()->getContent(false));

          $this->logIn($client, 'user1@example.com', 'foo');
          $client->request('PUT', '/api/cheeses/'.$cheeseListing->getId(), [
               'json' => ['title' => 'updated']
          ]);
          $this->assertResponseStatusCodeSame(200);
     }

}