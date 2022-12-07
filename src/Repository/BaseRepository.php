<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\UX\Datatable\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Aziz Benmallouk <azizbenmallouk4@gmail.com>
 *
 * @final
 */
class BaseRepository extends ServiceEntityRepository
{
    protected EntityManagerInterface $entityManager;
    protected string $entityClass;
    protected string $alias;
    protected ClassMetadata $metadata;

    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManager,string $entityClass,string $alias)
    {
        parent::__construct($registry, $entityClass);
        $this->entityManager = $entityManager;
        $this->entityClass = $entityClass;
        $this->alias = $alias;
        $this->metadata = $this->entityManager->getClassMetadata($this->entityClass);
    }

    /* start - get entity associated fields */
    public function hasQueryJoin(QueryBuilder $builder,string $join){
        foreach ($builder->getDQLPart('join') as $item){
            if(explode('.',$item[0]->getJoin())[1]==$join){
                return true;
            }
        }
        return false;
    }
    /* end - get entity associated fields */
}
