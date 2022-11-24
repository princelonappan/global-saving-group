<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function add(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Order $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function save(Order $order): Order
    {
        // _em is EntityManager which is DI by the base class
        $this->_em->persist($order);
        $this->_em->flush();
        return $order;
    }

    public function getOrders($limit, $offset)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT o.id, o.amount, o.status, c.name, o.status,
                v.code, v.description, v.type, v.discount_amount, o.created_date
                FROM App\Entity\Order o LEFT JOIN 
                App\Entity\OrderVoucher ov WITH (o.id = ov.order_id)
                LEFT JOIN App\Entity\Voucher v WITH (v.id = ov.voucher_id)
                LEFT JOIN App\Entity\Customer c WITH (o.customer_id= c.id) ')
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        return $query->getResult();
    }
}
