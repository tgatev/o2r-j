<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Delta Flip 
/-------------------------------------------------------------------------------------------------------/

	@version		1.0.21
	@build			26th November, 2019
	@created		5th July, 2019
	@package		Offers
	@subpackage		adnetwork.php
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

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('text');

/**
 * Adnetwork Form Field class for the Ofrs component
 */
class JFormFieldSearchText extends JFormFieldText
{
    /**
     * define additional attributes in input element
     * @var array ["attribute" => "value", ... ]
     */
    public $custom_attributes = array();
	/**
	 * The adnetwork field type.
	 *
	 * @var		string
	 */
	public $type = 'SearchText';

    /**
     * @{@inheritDoc}
     * @return string HTML string
     */
    public function getInput()
    {
        $html_result = parent::getInput(); // Get parent Html


        /**
         * Add custom attributes
         */
        // add defaults to custom attributes
        $defaults = array(
            "onfocus" => "this.placeholder = ''",
            "onblur" =>"this.placeholder = '".$this->hint."'",
//            "onmouseover" => "this.placeholder = ''",
//            "onmouseleave" => "this.placeholder = '".$this->hint."'",
            "onkeypress" => "triggerSearch(event, 'adnet_search_form');", );

        $this->custom_attributes = $defaults + $this->custom_attributes ;

        // remove closing '/>'
        $html_result = substr($html_result, 0 , -3);

        foreach ($this->custom_attributes as $attribute => $value ){
            $html_result.= sprintf(' %s="%s"', $attribute, $value );
        }

        // Close input
        $html_result.= '/>';
        $html_result.= '<span aria-hidden="true" class="btn-search" type="submit" onclick="submitOffersForm()">
            <i class="fa fa-search"></i>
        </span>';

        return $html_result ;
    }
}
