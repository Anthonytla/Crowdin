<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Lang;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Security;

class AccountDeleteLangType extends AbstractType
{
    public function __construct(Security $security, EntityManagerInterface $em)
    {
        $this->security = $security;
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $user = $this->security->getUser();
        $langs = $user->getLangs();
        $tab = [];
        foreach ($langs as $lang) {
            $tab[$lang->getName()] = $lang;
        }
        $builder
            ->add('languageList', ChoiceType::class, [
                'choices' => $tab,
                'mapped' => false,
                'required' => true,
            ])
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}