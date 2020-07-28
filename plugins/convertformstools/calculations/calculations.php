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

class PlgConvertFormsToolsCalculations extends JPlugin
{
    /**
     *  Application Object
     *
     *  @var  object
     */
    protected $app;

    /**
     *  Auto loads the plugin language file
     *
     *  @var  boolean
     */
    protected $autoloadLanguage = true;

    /**
     *  We need to load our assets regardless if the form doesn't include a field that supports calculations because
     *  user may add a field later. Thus we ensure the Calculation Builder is properly rendered.
     *
     *  @return  void
     */
    public function onConvertFormsBackendEditorDisplay()
    {
        JHtml::script('plg_convertformstools_calculations/calculation_builder.js', ['relative' => true, 'version' => 'auto']);
        JHtml::stylesheet('plg_convertformstools_calculations/calculation_builder.css', ['relative' => true, 'version' => 'auto']);
    }

    /**
     *  Add plugin fields to the form
     *
     *  @param   JForm   $form  
     *  @param   object  $data
     *
     *  @return  boolean
     */
    public function onConvertFormsBackendRenderOptionsForm($form, $field_type)
    {
        if (!in_array($field_type, ['text', 'number', 'hidden']))
        {
            return;
        }

        $form->loadFile(__DIR__ . '/form/form.xml');
    }

    # PRO-START
    /**
     * Event triggered during fieldset rendering in the form editing page in the backend.
     *
     * @param string $fieldset_name The name of the fieldset is going to be rendered
     * @param string $fieldset      The HTML output of the fieldset
     *
     * @return void
     */
    public function onConvertFormsFieldBeforeRender($field)
    {
        // Only on front-end
        if ($this->app->isAdmin())
        {
            return;
        }

        if (!isset($field->calculations) || !$field->calculations['enable'] || empty($field->calculations['formula']))
        {
            return;
        }

        $calculation_attributes = [
            'data-calc'       => $field->calculations['formula'],
            'data-precision'  => $field->calculations['precision'],
            'data-prefix'     => isset($field->calculations['prefix']) ? $field->calculations['prefix'] : '',
            'data-suffix'     => isset($field->calculations['suffix']) ? $field->calculations['suffix'] : '',
            //'data-thousand_separator' => $field->calculations['thousand_separator'],
            //'data-decimal_separator ' => $field->calculations['decimal_separator']
        ];

        $field->htmlattributes = array_merge($calculation_attributes, $field->htmlattributes);
    }

    /**
     * Determine whether the form has calculations in order to load the respective scripts.
     *
     * @param object $form  The form object
     * @param string $form  The form's final HTML layout.
     *
     * @return void
     */
    public function onConvertFormsAfterDisplay($form, $html)
    {
        // Only on front-end
        if ($this->app->isAdmin())
        {
            return;
        }

        // Check if we really need to load the script that will handle the calculations.
        if (!$hasCalculations = strpos($html, 'data-calc=') !== false)
        {
            return;
        }

        // Load scripts
        JHtml::script('plg_convertformstools_calculations/vendor/expr-eval.1.2.3.js', ['relative' => true, 'version' => 'auto']);
        JHtml::script('plg_convertformstools_calculations/calculations.js', ['relative' => true, 'version' => 'auto']);
    }
    # PRO-END
}
