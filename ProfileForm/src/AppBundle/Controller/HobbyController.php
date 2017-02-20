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
        return $this->render('AppBundle:Hobby:index.html.twig');
    }

    /**
     * @Route("/profile/{id}", name="profile")
     */
    public function viewAction($id)
    {
        return $this->render('AppBundle:Hobby:view.html.twig');
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
