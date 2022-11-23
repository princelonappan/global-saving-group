<?php

namespace App\Repository;

use App\Entity\Invitation;
use App\Entity\OrderVoucher;
use App\Entity\Voucher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @extends ServiceEntityRepository<Voucher>
 *
 * @method Voucher|null find($id, $lockMode = null, $lockVersion = null)
 * @method Voucher|null findOneBy(array $criteria, array $orderBy = null)
 * @method Voucher[]    findAll()
 * @method Voucher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VoucherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voucher::class);
    }

    public function save(Voucher $voucher): Voucher
    {
        // _em is EntityManager which is DI by the base class
        $this->_em->persist($voucher);
        $this->_em->flush();
        return $voucher;
    }

    public function getVoucherInfo($condition): array
    {
        return $this->findBy($condition);
    }

    public function getVoucherById($voucherID): ?Voucher
    {
        return $this->find($voucherID);
    }

    public function add(Voucher $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getActiveVouchers($status, $limit, $offset): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT v.id, v.description, v.code, v.type, v.discount_amount, 
         v. expires_at, v.status, v.created_date FROM App\Entity\Voucher v WHERE v.expires_at > 
                              CURRENT_TIMESTAMP() AND v.status = :status')->setParameter('status', $status)
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $query->getResult();
    }

    public function getExpiredVouchers($limit, $offset): array
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT v.id, v.description, v.code, v.type, v.discount_amount, 
         v. expires_at, v.status, v.created_date FROM App\Entity\Voucher v WHERE v.expires_at <
                              CURRENT_TIMESTAMP()')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $query->getResult();
    }

    public function remove(Voucher $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function updateOrderStatus($voucher, $status)
    {
        $voucher->setStatus($status);
        $this->_em->flush();
    }

    public function getVoucherDetails($voucher, $status)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT v.id, v.description, v.code, v.type, v.discount_amount, 
         v. expires_at, v.status, v.created_date FROM App\Entity\Voucher v  LEFT JOIN WHERE 
          v.status = :status AND code = :code')
            ->setParameter('status', $status)
            ->setParameter('code', $voucher);
        return $query->getResult();
//        return $this->createQueryBuilder('voucher ')
//            ->leftJoin(OrderVoucher::class, 'ov', Expr\Join::WITH, 'ov.order_id = voucher.id')
//            ->andWhere('voucher.code = :code')
//            ->setParameter('code', $voucher)
//            ->getQuery()
//            ->execute();
//
//        return $this->createQueryBuilder('voucher')
//            ->andWhere('voucher.code = :code')
//            ->leftJoin('cat.fortuneCookies', 'fc')
//            ->setParameter('searchTerm', '%'.$term.'%')
//            ->getQuery()
//            ->execute();
    }

//    /**
//     * @return Voucher[] Returns an array of Voucher objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Voucher
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
