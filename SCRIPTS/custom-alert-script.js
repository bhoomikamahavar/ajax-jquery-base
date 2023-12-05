/**
 * 
 */

jQuery(document).ready(function($){
    $("#doaction").click(function (e) {
        e.preventDefault();
        
     
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
            success: function( data ) {
                    if(data)
                    {
                        console.log(data);
                    }
                    else
                        {
                      console.log('error')
                        }
          
            }
        });
       
    });

    });
 



