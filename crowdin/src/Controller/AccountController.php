<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\LangRepository;
use App\Service\Account\AccountService;
use App\Form\AccountFormType;
use App\Form\AccountLangType;
use App\Form\AccountDeleteLangType;
use App\Security\UsersAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/account")
 */
class AccountController extends AbstractController
{
    public function __construct(AccountService $accountservice, EntityManagerInterface $em)
    {
        $this->accountservice = $accountservice;
        $this->em = $em;
    }
    /**
     * @Route("/", name="account_index", methods={"GET"})
     */
    public function index(): Response
    {
        $user = $this->getUser();
        //dd($user->getLangs());
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
            'user' => $user,
            'langs' => $user->getLangs(),
        ]);
    }

    /**
     * @Route("/edit"), name="account_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, UsersAuthenticator $authenticator): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountFormType::class, $user);
        $form->handleRequest($request);
        $error = false;

        if ($form->isSubmitted() && $form->isValid()) {
            if ($passwordEncoder->isPasswordValid($user, $form->get('plainPassword')->getData())) {
                if ($form->get('newPassword')->getData()){
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('newPassword')->getData()
                        )
                    );
                }
                $this->accountservice->editAccount();
                return $this->redirectToRoute('account_index');
            }
            $error = true;
        }
        return $this->render('account/edit.html.twig', [
            'editform' => $form->createView(),
            'user' => $user,
            'error' => $error,
        ]);
    }
    /**
     * @Route("/lang", name="account_lang", methods={"GET","POST"})
     */
    public function addLang(Request $request, LangRepository $langrepository, UsersAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountLangType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lang_code = $form->get('languageList')->getData();
            $lang_name = $form->get('languageList')->getConfig()->getAttribute('choice_list')->getOriginalKeys()[$lang_code];
            $this->accountservice->addAccountLang($user,$langrepository, $lang_code, $lang_name);
            //dd($guardHandler);
            $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            ); //use guardHandler to authenticate user after potential role change
            return $this->redirectToRoute('account_index');
        }
        return $this->render('account/addLang.html.twig', [
            'langform' => $form->createView(),
        ]);
    }

    /**
     * @Route("/lang/delete", name="account_lang_delete", methods={"GET", "POST"})
     */
    public function deleteLang(Request $request, UsersAuthenticator $authenticator, GuardAuthenticatorHandler $guardHandler)
    {
        $user = $this->getUser();
        $langs = $user->getLangs();
        if (!$langs->count())
            return $this->redirectToRoute('account_index');
        $form = $this->createForm(AccountDeleteLangType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->accountservice->deleteAccountLang($user, $form->get('languageList')->getData());
            $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            ); //use guardHandler to authenticate user after potential role change
            return $this->redirectToRoute('account_index');
        }
        return $this->render('account/deleteLang.html.twig', [
            'langform' => $form->createView(),
        ]);
    }
}
