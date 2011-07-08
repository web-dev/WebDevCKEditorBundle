<?php namespace WebDev\CKEditorBundle\CKEditor;

use Symfony\Component\Form\FormView;

/**
 * CKEditor Instance Manager
 * 
 * Manages the CKEditor instances that have rendered to the screen.
 *
 * @author Josiah <josiah@web-dev.com.au>
 */
class InstanceManager
{
    /**
     * Form views registered with this instance manager
     * 
     * @var Symfony\Component\Form\FormView[]
     */
    protected $formViews = array();

    /**
     * Registers a form view with the instance manager
     *
     * @param Symfony\Component\Form\FormView $view
     */
    public function registerFormView(FormView $view)
    {
        $this->formViews[] = $view;
    }

    /**
     * Indicates whether this instance manager has instances
     *
     * @return bool TRUE if this manager has instances; FALSE otherwise
     */
    public function hasInstances()
    {
        if(count($this->formViews)) return true;
        return false;
    }
    
    /**
     * Returns an array of instances from this manager
     *
     * @return array
     */
    public function getInstances()
    {
        $instances = array();
        foreach($this->formViews as $view)
        {
            $instances[] = array(
                'id' => $view->get('id'),
                'toolbar' => 'Basic');
        }
        return $instances;
    }
}