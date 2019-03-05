<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('user@rdc.fr');
        $user->setName('John');
        $user->setFirstName('Doe');
        $user->setPhone('0732239088');
        $user->setPseudo('firstuser');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->encoder->encodePassword($user, 'user'));
        $manager->persist($user);

        $user = new User();
        $user->setEmail('admin@rdc.fr');
        $user->setName('admin');
        $user->setPhone('0755676623');
        $user->setFirstName('super');
        $user->setPseudo('admin');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->encoder->encodePassword($user, 'admin'));
        $manager->persist($user);
        $manager->flush();
    }
}
