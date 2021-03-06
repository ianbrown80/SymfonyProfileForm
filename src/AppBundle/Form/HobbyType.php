<?php
namespace AppBundle\Form;

use AppBundle\Entity\Hobby;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/* Hobby form which is integrated multiple times into the user
 * form for each individual hobby.
 */

class HobbyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('hobby', TextType::class, array('attr' => array('class' => 'form-control hobby-input')));
        $builder->add('date', DateType::class, array(
          'widget' => 'choice',
          'years' => range(1900,2017),
          'attr' => array('class' => 'form-control hobby-input')
          )
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Hobby::class,
        ));
    }
}
