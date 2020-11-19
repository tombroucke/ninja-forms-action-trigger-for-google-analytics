<?php if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class NF_Field_ActionTriggerForGoogleAnalyticsExample
 */
class NF_ActionTriggerForGoogleAnalytics_Fields_ActionTriggerForGoogleAnalyticsExample extends NF_Fields_Textbox
{
    protected $_name;

    protected $_section;

    protected $_type;

    protected $_templates;

    public function __construct( $name, $nicename, $section = 'common', $type = 'textbox', $templates = 'textbox' )
    {
        parent::__construct();
        $this->_name = $name;
        $this->_section = $section;
        $this->_type = $type;
        $this->_templates = $templates;
        $this->_nicename = $nicename;
    }
}