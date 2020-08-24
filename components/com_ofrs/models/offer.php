<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.21
	@build			26th November, 2019
	@created		5th July, 2019
	@package		Offers
	@subpackage		offer.php
	@author			SMIG <http://fuckitall.info>	
	@copyright		Copyright (C) 2019. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Ofrs Offer Model
 */
class OfrsModelOffer extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_ofrs.offer';

	/**
	 * Model user data.
	 *
	 * @var        strings
	 */
	protected $user;
	protected $userId;
	protected $guest;
	protected $groups;
	protected $levels;
	protected $app;
	protected $input;
	protected $uikitComp;

	/**
	 * @var object item
	 */
	protected $item;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 *
	 * @return void
	 */
	protected function populateState()
	{
		$this->app = JFactory::getApplication();
		$this->input = $this->app->input;
		// Get the itme main id
		$id = $this->input->getInt('id', null);
		$this->setState('offer.id', $id);

		// Load the parameters.
		$params = $this->app->getParams();
		$this->setState('params', $params);
		parent::populateState();
	}

	/**
	 * Method to get article data.
	 *
	 * @param   integer  $pk  The id of the article.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$this->user = JFactory::getUser();
		$this->userId = $this->user->get('id');
		$this->guest = $this->user->get('guest');
		$this->groups = $this->user->get('groups');
		$this->authorisedGroups = $this->user->getAuthorisedGroups();
		$this->levels = $this->user->getAuthorisedViewLevels();
		$this->initSet = true;

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('offer.id');

		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				// Get a db connection.
				$db = JFactory::getDbo();

				// Create a new query object.
				$query = $db->getQuery(true);

				// Get from #__ofrs_offer as a
				$query->select('a.id AS id,a.ad_network_id AS ad_network_id,a.name AS name,a.description AS description, a.preview_url AS preview_url,a.published AS published,a.modified AS modified');
				$query->from($db->quoteName('#__ofrs_offer', 'a'));
				
				// Get from #__ofrs_ad_network as b
				$query->select('b.name AS ad_network_name,b.join_url AS ad_network_join_url, b.id as adnet_id, b.adnet_text_color as adnet_text_color, b.adnet_background_color as adnet_background_color ');

				$query->join('INNER', ($db->quoteName('#__ofrs_ad_network', 'b')) . ' ON (' . $db->quoteName('a.ad_network_id') . ' = ' . $db->quoteName('b.id') . ')');
				
				// Get from #__ofrs_offer as c
				$query->select('COUNT(b.id) AS offer_cnt');
				$query->join('INNER', ($db->quoteName('#__ofrs_offer', 'c')) . ' ON (' . $db->quoteName('a.ad_network_id') . ' = ' . $db->quoteName('c.ad_network_id') . ')');

				// Get from #__ofrs_offer_payout as d
				$query->select('d.type AS ofrs_offer_payout_payout_type,d.payout_eur AS ofrs_offer_payout_payout_eur,d.payout_usd AS ofrs_offer_payout_payout_usd,d.display AS ofrs_offer_payout_payout_display');
				$query->join('LEFT', ($db->quoteName('#__ofrs_offer_payout', 'd')) . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('d.offer_id') . ')');

				// Get from #__ofrs_payout_type as e
				$query->select('e.name AS ofrs_payout_type_name');
				$query->join('LEFT', ($db->quoteName('#__ofrs_payout_type', 'e')) . ' ON (' . $db->quoteName('d.type') . ' = ' . $db->quoteName('e.id') . ')');

				// Add countries for the offer
                $query->select('GROUP_CONCAT(DISTINCT(h.code) SEPARATOR \', \') AS geo_targeting');
                $query->join('LEFT', ($db->quoteName('#__ofrs_offer_country', 'f')) . 'ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('f.offer_id') . ')');
                $query->join('LEFT', ($db->quoteName('#__ofrs_country', 'h')) . 'ON (' . $db->quoteName('f.country_id') . ' = ' . $db->quoteName('h.id') . ')');

                $query->select('GROUP_CONCAT(DISTINCT(j.name) SEPARATOR \', \') AS verticals');
                $query->join('LEFT', ($db->quoteName('#__ofrs_offer_vertical', 'i')) . 'ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('i.offer_id') . ')');
                $query->join('LEFT', ($db->quoteName('#__ofrs_vertical', 'j')) . 'ON (' . $db->quoteName('i.vertical_id') . ' = ' . $db->quoteName('j.id') . ')');

                // Add image to Query result
                $query->select('OCTET_LENGTH(lp_thumbnail) as image_octet_len ');
                $query->join('LEFT', ($db->quoteName('#__ofrs_offer_preview', 'preview')) . 'ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('preview.offer_id') . ')');
                
                // Get logged user
                $user = JFactory::getUser();
                $user_id = $user->id;
                if ($user_id) {
                    $query->select('NOT ISNULL(om.offer_id) AS is_monitored');
                    $query->join('LEFT', 'ofrs_offer_monitor om ON (a.id = om.offer_id AND om.user_id = ' . $user_id . ')');
                } else {
                    $query->select('0 AS is_monitored');
                }


                $query->where('a.id = ' . (int) $pk);
                $query->group('a.id');
                
//                 echo $query->dump();
//                 die();

                // Reset the query using our newly populated query object.
				$db->setQuery($query);
				// Load the results as a stdClass object.
				$data = $db->loadObject();
                if (empty($data))
				{
					$app = JFactory::getApplication();
					// If no data is found redirect to default page and show warning.
					$app->enqueueMessage(JText::_('COM_OFRS_NOT_FOUND_OR_ACCESS_DENIED'), 'warning');
					$app->redirect(JURI::root());
					return false;
				}

                // Load the JEvent Dispatcher
                JPluginHelper::importPlugin('content');
                $this->_dispatcher = JEventDispatcher::getInstance();

				// Check if item has params, or pass whole item.
				$params = (isset($data->params) && OfrsHelper::checkJson($data->params)) ? json_decode($data->params) : $data;
				// Make sure the content prepare plugins fire on description
				$_description = new stdClass();
				$_description->text =& $data->description; // value must be in text
				// Since all values are now in text (Joomla Limitation), we also add the field name (description) to context
				$this->_dispatcher->trigger("onContentPrepare", array('com_ofrs.offer_profile.description', &$_description, &$params, 0));

				// set data object to item.
				$this->_item[$pk] = $data;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseWaring(404, $e->getMessage());
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

/***[JCBGUI.dynamic_get.php_after_getitem.43.$$$$]***/
//		$data->verticals = OfrsHelper::arrayToString($data->verticals, ', ', 'vertical');
//		$data->geo_targeting = OfrsHelper::arrayToString($data->geo_targeting, ',', 'country','id','code');
/***[/JCBGUI$$$$]***/


		return $this->_item[$pk];
	}

    public function getImageData($pk=null){
        $this->user = JFactory::getUser();
        $this->userId = $this->user->get('id');
        $this->guest = $this->user->get('guest');
        $this->groups = $this->user->get('groups');
        $this->authorisedGroups = $this->user->getAuthorisedGroups();
        $this->levels = $this->user->getAuthorisedViewLevels();
        $this->initSet = true;

        // If Empty get from cache
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('offer.id');

        if ($this->_item === null)
        {
            $this->_item = array();
        }

        if (!isset($this->_item[$pk]))
        {
            try
            {
                // Get a db connection.
                $db = JFactory::getDbo();

                // Create a new query object.
                $query = $db->getQuery(true);

                // Get Image
                $query->select('offer_id, lp_thumbnail');
                $query->from($db->quoteName('#__ofrs_offer_preview'));


                $query->where('offer_id = ' . (int) $pk);

                // Reset the query using our newly populated query object.
                $db->setQuery($query);
                // Load the results as a stdClass object.
                $data = $db->loadObject();

                if(empty($data)){
                    $data = null;
                }
                // set data object to item
                $this->_item[$pk] = $data;
            }
            catch (Exception $e)
            {
                if ($e->getCode() == 404)
                {
                    // Need to go thru the error handler to allow Redirect to work.
                    JError::raiseWaring(404, $e->getMessage());
                }
                else
                {
                    $this->setError($e);
                    $this->_item[$pk] = false;
                }
            }
        }

        return $this->_item[$pk];
    }
/***[JCBGUI.site_view.php_model.29.$$$$]***/
// none/***[/JCBGUI$$$$]***/

}
