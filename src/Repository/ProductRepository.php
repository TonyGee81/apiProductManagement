<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly PaginatorInterface $paginator)
    {
        parent::__construct($registry, Product::class);
    }

    public function paginateProducts(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this
                ->createQueryBuilder('p')
                ->leftJoin('p.supplier', 's')
                ->leftJoin('p.category', 'c')
                ->addSelect('s', 'c')
                ->orderBy('p.name', 'ASC'),
            $page,
            20,
            [
                'distinct' => true,
                'sortFieldAllowList' => ['p.code'],
            ]
        );
    }
}
