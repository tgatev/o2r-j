<?php

/**
 * @package         Convert Forms
 * @version         2.6.0 Free
 * 
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2020 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
*/

defined('_JEXEC') or die('Restricted access');
JFormHelper::loadFieldClass('checkboxes');

class JFormFieldLeadColumns extends JFormFieldCheckboxes
{
    /**
     * Method to get a list of options for a list input.
     *
     * @return      array           An array of JHtml options.
     */
    protected function getOptions()
    {
        $formID = $this->getFormID();

        $form_fields = ConvertForms\Helper::getColumns($formID);

        $optionsForm = [];

        foreach ($form_fields as $key => $field)
        {
            $label = ucfirst(str_replace('param_', '', $field));

            if (strpos($field, 'param_') === false)
            {
                $label = JText::_('COM_CONVERTFORMS_' . $label);
            }

            $optionsForm[] = (object) [
                'value' => $field,
                'text'  => $label
            ];
        }

        return $optionsForm;
    }

    protected function getInput()
    {
        JFactory::getDocument()->addStyleDeclaration('
            .chooseColumns {
                position:relative;
            }
            .chooseColumnsOptions {
                position: absolute;
                background-color: #fff;
                top: 30px;
                border-radius:4px;
                z-index:15; 
                transition: height 0.01s;
            }
            .chooseColumnsOptions.in {
                -webkit-box-shadow: 1px 1px 1px 1px rgba(0,0,0,0.1);
                box-shadow: 1px 1px 1px 1px rgba(0,0,0,0.1);
            }
            .chooseColumnsOptions > div {
                border: solid 1px #ccc;
                padding: 15px;
                min-width: 150px;
                border-radius:4px; 
            }
            .chooseColumnsOptions fieldset {
                margin-bottom:5px;
            }
            .chooseColumnsOptions fieldset .checkbox {
                white-space: nowrap;
            }
            .chooseColumnsOptions input {
                margin-top: 2px;
            }
            .chooseColumnsInfo {
                padding-top: 10px;
                line-height: 16px;
                font-size: 11px;
                color: #555;
            }
        ');

        $html = '
            <div class="chooseColumns">
                <button class="btn" role="button" data-toggle="collapse" href=".chooseColumnsOptions">'
                     . JText::_('COM_CONVERTFORMS_CHOOSE_COLUMNS') . '
                </button>
                <div class="collapse chooseColumnsOptions">
                    <div>
                        ' . parent::getInput() . '
                        <button class="btn btn-primary" onclick="this.form.submit();">'
                            . JText::_('JAPPLY') . 
                        '</button>';

        if (!$this->getFormID())
        {
            $html .= '<div class="chooseColumnsInfo">' . JText::_('COM_CONVERTFORMS_CHOOSE_COLUMNS_OPTIONS') . '</div>';
        }

        $html .= '
                    </div>
                </div>
            </div>
        ';

        return $html;
    }

    private function getFormID()
    {
        return $this->form->getData()->get('filter.form_id');
    }
}