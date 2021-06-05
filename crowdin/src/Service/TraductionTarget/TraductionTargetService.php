<?php

namespace App\Service\TraductionTarget;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class TraductionTargetService
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function createTraductionTarget($traduction)
    {
        $traduction->getSource()->getProject()->setIsTranslated(true);
        $this->em->persist($traduction);
        $this->em->flush();
    }
}
