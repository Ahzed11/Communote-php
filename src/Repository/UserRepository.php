<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use League\OAuth2\Client\Provider\GoogleUser;
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

    public function findOrCreate($user, string $client) : User {
        return match ($client) {
            'azure_main' => $this->findOrCreateFromAzureOAuth($user),
            'google_main' => $this->findOrCreateFromGoogleOAuth($user),
            default => throw new \Error("Invalid client given"),
        };
    }

    private function findOrCreateFromAzureOAuth(AzureResourceOwner $azureUser): User
    {
        // If User already exist and has oid
        $user = $this->createQueryBuilder('u')
            ->where('u.openID = :openID')
            ->setParameter('openID', 'azure-' . $azureUser->getId())
            ->getQuery()
            ->getOneOrNullResult();

        if ($user) {
            return $user;
        }

        $em = $this->getEntityManager();
        $names = explode(' ', $azureUser->claim('name'));

        // if User already exist but doesn't have an oid
        $user = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $azureUser->claim('email'))
            ->getQuery()
            ->getOneOrNullResult();

        if ($user) {
            $user->setOpenID('azure-'.$azureUser->getId())
                ->addRole('ROLE_VALIDATED')
                ->setFirstName($names[0])
                ->setLastName($names[1]);
            $em->persist($user);
            $em->flush();
            return $user;
        }

        // If User doesn't exist
        $user = (new User())->setOpenID('azure-'.$azureUser->getId())
                            ->setEmail($azureUser->claim('email'))
                            ->setPassword(null)
                            ->addRole('ROLE_VALIDATED')
                            ->setFirstName($names[0])
                            ->setLastName($names[1]);
        $em->persist($user);
        $em->flush();

        return $user;
    }

    private function findOrCreateFromGoogleOAuth(GoogleUser $googleUser): User
    {
        // If User already exist and has oid
        $user = $this->createQueryBuilder('u')
            ->where('u.openID = :openID')
            ->setParameter('openID', 'google-' . $googleUser->getId())
            ->getQuery()
            ->getOneOrNullResult();

        if ($user) {
            return $user;
        }

        $em = $this->getEntityManager();

        // if User already exist but doesn't have an oid
        $user = $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->setParameter('email', $googleUser->getEmail())
            ->getQuery()
            ->getOneOrNullResult();

        if ($user) {
            $user->setOpenID('google-'.$googleUser->getId())
                ->addRole('ROLE_VALIDATED')
                ->setFirstName(empty($googleUser->getFirstName()) ? "firstname" : $googleUser->getFirstName())
                ->setLastName(empty($googleUser->getLastName()) ? "surname" : $googleUser->getLastName());
            $em->persist($user);
            $em->flush();
            return $user;
        }

        // If User doesn't exist
        $user = (new User())->setOpenID('google-'.$googleUser->getId())
            ->setEmail($googleUser->getEmail())
            ->setPassword(null)
            ->addRole('ROLE_VALIDATED')
            ->setFirstName($googleUser->getFirstName())
            ->setLastName($googleUser->getLastName());
        $em->persist($user);
        $em->flush();

        return $user;
    }
}
