<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use AppBundle\Entity\User;
use AppBundle\Entity\Hobby;
use AppBundle\Form\CreateUserType;
use AppBundle\Form\UpdateUserType;
use Doctrine\Common\Collections\ArrayCollection;

class HobbyController extends Controller
{
    /**
     * Gets a list of users and displays them in a table
     *
     * @Route("/", name="homepage")
     * @Method({"GET"})
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
     * Gets an individual users profile and displays it on a page.
     *
     * @Route("/profile/{id}", name="profile")
     * @Method({"GET"})
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
     * Creates a new user profile made up of name, image,
     * biography and a collection of Hobbies made up of hobby
     * name and date started
     *
     * @Route("/create", name="create")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request)
    {
      //Create a new instance of a user and a hobby
      $user = new User;
      $hobbies = New Hobby();

     /* Give the hobby a value of an empty string and
      * add it to the user object.
      * This step is required to render an empty hobby
      * form on the page.
      */
      $hobbies->setHobby("");
      $user->getHobby()->add($hobbies);

      /*
       * Create the form from the form builder and generate
       * the attributes needed for the AJAX submission that
       * is needed to allow the image to be uploaded using
       * drag and drop
       */
      $form = $this->createForm(CreateUserType::class, $user,
        array(
          'method' => 'POST',
          'action' => $this->generateUrl('create'),
          'attr' => array(
            'class' => 'form'
          )
        )
      );
      $form->handleRequest($request);


      if ($form->isSubmitted()) {

        /* Get the image upload, rename it to a new random
         * filename and move it to the images file on the server.
         * Then set the database entry to the new name of the
         * file.
         */
        $image = $user->getImage();
        $fileName = md5(uniqid()).'.'.$image->guessExtension();
        $image->move(
          $this->getParameter('images_directory'),
          $fileName
        );
        $user->setImage($fileName);

        // Link the user with the hobbies so they can be stored
        // in the database.
        $hobbies->setUser($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->persist($hobbies);
        $em->flush();

        $this->addFlash('notice', 'User added');

        return new Response();

      }
      return $this->render('AppBundle:Hobby:create.html.twig',
        array('form' => $form->createView())
      );
    }

    /**
     * Get the selected user and place its data into a form
     * to allow the profile to be updated.
     *
     * @Route("/update/{id}", name="update")
     * @Method({"GET", "POST"})
     */
    public function updateAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $user = $this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->find($id);

      /* Get the path of the current image stored for the profile.
       * This is used to remove the image from the server if a
       * new image is uploaded.
       */
      $originalImage = $user->getImage();
      $originalImagePath = $this->getParameter('images_directory').'/'.$originalImage;

      /* Get a list of the currently saved hobbies for the user
       * to be checked against the updated hobbies and to be
       * removed if necessary.
       */
      $originalHobbies = new ArrayCollection();
      foreach ($user->getHobby() as $hobby) {
        $originalHobbies->add($hobby);
      }

      /* The user form is expecting a file for the image however
       * the data is stored as a string so this needs to be
       * converted to the file stored on the server.
       */
      $user->setImage(
        new File($this->getParameter('images_directory').'/'.$user->getImage())
      );

      /*
       * Create the form from the form builder and generate
       * the attributes needed for the AJAX submission that
       * is needed to allow the image to be uploaded using
       * drag and drop
       */
      $form = $this->createForm(UpdateUserType::class, $user,
        array(
          'method' => 'POST',
          'action' => $this->generateUrl('update',
            array(
              'id' => $id
            )
          ), 'attr' => array(
            'class' => 'form'
          ),
        )
      );
      $form->handleRequest($request);

      if ($form->isSubmitted()) {

        /* Check the submitted hobbies against the hobbies
         * against the hobbies stored on the database and
         * delete them if necessary.
         */
        foreach ($originalHobbies as $hobby) {
          if (false === $user->getHobby()->contains($hobby)) {
            $user->removeHobby($hobby);
          }
        }

        $name = $form['name']->getData();
        $biography = $form['biography']->getData();

        /* If an image has been uploaded, give it a unique name
         * to be stored in the database and remove the existing
         * image. Otherwise, set the image back to the string
         * of its file name to be stored back in the database.
         */
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

        $em->persist($user);
        $em->flush();

        $this->addFlash('notice', 'User updated');

        return new Response();
      }
    return $this->render('AppBundle:Hobby:update.html.twig',
        array('form' => $form->createView(), 'image' => $originalImage)
      );
    }

    /**
     * Delete the user from the database as well the
     * users image and all associated hobbies.
     * @Route("/delete/{id}", name="delete")
     * @Method({"GET", "POST", "DELETE"})
     */
    public function deleteAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $user = $em->getRepository('AppBundle:User')
        ->find($id);

      // Itterate through the hobbies and remove them
      foreach ($user->getHobby() as $hobby) {
        $user->removeHobby($hobby);
      }

      // Remove the profile image from the server
      $image = $this->getParameter('images_directory').'/'.$user->getImage();
      if (file_exists($image)) {
        unlink($image);
      }

      //Then remove the user itself
      $em->remove($user);
      $em->flush();

      $this->addFlash('notice', 'User removed');

      return $this->redirectToRoute('homepage');
    }
}
