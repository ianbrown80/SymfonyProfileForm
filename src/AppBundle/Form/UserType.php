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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array('attr' => array('class' => 'form-control')));
        $builder->add('biography', CKEditorType::class, array('attr' => array('class' => 'form-control')));
        $builder->add('image', TextType::class, array('attr' => array('class' => 'form-control')));
        $builder->add('save', SubmitType::class, array('label' => 'Add user', 'attr' => array('class' => 'btn btn-primary')));

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