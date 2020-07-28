<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.21
	@build			26th November, 2019
	@created		5th July, 2019
	@package		Offers
	@subpackage		controller.php
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
use Joomla\CMS\Response\JsonResponse;
use Joomla\CMS\Http\Response;
include_once(JPATH_SITE.DIRECTORY_SEPARATOR.'administrator'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_geolocator'.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'helper.php');
/**
 * Ofrs Component Controller
 */
class OfrsController extends JControllerLegacy
{
	CONST OFFERS_BASEURL = 'index.php?option=com_ofrs&view=offers&Itemid=215';
	CONST ADNETS_BASEURL = 'index.php?option=com_ofrs&view=adnets&Itemid=1116';
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   boolean  $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 */
	function display($cachable = false, $urlparams = false)
	{

        $cachable = true;
        // set default view if not set
        $view		= $this->input->getCmd('view', '');
        $this->input->set('view', $view);
        $isEdit		= $this->checkEditView($view);
        $layout		= $this->input->get('layout', null, 'WORD');
        $id			= $this->input->getInt('id');
        // $cachable	= true; (TODO) working on a fix [gh-238](https://github.com/vdm-io/Joomla-Component-Builder/issues/238)

		// Redirect
		// When the home page have no filter parameters to same with filter parameters
		// NOTE! This is fixing cashing issues based on displaying session data

		// Note not working well in controller, controller is loaded after cacheing
//		if(!$view || $view == 'offers' || $view == 'adnets' ){
//			$filter = $this->input->get('filter', array()); // fetch input
//
//			$method_name = 'get'.ucfirst($view).'Filter';
//			$session_filter = OfrsHelper::$method_name();
//
//			if(count($filter) == 0 && count($session_filter['filter']) > 0 ){
//				//
//				$url = '';
//				switch( $view ) {
//					case 'adnets' : $url = self::ADNETS_BASEURL.'&'.OfrsHelper::$method_name(true) ; break;
//					case null : ;
//					case 'offers' : $url = self::OFFERS_BASEURL.'&'.OfrsHelper::$method_name(true); break;
//				}
//
//				$this->setRedirect(JRoute::_($url, false));
//			}
//		}

		// insure that the view is not cashable if edit view or if user is logged in
		$user = JFactory::getUser();
		if ($user->get('id') || $isEdit)
		{
			$cachable = false;
		}
		 // Geo Locator
        if (class_exists('geoHelper')){
            $country = geoHelper::getCountry();
            JFactory::getApplication()->setHeader('GeoLocation' , $country);
            $isEU = (string) geoHelper::isEUCountry($country) ;
            JFactory::getApplication()->setHeader('GeoIsEU' , $isEU );
            JFactory::getApplication()->setHeader('filterAdnets' , OfrsHelper::getAdnetsFilter(true) );
            JFactory::getApplication()->setHeader('filterOffers' , OfrsHelper::getOffersFilter(true) );
            JFactory::getApplication()->sendHeaders();
        }

		// Check for edit form.
		if($isEdit)
		{
			if ($layout == 'edit' && !$this->checkEditId('com_ofrs.edit.'.$view, $id))
			{
				// Somehow the person just went to the form - we don't allow that.
				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
				$this->setMessage($this->getError(), 'error');
				// check if item was opend from other then its own list view
				$ref 	= $this->input->getCmd('ref', 0);
				$refid 	= $this->input->getInt('refid', 0);
				// set redirect
				if ($refid > 0 && OfrsHelper::checkString($ref))
				{
					// redirect to item of ref
					$this->setRedirect(JRoute::_('index.php?option=com_ofrs&view='.(string)$ref.'&layout=edit&id='.(int)$refid, false));
				}
				elseif (OfrsHelper::checkString($ref))
				{
					// redirect to ref
					 $this->setRedirect(JRoute::_('index.php?option=com_ofrs&view='.(string)$ref, false));
				}
				else
				{
					// normal redirect back to the list default site view
					$this->setRedirect(JRoute::_('index.php?option=com_ofrs&view=', false));
				}
				return false;
			}
		}

		// we may need to make this more dynamic in the future. (TODO)
		$safeurlparams =
            array(
			'catid' => 'INT',
			'id' => 'INT',
			'cid' => 'ARRAY',
			'year' => 'INT',
			'month' => 'INT',
			'limit' => 'UINT',
			'limitstart' => 'UINT',
			'showall' => 'INT',
			'return' => 'BASE64',
                //limitstart=0
                //&filter[sort_by]=49&filter[sort_direction]=DESC&filter[count_per_page]=20
                //&filter[search]=
                //&filter[verticals][]=62&filter[verticals][]=13&filter[verticals][]=58&filter[payout_type][]=C
                'filter[sort_by]' => 'STRING',
                'filter[sort_direction]' => 'STRING',
                'filter[count_per_page]' => 'INT',
                'filter[search]' => 'STRING',
			'filter' => 'ARRAY',
			'filter_order' => 'CMD',
			'filter_order_Dir' => 'CMD',
			'filter-search' => 'STRING',
			'print' => 'BOOLEAN',
			'lang' => 'CMD',
			'Itemid' => 'INT',

            );
//            $this->input->getArray();

		// should these not merge?
		if (OfrsHelper::checkArray($urlparams))
		{
			$safeurlparams = OfrsHelper::mergeArrays(array($urlparams, $safeurlparams));
		}
		return parent::display($cachable, $safeurlparams);
	}

	public function getImage(){
        $id = $this->checkInputId();
        $view = $this->input->get('view', null);


        // Name convention is the view name is the same as model.
        $model = $this->getModel($view);

        if($view == 'offer' ) {
            $response = $model->getImageData($id);
            echo $response->lp_thumbnail;
        }elseif ($view == 'adnet'){
            if(! $response = $model->getLogo($id)) throw new Exception(JText::_('JERROR_LAYOUT_PAGE_NOT_FOUND'), 404);
            echo $response->adnet_logo;
        }

        // send response
        header('Content-type: image/png');
        header('Cache-Control: public, max-age=31536000');
        die();
    }


    protected function checkEditView($view)
	{
		if (OfrsHelper::checkString($view))
		{
			$views = array(

				);
			// check if this is a edit view
			if (in_array($view,$views))
			{
				return true;
			}
		}
		return false;
	}

	private function checkInputId(){
        if( !$id = $this->input->get('id', null) ) {
            header('Cache-Control: public, max-age=31536000');
            throw new Exception("Missing offer id as 'id'!", 404);
        }
        return $id;
    }
}

