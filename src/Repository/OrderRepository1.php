<?php

namespace App\Repository;

use App\Entity\Invitation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invitation>
 *
 * @method Invitation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invitation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invitation[]    findAll()
 * @method Invitation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository1 extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invitation::class);
    }

    public function save(Invitation $invitation): Invitation
    {
        // _em is EntityManager which is DI by the base class
        $this->_em->persist($invitation);
        $this->_em->flush();
        return $invitation;
    }

    public function getInvitation($condition): array
    {
        return $this->findBy($condition);
    }

    public function getInvitationById($invitationId): ?Invitation
    {
        return $this->find($invitationId);
    }
}
