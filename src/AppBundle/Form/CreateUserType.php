<?php
namespace AppBundle\Form;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/* Build a form for creating anew user. The differences between this form
 * and the update user form are only that the image file is required
 * and the name of the submit button.
 * In hindsight the required attribute could have been changed in javascript
 * and the submit button could have been rendered seperate to the form.
 */


/* The form integrates the seperate hobby form which can be duplicated to
 * add more hobbies.
 */
class CreateUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('attr' => array('class' => 'form-control')));
        $builder->add('biography', CKEditorType::class, array('attr' => array('class' => 'form-control', 'id' => 'biography')));
        $builder->add('image', FileType::class);
        $builder->add('save', SubmitType::class, array('label' => 'Add user', 'attr' => array('class' => 'submit btn btn-primary')));
        $builder->add('hobby', CollectionType::class, array(
            'entry_type' => HobbyType::class,
            'allow_add' => true,
            'allow_delete' => true,
            'by_reference' => false
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}
