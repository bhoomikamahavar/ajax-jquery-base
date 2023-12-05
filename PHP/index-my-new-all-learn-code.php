<?php

/**
 *   BHOOMIKA
 * 
 *   New Code Learn & Add Here
 */

/**
 1)  Add inline CSS [ Already Exists Stylesheet ]
     #URL: : https://developer.wordpress.org/reference/functions/wp_add_inline_style/
*/

	/**
	 * Add color styling from theme
	 */
	function wpdocs_styles_method() {
		wp_enqueue_style(
			'custom-style',
			get_template_directory_uri() . '/css/custom_script.css'
		);
	        $color = get_theme_mod( 'my-custom-color' ); //E.g. #FF0000
	        $custom_css = "
	                .mycolor{
	                        background: {$color};
	                }";
	        wp_add_inline_style( 'custom-style', $custom_css );
	}
	add_action( 'wp_enqueue_scripts', 'wpdocs_styles_method' );

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 2)  Customize the contact information fields available to your WordPress users. Edits the available contact methods on a user’s profile page. Contact methods can be both added and removed.
     #URL: : https://developer.wordpress.org/reference/hooks/user_contactmethods/
*/

	add_filter( 'user_contactmethods', 'modify_user_contact_methods' );

	function modify_user_contact_methods( $methods ) {

	        // Add user contact methods
	        $methods['skype']   = __( 'Skype Username'   );
	        $methods['twitter'] = __( 'Twitter Username' );

	        // Remove user contact methods
	        unset( $methods['aim']    );
	        unset( $methods['jabber'] );

	        return $methods;
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 3)  Text Limiter Function
*/
	function tokopress_text_limiter($text, $limit = 25, $ending = '...') {
	        if ( strlen($text) > $limit ) {

	                $text = wp_strip_all_tags($text);
	                $text = substr($text, 0, $limit);
	                $text = substr($text, 0, -(strlen(strrchr($text, ' '))));
	                $text = $text . $ending;

	        }

	        return $text;
	}

	//Function Use like this :=

	<?php echo tokopress_text_limiter( get_the_excerpt(), 860 );

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
*  4) For THeme Custmization Save = Action
*/
	add_action( 'customize_save_after', 'tokopress_customize_save_after' );

	function tokopress_customize_save_after() {
		$output = tokopress_customize_output();
		set_theme_mod( 'tokopress_customize_css', $output['style'] );
		set_theme_mod( 'tokopress_customize_fonts', $output['fonts'] );
		set_theme_mod( 'tokopress_customize_saved', true );
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
*  5) How To Find WordPress Page ID By Title Then Replace the_content()
   URL : https://developer.wordpress.org/reference/functions/get_page_by_title/
*/
	function my_content($content) {
		$page = get_page_by_title( 'Sample Page' );
		if ( is_page($page->ID) )
			$content = "Hello World!";
		return $content;
	}
	add_filter('the_content', 'my_content');

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
 * 6) Remove a previously enqueued CSS stylesheet.
 * URL : https://developer.wordpress.org/reference/functions/wp_dequeue_style/
 */
	add_action( 'wp_enqueue_scripts', 'mywptheme_child_deregister_styles', 11 );
	function mywptheme_child_deregister_styles() {
		wp_dequeue_style( 'mywptheme' );

	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 *  7) Check category are multiple insert into the deal listing  post type via cron
 * 	#URL = https://manawatunz.co.nz/
 * 
	https://manawatunz.co.nz/wp-admin/edit.php?post_type=deals
	https://manawatunz.co.nz/wp-admin/edit.php?post_type=listing
 
    This all Data is add by PostMan API
    #URL = https://www.postman.com/

 */

#OLD_CODE = START

	function insertListCategories($val, $dealCat = "deal_cat")
	{
		$parent_term = term_exists($val,$dealCat); // array is returned if taxonomy is given
		if (empty($parent_term)) {
			$parent_term = wp_insert_term(
				$val,    
				$dealCat, 
			);
		}
		return $parent_term;
	}

	function instertSubcategory($val, $dealCat = "deal_cat", $parent_id)
	{
		$parent_term = term_exists($val,$dealCat); // array is returned if taxonomy is given

		// $testing = $parent_term;
		
		if (empty($parent_term)) {
			$parent_term = wp_insert_term(
				$val,   // the term 
				$dealCat, // the taxonomy
				array(
					'parent' => $parent_id
				)
			);
		} else {
			wp_update_term(
				$parent_term,   // the term 
				$dealCat, // the taxonomy
				array(
					'parent' => $parent_id
				)
			);
		}
		return $parent_term;
	}


#OLD_CODE = END

============================

#REPPLACENEWCODE = START

	function insertListCategories($val, $dealCat = "deal_cat")
	{
		$parent_term = get_term_by( 'name', $val, $dealCat);
		if( empty($parent_term) ){
			
			$parent_term = wp_insert_term( 
							  $val, 
							  $dealCat,
							  array(
								'description' => 'Parent category',
							  )
							);
			return $parent_term;
		}
		return $parent_term->term_id;
	}



	function instertSubcategory( $val, $dealCat = "deal_cat", $parent_id ){
		$term_check = get_term_by( 'name', $val, $dealCat);
		
		if( empty($term_check)){
			$output_parent_term =   wp_insert_term( 
				$val, 
				$dealCat, 
				array(
					'description' => 'Sub-category',
					'parent' => $parent_id,
				)
			);
			return $output_parent_term;
		}
		return $term_check->term_id;;
	}

#REPPLACENEWCODE = END

// NOTE :    When you want to add parent term OR You want to add child term in Parent term then

