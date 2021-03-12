<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use TheNetworg\OAuth2\Client\Provider\AzureResourceOwner;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findOrCreateFromOAuth(AzureResourceOwner $owner): User
    {
        // If User already exist and has oid
        $user = $this->createQueryBuilder('u')
            ->where('u.azureOID = :oid')
            ->setParameter('oid', $owner->getId())
            ->getQuery()
            ->getOneOrNullResult();

        if ($user) {
            return $user;
        }

        $em = $this->getEntityManager();
        $names = explode(' ', $owner->claim('name'));

        // if User already exist but doesn't have an oid
        $user = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $owner->claim('email'))
            ->getQuery()
            ->getOneOrNullResult();

        if ($user) {
            $user->setAzureOID($owner->getId())
                ->setRoles(['ROLE_VALIDATED'])
                ->setFirstName($names[0])
                ->setLastName($names[1]);
            $em->persist($user);
            $em->flush();
            return $user;
        }

        // If User doesn't exist
        $user = (new User())->setAzureOID($owner->getId())
                            ->setEmail($owner->claim('email'))
                            ->setPassword(null)
                            ->setRoles(['ROLE_VALIDATED'])
                            ->setFirstName($names[0])
                            ->setLastName($names[1]);
        $em->persist($user);
        $em->flush();

        return $user;
    }
}
