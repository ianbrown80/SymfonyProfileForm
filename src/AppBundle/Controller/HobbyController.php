<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HobbyController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function usersAction()
    {
      $users = $this->getDoctrine()
        ->getRepository('AppBundle:users')
        ->findAll();
      return $this->render('AppBundle:Hobby:index.html.twig',
        array('users' => $users));
    }

    /**
     * @Route("/profile/{id}", name="profile")
     */
    public function viewAction($id)
    {
      $user = $this->getDoctrine()
        ->getRepository('AppBundle:users')
        ->find($id);
      return $this->render('AppBundle:Hobby:profile.html.twig',
        array('user' => $user));
    }

    /**
     * @Route("/create", name="create")
     */
    public function createAction(Request $request)
    {
        return $this->render('AppBundle:Hobby:create.html.twig');
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function updateAction(Request $request, $id)
    {
        return $this->render('AppBundle:Hobby:update.html.twig');
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function deleteAction(Request $request, $id)
    {
        return $this->render('AppBundle:Hobby:delete.html.twig');
    }
}