//	         instead of this [ term_exists() == FALSE [ this is wrong ] ] ====== [ get_term_by() == TRUE [ Use this for check term ] ]  

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 *  8) FOR Remove Space and return value with proper slug use this function
 */

	function get_strip_all_tags($string, $remove_breaks = false)
	{
		$string = preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);
		$string = strip_tags($string);
		if ($remove_breaks) {
			$string = preg_replace('/[\r\n\t ]+/', ' ', $string);
		}
		return trim($string);
	}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*
  9)  In Example [ https://testing.local/wp-admin/edit.php?post_type=post ]

	+ With Fix Selected value and action like [ Trash post Alert AND ] = If YES Then [ Action Submit ] + Else [ Only Cancel ]

   #REF_URL [ MAIN ] : https://plnkr.co/edit/YTY7PDs5Uh1XGUo9ic1s?p=preview&preview

   #CSS = https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css
   #JS = https://unpkg.com/sweetalert/dist/sweetalert.min.js

   #HELPFUL = https://stackoverflow.com/questions/6799533/how-to-submit-a-form-with-javascript-by-clicking-a-link

*/

	'use strict' ?>

	<script>

	    document.querySelector('#bulk-action-selector-top').addEventListener('change', function(e) {
	    
	        var form = this;
	      
	        e.preventDefault();

	        let selectedvalue = this.value;

	        if( selectedvalue == 'trash' ){
	      
	            swal({
	              title: "Are you sure?",
	              text: 'It will temporary deleted in ' + selectedvalue.toUpperCase(),
	              icon: "warning",
	              buttons: [
	                'No, cancel it!',
	                'Yes, I am sure!'
	              ],
	              dangerMode: true,

	            }).then(function(isConfirm) {

	                  if (isConfirm) {

	                    swal({
	                      title: 'Trash!',
	                      text: 'Post is temporary deleted successfully !',
	                      icon: 'success'

	                    }).then(function() {

	                            var confirm_action = document.getElementById("posts-filter");

	                            confirm_action.submit();

	                            console.log("Yes");

	                    });

	                  } else {

	                    swal("Cancelled", "Your imaginary file is safe :)", "error");

	                  }

	            });

	        }

	    });

	</script>

<?php /*

I just change action like

OLD = First SweetAlert Shown first and then action submit OR Delete shown = This is wrong

So

NEW = When we will give change value of select option to TRASH == 

TRASH VALUE GET THEN LOOP IS START

===

IF TRASH THEN SHOWN

2 buttons 

Button 1) No 

  ACTION IS NORMAL EXIT

Button 2) Yes

	So, Here we will first do Action && ALso with this we will give Sweet Alert Notification for user so user can understand that action is in process...

NEW CHANGES START =======

*/ ?>

	<script type="text/javascript">

		swal({
			title: 'Trash!',
			text: 'Post is temporary deleted successfully !',
			icon: 'success'

		});

		var confirm_action = document.getElementById("posts-filter");

		confirm_action.submit();

	</script>

<?php /*

NEW CHANGES END =======

In Case [ If we hit button [ Cancel ] and then again click on [Confirm] Then issue happend of Loop So I give window.reload so loop continues ] CODE BELOW

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// ?>

	<script type="text/javascript">

		'use strict'

	    document.querySelector('#bulk-action-selector-top').addEventListener('change', function() {
	    
	        var form = this;

	        let selectedvalue = this.value;

	        if( selectedvalue == 'trash' ){
	      
	                swal({

	                    title: "Are you sure?",
	                    text: 'It will temporary deleted in ' + selectedvalue.toUpperCase(),
	                    icon: "warning",
	                    buttons: [
	                    'No, cancel it!',
	                    'Yes, I am sure!'
	                    ],
	                    dangerMode: true,

	                }).then(function(isConfirm) {

	                    if ( isConfirm ) {

	                        swal({
	                          title: 'Trash!',
	                          text: 'Post is temporary deleted successfully !',
	                          icon: 'success'

	                        });

	                        var confirm_action = document.getElementById("posts-filter");

	                        confirm_action.submit();

	                    }else {

	                        swal({
	                            title: 'Cancelled!',
	                            text: 'Your post is safe !',
	                            icon: 'error'
	                        });

	                        window.location.reload();

	                      }

	                });

	        }

	    });

	</script>

<?php /*

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

10) Getting values of multiple selected checkboxes

	+ In Admin [ https://testing.local/wp-admin/edit.php?post_type=product ]

	+ For Get Value of selected checkbox - Condition is for each And Also Multiple checkbox checked and get value

	+ REF URL : https://www.javascripttutorial.net/javascript-dom/javascript-checkbox/

*/ ?>

	<script type="text/javascript">

		const btn = document.querySelector('#bulk-action-selector-top');

		btn.addEventListener('change', (event) => {
		  
		    let checkboxes = document.querySelectorAll('input[name="post[]"]:checked');

		    let values = [];

		    checkboxes.forEach((checkbox) => {
		        values.push(checkbox.value);
		    });

		    alert(values);
		    
		}); 

	</script>


<?php /*

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

11) Getting current page url on wp-admin/admin dashboard?

#REF_URL = https://stackoverflow.com/questions/64828762/getting-current-page-url-on-wp-admin-admin-dashboard

Condition is The Slug is [ ?Page ]

And we want to add this script in this admin page

So we will first find 

1)  in admin url
    + admin_url()

2)  $GET["page"]
    + Page name get

[ full function to get current admin page with url is == admin_url( "admin.php?page=".$_GET["page"] ) ]

*/

	add_action( 'admin_enqueue_scripts', 'custom_script_marketica', 99 );

	function custom_script_marketica(){

		global $pagenow;

		$current_page = admin_url( "admin.php?page=".$_GET["page"] );

		if ( $_GET["page"] == 'wc-admin' ) {

			  wp_enqueue_script( 'testing-checkbox-custom-script', get_template_directory_uri() . '/assets/js/testing-checkbox-custom-script.js', array("jquery"), rand(111,9999), true );

		}

	}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * 
 12) Getting current page url on wp-admin/admin dashboard? && With Post Type [XYZ]
 *   + If we want to add CSS & JS in some fix page in admin dashboard then this condition will use
 */<?php

	if ( admin_url( 'edit.php?post_type=post' ) && ( 'XYZ' == get_post_type() ) ){

	}



