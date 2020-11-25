<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2013. All rights reserved.
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
require_once (JPATH_ROOT .'/components/com_uu/libraries/parameter.php');

/**
 * Field controller class.
 */
class UuControllerField extends JControllerForm
{
    function getFieldParams() {
        $found = true;
        $jinput = JFactory::getApplication()->input;

        $type = $jinput->get('type', null);

        if ($type != null) {
            $found = true;
        }

        if (!$found) {
            echo '<div class="field_no_params">'.JText::_('COM_UU_FIELD_PARAMS_NOT_FOUND').'</div>';
        } else {
            $html	= $this->_buildFieldParams( $type );
            echo $html;
        }
        exit;
    }

    /**
     * Read custom params from XML file and render them
     **/
    private function _buildFieldParams( $type , $params = '' ) {
        $xmlPath = JPATH_ROOT . '/components/com_uu/libraries/fields/'. $type.'.xml';

        $html = '';

        jimport('joomla.filesystem.file');
        if( JFile::exists($xmlPath) )
        {
            $params = new UuParameter($params, $xmlPath);
            $html   = $params->render();

        }

        return $html;
    }


}