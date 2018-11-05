<?php 
function wmlp_layout_themes(){
	$dirPath 		= plugin_dir_path( __FILE__ ).'themes';
	$dirList 		= get_option('wmlo_layout_themes');
	$dirList		= json_decode($dirList,true);
	include('includes/admin/common/wmlp_header.php');
	include('includes/admin/layout/wmlp_themes.php');
	include('includes/admin/common/wmlp_footer.php');
}

function wmlp_layout_settings(){
	$dirPath 	= plugin_dir_path( __FILE__ ).'themes';
	$dirTheme 	= $_GET['theme_name']; 
	if (!@include($dirPath.'/'.$dirTheme.'/settings.php')){
		echo '<p>Unable to find the specified theme. Please go <a href="admin.php?page=wmlp_layout_themes">back</a> and try again.</p>';		
	} else {
		include('includes/admin/common/wmlp_header.php');
		include('includes/admin/layout/wmlp_layout_settings.php');
		include('includes/admin/common/wmlp_footer.php');
	}
}

function wmlp_create_setting_field($field_key, $settings, $savedValues){
	echo '<label>'.$settings['label'].'</label>';
	switch ($settings['input_field']){
		case 'radio':
			foreach($settings['options'] as $value => $display):
				$selected = '';
				if ($savedValues[$field_key] == $value){
					$selected = 'checked="checked"';
				}
				echo '<input class="radio" '.$selected.' type="radio" name="'.$field_key.'" value="'.$value.'" /><span class="radio-text">'.$display.'</span>';
			endforeach;
		break;
		
		case 'border':
			$borderStyleOptions = array('none','solid','dotted','dashed','double');
			echo '<input type="text" class="border_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][0].'" />';
			echo '<select name="'.$field_key.'[]"> class="small required">';
			foreach ($borderStyleOptions as $borderStyleOption):
				$selected = '';
				if ($savedValues[$field_key][1] == $borderStyleOption){
					$selected = 'selected="selected"';
				}
				echo '<option '.$selected.' value="'.$borderStyleOption.'">'.$borderStyleOption.'</option>';
			endforeach;
			echo '</select>';
			echo '<input type="text" class="border_color color {hash:true} small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][2].'" />';
		break;
		
		case 'box_shadow':
			echo '<input type="text" class="shadow_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][0].'" />';
			echo '<input type="text" class="shadow_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][1].'" />';
			echo '<input type="text" class="shadow_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][2].'" />';
			echo '<input type="text" class="shadow_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][3].'" />';
			echo '<input type="text" class="shadow_color color {hash:true} small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][4].'" />';
		break;
		
		case 'text_shadow':
			echo '<input type="text" class="shadow_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][0].'" />';
			echo '<input type="text" class="shadow_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][1].'" />';
			echo '<input type="text" class="shadow_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][2].'" />';
			echo '<input type="text" class="shadow_color color {hash:true} small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][3].'" />';
		break;
		
		case 'border_radius':
			echo '<input type="text" class="radius_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][0].'" />';
			echo '<input type="text" class="radius_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][1].'" />';
			echo '<input type="text" class="radius_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][2].'" />';
			echo '<input type="text" class="radius_px very_small required" name="'.$field_key.'[]" value="'.$savedValues[$field_key][3].'" />';
		break;
		
		case 'input':
			echo '<input type="text" class="input very_small required" name="'.$field_key.'" value="'.$savedValues[$field_key].'" />';
		break;
		
		case 'color':
			echo '<input type="text" class="color {hash:true} small required" name="'.$field_key.'" value="'.$savedValues[$field_key].'" />';
		break;
		
		default:
		break; 
	}
}

function wmlp_write_theme_settings(){
	$dirPath 		= plugin_dir_path( __FILE__ ).'themes';
	$dirList		= scandir($dirPath);
	$removefolders 	= array('..', '.');
	$dirList 		= array_diff($dirList, $removefolders);
	
	$allLayoutThemes = array();
	
	if (!empty($dirList)):
    	foreach ($dirList as $dir):
			if (is_dir($dirPath.'/'.$dir)):
				$savedValues 		= get_option('wmlo_theme_settings_'.$dir);
				include($dirPath.'/'.$dir.'/settings.php');
				$themeSettings = $themeDetails['settings'];
				if (empty($savedValues)){ // IF DATABASE PREVIOUSLY NOT WRITTEN
					$defaultSettingsForDatabase = array();
					foreach ($themeSettings as $field_key => $setting):
						$defaultSettingsForDatabase[$field_key] = $setting['default'];
					endforeach;
					$defaultSettingsForDatabaseEncoded = json_encode($defaultSettingsForDatabase);
					update_option('wmlo_theme_settings_'.$dir, $defaultSettingsForDatabaseEncoded);
				}
				$allLayoutThemes[$dir] = $themeDetails['name'];
			endif;
		endforeach;
		update_option('wmlo_layout_themes', json_encode($allLayoutThemes));
	endif;
}


function wmlp_reset_theme_settings($themeName){
	$dirPath 		= plugin_dir_path( __FILE__ ).'themes';
	include($dirPath.'/'.$themeName.'/settings.php');
	$themeSettings = $themeDetails['settings'];
	$defaultSettingsForDatabase = array();
	foreach ($themeSettings as $field_key => $setting):
		$defaultSettingsForDatabase[$field_key] = $setting['default'];
	endforeach;
	$defaultSettingsForDatabaseEncoded = json_encode($defaultSettingsForDatabase);
	update_option('wmlo_theme_settings_'.$themeName, $defaultSettingsForDatabaseEncoded);
}

function wmlp_update_check(){ // NEED TO CHANGE IN EVERY VERSION
	$wmlp_version_check = get_option('wmlp_current_version');
	if ($wmlp_version_check != '1.8'):
		update_option('wmlp_current_version', '1.8');
		wmlp_write_theme_settings();
	endif;
	wmlp_rewrite_taxonomy_data();
}