/*
   13) Current screen object or null when screen not defined.
*      Useful later : #REF_URL : https://developer.wordpress.org/reference/functions/get_current_screen/
*/

#REF_URL : https://developer.wordpress.org/reference/functions/get_current_screen/


/*
14) Return current page type

#REF_URL https://wordpress.stackexchange.com/questions/83887/return-current-page-type

*/

function wpse8170_loop() {
    global $wp_query;
    $loop = 'notfound';

    if ( $wp_query->is_page ) {
        $loop = is_front_page() ? 'front' : 'page';
    } elseif ( $wp_query->is_home ) {
        $loop = 'home';
    } elseif ( $wp_query->is_single ) {
        $loop = ( $wp_query->is_attachment ) ? 'attachment' : 'single';
    } elseif ( $wp_query->is_category ) {
        $loop = 'category';
    } elseif ( $wp_query->is_tag ) {
        $loop = 'tag';
    } elseif ( $wp_query->is_tax ) {
        $loop = 'tax';
    } elseif ( $wp_query->is_archive ) {
        if ( $wp_query->is_day ) {
            $loop = 'day';
        } elseif ( $wp_query->is_month ) {
            $loop = 'month';
        } elseif ( $wp_query->is_year ) {
            $loop = 'year';
        } elseif ( $wp_query->is_author ) {
            $loop = 'author';
        } else {
            $loop = 'archive';
        }
    } elseif ( $wp_query->is_search ) {
        $loop = 'search';
    } elseif ( $wp_query->is_404 ) {
        $loop = 'notfound';
    }

    return $loop;
}

/**
 * 15) WooCommerce: Get Product Info (ID, SKU, $) From $product Object
 * 
 * #REF_URL = https://www.businessbloomer.com/woocommerce-easily-get-product-info-title-sku-desc-product-object/ 
*/


/**
 * 16) Global_Variables
 * 
 * #USEFUL_URL = #USEFUL_URL = [ https://codex.wordpress.org/Global_Variables ]
 * 
 * #REF_URL = https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/forEach#syntax
 * 
*/

