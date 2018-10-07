<table class="wp-list-table widefat fixed bookmarks wmle_doc_table">
    <thead>
        <tr>
            <th><strong>Create Shortcode</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
			<div class="documentation_content">
            <ol>
                <li>Goto WP Masonry &gt; Shortcodes</li>
                <li>Under Create Shortcode Section, select the options as per your preferences.</li>
                <li>Click Save.</li>
                <li>Your shortcode will be added to Shortcodes section.</li>
                <li>Copy the shortcode and place it in your post / page editor or in theme template files.</li>
            </ol>
            <strong>Reference : You can keep these shortcode in theme template files like this &lt;?php echo do_shortcode('SHORTCODE HERE');&nbsp; ?&gt;</strong>
            <br /><br />
            <img alt="Create Masonry Brick Shortcode" src="<?php echo plugins_url("wp-masonry-layout/images/create_shortcode.png"); ?>" class="aligncenter size-full wp-image-426">
            
            </div>
            
        </td>
     </tr>
  </tbody>
</table><br/>
<table class="wp-list-table widefat fixed bookmarks wmle_doc_table">
    <thead>
        <tr>
            <th><strong>Edit / Delete Shortcode</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
			<div class="documentation_content">
            <ol>
                <li>Select Shortodes under WP Masonry</li>
                <li>Click on Edit to edit the shortcode or Delete to delete.</li>
                <li>Change settings.</li>
                <li>Click on Save.</li>
            </ol>
            <strong>Note : You don't need to copy/paste the shortcode after the settings has been changed. The shortcode remains same.</strong><br /><br />
            <img alt="Create Masonry Brick Shortcode" src="<?php echo plugins_url("wp-masonry-layout/images/edit_shortcode.png"); ?>" class="aligncenter size-full wp-image-426">
            
            </div>
            
        </td>
     </tr>
  </tbody>
</table><br/>
<script>
jQuery(document).ready(function(){
    jQuery('.wmle_doc_table thead').click(function(){
          jQuery(this).parent().children('tbody').slideToggle();
    });
});
</script>