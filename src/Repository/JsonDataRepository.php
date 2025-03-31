<?php

namespace App\Repository;

use App\Entity\JsonData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class JsonDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JsonData::class);
    }

    public function getRawData(int $id): ?string
    {
        $result = $this->createQueryBuilder('j')
            ->select('j.data')
            ->where('j.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();

        return $result ? $result['data'] : null;
    }
} 