/**
 * 17) JS CODE = Multiple Actions
 * 
*/	

	// 1) Condition 1 :

	//     => When user click on this [ Submit Button =>> [ id="doaction" ] ]
	
	// 2) Condition 2 :

	//     2.1 => After [Submit Button] Click ... Check Selected Value in select Options ... If this selected options value is [trash] :
	// 	2.1 => After [Submit Button] Click ... Check Minimum 1 [ONE] Checkbox is checked [ From All Checkbox : Min 1 is selected ]
	// 	       IF this checkbox check value is TRUE


	// 	In this Condition 2 [ Selected Option is TRASH && Also Checkbox any 1 is checked minimun ]
	// 	Then it will going on loop 

	// 3) Condition 3 :

	// 	=> In this Condition 2 [ Selected Option Value is (TRASH) && Also Checkbox any 1 is checked minimun ]

	// 	If( This Both Condition is TRUE )

	// 	3.1 [ SweetAlert Shown ]
	// 	3.2 [ SweetAlert Shown == YES [Button] ]
	// 	3.2 [ SweetAlert Shown == NO [Button] ]

	// 	3.1 = We will give sweetalert for user that user want to delete this post in question ?
	// 	3.2 = If user is click on yes => Means user confirm this action
	// 	3.2 = If user is click on yes => Means user cancel this action		

		// MAIN CODE */ ?>
			// <script type="text/javascript">

			// 	jQuery("#doaction").click(function( e ) {

			// 		e.preventDefault();

			// 		// console.log("Submit Button Click Event Happened");

			// 		var selected_checkbox_value = document.getElementById( 'bulk-action-selector-top' ).value;

			// 		let checkboxes = document.querySelector( 'input[name="post[]"]' );

			// 		if( selected_checkbox_value == 'trash' && ( checkboxes.checked == true ) ){
				
			// 				swal({

			// 					title: "Are you sure?",
			// 					text: 'It will temporary deleted in ' + selected_checkbox_value.toUpperCase(),
			// 					icon: "warning",
			// 					buttons: [
			// 					'No, cancel it!',
			// 					'Yes, I am sure!'
			// 					],
			// 					dangerMode: true,

			// 				}).then(function(isConfirm) {

			// 					if ( isConfirm ) {

			// 							var confirm_action = document.getElementById("posts-filter");

			// 							confirm_action.submit();

			// 							//console.log("Yes");

			// 								swal({
			// 								title: 'Trash!',
			// 								text: 'Post is temporary deleted successfully !',
			// 								icon: 'success'

			// 								});

			// 					}else {

			// 						window.location.reload();

			// 						// console.log("No");

			// 						swal({
			// 							title: 'Cancelled!',
			// 							text: 'Your post is safe !',
			// 							icon: 'error'

			// 						});

			// 					}

			// 				});

			// 		}

			// 	}); 

			// </script>

				// jQuery("#doaction").click(function( e ) {

				//     e.preventDefault();

				//     // console.log("Submit Button Click Event Happened");

				//     var selected_checkbox_value = document.getElementById( 'bulk-action-selector-top' ).value;

				//     let checkboxes = document.querySelector( 'input[name="post[]"]' );

				//     if( selected_checkbox_value == 'trash' && ( checkboxes.checked == true ) ){
				
				//             swal({

				//                 title: "Are you sure?",
				//                 text: 'It will temporary deleted in ' + selected_checkbox_value.toUpperCase(),
				//                 icon: "warning",
				//                 buttons: [
				//                 'No, cancel it!',
				//                 'Yes, I am sure!'
				//                 ],
				//                 dangerMode: true,

				//             }).then(function(isConfirm) {

				//                 if ( isConfirm ) {

				//                         var confirm_action = document.getElementById("posts-filter");

				//                         confirm_action.submit();

				//                         //console.log("Yes");

				//                             swal({
				//                               title: 'Trash!',
				//                               text: 'Post is temporary deleted successfully !',
				//                               icon: 'success'

				//                             });

				//                 }else {

				//                     window.location.reload();

				//                     // console.log("No");

				//                     swal({
				//                         title: 'Cancelled!',
				//                         text: 'Your post is safe !',
				//                         icon: 'error'

				//                     });

				//                 }

				//             });

				//     }

				// });<?php

		// MAIN CODE

		// [ PRACTICE CODE EXTRA START ]
		<?php

				// document.getElementById("posts-filter").addEventListener("submit", function(){

				//     //console.log("YEs In");


				//     document.querySelector('#bulk-action-selector-top').addEventListener('change', function() {
				    
				//         var form = this;

				//         let selectedvalue = this.value;

				//         if( selectedvalue == 'trash' ){
				      
				//                 swal({

				//                     title: "Are you sure?",
				//                     text: 'It will temporary deleted in ' + selectedvalue.toUpperCase(),
				//                     icon: "warning",
				//                     buttons: [
				//                     'No, cancel it!',
				//                     'Yes, I am sure!'
				//                     ],
				//                     dangerMode: true,

				//                 }).then(function(isConfirm) {

				//                     if ( isConfirm ) {

				//                         swal({
				//                           title: 'Trash!',
				//                           text: 'Post is temporary deleted successfully !',
				//                           icon: 'success'

				//                         });

				//                         var confirm_action = document.getElementById("posts-filter");

				//                         confirm_action.submit();

				//                     }else {

				//                         swal({
				//                             title: 'Cancelled!',
				//                             text: 'Your post is safe !',
				//                             icon: 'error'
				//                         });

				//                         window.location.reload();

				//                       }

				//                 });

				//         }

				//     });


				// });

			    // document.querySelector('#bulk-action-selector-top').addEventListener('change', function() {
			    
			    //     var form = this;

			    //     let selectedvalue = this.value;

			    //     if( selectedvalue == 'trash' ){
			      
			    //             swal({

			    //                 title: "Are you sure?",
			    //                 text: 'It will temporary deleted in ' + selectedvalue.toUpperCase(),
			    //                 icon: "warning",
			    //                 buttons: [
			    //                 'No, cancel it!',
			    //                 'Yes, I am sure!'
			    //                 ],
			    //                 dangerMode: true,

			    //             }).then(function(isConfirm) {

			    //                 if ( isConfirm ) {

			    //                     swal({
			    //                       title: 'Trash!',
			    //                       text: 'Post is temporary deleted successfully !',
			    //                       icon: 'success'

			    //                     });

			    //                     var confirm_action = document.getElementById("posts-filter");

			    //                     confirm_action.submit();

			    //                 }else {

			    //                     swal({
			    //                         title: 'Cancelled!',
			    //                         text: 'Your post is safe !',
			    //                         icon: 'error'
			    //                     });

			    //                     window.location.reload();

			    //                   }

			    //             });

			    //     }

			    // });


				// document.querySelector('#bulk-action-selector-top').addEventListener('change', function() {

				// console.log( "Hello all !" );




				//     var selected = new Array();

				//     $("#posts-filter input[type=checkbox]:checked").each(function () {
				//         selected.push(this.value);
				//     });




				    // var ele = document.getElementsByName('post[]');  

				    // for(var i=0; i<ele.length; i++){  
				        
				    //     if(ele[i].type=='checkbox')  
				            

				    //         ele[i].checked=true;  
				    

				    // }



				// n = jQuery("input:checked").length;



				 // if (jQuery(this).prop("checked") == true) {

				 //    console.log( "Value is Exists" );

				 // }else{

				 //    console.log( "Error ! Not Found" );

				 // }


				//console.log($('input[name="locationthemes"]:checked').serialize());



				        // jQuery(".price").change(function (i) {

				            // n = jQuery("input:checked").length;


				            // if (jQuery(this).prop("checked") == true) {

				            //     // jQuery(".price column-price").show();

				            //     alert( jQuery(".price column-price").value );

				            //     console.log( "Yes" );

				            // } else {
				            //     if (n == 0) {

				            //         jQuery(".price column-price").hide();

				            //         console.log( "No" );

				            //     }
				            // }


				        // });



				// });

		?>
		//<!-- [ PRACTICE CODE EXTRA START ] -->
	
	<?php


	
