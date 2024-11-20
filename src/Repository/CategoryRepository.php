<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private readonly PaginatorInterface $paginator)
    {
        parent::__construct($registry, Category::class);
    }

    public function paginateCategories(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this
                ->createQueryBuilder('c')
                ->orderBy('c.name', 'ASC'),
            $page,
            20,
            [
                'distinct' => true,
                'sortFieldAllowList' => ['c.name'],
            ]
        );
    }
}
