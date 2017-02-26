<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\User;
use AppBundle\Entity\Hobby;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\UserType;
use Doctrine\Common\Collections\ArrayCollection;

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
      $hobbies = New Hobby();

      $hobbies->setHobby("");
      $user->getHobby()->add($hobbies);

      $form = $this->createForm(UserType::class, $user);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $name = $form['name']->getData();
        $biography = $form['biography']->getData();

        $user->setName($name);
        $user->setBiography($biography);
        $hobbies->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->persist($hobbies);
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
      $em = $this->getDoctrine()->getManager();
      $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find($id);

      $user->setName($user->getName());
      $user->setBiography($user->getBiography());
      $user->setImage($user->getImage());

      $originalHobbies = new ArrayCollection();
      foreach ($user->getHobby() as $hobby) {
        $originalHobbies->add($hobby);
      }

      $form = $this->createForm(UserType::class, $user);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

        foreach ($originalHobbies as $hobby) {
          if (false === $user->getHobby()->contains($hobby)) {

            $user->removeHobby($hobby);

          }
        }

        $name = $form['name']->getData();
        $biography = $form['biography']->getData();
        $image = $form['image']->getData();

        $user->setName($name);
        $user->setBiography($biography);
        $user->setImage($image);
        $em->persist($user);
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
      $user = $em->getRepository('AppBundle:User')
        ->find($id);

      foreach ($user->getHobby() as $hobby) {
        $user->removeHobby($hobby);
      }

      $em->remove($user);
      $em->flush();

      $this->addFlash('notice', 'User removed');

      return $this->redirectToRoute('homepage');
    }
}
