<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * Users list controller class.
 */
class UuControllerFields extends JControllerAdmin
{

    public function __construct($config = array())
    {
        parent::__construct($config);
        $this->registerTask('unrequired', 'required');
        $this->registerTask('unregistration', 'registration');
        $this->registerTask('uneditable', 'editable');
    }
	/**
	 * Proxy for getModel.
	 * @since	1.6
	 */
	public function getModel($name = 'field', $prefix = 'UuModel')
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		return $model;
	}
    
    
	/**
	 * Method to save the submitted ordering values for records via AJAX.
	 *
	 * @return  void
	 *
	 * @since   3.0
	 */
	public function saveOrderAjax()
	{
		// Get the input
		$input = JFactory::getApplication()->input;
		$pks = $input->post->get('cid', array(), 'array');
		$order = $input->post->get('order', array(), 'array');

		// Sanitize the input
		JArrayHelper::toInteger($pks);
		JArrayHelper::toInteger($order);

		// Get the model
		$model = $this->getModel();

		// Save the ordering
		$return = $model->saveorder($pks, $order);

		if ($return)
		{
			echo "1";
		}

		// Close the application
		JFactory::getApplication()->close();
	}

    public function required() {
        $this->toggle('required');
    }

    public function registration() {
        $this->toggle('registration');
    }

    public function editable() {
        $this->toggle('editable');
    }


    private function toggle($field_name)
    {
        $ids        = JRequest::getVar('cid', array(), '', 'array');
        $states = array($field_name => 1, 'un'.$field_name => 0);
        $task     = $this->getTask();
        $state   = JArrayHelper::getValue($states, $task, 0, 'int');
        $model = $this->getModel();
        if (!$model->toggle($ids, $state,$field_name)) {
            JError::raiseWarning(500, $model->getError());
        }
        $this->setRedirect('index.php?option=com_uu&view=fields');
    }

}