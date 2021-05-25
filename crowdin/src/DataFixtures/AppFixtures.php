<?php

namespace App\DataFixtures;

use App\Entity\Lang;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $lang = new Lang();
        $lang->setCode("ab");
        $user = new User();
        $user->setUsername("Antho");
        $user->setPassword($this->passwordEncoder->encodePassword($user, "AnthoAntho")); 
        $user->addLang($lang);
        $manager->persist($user);
        $manager->flush();
    }
}
