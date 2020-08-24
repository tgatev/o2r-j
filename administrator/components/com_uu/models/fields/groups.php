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
class JFormFieldGroups extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'groups';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getOptions()
	{
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);

        $query->select("a.id as value ,a.name as text");
        $query->from('#__uu_fields AS a');
        $query->where('type = '.$db->quote('group'));
        $query->order('a.ordering ASC');

        $db->setQuery((string)$query);

        // Check for a database error.
        if ($db->getErrorNum()) {
            JError::raiseWarning(500, $db->getErrorMsg());
        }

        $options = $db->loadObjectList();

		return $options;
	}


}