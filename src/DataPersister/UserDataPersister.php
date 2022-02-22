<?php

namespace App\DataPersister;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserDataPersister implements DataPersisterInterface
{
     private $em;

     public function __construct(EntityManagerInterface $entityManagerInterface, UserPasswordHasherInterface $userPasswordHasherInterface)
     {
          $this->em = $entityManagerInterface;
          $this->userPasswordHasherInterface = $userPasswordHasherInterface;
     }

     public function supports($data): bool
     {
          return $data instanceof User;
     }

     /**
      * @param User $data
      */
     public function persist($data)
     {
          if ($data->getPlainPassword()){
               $data->setPassword(
                    $this->userPasswordHasherInterface->hashPassword($data, $data->getPlainPassword())
               );

               $data->eraseCredentials();
          }

          $this->em->persist($data);
          $this->em->flush();
     }

     public function remove($data)
     {
          $this->em->remove($data);
          $this->em->flush();
     }

}