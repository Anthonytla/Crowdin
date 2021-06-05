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
        $this->em->remove($traductionSource);
        $this->em->flush();
    }

    public function blockSource($source)
    {
        $source->setBlocked(1);
        $this->em->flush();
    }

    public function unblockSource($source)
    {
        $source->setBlocked(0);
        $this->em->flush();
    }

}
