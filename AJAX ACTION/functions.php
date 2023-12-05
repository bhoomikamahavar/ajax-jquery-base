<?php
/**
 *  In Single CPT Collection [ Button = All Add To Cart ] = In this button click action is here
 */

/**
 *  All Add To Cart [ Ajax Action PHP Call ]
 */
add_action( 'wp_ajax_' . 'bhoomika_mahavar_add_all_in_cart', 'bhoomika_mahavar_add_all_in_cart' );

add_action( 'wp_ajax_nopriv_' . 'bhoomika_mahavar_add_all_in_cart', 'bhoomika_mahavar_add_all_in_cart' );

function bhoomika_mahavar_add_all_in_cart(){

    global $woocommerce;

    if( isset( $_POST[ 'product_ids' ] ) && $_POST[ 'product_ids' ] != '' ){

        $_product_ids   =   explode( ',' , $_POST[ 'product_ids' ] );

        if( is_array( $_product_ids ) ){

            foreach( $_product_ids as $key => $value ){

                global $woocommerce;

                $woocommerce->cart->add_to_cart( $value );
            }
        }
        
        die( json_encode( [

            'message'               =>  esc_attr__( 'Collection Added in Cart', 'weddingdir' )

        ] ) );

        
    }else{

        die( json_encode( [

            'message'               =>  esc_attr__( 'Error', 'weddingdir' )

        ] ) );
    }
}


/**
 *  1. Load Script for couple registration
 *  --------------------------------------
 */
add_action( 'wp_enqueue_scripts', 'bhoomika_weddingdir_script' );

function bhoomika_weddingdir_script(){

    /**
     *  Bhoomika Mahavar
     *  ----------------
     */
    wp_enqueue_script( 'bhoomika-mahavar' , get_stylesheet_directory_uri() . '/custom_js.js', array('jquery' ), '1.1.1', true );

    /**
     *  WeddingDir - Localize Script
     *  ----------------------------
     */
    wp_localize_script(

        /**
         *  Load After Script NAME
         *  ----------------------
         */
        esc_attr( 'bhoomika-mahavar' ),

        /**
         *  Localize Object
         *  ---------------
         */
        esc_attr( 'BHOOMIKA_AJAX_OBJ' ),

        /**
         *  Localize Object Data 
         *  --------------------
         */
        array(

            /**
             *  WordPress AJAX File
             *  -------------------
             */
            'ajax_url'       =>  admin_url( 'admin-ajax.php' ),

        )
    );

}