/**
 * 18)  
 * 
 * 	   If you want to flush rules while updating posts based on post type:
 *     REF_URL : https://developer.wordpress.org/reference/functions/flush_rewrite_rules/
 * 
*/	
	function wpdoc_flush_rules_on_save_posts( $post_id ) {

		// Check the correct post type.
		// Example to check, if the post type isn't 'post' then don't flush, just return.
		if ( ! empty( $_POST['post_type'] && $_POST['post_type'] != 'post' ) {
			return;
		}
	
		flush_rewrite_rules();
	
	}
	
	add_action( 'save_post', 'wpdoc_flush_rules_on_save_posts', 20, 2);


/**
 * 19)  
 * 
 * 	   PHP: How to compare WordPress versions?
 *     REF_URL : https://stackoverflow.com/questions/42506050/php-how-to-compare-wordpress-versions
 * 
*/	


/**
 * 20)  
 * 
 * 	   Add_action in class and use it in theme
 * 
 *     REF_URL : https://wordpress.stackexchange.com/questions/62979/add-action-in-class-and-use-it-in-theme
 * 
*/	
class My_Plugin
{
    private $var = 'foo';

    protected static $instance = NULL;

    public static function get_instance()
    {
        // create an object
        NULL === self::$instance and self::$instance = new self;

        return self::$instance; // return the object
    }

    public function __construct()
    {
        // set up your variables etc.
    }

    public function foo()
    {
        return $this->var;
    }
}

// create an instance on wp_loaded
add_action( 'wp_loaded', array( 'My_Plugin', 'get_instance' ) );


// 21) Calls the callback functions that have been added to an action hook.

#RFE_URL = https://developer.wordpress.org/reference/functions/do_action/


// The action callback function.
function example_callback( $arg1, $arg2 ) {
    // (maybe) do something with the args.
}
add_action( 'example_action', 'example_callback', 10, 2 );

/*
 * Trigger the actions by calling the 'example_callback()' function
 * that's hooked onto `example_action` above.
 *
 * - 'example_action' is the action hook.
 * - $arg1 and $arg2 are the additional arguments passed to the callback.
do_action( 'example_action', $arg1, $arg2 );



*** https://developer.wordpress.org/apis/security/ ****

1] https://developer.wordpress.org/apis/security/sanitizing/
2] https://developer.wordpress.org/apis/security/escaping/
3] https://developer.wordpress.org/apis/security/data-validation/

// 22) Escaping with Localization

#RFE_URL = https://developer.wordpress.org/apis/security/escaping/

https://developer.wordpress.org/apis/security/escaping/

esc_html_e( 'Hello World', 'text_domain' );
// Same as
echo esc_html( __( 'Hello World', 'text_domain' ) );


=============================================================================================================
esc_html() – Use anytime an HTML element encloses a section of data being displayed. This will remove HTML.

<h4><?php echo esc_html( $title ); ?></h4>


=============================================================================================================
esc_js() – Use for inline Javascript.


<div onclick='<?php echo esc_js( $value ); ?>' />
=============================================================================================================


esc_url() – Use on all URLs, including those in the src and href attributes of an HTML element.

<img alt="" src="<?php echo esc_url( $media_url ); ?>" />

=============================================================================================================




23) In admin_enqueue_scripts : Callback Function : Must Pass Argument [ $hooks ]


    + If you simply pass condition and it works it's good for now but later if this [ $hooks not added ] then issue happend in admin dashboard. So that Whenever you add style and Script in 
	  Admin Enqueque Scripts [ Add Argument $hooks and Verify with page ]
	
	CODE :

	public function testing_custom_scripts_admin( $hook ) {

		global $pagenow;

		$check_page = ( 'admin.php' == $pagenow ) && ( $_GET["page"] == 'theme-op-settings' );

		if( $check_page != $hook ){

			return;

		}

		wp_enqueue_script( 'testing-plugin-alert-custom-script', plugin_dir_url(__FILE__) . '/assets/js/plugin-testing-alert-custom-script.js' , array("jquery"), rand(111,9999), true );

	}


=============================================================================================================

add_action( 'admin_enqueue_scripts', 'custom_script_marketica', 99 );

function custom_script_marketica( $hook ) {

    global $pagenow;

    $check_page = ( 'admin.php' == $pagenow ) && ( $_GET["page"] == 'wcv-commissions' );

    if( $check_page != $hook ){

        return;

    }

    wp_enqueue_style( 'sweetalert-new', get_stylesheet_directory_uri() . '/css/sweetalert.min-new.css', array(), rand(111,9999), 'all' );

    wp_enqueue_script( 'sweetalert-new', get_stylesheet_directory_uri() . '/js/sweetalert.min-new.js', array("jquery"), rand(111,9999), true );

    wp_enqueue_script( 'custom-alert-script', get_stylesheet_directory_uri() . '/js/custom-alert-script.js', array("jquery"), rand(111,9999), true );

}

=============================================================================================================


marketica-wp

author.php

62

<section class="content-area" id="content">
	<div id="container">
	<div class="section-user-biography">
		<div class="user-biography">
		    <?php if ( $user->user_description ) : ?>
			    <?php echo wpautop( $user->user_description ); ?>
		    <?php else : ?>
		    	<?php printf( __( '%s does not have personal biography.', 'marketica-wp' ), $user->display_name ); ?>
		    <?php endif; ?>
		</div>
	    <?php do_action( 'tokopress_section_user_biography' ); ?>
	</div>
	</div>
</section>

=============================================================================================================

24) Command Use

https://www.digitalcitizen.life/command-prompt-how-use-basic-commands/

=============================================================================================================

25) WooCommerce PayPal Payments

https://woocommerce.com/document/woocommerce-paypal-payments/











global $product;

// $product_single_ID = get_the_ID();


// echo "ID : " $product_single_ID;
// echo "<br/>";




$product_id = get_the_ID();

$product = wc_get_product($product_id);



echo "Name is START" . "<br/>";
echo $product->get_name() . "<br/>";
echo "Name is END" . "<br/><br/>";



echo "Price is START" . "<br/>";
echo $product->get_price() . "<br/>";
echo "Price is END" . "<br/><br/>";



echo "Get downloadable is START" . "<br/>";
echo $product->get_downloadable() . "<br/>";
echo "Get downloadable is END" . "<br/><br/>";



echo "Get downloads is START" . "<br/>";
echo "<pre>";
echo print_r( $product->get_downloads() ) . "<br/>";
echo "</pre>";
echo "Get downloads is END" . "<br/><br/>";



echo "Get a file by is START" . "<br/>";
echo "<pre>";
echo print_r( $product->get_file() ) . "<br/>";
echo "</pre>";
echo "Get a file by is END" . "<br/>";

echo "<br/>";


echo "END";


	foreach ($variable as $key => $value) {
		# code...
	}






/**
 *  Teacha Project Code - For Excerpt length
 */
// function tokopress_text_limiter($text, $limit = 25, $ending = '...') {
// 	if ( strlen($text) > $limit ) {

// 		$text = wp_strip_all_tags($text);
// 		$text = substr($text, 0, $limit);
// 		$text = substr($text, 0, -(strlen(strrchr($text, ' '))));
// 		$text = $text . $ending;

// 	}

// 	return $text;
// }




/**
 * Text change of Product
 */
// add_filter('gettext', 'translate_text');
// add_filter('ngettext', 'translate_text');
// function translate_text($translated)
// {
//   $translated = str_ireplace('Product', 'Resource', $translated);
//   $translated = str_ireplace('Product Categories', 'Resource Categories', $translated);
//   $translated = str_ireplace('Coupon', 'Discount', $translated);
//   return $translated;
// }




// DataTables Server Side Processing in WordPress
// https://itsmereal.com/datatables-server-side-processing-in-wordpress/




/**
 * Text change of Vendor
 */
// add_filter('gettext', 'vendor_translate_text');
// add_filter('ngettext', 'vendor_translate_text');
// function vendor_translate_text($translated)
// {
//   $translated = str_replace('Vendor', 'Seller', $translated);
//   return $translated;
// }




/* For hide the filter from bottom of the edit user page */
// add_action('in_admin_footer', function () {
// 	global $pagenow;
// 	if ('users.php' == $pagenow) {
// 	
// 	  <script type="text/javascript">
// 		jQuery(".tablenav.bottom select[name='auth_mech_bottom']").remove();
// 		jQuery(".tablenav.bottom input.store").remove();
// 	  </script>
// 	
// 	}
//   });


//Add Filter

// add_filter( 'tokopress_customize_google_fonts', 'tokopress_customize_google_fonts_filter' );
// function tokopress_customize_google_fonts_filter( $value ) {
// 	$value['Merriweather Sans'] = 'Merriweather Sans';
// 	$value['Maven Pro'] = 'Maven Pro';
// 	return $value;
// }

//$fonts = array();
// $fonts = apply_filters( 'tokopress_customize_google_fonts', $fonts );
// $weights = apply_filters( 'tokopress_customize_font_weights', array('300','700') );
// if ( !$weights ) {
// 	$weights = array('300','700');
// }
// if ( is_array( $fonts ) && !empty( $fonts ) ) {
// 	$googlefonts = array();
// 	foreach ( $fonts as $font ) {
// 		$googlefonts[] = urlencode($font).':'.implode( ',', $weights );
// 	}
// 	$stylesheet = 'https://fonts.googleapis.com/css?family='.implode( '|', $googlefonts );
// 	wp_enqueue_style( 'googlefonts', $stylesheet );
// }





// function tokopress_customize_wp_filter_post_kses( $data ) {
// 	$data = stripslashes( $data );
//     return wp_kses( $data, 'post' );
// }

// function tokopress_customize_sanitize_html( $html ) {
// 	if ( is_array( $html ) ) {
// 		return array_map( 'tokopress_customize_wp_filter_post_kses', $$html );
// 	}
// 	else {
// 		return tokopress_customize_wp_filter_post_kses( $html );
// 	}
// }

// function tokopress_customize_wp_filter_nohtml_kses( $data ) {
//     return wp_kses( stripslashes( $data ), 'strip' );
// }

// function tokopress_customize_sanitize_nohtml( $nohtml ) {
// 	if ( is_array( $nohtml ) ) {
// 		return array_map( 'tokopress_customize_wp_filter_nohtml_kses', $nohtml );
// 	}
// 	else {
// 		return tokopress_customize_wp_filter_nohtml_kses( $nohtml );
// 	}
// }


//Spinner - Loader - Pre Loader
// https://tobiasahlin.com/spinkit/



// Footer Widget Direct set in footer.php

/*
<div class="footer-widgets">

<?php if( is_active_sidebar( 'footer_widget_1' ) ) :

	<?php dynamic_sidebar( 'footer_widget_1' );

else :
	
	<?php
	$footer_widget_1_args = array(
		'before_widget' => '<div class="widget footer-widget widget_archive">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>'
	);

	the_widget( 'WP_Widget_Archives', array(), $footer_widget_1_args );

endif;

</div>

*/

/**
 *  Breadcrumb Functions Customization
 *  URL = https://github.com/themehybrid
 */
function testing_breadcrumb() {
	breadcrumb_trail(
		array(
			'container' => 'nav',
			'container_class' => 'breadcrumb-trail breadcrumbs',
			'separator' => '<i class="fa fa-angle-right"></i>',
			'labels'    => array(
				'browse' => ''
			),
			'markup_type'=>'no-list',
			'post_taxonomy' => array(
				'post'  => 'category',
			),
		)
	);
}









/**
 *  Simply Class Structure To Add And Start work = Action and Callback With
 */

 <?php

/**
 * =====================================================
 * Class Exists Check = [ Class_Theme_Functions ]
 * =====================================================
 */
if ( ! class_exists( 'Class_Theme_Functions' ) ) {

    class Class_Theme_Functions{

        /**
         * ================================
         * The single instance of the class
         * ================================
         */
        protected static $_instance = null;

        /**
         * =======================================
         * Main Plugins Instance
         * =======================================
         * Ensures only one instance of this class
         * =======================================
         */
        public static function get_instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * ===============
         * Register Hoooks
         * ===============
         */
        public function __construct() {

            /**
             * =======
             * Actions
             * =======
             */
            add_action( 'save_post', array( $this, 'testing_custom_plugin_save_posts' ) );
            
        }

        /**
         * =======================
         * Handle saving post data
         * =======================
         */
        public function testing_custom_plugin_save_posts() {
            // do stuff here...
        }


    }

    $class_theme_functions = Class_Theme_Functions::get_instance();

}







echo "START";

global $product;

// Check if the global $product variable is a product object 
if ( ! is_a( $product, 'WC_Product' ) ) {
   //the $product variable is not a product object
}

echo $product->get_name();



echo ""

// $product_ids = get_posts( array(
//    'post_type' => 'product',
//    'numberposts' => -1,
//    'post_status' => 'publish',
//    'fields' => 'ids',
//    'meta_query' => array( array(
//       'key' => '_downloadable',
//       'value' => 'yes',
//       'compare' => '=',
//    )),
// ));
 
// Print array on screen
//print_r( $product_ids );


?>

	<?php /*the_ID()

	// $single_product = array(

	// 	'post_type'  => 'product',
	// 	'meta_query' => array( array(
	// 	  'key' => '_downloadable',
	// 	  'value' => 'yes',
	// 	  'compare' => '=',
	// 	)),

	// );

	// $wc_query = new WP_Query( $single_product );
	// global $post, $product;






/**
 *  In Custom Page Templet : We get our data but for get proper Archive Title this helpful code
 */

#REF =  https://clubmate.fi/get-custom-taxonomy-term-name-on-an-archive-page-on-wordpress

$tax = $wp_query->get_queried_object();
echo $tax->name




Post in

post_in
https://wordpress.org/support/topic/pull-in-two-posts-to-custom-post-type-cpt-single-php-file-with-wp_query/


$args_new = array(
	'post_type' => 'product',
	'post__in' => array('34156'),
	'order' => 'ASC'
 );
 $the_query_new = new WP_Query($args_new);
 if($the_query_new->have_posts()):
	 while($the_query_new->have_posts() ): $the_query_new->the_post();
		echo '<h2>'.the_title().'</h2>';
	 endwhile;
 endif;
 wp_reset_postdata();



 PHP

 inline condition

 ( this == true ) ? "Yes" : "No"

 Another Example is like = For true

 ( this == true ) && "Yes"

 Another Example is like = For false

 ( this == true ) ?? "No"





Basic Start AJax with $this

https://stackoverflow.com/questions/43557755/how-to-call-ajax-in-wordpress = [ AJAX URL ]\

jQuery(document).ready(function($){
    $("#doaction").click(function (e) {
        e.preventDefault();
       // Create an Array.
       var selected = new Array();

       // Reference the CheckBoxes and insert the checked CheckBox value in Array.
        $("input[name='post[]']:checked").each(function () {
            selected.push(this.value);
        });

       // Display the selected CheckBox values.
        if (selected.length > 0) {
           var checbox_val = selected.join(",");
        }

        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: { 
                action : 'get_ajax_posts',
                check: checbox_val,
            },
            success: function( response ) {
             console.log(response);
          
            }
        });
       
    });

});
 


