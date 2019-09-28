<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductRepository extends ServiceEntityRepository
{

    /**
     * ProductRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }


    /**
     * @param int $days
     * @param string $status
     * @return mixed
     * @throws \Exception
     */
    public function getProductByDays($days=7, $status='pending') {

        $date = new \DateTime('-7 days');

        return $this->createQueryBuilder('p')
            ->where('p.status = :status')
            ->andWhere('p.created_at <= :date')
            ->setParameters([
                'status'=>$status,
                'date'=>$date
            ])
            ->orderBy('p.created_at', 'desc')
            ->getQuery()
            ->getResult();
    }
}
