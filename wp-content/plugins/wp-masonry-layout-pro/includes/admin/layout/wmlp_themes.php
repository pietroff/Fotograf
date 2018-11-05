<?php 
if(isset($_GET['move_layout'])){
	$currentWPTheme 		= wp_get_theme();
	$currentWPThemeSlug 	= $currentWPTheme->get( 'TextDomain' );
	$currentWPThemeDir 		= get_theme_root() . '/' . $currentWPThemeSlug;
	$mainfolderName 		= '/masonry-layout';
	$masonryLayoutName 		= '/'.$_GET['move_layout'];
	if (! is_dir($currentWPThemeDir.$mainfolderName)) {
    	mkdir( $currentWPThemeDir.$mainfolderName, 0755 );
    }
	
	if (! is_dir($currentWPThemeDir.$mainfolderName.$masonryLayoutName)) {
    	mkdir( $currentWPThemeDir.$mainfolderName.$masonryLayoutName, 0755 );
    }
	
	include($dirPath.$masonryLayoutName.'/settings.php');
	$filesToMove = $themeDetails['movefiles'];
	foreach($filesToMove as $fileMove){
		$source 		=	$dirPath.$masonryLayoutName.'/'.$fileMove;
		$destination 	=	$currentWPThemeDir.$mainfolderName.$masonryLayoutName.'/'.$fileMove;
		@copy($source, $destination);	
	}
	
	$moved_msg 			= 'Layout Copied to your active theme. You can edit the layout files from <strong>'.$currentWPThemeDir.$mainfolderName.$masonryLayoutName.'</strong> and will be safe from future update.';
	$moved_msg_status 	= 'updated';
}	
?>
<?php if (!empty($moved_msg)):?>
    <div class="<?php echo $moved_msg_status; ?>" id="message"><p><?php echo $moved_msg ?></p></div>
<?php endif; ?>
<table class="wp-list-table widefat fixed bookmarks">
    <thead>
        <tr>
            <th><strong>Layout Themes</strong></th>
        </tr>
    </thead>
    <tbody>
    <tr>
        <td>
			<table cellspacing="0" class="wp-list-table widefat fixed bookmarks">
                <thead>
                    <tr>
                        <th width="20"><strong>Sn</strong></th>
                        <th width="250"><strong>Name</strong></th>
                        <th width="200"><strong>Actions</strong></th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php if (!empty($dirList)): ?>
                    <?php 
                    $sn = 0;
                    foreach ($dirList as $dir=>$name):
						if (is_dir($dirPath.'/'.$dir)):
							include($dirPath.'/'.$dir.'/settings.php');
							$sn++;
                    ?>
                            <tr <?php echo $sn % 2 == 0?'':'class="alternate"'; ?>>
                                <td><?php echo $sn; ?></td>
                                <td><?php echo $themeDetails['name'] ?></td>
                                <td>
                                    <a href="<?php echo $themeDetails['demo_url'] ?>" target="_blank">Demo</a> | 
                                    <a href="admin.php?page=wmlp_layout_settings&theme_name=<?php echo $themeDetails['folder_name'] ?>">Edit Settings</a> | 
                                    <a href="admin.php?page=wmlp_layout_themes&move_layout=<?php echo $themeDetails['folder_name']; ?>">Copy to Wordpress Theme</a>
                                </td>
                            </tr>
                    <?php 
						endif;	
					endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="3">No Layout Found</td>
                    </tr>
                    <?php endif; ?>        
                </tbody>
                
            </table>
        </td>
     </tr>
  </tbody>
</table>