<?php

namespace App\Repository;

use App\Entity\JsonData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RawJsonDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JsonData::class);
    }

    public function getRawData(int $id): ?string
    {
        $result = $this->getEntityManager()
            ->getConnection()
            ->executeQuery(
                'SELECT data::text FROM json_data WHERE id = :id',
                ['id' => $id]
            )
            ->fetchAssociative();

        return $result ? $result['data'] : null;
    }
} 