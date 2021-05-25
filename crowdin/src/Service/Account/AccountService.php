<?php

namespace App\Service\Account;

use App\Entity\User;
use App\Entity\Lang;
use App\Repository\LangRepository;
use Doctrine\ORM\EntityManagerInterface;

class AccountService
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function editAccount()
    {
        $this->em->flush();
    }

    private function addTranslatorRole(User $user)
    {
        if ($user->getLangs->count() > 1)
            $user->setRoles(['ROLE_TRANSLATOR']);
        return $user;
    }
    
    public function addAccountLang(User $user, LangRepository $langrepository, string $lang_code, string $lang_name)
    {
        $lang = $langrepository->findOneBy(['code' => $lang_code]);
        if (!$lang) {
            $lang = new Lang();
            $lang->setCode($lang_code);
            $lang->setName($lang_name);
            $this->em->persist($lang);
        }
        $user->addLang($lang);
        if ($user->getLangs()->count() == 2)
            $user->setRoles(['ROLE_TRANSLATOR']);
        $this->em->flush();
    }

    public function deleteAccountLang(User $user, Lang $lang)
    {
        $user->removeLang($lang);
        if ($user->getLangs()->count() == 1)
            $user->setRoles([]);
        $this->em->flush();
    }
}