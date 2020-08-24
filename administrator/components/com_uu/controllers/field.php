<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2015. All rights reserved.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * User controller class.
 */
class UuControllerField extends JControllerForm
{

    function __construct() {
        $this->view_list = 'fields';
        parent::__construct();

        $this->registerTask( 'addgroup' , 'addGroup' );

    }

    function addGroup() {
//        $app = JFactory::getApplication();
//        $app->setUserState('com_uu.edit.field.type', 'group');
        $this->setRedirect(JRoute::_('index.php?option=com_uu&view=field&layout=group', false));
        return true;
    }

}