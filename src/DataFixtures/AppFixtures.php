<?php

namespace App\DataFixtures;

use App\Factory\CategoryFactory;
use App\Factory\ProductFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $categories = [
            'Voiture',
            'Maison',
            'Jeux vidéos',
            'Vêtements',
            'Meubles',
            'Electroménager'
        ];

        CategoryFactory::createMany(count($categories), static function (int $i) use ($categories) {
            return ['title' => $categories[$i - 1]];
        });

        /** Admin */
        UserFactory::createOne([
            'email' => 'admin@admin.fr',
            'firstname' => 'Admin',
            'lastname' => 'Doe',
            'password' => 'admin',
            'roles' => ['ROLE_ADMIN']
        ]);

        /** Manager */
        UserFactory::createOne([
            'email' => 'manager@manager.fr',
            'firstname' => 'Manager',
            'lastname' => 'Doe',
            'password' => 'manager',
            'roles' => ['ROLE_MANAGER']
        ]);

        /** User */
        UserFactory::createOne([
            'email' => 'user@user.fr',
            'firstname' => 'User',
            'lastname' => 'Doe',
            'password' => 'user',
            'roles' => []
        ]);

        UserFactory::createMany(10);

        ProductFactory::createMany(30);

    }
}
