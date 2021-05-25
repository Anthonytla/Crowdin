<?php

namespace App\Service\Registration;

use App\Entity\User;
use App\Entity\Lang;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LangRepository;

class RegistrationService
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createUser(User $user, LangRepository $langrepository, string $lang_code, string $lang_name)
    {
        $lang = $langrepository->findOneBy(['code' => $lang_code]);
        if (!$lang) {
            $lang = new Lang();
            $lang->setCode($lang_code);
            $lang->setName($lang_name);
            $this->em->persist($lang);
        }
        $user->addLang($lang);
        $lang->addUser($user);
        $this->em->persist($user);
        $this->em->flush();
    }
}