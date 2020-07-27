<?php

class InvocieValidatorMetaboxes {
    
    /**
    * Instance of Class.
    *
    * @since 1.0.0
    * @access public 
    * @var InvocieValidatorMetaboxes
    * */
    public static $instance;

    /**
    * Provides access to a single instance of a module using the singleton pattern.
    *
    * @since   1.0.0
    * @return	object
    */
	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new self();
        }
		return self::$instance;
    }

    /**
    * Define the core functionality of the plugin.
    *
    * @since	1.0.0
    */
	public function __construct() {
        if( ! class_exists( 'CMB2_Bootstrap_260' ) ) {
            include_once INVLIDIR . 'admin/vendor/cmb2/init.php';
        }
        add_action( 'cmb2_admin_init', [$this, 'group_field_metabox'] );
    }
    
    /**
    * Hook in and add a metabox to demonstrate repeatable grouped fields
    */
    public function group_field_metabox() {
        $customer   = INVLIBASEID . '_customer_details_';
        $prefix     = INVLIBASEID . '_group_';
        $products   = get_posts([ 'post_type' => 'invoide_products', ]);
        $allproducts = [];
        foreach ( $products as $product ) {
            $allproducts[$product->ID] = $product->post_title;
        }

        /**
        * Repeatable Field Groups
        */
        $cmb_group = new_cmb2_box( array(
            'id'           => $prefix . 'metabox',
            'title'        => esc_html__( 'Repeating Field Group', 'invoice-validator' ),
            'object_types' => array( INVLIBASEID ),
        ));
        $cmb_group->add_field( array(
            'name'          => esc_html__( 'Customer Name', 'invoice-validator' ),
            'id'            => $customer . 'name',
            'type'          => 'text',
            'attributes'    => array(
                'required'  => 'required'
            ),
        ));
        $cmb_group->add_field( array(
            'name'          => esc_html__( 'Customer National Code', 'invoice-validator' ),
            'id'            => $customer . 'national_code',
            'type'          => 'text',
            'attributes'    => [
                'novalidmsg'    => __( 'This natural code is not valid','invoice-validator' ),
                'required'      => 'required',
                'isvalid'       => 'ncodev',
            ]
        ));
        $cmb_group->add_field( array(
            'name'          => esc_html__( 'Customer VAT Number', 'invoice-validator' ),
            'id'            => $customer . 'vat_number',
            'type'          => 'text',
        ));
        $cmb_group->add_field( array(
            'name'          => esc_html__( 'Customer address', 'invoice-validator' ),
            'id'            => $customer . 'address',
            'type'          => 'textarea',
        ));
        $cmb_group->add_field( array(
            'name'          => esc_html__( 'Customer Zip Code', 'invoice-validator' ),
            'id'            => $customer . 'zip_code',
            'type'          => 'text',
        ));
        $cmb_group->add_field( array(
            'name'          => esc_html__( 'Customer Phone Number', 'invoice-validator' ),
            'id'            => $customer . 'phone_number',
            'type'          => 'text',
        ));
        $cmb_group->add_field( array(
            'name'          => esc_html__( 'Plaque', 'invoice-validator' ),
            'id'            => $customer . 'plaque',
            'type'          => 'checkbox',
        ));
        $cmb_group->add_field( array(
            'name'          => esc_html__( 'Insurance', 'invoice-validator' ),
            'id'            => $customer . 'insurance',
            'type'          => 'checkbox',
        ));
        $user = wp_get_current_user();
        if ( is_user_logged_in() ) {
            if ( $user->roles[0] == 'administrator' ) {
                $cmb_group->add_field( array(
                    'name'          => esc_html__( 'Invoice Expire Date', 'invoice-validator' ),
                    'id'            => $customer . 'expire_date',
                    'type'          => 'text',
                    'attributes'    => [
                        'class'    => 'pdatepicker',
                    ]
                ));
                $cmb_group->add_field( array(
                    'name'          => esc_html__( 'Invoice Description', 'invoice-validator' ),
                    'id'            => $customer . 'invoice_description',
                    'type'          => 'textarea',
                ));
            }
        }

        
        // $group_field_id is the field id string, so in this case: $prefix . 'demo'
        $group_field_id = $cmb_group->add_field( array(
            'id'          => $prefix . 'products',
            'type'        => 'group',
            'options'     => array(
                'group_title'       => esc_html__( 'Product {#}', 'invoice-validator' ), // {#} gets replaced by row number
                'add_button'        => esc_html__( 'Add', 'invoice-validator' ),
                'remove_button'     => esc_html__( 'Remove', 'invoice-validator' ),
                'sortable'          => true,
                'closed'            => true, // true to have the groups closed by default
                'remove_confirm'    => esc_html__( 'Are you sure you want to remove?', 'invoice-validator' ), // Performs confirmation before removing group.
            ),
        ));

        /**
        * Group fields works the same, except ids only need
        * to be unique to the group. Prefix is not needed.
        *
        * The parent field's id needs to be passed as the first argument.
        */
        $cmb_group->add_group_field( $group_field_id, array(
            'name'      => esc_html__( 'Product', 'invoice-validator' ),
            'id'        => 'product_name',
            'type'      => 'select',
            'options'   => get_option( 'invoce_validator_product_list' ),
            'attributes'    => array(
                'required'  => 'required',
            ),
        ));
        $cmb_group->add_group_field( $group_field_id, array(
            'name'      => esc_html__( 'Index', 'invoice-validator' ),
            'id'        => 'product_index',
            'type'      => 'text_small',
            'attributes'    => array(
                'type'      => 'number'
            ),
        ));
        $cmb_group->add_group_field( $group_field_id, array(
            'name'      => esc_html__( 'Amount', 'invoice-validator' ),
            'id'        => 'product_amount',
            'type'      => 'text_small',
            'attributes'    => array(
                'required'  => 'required',
                'type'      => 'number'
            ),
        ));
        $cmb_group->add_group_field( $group_field_id, array(
            'name'      => esc_html__( 'Discount(%)', 'invoice-validator' ),
            'id'        => 'product_discount',
            'type'      => 'text_small',
            'attributes'    => array(
                'type'      => 'number'
            ),
        ));

        // products post meta
        $cmb_group = new_cmb2_box( array(
            'id'           => $prefix . '_products_metabox',
            'title'        => esc_html__( 'Repeating Field Group', 'invoice-validator' ),
            'object_types' => array( 'invoide_products' ),
        ));
        $cmb_group->add_field( array(
            'name'          => esc_html__( 'Product Code', 'invoice-validator' ),
            'id'            => 'invoice_product_code',
            'type'          => 'text',
            'attributes'    => array(
                'required'  => 'required'
            ),
        ));
        $cmb_group->add_field( array(
            'name'          => esc_html__( 'Product Price', 'invoice-validator' ),
            'id'            => 'invoice__productprice',
            'type'          => 'text',
            'attributes'    => array(
                'required'  => 'required'
            ),
        ));
    }
}

InvocieValidatorMetaboxes::get_instance();