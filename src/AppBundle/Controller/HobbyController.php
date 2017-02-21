<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HobbyController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method({"POST", "GET"})
     */
    public function usersAction()
    {
      $users = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->findAll();
      return $this->render('AppBundle:Hobby:index.html.twig',
        array('users' => $users)
      );
    }

    /**
     * @Route("/profile/{id}", name="profile")
     * @Method({"POST", "GET"})
     */
    public function viewAction($id)
    {
      $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find($id);
      return $this->render('AppBundle:Hobby:profile.html.twig',
        array('user' => $user)
      );
    }

    /**
     * @Route("/create", name="create")
     * @Method({"POST", "GET"})
     */
    public function createAction(Request $request)
    {
      $user = new User;
      $form = $this->createFormBuilder($user)
        ->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('biography', CKEditorType::class, array('attr' => array('class' => 'form-control')))
        ->add('image', TextType::class, array('attr' => array('class' => 'form-control')))
        ->add('save', SubmitType::class, array('label' => 'Add user', 'attr' => array('class' => 'btn btn-primary')))
        ->getForm();
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $name = $form['name']->getData();
        $biography = $form['biography']->getData();

        $user->setName($name);
        $user->setBiography($biography);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $this->addFlash('notice', 'User added');

        return $this->redirectToRoute('homepage');

      }
      return $this->render('AppBundle:Hobby:create.html.twig',
        array('form' => $form->createView())
      );
    }

    /**
     * @Route("/update/{id}", name="update")
     */
    public function updateAction(Request $request, $id)
    {
      $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find($id);

        $user->setName($user->getName());
        $user->setBiography($user->getBiography());
        $user->setImage($user->getImage());

        $form = $this->createFormBuilder($user)
          ->add('name', TextType::class, array('attr' => array('class' => 'form-control')))
          ->add('biography', CKEditorType::class, array('attr' => array('class' => 'form-control')))
          ->add('image', TextType::class, array('attr' => array('class' => 'form-control')))
          ->add('save', SubmitType::class, array('label' => 'Update user', 'attr' => array('class' => 'btn btn-primary')))
          ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          $name = $form['name']->getData();
          $biography = $form['biography']->getData();
          $image = $form['image']->getData();

          $em = $this->getDoctrine()->getManager();
          $user = $em->getRepository('AppBundle:User')
            ->find($id);

          $user->setName($name);
          $user->setBiography($biography);
          $user->setImage($image);

          $em->flush();

          $this->addFlash('notice', 'User updated');

          return $this->redirectToRoute('homepage');
        }
        return $this->render('AppBundle:Hobby:update.html.twig',
          array('form' => $form->createView())
        );
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @Method({"GET", "POST", "DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $user = $em->getRepository('AppBundle:user')
        ->find($id);

      $em->remove($user);
      $em->flush();

      $this->addFlash('notice', 'User removed');

      return $this->redirectToRoute('homepage');
    }
}
