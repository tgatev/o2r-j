<?php
/**
 * @package LiveUpdate
 * @copyright Copyright ©2011 Nicholas K. Dionysopoulos / AkeebaBackup.com
 * @license GNU LGPLv3 or later <http://www.gnu.org/copyleft/lesser.html>
 */

defined('_JEXEC') or die();

/**
 * Configuration class for your extension's updates. Override to your liking.
 */
class LiveUpdateConfig extends LiveUpdateAbstractConfig
{
    var $_extensionName			= 'com_uu';
    var $_extensionTitle		= 'Ultimate User Community';
    var $_versionStrategy		= 'vcompare';
    var $_updateURL				= 'http://www.faboba.com/index.php?option=com_ars&view=update&format=ini&id=9';
    var $_requiresAuthorization = true;
    var $_storageAdapter		= 'component';
    var $_storageConfig			= array(
        'extensionName'	=> 'com_uu',
        'key'			=> 'liveupdate'
    );

    function __construct()
    {
        JLoader::import('joomla.filesystem.file');
        $this->_cacerts = dirname(__FILE__).'/../assets/cacert.pem';

        switch (UU_LICENCE) {
            case "standard" :
                $this->_requiresAuthorization = true;
                $this->_updateURL = 'http://www.faboba.com/index.php?option=com_ars&lang=en&view=update&format=ini&id=1';
                break;
            case "pro" :
                $this->_requiresAuthorization = true;
                $this->_updateURL = 'http://www.faboba.com/index.php?option=com_ars&lang=en&view=update&format=ini&id=8';
                break;
            default :
                $this->_requiresAuthorization = false;
                $this->_updateURL = 'http://www.faboba.com/index.php?option=com_ars&lang=en&view=update&format=ini&id=9';
        }

        $this->_extensionTitle = 'Ultimate User '. UU_LICENCE .' version';

        parent::__construct();

    }

}