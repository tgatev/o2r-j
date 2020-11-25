<?php
/**
 * @package     UltimateUser for Joomla!
 * @author      Stéphane Bouey <stephane.bouey@faboba.com> - http://www.faboba.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @copyright   Copyright (C) 2012-2015. All rights reserved.
 */


defined('_JEXEC') or die('Restricted access');
jimport('joomla.registry.registry');

class UuParameter extends JRegistry
{
	/**
	 * [$_xml description]
	 * @var [type]
	 */
	protected $_xml = null;

	/**
	 * __construct description
	 * @param [object] $data    [description]
	 * @param [string] $xmlPath [description]
	 */
	public function __construct($data, $xmlPath = NULL)
	{
		parent::__construct($data);

		if(!is_null($xmlPath))
		{
			$this->_xml = new SimpleXMLElement($xmlPath,NULL,true);
		}
	}

	/**
	* @param string name
	* @param string group [currently not used, being put there to imitate JParameter render()]
	* @return string html
	*/
	public function render()
	{
		$html	= array();
		$html[]	= '<table width="100%" class="cFormTable" cellspacing="0" cellspacing="0">';
		$params	= $this->_xml->params;
		$data	= $this->data;
		foreach($params as $param)
		{
			foreach($param as $_param)
			{
				$html[] = '<tr>';

				if($_param['type'] != 'spacer')
				{
					$html[] = '<td class="label"><span class="editlinktip">'. $_param['label'] .'</span></td>';
					$html[] = '<td class="field" valign="top">'. $this->_generateHTML($_param,$data) .'</td>';
				}


				$thml[] = '</tr>';
			}
		}

		$html[] = '</table>';
		return implode("\n", $html);

	}
	/**
	 * [bind description]
	 * @param  [type] $data  [description]
	 * @param  string $group [description]
	 * @return [type]        [description]
	 */
	public function bind($data, $group = '_default')
	{
		if (is_array($data))
		{
			return $this->loadArray($data, $group);

		} elseif (is_object($data))
		{
			return $this->loadObject($data, $group);

		} else
		{
			// Return JSON
			return $this->loadString($data);
		}

	}
	/**
	 * [generateHTML description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function _generateHTML($data,$value)
	{
		if(!is_object($data))
		{
			return false;
		}
		$html = array();
		// Setup empty value if there is none
        //empty don't work with radio default 0
		if(!isset($value->{(string)$data['name']})){
            if ($data['type'] == 'radio') {
                $value->{(string)$data['name']} = (int)$data['default'];
            } else {
                $value->{(string)$data['name']} = '';
            }
		}

        switch ($data['type'])
		{
			case 'list':
				$html[] = '<select id="jform_params_'.$data['name'].'" name="jform[params]['.$data['name'].']">';
				$html[] = $this->_getOption($data,$value->{(string)$data['name']});
				$html[] = '</select>';
				break;
			case 'radio':
				//$html[] = $this->_getRadio($data,$value->$data['name']);
                $html[] = $this->_getRadio($data,$value->{(string)$data['name']});
				break;
			case 'text':
				$html[] = '<input id="jform_params_'.$data['name'].'" class="text_area" type="text" value="'.$value->{(string)$data['name']}.'" name=jform[params]['.$data['name'].']>';
				break;
			case 'textarea':
				$html[] = '<textarea id="jform_params_'.$data['name'].'" class="fullwidth" rows="8" name=jform[params]['.$data['name'].']>'.$value->{(string)$data['name']}.'</textarea>';
				break;
		}
		return implode("\n",$html);
	}

	/**
	 * [getOption description]
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	private function _getOption($data,$value)
	{
		$html = array();
		foreach($data->children() as $_data)
		{
			$selected = ($_data['value'] == $value) ? ' selected="selected"' : '';
			$html[] = '<option value='.$_data['value'].$selected.'>'.$_data['name'].'</option>';
		}

		return implode("\n", $html);
	}
	private function _getRadio($data,$value)
	{
		$html = array();
		$name = $data['name'];
        if (UU_J30){
                //$html[] = '<div class="control-group">';
                //$html[] = '  <div class="control-label">';
                //$html[] = '    <label id="jform_params_'.$name.$data['value'].'-lbl" for="jform_params_'.$name.$data['value'].'">'.$data['name'].'</label>';
                //$html[] = '  </div>';
                $html[] = '  <div class="controls" style="margin-left:0">';
                $html[] = '  <fieldset id="jform_params_'.$name.'" class="radio inputbox btn-group">';
                $i = 0;
                foreach($data->children() as $key => $_data)
                {
                    $selected = (isset($value) && $_data['value'] == $value) ? ' checked="checked"' : '';
                    $selected_label = '';
                    if (isset($value) && $_data['value'] == $value) {
                        if ($value == 0) {
                            $selected_label = 'active btn-danger';
                        } else {
                            $selected_label = 'active btn-success';

                        }
                    }
                    $html[] = '    <input type="radio" id="jform_params_'.$name.$i.'"  name="jform[params]['.$name.']" value="'.$_data['value'].'" '.$selected.' />';
                    $html[] = '    <label class="btn '.$selected_label.'" for="jform_params_'.$name.$i.'">'.$_data['name'].'</label>';
                    $i = $i+1;
                }
                $html[] = '  </fieldset>';
                $html[] = '  </div>';
                //$html[] = '</div>';

        } else {
            foreach($data->children() as $_data)
            {
                $selected = (isset($value) && $_data['value'] == $value) ? ' checked="checked"' : '';
                $html[] = '<fieldset class="btn-group">';
                $html[] = '<input id="jform_params_'.$name.$_data['value'].'" type="radio" name="jform[params]['.$name.']" value='.$_data['value'].$selected.' />';
                $html[] = '<label for="_form_params_'.$name.$_data['value'].'">'.$_data['name'].'</label>';
                $html[] = '</fieldset>';
            }

        }

		return implode("\n", $html);
	}
}