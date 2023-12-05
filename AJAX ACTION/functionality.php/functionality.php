1) CODE :

<a href="javascript:" id="_click_to_go_aja_" data-product-id="2854,2855,2856,2863" class="btn mt-2 mb-3 btn-primary btn-block" <="" a="">Hello Submit</a>



===============================
2) Script.js


/**
 *  Bhoomika Mahavar Project Scripts
 *  --------------------------------
 */
(function($) {

    "use strict";

    var Bhoomika_Mahavar  = {

        /**
         *  Add to Cart
         *  -----------
         */
        add_to_cart_process: function(){

            if( $( '#_click_to_go_aja_' ).length ){

                $( "#_click_to_go_aja_" ).on( 'click', function( e ){
                    
                        e.preventDefault();
                    
                        var product_ids     =   $(this).attr( 'data-product-id' );

                        console.log( product_ids );

                        $.ajax({
                            type: 'POST',
                            
                            dataType: 'json',
                            
                            url: BHOOMIKA_AJAX_OBJ.ajax_url,
                            
                            data: { 

                                /**
                                 *  Action + Security
                                 *  -----------------
                                 */
                                'action'                                   : 'bhoomika_mahavar_add_all_in_cart',

                                'product_ids'                              : product_ids
                            },

                            beforeSend: function(){

                                console.log( 'I am in AJAX' );
                            },

                            complete: function(){

                            },

                            success: function( PHP_RESPONSE ){

                                console.log( 'DONE - What i GET IN AJAX' );
                                
                                console.log( PHP_RESPONSE );
                            },

                            error: function(jqXHR, textStatus, errorThrown) {

                                console.log( jqXHR );

                                console.log( textStatus );

                                console.log( errorThrown );
                            },
                        });
                        
                    
                } );
            }
        },

        /**
         *  By Default Load Script
         *  ----------------------
         */
        init: function(){

            /**
             *  Add to Cart
             *  -----------
             */
            this.add_to_cart_process();
        }
    };

    /**
     *  Common Script updated in Window Variable
     *  ----------------------------------------
     */
    window.Bhoomika_Mahavar     = Bhoomika_Mahavar;
    
    /**
     *  Document Ready to Run Object
     *  ----------------------------
     */
    $(document).ready( function(){   Bhoomika_Mahavar.init(); } );

})(jQuery);



===============================

3) Functions.php




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
    wp_enqueue_script( 'bhoomika-mahavar' , plugin_dir_url( __FILE__ ) . 'script.js', array('jquery', 'toastr' ), '1.1.1', true );

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
