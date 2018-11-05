<?php 
	if ($_GET['msg'] == 'licenseNeeded'){
		echo '<div class="updated error"><p>You must activate the license key to proceed.</p></div>';
	}	
?>
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th>
            	<strong>License</strong>
            </th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <form method="post" action="admin.php?page=wmlp_license" id="license_form">
                <?php $licenseStatus = get_option('wmlp_license_status'); ?>
                <?php $licenseKey 	 = trim(get_option('wmlp_license_key')); ?>
                <ul class="edit_settings_form">
                    <li>
                        <label>Status</label>
                        <strong class="<?php echo $licenseStatus; ?>"><?php echo ucfirst($licenseStatus); ?></strong>
                    </li>
                    
                    <li>
                        <label>Enter License Key</label>
                        <input type="text" name="wmlp_license_key" class="required medium" value="<?php echo $licenseKey; ?>" <?php echo $licenseStatus == 'valid'?'readonly':''; ?> />                        
                    </li>
                    
                    <li>
                        <label>&nbsp;</label>
                        <?php wp_nonce_field( 'wplp_nonce', 'wmlp_nonce' ); ?>
                        <?php if ($licenseStatus == 'valid'): ?>
                        	<input type="submit" name="wmlp_license_deactivate" class="button-primary small" value="Deactivate" />                        <?php else: ?>
                        	<input type="submit" name="wmlp_license_activate" class="button-primary small" value="Activate" />	
                        <?php endif; ?>
                    </li>
                         
               </ul>
            </form>   
           
            <script>
            	jQuery("#license_form").validate();
            </script>
        </td>
     </tr>
  </tbody>
</table>