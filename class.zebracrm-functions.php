<?php
class ZebraCRM {
    private static $initiated = false;

    public static function init() {
        if ( ! self::$initiated ) {
            self::init_hooks();
        }
    }

    /**
     * Initializes WordPress hooks
     */
    private static function init_hooks() {
        self::$initiated = true;

        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'zebraCRM_load_resources' ), 20 );

        add_shortcode( 'zebracrm_form', array( __CLASS__, 'display_form' ) );

        add_action( 'wp_ajax_zebraCRM_submit', array( __CLASS__, 'zebraCRM_submit') );
        add_action( 'wp_ajax_nopriv_zebraCRM_submit', array( __CLASS__, 'zebraCRM_submit') );
    }

    public static function display_form() {
        ob_start();
        include_once( ZEBRACRM__PLUGIN_DIR . 'inc/zebracrm-form.php' );

        echo ob_get_clean();
    }

    public static function zebraCRM_load_resources() {
        global $post;

        wp_register_script( 'zebracrm-validate', '//ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.min.js' );
        wp_register_script( 'zebracrm-script', plugins_url('js/scripts.js', __FILE__), array( 'jquery', 'zebracrm-validate' ), ZEBRACRM_VERSION , true );
        wp_register_style( 'zebracrm-style', plugin_dir_url( __FILE__ ) . 'css/styles.css' );

        wp_enqueue_script( 'zebracrm-script' );
        wp_enqueue_style('zebracrm-style');

        wp_localize_script( 'zebracrm-script', 'zcrm', array(
            'nonce'          => wp_create_nonce( 'zebracrm_nonce_field' ),
            'ajax_url'       => admin_url( 'admin-ajax.php' )
        ) );
    }

    public static function zebraCRM_submit() {
        $return = array( 'error' => true, 'msg' => 'Error' );

        if ( ! isset( $_POST['zebracrm_nonce_field'] )
            || ! wp_verify_nonce( $_POST['zebracrm_nonce_field'], 'zebracrm_action' ) ) {
            wp_die( json_encode( $return ) );
        }

        $first_name = isset( $_POST['first_name'] ) ? $_POST['first_name'] : '';
        $last_name  = isset( $_POST['last_name'] ) ? $_POST['last_name'] : '';
        $email      = isset( $_POST['email'] ) ? $_POST['email'] : '';
        $license    = isset( $_POST['license'] ) ? $_POST['license'] : '';
        $phone      = isset( $_POST['phone'] ) ? $_POST['phone'] . "" : '';
        $city       = isset( $_POST['city'] ) ? $_POST['city'] : '';

        if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) ) {
            wp_die( json_encode( $return ) );
        }

        $xml = "<?xml version='1.0' encoding='utf-8'?>
            <ROOT>
                <PERMISSION>
                    <USERNAME>API</USERNAME>
                    <PASSWORD>API</PASSWORD>
                </PERMISSION>
                <CARD_TYPE>private_customer</CARD_TYPE>
                <CUST_DETAILS>
                    <P_N>" . $first_name . "</P_N>
                    <F_N>" . $last_name . "</F_N>
                    <MAIL>" . $email . "</MAIL>
                    <PHN_H>" . $phone . "</PHN_H>
                    <licences>" . $license . "</licences>
                    <CITY>" . $city . "</CITY>
                </CUST_DETAILS>
            </ROOT>";

        $url = "http://15644.zebracrm.com/ext_interface.php?b=add_customer";

        //setting the curl parameters.
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $xml );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 300 );

        $data = curl_exec( $ch );
        curl_close( $ch );

        $array_data = json_decode( json_encode( simplexml_load_string( $data ) ), true );
        $return = array( 'error' => false, 'msg' => $array_data['result']['msg'] );

        wp_die( json_encode( $return ) );
    }
}