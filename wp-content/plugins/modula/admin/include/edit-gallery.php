<?php
    function tg_p($gallery, $field, $default = NULL)
    {    
    	global $modula_options;
    	
    	if($modula_options) {
    		print stripslashes($modula_options[$field]);
    		return;
    	}
    	
        if($gallery == NULL || $gallery->$field === NULL) 
        {
            if($default === NULL)
            {
                print "";
            }
            else
            {
                print stripslashes($default);
            }
        } 
        else 
        {
            print stripslashes($gallery->$field);
        }
    }
    function tg_sel($gallery, $field, $value)
    {
    	global $modula_options;

    	if($modula_options && $modula_options[$field] == $value) {
    		print "selected";
    		return;
    	}
    	
        if($gallery == NULL)
            print "";
        else
            if($gallery->$field == $value)
                print "selected";
    }

    global $modula_parent_page;
    global $modula_options;

?>
    
			
            
            <script>
            //jQuery("tr:even").addClass("alternate");
            var modula_wp_caption_field = '<?php tg_p($gallery, "wp_field_caption")  ?>';
           </script>