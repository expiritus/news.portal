<?php

namespace News\MainBundle\Controller;

use News\MainBundle\Entity\Comments;
use News\MainBundle\Entity\OnLine;
use News\MainBundle\Form\CommentsType;
use Padam87\SearchBundle\Filter\Filter;
use Proxies\__CG__\News\MainBundle\Entity\Articles;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Exception\Exception;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $query = $this->getDoctrine()->getRepository('NewsMainBundle:Articles')->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), 5
        );
        return $this->render('NewsMainBundle:Default:index.html.twig', array(
            'articles' => $pagination,
        ));
    }

    public function searchFormAction(){
        return $this->render('NewsMainBundle:Default:search_form.html.twig');
    }

    public function registeredUserAction(){
        $users = $this->getDoctrine()->getRepository('NewsMainBundle:User')->findAll();
        $count_register_users = count($users);

        return $this->render('NewsMainBundle:Default:registered_user.html.twig', array(
            'registered_users' => $count_register_users,
        ));
    }


    public function loginFormAction(Request $request){
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        if (class_exists('\Symfony\Component\Security\Core\Security')) {
            $authErrorKey = Security::AUTHENTICATION_ERROR;
            $lastUsernameKey = Security::LAST_USERNAME;
        } else {
            // BC for SF < 2.6
            $authErrorKey = SecurityContextInterface::AUTHENTICATION_ERROR;
            $lastUsernameKey = SecurityContextInterface::LAST_USERNAME;
        }

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has($authErrorKey)) {
            $error = $request->attributes->get($authErrorKey);
        } elseif (null !== $session && $session->has($authErrorKey)) {
            $error = $session->get($authErrorKey);
            $session->remove($authErrorKey);
        } else {
            $error = null;
        }

        if (!$error instanceof AuthenticationException) {
            $error = null; // The value does not come from the security component.
        }

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get($lastUsernameKey);

        if ($this->has('security.csrf.token_manager')) {
            $csrfToken = $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue();
        } else {
            // BC for SF < 2.4
            $csrfToken = $this->has('form.csrf_provider')
                ? $this->get('form.csrf_provider')->generateCsrfToken('authenticate')
                : null;
        }

        $user = $this->getUser();
        if($user){
            if (!is_object($user) || !$user instanceof UserInterface) {
                throw new AccessDeniedException('This user does not have access to this section.');
            }

            return $this->render('NewsMainBundle:Default:login_form.html.twig', array(
                'user' => $user,
            ));
        }else{
            return $this->render('NewsMainBundle:Default:login_form.html.twig', array(
                'last_username' => $lastUsername,
                'error' => $error,
                'csrf_token' => $csrfToken,
            ));
        }
    }

    public function searchAction(Request $request){
        $word = $request->query->get('search_word');
        $search_result = $this->getDoctrine()->getRepository('NewsMainBundle:Articles')->search($word);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $search_result,
            $request->query->getInt('page', 1), 5
        );

        return $this->render('NewsMainBundle:Default:search.html.twig', array(
            'articles' => $pagination,
        ));
    }



    public function menuAction(){
        $categories = $this->getDoctrine()->getRepository('NewsMainBundle:Category')->findAll();
        return $this->render('NewsMainBundle:Default:category.html.twig', array(
            'categories' => $categories,
        ));
    }


    public function showArticleAction(Request $request, $id){
        $article = $this->getDoctrine()->getRepository('NewsMainBundle:Articles')->find($id);
        if(!$article){
            throw new Exception('Статьи с таким id не найдено');
        }
        $comments = new Comments();
        $all_comments = $this->getDoctrine()->getRepository('NewsMainBundle:Comments')->findBy(array('articleId' => $id));
        $comments_form = $this->createCommentsForm($comments);
        $comments_form->handleRequest($request);
        if($request->getMethod() == 'POST') {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $comment = $comments_form['comments']->getData();
            if(empty($comment)){
                $referer = $request->headers->get('referer');
                return new RedirectResponse($referer);
            }
            $comments->setArticles($article);
            $comments->setComments($comment);
            $comments->setUserName($user);
            $em->persist($comments);
            $em->flush();
            $referer = $request->headers->get('referer');
            return new RedirectResponse($referer);
        }
        $user = $this->getUser();
        return $this->render('NewsMainBundle:Default:show_article.html.twig', array(
            'article' => $article,
            'form' => $comments_form->createView(),
            'all_comments' => $all_comments,
            'user' => $user,
        ));
    }



    private function createCommentsForm(Comments $entity){
        $form = $this->createForm(new CommentsType(), $entity, array(
            'method' => 'POST',
        ));
        $form->add('submit', 'submit', array('label' => 'Отправить', 'attr' => array('class' => 'button')));
        return $form;
    }

    public function articleCategoryAction(Request $request, $id, $category){
        $query = $this->getDoctrine()->getRepository('NewsMainBundle:Articles')->findByCategory($id);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), 10
        );
        return $this->render('NewsMainBundle:Default:index.html.twig', array(
            'articles' => $pagination,
        ));
    }

    public function confirmedAction()
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        return $this->render('NewsMainBundle:Default:confirmed.html.twig', array(
            'user' => $user,
        ));
    }

    public function onlineAction(){
        $ip = $_SERVER['REMOTE_ADDR'];
        $date = new \DateTime();
        $em = $this->getDoctrine()->getManager();
        $user_ip = $em->getRepository('NewsMainBundle:Online')->findOneBy(array('user_ip' => $ip));
        if(!$user_ip){
            $last_visit = new Online();
            $last_visit->setUserIp($ip);
            $last_visit->setLastVisit($date);
            $em->persist($last_visit);
            $em->flush();
        }else{
            $user_ip->setLastVisit($date);
            $em->flush();
        }

        $offline = $this->getDoctrine()->getRepository('NewsMainBundle:Online')->deleteUsersOfflineFromDb();
        $online = $this->getDoctrine()->getRepository('NewsMainBundle:OnLine')->getUsersOnline();
        if($online){
            $count_users_online = count($online);
        }else{
            $count_users_online = 0;
        }

        return $this->render('NewsMainBundle:Default:online.html.twig', array(
            'count_users_online' => $count_users_online,
        ));
    }

}
