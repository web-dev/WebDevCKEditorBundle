<?php namespace WebDev\CKEditorBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use WebDev\CKEditorBundle\CKEditor\InstanceManager;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class HtmlType
    extends TextareaType
{
    public function __construct(InstanceManager $instanceManager)
    {
        $this->instanceManager = $instanceManager;
    }

    /**
     * @var WebDev\CKEditorBundle\CKEditor\InstanceManager
     */
    protected $instanceManager;

    /**
     * {@inheritdoc}
     */
    public function getParent(array $options)
    {
        return 'textarea';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'html';
    }

    /**
     * Builds the form view.
     *
     * This method gets called for each type in the hierarchy starting form the
     * top most type.
     * Type extensions can further modify the view.
     *
     * @see FormTypeExtensionInterface::buildView()
     *
     * @param FormView      $view The view
     * @param FormInterface $form The form
     */
    public function buildView(FormView $view, FormInterface $form)
    {
        parent::buildView($view,$form);
        $this->instanceManager->registerFormView($view);
    }
}