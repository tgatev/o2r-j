<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.21
	@build			26th November, 2019
	@created		5th July, 2019
	@package		Offers
	@subpackage		adnet.php
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
use OfrsHelper;
/**
 * Ofrs Adnet Model
 */
class OfrsModelAdnet extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_ofrs.adnet';

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
		$this->setState('adnet.id', $id);

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

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('adnet.id');

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

				// Get from #__ofrs_ad_network as a
				$query->select('a.id AS id,a.asset_id AS asset_id,a.name AS name,a.tracking_platform_id AS tracking_platform_id,a.currency_id AS currency_id,a.api_params AS api_params,a.import_setup AS import_setup,a.description AS description,a.join_url AS join_url,a.min_payment_amt AS min_payment_amt,a.payment_frequency AS payment_frequency,a.account_login AS account_login,a.account_created AS account_created,a.login_url AS login_url,a.account_password AS account_password,a.stats_tz AS stats_tz,a.payment_method AS payment_methods,a.published AS published,a.created_by AS created_by,a.modified_by AS modified_by,a.created AS created,a.modified AS modified,a.version AS version,a.hits AS hits,a.ordering AS ordering,a.adnet_logo AS adnet_logo, a.adnet_text_color AS adnet_text_color, a.adnet_background_color AS adnet_background_color');
				$query->from($db->quoteName('#__ofrs_ad_network', 'a'));

				// Get from #__ofrs_tracking_platform as b
				$query->select('b.name AS tracking_platform_name');
				$query->join('LEFT OUTER', ($db->quoteName('#__ofrs_tracking_platform', 'b')) . ' ON (' . $db->quoteName('a.tracking_platform_id') . ' = ' . $db->quoteName('b.id') . ')');

				// Get from #__ofrs_tracking_platform as b
				$query->select('currency.symbol AS currency_symbol');
				$query->join('LEFT OUTER', ($db->quoteName('#__ofrs_currency', 'currency')) . ' ON (' . $db->quoteName('a.currency_id') . ' = ' . $db->quoteName('currency.id') . ')');

				// Get from #__ofrs_offer as c
				$query->select('COUNT(c.id) AS offer_count');
				$query->join('LEFT OUTER', ($db->quoteName('#__ofrs_offer', 'c')) . ' ON (' . $db->quoteName('a.id') . ' = ' . $db->quoteName('c.ad_network_id') . ')');

				// Get from #__ofrs_offer_payout as d
				$query->select('d.type AS ofrs_offer_payout_payout_type');
				$query->join('LEFT OUTER', ($db->quoteName('#__ofrs_offer_payout', 'd')) . ' ON (' . $db->quoteName('c.id') . ' = ' . $db->quoteName('d.offer_id') . ')');

				// Get from #__ofrs_payout_type as e
				$query->select('GROUP_CONCAT(DISTINCT e.name) AS payout_types');
				$query->join('LEFT OUTER', ($db->quoteName('#__ofrs_payout_type', 'e')) . ' ON (' . $db->quoteName('d.type') . ' = ' . $db->quoteName('e.id') . ')');
				$query->where('a.id = ' . (int) $pk);
				$query->group('a.id');

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
				// Check if we can decode payment_methods
				if (OfrsHelper::checkJson($data->payment_methods))
				{
					// Decode payment_methods
					$data->payment_methods = json_decode($data->payment_methods, true);
				}
				// Check if item has params, or pass whole item.
				$params = (isset($data->params) && OfrsHelper::checkJson($data->params)) ? json_decode($data->params) : $data;
				// Make sure the content prepare plugins fire on description
				$_description = new stdClass();
				$_description->text =& $data->description; // value must be in text
				// Since all values are now in text (Joomla Limitation), we also add the field name (description) to context
				$this->_dispatcher->trigger("onContentPrepare", array('com_ofrs.adnet_profile.description', &$_description, &$params, 0));
				// Make sure the content prepare plugins fire on api_params
				$_api_params = new stdClass();
				$_api_params->text =& $data->api_params; // value must be in text
				// Since all values are now in text (Joomla Limitation), we also add the field name (api_params) to context
				$this->_dispatcher->trigger("onContentPrepare", array('com_ofrs.adnet_profile.api_params', &$_api_params, &$params, 0));

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

/***[JCBGUI.dynamic_get.php_after_getitem.42.$$$$]***/
$data->payment_methods = $this->getPaymentMethodsDisplay($data->payment_methods);/***[/JCBGUI$$$$]***/


		return $this->_item[$pk];
	}


/***[JCBGUI.site_view.php_model.28.$$$$]***/
	public function getPaymentMethodsDisplay($pms) {
	    $payment_methodArray = array(
	        'P' => 'PayPal',
	        'C' => 'Check',
	        'W' => 'Wire',
	        'A' => 'ACH',
	        'D' => 'Direct Deposit'
	    );

	    foreach($pms AS $pm) {
	        if (isset($ret))
	            $ret .= ',';
	        $ret .= $payment_methodArray[$pm];
	    }
	    return $ret;
	}/***[/JCBGUI$$$$]***/

    /**
     * @param null $pk primary key
     * @return mixed
     */
    public function getLogo($pk=null){
        $this->user = JFactory::getUser();
        $this->userId = $this->user->get('id');
        $this->guest = $this->user->get('guest');
        $this->groups = $this->user->get('groups');
        $this->authorisedGroups = $this->user->getAuthorisedGroups();
        $this->levels = $this->user->getAuthorisedViewLevels();
        $this->initSet = true;

        // If Empty get from cache
        $pk = (!empty($pk)) ? $pk : (int) $this->getState('adnet.id');

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
                $query->select('id, adnet_logo');
                $query->from($db->quoteName('#__ofrs_ad_network'));


                $query->where('id = ' . (int) $pk);

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

}
