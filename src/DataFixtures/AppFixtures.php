<?php

namespace App\DataFixtures;


use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    /**
     * AppFixtures constructor.
     * @param $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
        $this->randomUsers($manager);
    }

    private function loadUsers(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        $user = new User();

        $user->setFirstname('Florian')
            ->setLastname('Fazer')
            ->setUsername('flopyeah')
            ->setEmail('florian.fazer@gmail.com')
            ->setStatus(1)
            ->setRoles([User::ROLE_ADMIN])
            ->setPassword($this->passwordEncoder->encodePassword($user, 'testtest'))
        ;

        $manager->persist($user);

        $manager->flush();

        for ($i = 0; $i < rand(20, 30); $i++) {
            $Post = new Post();
            $Post  ->setContent($faker->sentence(rand(10, 100)))
                ->setTitle($faker->sentence(5))
                ->setDateCreated($faker->dateTimeBetween('-4 days'))
                ->setDateModified($faker->dateTimeBetween('-4 days'))
                ->setType(1)
                ->setStatus(1)
                ->setUser($user)
            ;

            $manager->persist($Post);

        }
        $manager->flush();

    }

    private function randomUsers(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $user = new User();

            $user->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setUsername($faker->userName)
                ->setEmail($faker->email)
                ->setStatus(1)
                ->setRoles([User::ROLE_USER])
                ->setPassword($this->passwordEncoder->encodePassword($user, 'testtest'))
            ;

            $manager->persist($user);

            $manager->flush();

            for ($j = 0; $j < rand(1, 10); $j++) {
                $Post = new Post();
                $Post->setContent($faker->sentence(rand(10, 100)))
                    ->setTitle($faker->sentence(5))
                    ->setDateCreated($faker->dateTimeBetween('-4 days'))
                    ->setDateModified($faker->dateTimeBetween('-4 days'))
                    ->setType(1)
                    ->setStatus(1)
                    ->setUser($user)
                    ;

                $manager->persist($Post);
            }
            $manager->flush();
        }
    }
}
