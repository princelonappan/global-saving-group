<?php

namespace App\Repository;

use App\Entity\OrderVoucher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderVoucher>
 *
 * @method OrderVoucher|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderVoucher|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderVoucher[]    findAll()
 * @method OrderVoucher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderVoucherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderVoucher::class);
    }

    public function add(OrderVoucher $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OrderVoucher $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function save(OrderVoucher $orderVoucher): OrderVoucher
    {
        // _em is EntityManager which is DI by the base class
        $this->_em->persist($orderVoucher);
        $this->_em->flush();
        return $orderVoucher;
    }
}
