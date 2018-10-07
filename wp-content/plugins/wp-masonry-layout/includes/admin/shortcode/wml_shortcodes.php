<?php
// ADD / UPDATE SHORTCODE
if(isset($_POST['submit-wmp-shortcode'])){
	//Check Add Or Edit
	if (empty($_POST['wmlo_shortcode_id'])){
		$wmlo_shortcode_id = get_option('wmlo_primary_key');
		update_option('wmlo_primary_key',$wmlo_shortcode_id + 1 );
	} else {
		$wmlo_shortcode_id = $_POST['wmlo_shortcode_id'];
	}
	
	// KEEP DATA IN ARRAY
	$shortcodeDataInput = array(
						   'wmlo_reference_name'		=> $_POST['wmlo_reference_name'],
						   'wmlo_layout_theme'			=> $_POST['wmlo_layout_theme'],
						   'wmlo_columns'				=> $_POST['wmlo_columns'],
						   'wmlo_post_type'				=> $_POST['wmlo_post_type'],
						   'wmlo_post_category'			=> $_POST['post_category'],
						   //'wmlo_product_category'		=> $_POST['wmlo_product_category'],
						   'wmlo_tax_input'				=> $_POST['tax_input'],
						   'wmlo_page_parent'			=> $_POST['wmlo_page_parent'],
						   'wmlo_image_size'			=> $_POST['wmlo_image_size'],
						   'wmlo_post_count'			=> $_POST['wmlo_post_count'],
						   'wmlo_order_by'				=> $_POST['wmlo_order_by'],
						   'wmlo_order'					=> $_POST['wmlo_order'],
						   'wmlo_pagination_style'		=> $_POST['wmlo_pagination_style'],
						   'wmlo_responsive'			=> $_POST['wmlo_responsive'],
						   'wmlo_use_lightbox'			=> $_POST['wmlo_use_lightbox'],
						   'wmlo_use_lazyload'			=> $_POST['wmlo_use_lazyload']
						   );
	
	$shortcodesRawData 	= get_option('wmlo_shortcodes_data');
	$shortcodesData		= json_decode($shortcodesRawData, true);
	if (empty($shortcodesData)):
		$shortcodesData = array();
	endif;
	
	$shortcodesData[$wmlo_shortcode_id]	= $shortcodeDataInput;
	$updateshortcodesData				= json_encode($shortcodesData);
	update_option('wmlo_shortcodes_data',$updateshortcodesData);
	$shortcodes_msg 		= 'Shortcode Saved';
	$shortcodes_msg_status 	= 'updated';
	
} // EOF $_POST['submit-wmp-shortcode'] check


// DELETE SHORTCODE
if (isset($_GET['delete_shortcode_key'])){
	$deleteShortcodeId 		= $_GET['delete_shortcode_key'];
	$shortcodesRawData 		= get_option('wmlo_shortcodes_data');
	$shortcodesData			= json_decode($shortcodesRawData, true);
	unset($shortcodesData[$deleteShortcodeId]);
	$updateshortcodesData	= json_encode($shortcodesData);
	update_option('wmlo_shortcodes_data',$updateshortcodesData);
	$shortcodes_msg 		= 'Shortcode Deleted';
	$shortcodes_msg_status 	= 'updated';
}

$shortcodesRawData 	= get_option('wmlo_shortcodes_data');
$shortcodesData		= json_decode($shortcodesRawData, true);
?>
<?php if (!empty($shortcodes_msg)):?>
    <div class="<?php echo $shortcodes_msg_status; ?>" id="message"><p><?php echo $shortcodes_msg ?></p></div>
<?php endif; ?>
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><strong>Shortcodes</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
			<table cellspacing="0" class="wp-list-table widefat fixed bookmarks">
                <thead>
                    <tr>
                        <th width="20"><strong>Sn</strong></th>
                        <th width="250"><strong>Reference Name</strong></th>
                        <th><strong>Shortcode</strong></th>
                        <th width="100"><strong>Actions</strong></th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if (!empty($shortcodesData)): ?>
                    <?php 
                    $sn = 0;
                    foreach ($shortcodesData as $key=>$shortcodeData):
					$sn++;
                    ?>
                    <tr <?php echo $sn % 2 == 0?'':'class="alternate"'; ?>>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $shortcodeData['wmlo_reference_name'] ?></td>
                        <td>
                        	<input type="text" class="shortcode-in-list-table wp-ui-text-highlight code" value='[wmls name="<?php echo $shortcodeData['wmlo_reference_name'] ?>" id="<?php echo $key; ?>"]' readonly="readonly" onfocus="this.select();" style="width:90%;">
                        </td>
                        <td><a href="admin.php?page=wml_shortcodes&edit_shortcode_key=<?php echo $key; ?>">Edit</a> | <a onclick="if (!confirm('Are you sure ?')){return false;}" href="admin.php?page=wml_shortcodes&delete_shortcode_key=<?php echo $key; ?>">Delete</a></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="4">No Shortcode found. Please use create shortcode from section below.</td>
                    </tr>
                    <?php endif; ?>        
                </tbody>
                
            </table>
        </td>
     </tr>
  </tbody>
</table><br/>