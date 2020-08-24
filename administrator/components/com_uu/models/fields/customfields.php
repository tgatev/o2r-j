<?php
/**
 * @package     com_uu
 * @copyright   Copyright (C) 2012. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 */

defined('JPATH_BASE') or die;

//jimport('joomla.html.html');
//jimport('joomla.form.formfield');
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');


/**
 * Supports an HTML select list of fields
 */
class JFormFieldCustomfields extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'customfields';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getOptions()
	{
		return $this->getFieldType();
	}

    private function getFieldType()
    {
        static $types = false;

        if( !$types )
        {
            $path	= JPATH_ROOT . '/components/com_uu/libraries/fields/customfields.xml';

            if($xml = simplexml_load_file($path)) {
                $data	= array();

                foreach( $xml as $field )
                {
                    $type	= (string)$field->type;
                    $name	= (string)$field->name;
                    $data[ $type ]	= $name;
                }
                $types	= $data;

            } else {
                throw new Exception(JText::_('COM_UU_CUSTOMFIELDS_NOT_FOUND.'));
            }
        }
        return $types;
    }

}