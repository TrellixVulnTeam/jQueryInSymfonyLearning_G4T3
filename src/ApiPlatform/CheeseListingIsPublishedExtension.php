<?php
namespace App\ApiPlatform;

use App\Entity\CheeseListing;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Security\Core\Security;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;

class CheeseListingIsPublishedExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
     private $security;

     public function __construct(Security $security)
     {
          $this->security = $security;
     }

     public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, ?string $operationName = null)
     {
          $this->addWhere($queryBuilder, $resourceClass);
     }

     public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, ?string $operationName = null, array $context = [])
     {
          $this->addWhere($queryBuilder, $resourceClass);
     }

     private function addWhere(QueryBuilder $queryBuilder, string $resourceClass)
     {

          if ($resourceClass != CheeseListing::class){
               return;
          }

          if ($this->security->isGranted('ROLE_ADMIN')){
               return;
          }

          $rootAlias = $queryBuilder->getRootAliases()[0];
          $queryBuilder->andWhere(sprintf('%s.isPublished = :isPublished', $rootAlias))
                    ->setParameter('isPublished', true);
     }
}