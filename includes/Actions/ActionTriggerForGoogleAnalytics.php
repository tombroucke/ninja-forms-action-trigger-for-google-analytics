<?php if ( ! defined( 'ABSPATH' ) || ! class_exists( 'NF_Abstracts_Action' )) exit;

/**
 * Class NF_Action_ActionTriggerForGoogleAnalytics
 */

final class NF_ActionTriggerForGoogleAnalytics_Actions_ActionTriggerForGoogleAnalytics extends NF_Abstracts_Action
{
    /**
     * @var string
     */
    protected $_name  = 'action-trigger-for-google-analytics';

    /**
     * @var array
     */
    protected $_tags = array();

    /**
     * @var string
     */
    protected $_timing = 'normal';

    /**
     * @var int
     */
    protected $_priority = '10';

    private $_ssga;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_nicename = __( 'Action Trigger for GA', 'ninja-forms-action-trigger-for-google-analytics' );
        $this->_settings = array(
            'tracking_id' => array(
                'name' => 'tracking_id',
                'type' => 'textbox',
                'label' => __( 'GA Tracking ID', 'ninja-forms-action-trigger-for-google-analytics'),
                'width' => 'full',
                'group' => 'primary',
                'value' => 'UA-XXXXXXXX-X',
                'help' => __( 'Enter the GA tracking code.', 'ninja-forms-action-trigger-for-google-analytics' ),
            ),
            'tracking_category' => array(
                'name' => 'tracking_category',
                'type' => 'textbox',
                'label' => __( 'Tracking Category', 'ninja-forms-action-trigger-for-google-analytics'),
                'width' => 'full',
                'group' => 'primary',
                'value' => 'form',
                'help' => __( 'Enter the tracking category.', 'ninja-forms-action-trigger-for-google-analytics' ),
            ),
            'tracking_action' => array(
                'name' => 'tracking_action',
                'type' => 'textbox',
                'label' => __( 'Tracking Action', 'ninja-forms-action-trigger-for-google-analytics'),
                'width' => 'full',
                'group' => 'primary',
                'value' => 'submit',
                'help' => __( 'Enter the tracking action.', 'ninja-forms-action-trigger-for-google-analytics' ),
            ),
            'opt_label' => array(
                'name' => 'opt_label',
                'type' => 'textbox',
                'label' => __( 'Optional Label', 'ninja-forms-action-trigger-for-google-analytics'),
                'width' => 'full',
                'group' => 'primary',
                'value' => 'Contact Form',
                'help' => __( 'Enter an optional label.', 'ninja-forms-action-trigger-for-google-analytics' ),
            ),
            'opt_value' => array(
                'name' => 'opt_value',
                'type' => 'textbox',
                'label' => __( 'Optional Value', 'ninja-forms-action-trigger-for-google-analytics'),
                'width' => 'full',
                'group' => 'primary',
                'value' => 'Contact Page',
                'help' => __( 'Enter an optional value.', 'ninja-forms-action-trigger-for-google-analytics' ),
            )
        );

    }

    /*
    * PUBLIC METHODS
    */

    public function save( $action_settings )
    {
    
    }

    public function process( $action_settings, $form_id, $data )
    {

        $tracking_id        = $action_settings['tracking_id'];

        $tracking_category  = $action_settings['tracking_category'];
        $tracking_action    = $action_settings['tracking_action'];
        $opt_label          = $action_settings['opt_label'];
        $opt_value          = $action_settings['opt_value'];

        $this->ssga = new ssga( $tracking_id , get_bloginfo( 'url' ) );

        $this->ssga->set_event( $tracking_category, $tracking_action, $opt_label, $opt_value );
        $this->ssga->send();

        return $data;
    }
}