function get_ajax_posts() {
	$bool = $_POST['check'];
	echo "<pre>"; print_r($bool); echo "</pre>";
	die();
  }
  
  // Fire AJAX action for both logged in and non-logged in users
  add_action('wp_ajax_get_ajax_posts', 'get_ajax_posts');
  add_action('wp_ajax_nopriv_get_ajax_posts', 'get_ajax_posts');
  
  




  NEW Custom Code Like :

  // All Products Is Pass in Ajax Query with One Single CLick

=> IN PHP FILE

<div class="btn-all-add-to-cart">
	<a href="<?php echo json_encode($single_product_id); ?>" id="bhoomikafun" class="button">
		Add all to cart
	</a>
</div>

=> In JS Script File

var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";


jQuery(document).ready(function($){

	$("#bhoomikafun").click(function (e) {
		e.preventDefault();

		var product_ids = $(this).attr('href');

		$.ajax({
			type: 'GET',
			url:   my_ajax_object.ajax_url,
			data: { 
				action : 'get_products_ids',
				product_ids: product_ids,
			},
			success: function( response ) {
			console.log(response);
		
			}
		});

	});

});

=> In functions.php file

/**
 * 
 *  Get list of all product categories in WooCommerce
 */
If woocommerce have products category [ Term data ] then display in menu with ABCD [ Order ]

