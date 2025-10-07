<?php

namespace App\DataFixtures;

use App\Entity\Stadium;
use App\Entity\LockerRoom;
use App\Entity\Shirt;
use App\Entity\Member;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Création des LockerRooms et leur association aux membres
        $lockerRoomsData = [
            ['title' => "Hugo's Locker Room", 'member_email' => 'hugo@intv.fr'],
            ['title' => "Emilien's Locker Room", 'member_email' => 'emilien@intv.fr'],
            ['title' => "Max's Locker Room", 'member_email' => 'max@intv.fr'],
        ];

        // Ajout des Shirts
        $shirtsData = [
            ["Hugo's Locker Room", "Bayern Munich"],
            ["Hugo's Locker Room", "PSG"],
            ["Emilien's Locker Room", "France"],
            ["Max's Locker Room", "Manchester United"],
        ];

        foreach ($shirtsData as [$lockerRoomTitle, $team]) {
            $lockerRoom = $manager->getRepository(LockerRoom::class)->findOneBy(['title' => $lockerRoomTitle]);
            $shirt = new Shirt();
            $shirt->setTeam($team);
            $lockerRoom->addShirt($shirt);

            $manager->persist($shirt);
        }

        // Ajout des Stadiums
        $stadiaData = [
            ["Bundesliga", false, "hugo@intv.fr"],
            ["Ligue1", true, "hugo@intv.fr"],
            ["Nations", true, "emilien@intv.fr"],
            ["Premierleague", true, "max@intv.fr"],
        ];

        foreach ($stadiaData as [$description, $published, $email]) {
            $member = $this->getReference($email);
            $stadium = new Stadium();
            $stadium->setDescription($description);
            $stadium->setCreator($member);
            $stadium->setPublished($published);

            $manager->persist($stadium);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MemberFixtures::class,
        ];
    }
}
