<?php 
if (isset($_POST['submit-wmlp-layout-setttings'])){
	unset($_POST['submit-wmlp-layout-setttings']);
	$saveSettingsForDatabaseEncoded = json_encode($_POST);
	update_option('wmlo_theme_settings_'.$_GET['theme_name'], $saveSettingsForDatabaseEncoded);
	$layout_setting_msg 			= 'Layout Settings Saved';
	$layout_setting_msg_status 		= 'updated';
}

if (isset($_GET['reset'])){
	wmlp_reset_theme_settings($_GET['theme_name']);
	echo '<script>window.location = "admin.php?page=wmlp_layout_settings&theme_name='.$themeDetails['folder_name'].'"</script>';
	exit();
}

$savedValues 		= get_option('wmlo_theme_settings_'.$_GET['theme_name']);
$savedValuesArray 	= json_decode($savedValues,true);
?>
<?php if (!empty($layout_setting_msg)):?>
    <div class="<?php echo $layout_setting_msg_status; ?>" id="message"><p><?php echo $layout_setting_msg ?></p></div>
<?php endif; ?>
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th>
            	<strong>Layout Settings &raquo; <?php echo $themeDetails['name'];?> </strong>
            	<div style="float:right;">
               <a class="add-new-h2 notop" href="admin.php?page=wmlp_layout_themes">&laquo; Back To Layouts</a></div>
            </th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            <form action="" method="post" id="layout_setting_form">
                <ul class="edit_settings_form">
                    <?php $settings = $themeDetails['settings']; ?>
                    <?php foreach ($settings as $field_key => $setting):?>
                         <li><?php echo wmlp_create_setting_field($field_key, $setting, $savedValuesArray); ?></li>
                    <?php endforeach; ?>
                    <li>
                        <label>&nbsp;</label>
                        <input type="submit" name="submit-wmlp-layout-setttings" class="button-primary small" value="Save" />
                        <a href="admin.php?page=wmlp_layout_settings&theme_name=<?php echo $themeDetails['folder_name']; ?>&reset=true" class="button-primary" onclick="if (!confirm('Are you sure you want to reset to default ?')){return false;}">Reset To Default Settings</a>
                    </li>
                </ul>
            </form>
            
            <script>
            	jQuery("#layout_setting_form").validate();
            </script>
            
        </td>
     </tr>
  </tbody>
</table>