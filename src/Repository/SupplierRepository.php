<?php

namespace App\Repository;

use App\Entity\Supplier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Supplier>
 */
class SupplierRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly PaginatorInterface $paginator)
    {
        parent::__construct($registry, Supplier::class);
    }

    public function paginateSuppliers(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this
                ->createQueryBuilder('s')
                ->orderBy('s.name', 'ASC'),
            $page,
            20,
            [
                'distinct' => true,
                'sortFieldAllowList' => ['s.name'],
            ]
        );
    }
}
