<?php

namespace App\Service\TraductionSource;

use App\Entity\Lang;
use App\Entity\TraductionSource;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TraductionSourceService
{
    public function __construct(EntityManagerInterface $em, Security $security)
    {
        $this->em = $em;
        $this->security = $security;
    }

    public function createTraductionSource($traductionSource)
    {
        $this->em->persist($traductionSource);
        $this->em->flush();
    }

    public function updateTraductionSource()
    {
        $this->em->flush();
    }

    public function deleteTraductionSource($traductionSource)
    {
        $count = $traductionSource->getProject()->getIsTranslated();
        $count = $count - $traductionSource->getTargets()->count();
        $traductionSource->getProject()->setIsTranslated($count);
        $this->em->remove($traductionSource);
        $this->em->flush();
    }
}
