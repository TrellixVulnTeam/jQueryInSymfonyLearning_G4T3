<?php

namespace App\Test;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomApiTestCase extends ApiTestCase
{
     protected function createUser(string $email, string $password): User 
     {
          $em = self::getContainer()->get(EntityManagerInterface::class);
          $encode = self::getContainer()->get(UserPasswordHasherInterface::class);
          $user = new User();
          $user->setEmail($email);
          $user->setPlainPassword($password);
          $user->setUsername(substr($email,0, strpos($email, '@')));
          $user->setPassword($encode->hashPassword($user, $user->getPlainPassword()));
          $user->eraseCredentials();
          $em->persist($user);
          $em->flush();
          return $user;
     }

     protected function login(Client $client, string $email, string $password)
     {
          $client->request('POST', '/login', [
               'headers' => [
                    'Content-Type' => 'application/json',
               ],
               'json' => [
                    'email' => $email,
                    'password' => $password,
               ],
          ]);
          $this->assertResponseStatusCodeSame(204);
     }

     protected function createUserAndLogin(Client $client, string $email, string $password): User
     {
          $user = $this->createUser($email, $password);

          $this->login($client, $email, $password);

          return $user;
     }

     protected function getEntityManager()
     {
          $em = self::getContainer()->get(EntityManagerInterface::class);

          return $em;
     }
}