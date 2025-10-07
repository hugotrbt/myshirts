<?php

namespace App\DataFixtures;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Member;
use App\Entity\LockerRoom;


class MemberFixtures extends Fixture
{

        private UserPasswordHasherInterface $hasher;

        public function __construct(UserPasswordHasherInterface $hasher)
        {
                $this->hasher = $hasher;
        }

        public function load(ObjectManager $manager): void
        {
            // Données pour les membres
            $membersData = [
                ['email' => 'hugo@intv.fr', 'password' => '123456', 'roles' => ['ROLE_ADMIN'], 'lockerRoom' => "Hugo's Locker Room"],
                ['email' => 'emilien@intv.fr', 'password' => '123456', 'roles' => ['ROLE_MEMBER'], 'lockerRoom' => "Emilien's Locker Room"],
                ['email' => 'max@intv.fr', 'password' => '123456', 'roles' => ['ROLE_MEMBER'], 'lockerRoom' => "Max's Locker Room"],
            ];
    

            foreach ($membersData as $memberData) {
                    $member = new Member();
                    $member->setEmail($memberData['email']);
                    $hasedpassword = $this->hasher->hashPassword($member, $memberData['password']);
                    $member->setPassword($hasedpassword);
                    $member->setRoles($memberData['roles']);

                    $lockerRoom = new LockerRoom();
                    $lockerRoom->setTitle($memberData['lockerRoom']);
                    
        
                    // Association entre Member et LockerRoom
                    $member->setLockerRoom($lockerRoom);
        
                    $manager->persist($lockerRoom);
                    $manager->persist($member);
        
                    // Ajout de la référence pour utilisation dans d'autres fixtures
                    $this->addReference($memberData['email'], $member);

                }
            $manager->flush();
        }
}