I did applfilter and add action

https://developer.wordpress.org/reference/hooks/wp_nav_menu_items/

REF = https://phptechnologytutorials.wordpress.com/2018/03/10/get-list-of-all-product-categories-in-woocommerce/



Helpful Extract Array & implode

echo print_r( $args );

echo "Second </br>";

$new_ = implode(',', $args);

echo $new_;

===================================================================================================================


Tutorial: How to Add a Custom Bulk Action in WordPress Admin


Adding a custom bulk action in the dropdown

#REF LINK


https://awhitepixel.com/blog/wordpress-admin-add-custom-bulk-action/





Helpful - SweetAlert In 3rd level Check


jQuery(document).ready(function($){

    document.querySelector('#doaction').addEventListener('click', function(e) {

        e.preventDefault();

            $("table > tbody > tr").each(function () {

                var $tr = $(this);             

                    if ( $tr.find('input[name="post[]"]').is(":checked") ) {

                            var selected_option_value = $('#bulk-action-selector-top').val();

                            if( selected_option_value == "add_to_services" ){

                                console.log("1st - You want to add this product in collection!");
                  
                                            swal({
                                              title: "Are you sure?",
                                              text: "You want to add this product in collection!",
                                              icon: "warning",
                                              buttons: [
                                                'No, cancel it!',
                                                'Yes, I am sure!'
                                              ],

                                              dangerMode: true,

                                            }).then(function(isConfirm) {

                                              if (isConfirm) {

                                                console.log("2nd - Yessss");

                                                    swal({

                                                      title: 'Confirm To Add ?',
                                                      text: 'Sure ?',
                                                      icon: 'success',
                                                      buttons: [
                                                        'Not Confirm',
                                                        'Yes Plz Add'
                                                      ],

                                                    }).then(function( isConfirm ) {

                                                        if (isConfirm) {

                                                            console.log("3nd - Yessss");

                                                          //form.submit();

                                                            var confirm_action = $('#bulk-action-selector-top');

                                                            confirm_action.submit();

                                                            console.log("Submitted Done");

                                                        }else{

                                                            console.log("3nd - Noooo - Cancel All");
                                                        }

                                                    });

                                              } else {

                                                console.log("2nd - Noooo");

                                                swal("Cancelled", "Your imaginary file is safe :)", "error");
                                              
                                              }

                                            });

                            }

                    }

            });
        });
    });


   // jQuery('#doaction').on("click", function() {

   //    $("table > tbody > tr").each(function () {

   //       var $tr = $(this);

   //       if ($tr.find('input[name="post[]"]').is(":checked")) {

   //          var selected_option_value = $('#bulk-action-selector-top').val();

   //          if( selected_option_value == "add_to_services" ){

   //                   swal({

   //                       title: "Are you sure?",
   //                       text: "You want to add this product in collection!",
   //                       icon: "warning",
   //                       buttons: [
   //                       'No, cancel it!',
   //                       'Yes, I am sure!'
   //                       ],
   //                       dangerMode: true,

   //                   }).then(function(isConfirm) {

   //                       if ( isConfirm ) {

   //                           swal({
   //                             title: 'Plz Choose in Which collection Post ?',
   //                             text: 'You want to add this products !',
   //                             icon: 'success',
   //                             buttons: [
   //                               'No, cancel it!',
   //                               'Yes, I am sure!'
   //                               ],

   //                           }).then(function(isConfirm) {

   //                              // swal({
   //                              //     title: 'Plz Choose in Which collection Post ?'
   //                              // });


   //                                   swal({
   //                                     title: '12',
   //                                     text: '12',
   //                                     icon: 'success',
   //                                       buttons: [
   //                                       'No, cancel it!',
   //                                       'Yes, I am sure!'
   //                                       ],
   //                                   }).then(function(isConfirm) {

   //                                      swal({
   //                                          title: 'Plz Choose in Which collection Post ?'
   //                                      });

   //                                   });

   //                           });

   //                           //var confirm_action = document.getElementById("posts-filter");

   //                           //confirm_action.submit();

   //                       }else {

   //                           swal({
   //                               title: 'Cancelled!',
   //                               text: 'This Commissions is safe !',
   //                               icon: 'error'
   //                           });
   //                       }

   //                   });


   //          }

              

   //          }
   //    });


   // });

