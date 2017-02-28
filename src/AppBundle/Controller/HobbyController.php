<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use AppBundle\Entity\User;
use AppBundle\Entity\Hobby;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\CreateUserType;
use AppBundle\Form\UpdateUserType;
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

      $form = $this->createForm(CreateUserType::class, $user);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $name = $form['name']->getData();
        $biography = $form['biography']->getData();
        $image = $user->getImage();

        $fileName = md5(uniqid()).'.'.$image->guessExtension();

        $image->move(
          $this->getParameter('images_directory'),
          $fileName
        );

        $user->setName($name);
        $user->setBiography($biography);
        $user->setImage($fileName);
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

      $originalImage = $user->getImage();
      $originalImagePath = $this->getParameter('images_directory').'/'.$originalImage;

      $originalHobbies = new ArrayCollection();
      foreach ($user->getHobby() as $hobby) {
        $originalHobbies->add($hobby);
      }

      $user->setName($user->getName());
      $user->setBiography($user->getBiography());
      $user->setImage(
        new File($this->getParameter('images_directory').'/'.$user->getImage())
      );

      $form = $this->createForm(UpdateUserType::class, $user);
      $form->handleRequest($request);

      if ($form->isSubmitted()) {

        foreach ($originalHobbies as $hobby) {
          if (false === $user->getHobby()->contains($hobby)) {

            $user->removeHobby($hobby);

          }
        }

        $name = $form['name']->getData();
        $biography = $form['biography']->getData();

        if ($user->getImage() != null) {
          $image = $user->getImage();
          $fileName = md5(uniqid()).'.'.$image->guessExtension();
          $image->move(
            $this->getParameter('images_directory'),
            $fileName
          );
          $user->setImage($fileName);
          if (file_exists($originalImagePath)) {
            unlink($originalImagePath);
          }
        } else {
          $user->setImage($originalImage);
        }

        $user->setName($name);
        $user->setBiography($biography);

        $em->persist($user);
        $em->flush();

        $this->addFlash('notice', 'User updated');

        return $this->redirectToRoute('homepage');
      }
    return $this->render('AppBundle:Hobby:update.html.twig',
        array('form' => $form->createView(), 'image' => $originalImage)
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
      $image = $this->getParameter('images_directory').'/'.$user->getImage();
      if (file_exists($image)) {
        unlink($image);
      }

      $em->remove($user);
      $em->flush();

      $this->addFlash('notice', 'User removed');

      return $this->redirectToRoute('homepage');
    }
}
