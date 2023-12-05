(function($) {

    "use strict";

    var Bhoomika_Mahavar  = {

        /**
         *  Add to Cart
         *  -----------
         */
        add_to_cart_process: function(){

            if( $( '#all_product_ids_in_ajax' ).length ){

                $( "#all_product_ids_in_ajax" ).on( 'click', function( e ){
                    
                        e.preventDefault();
                    
                        var product_ids     =   $(this).attr( 'data-product-id' );

                        console.log( product_ids );

                        $.ajax({
                            type: 'POST',
                            
                           // dataType: 'json',
                            
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

                                location.reload();
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




/**
* In Short [ This script will put in live ]
*/
jQuery(document).ready(function($){
    $("#all_product_ids_in_ajax").click(function (e) {

        e.preventDefault();

        var product_ids = $(this).attr( 'data-product-id' );

        $.ajax({
            type: 'POST',
            url:   BHOOMIKA_AJAX_OBJ.ajax_url,
            data: { 
                action : 'bhoomika_mahavar_add_all_in_cart',
                product_ids: product_ids,
            },
            success: function( data ) {

              console.log( data );

              location.reload();
           
            }
        });
    
    });

});