//});


==============================================================================================================
Important : In SweetAlert - Want To Add Select Option THen Ref. Link is Below :

https://stackoverflow.com/questions/43266673/missing-select-options-with-sweet-alert

https://reqbin.com/code/php/u1at9ko5/php-get-request-example




https://stackoverflow.com/questions/12595604/add-meta-key-and-meta-value-to-post-in-wordpress-programmatically



==============================================================================================================

17-03-23

WordPress

Basics of Using WordPress WP_Query + Examples With Code


https://www.hostinger.in/tutorials/wordpress-wp_query#:~:text=WP_Query%20is%20a%20class%20in,%2C%20author%2C%20and%20custom%20fields.


Inline Condition 1 

( $product_img_data ) ? '<span class="ct-image-container"><img src="'. esc_url( $product_img_data ) .'"></span>' : '',


Inline Condition 2
For = Get Post Meta and $var check direct if yes then data else null
( $product_img_data ) ? '<span class="ct-image-container"><img src="'. esc_url( $product_img_data ) .'"></span>' : '',




Product Term Get

$format_att = array_shift( wc_get_product_terms( $product->id, 'pa_format', array( 'fields' => 'names' ) ) );
if($format_att != '') { ?>
	<div class="product_format">
	 <?php echo "<span>".$format_att."</span>" ?>
   </div>
<?php } ?>







function wpdocs_enqueue_scripts( $hook ) {
	// Load only in add new post page
	if ( is_admin() && 'post-new.php' !== $hook ) {
		return;
	}

	// rest of your code here..
}
add_action( 'wp_enqueue_scripts', 'wpdocs_enqueue_scripts' );





https://make.wordpress.org/core/2016/10/04/custom-bulk-actions/



An option in the dropdown

add_filter( 'bulk_actions-edit-post', 'register_my_bulk_actions' );
 
function register_my_bulk_actions($bulk_actions) {
  $bulk_actions['email_to_eric'] = __( 'Email to Eric', 'email_to_eric');
  return $bulk_actions;
}