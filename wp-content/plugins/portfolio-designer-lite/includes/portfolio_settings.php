<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add or Edit portfolio layout admin view
 */
global $wpdb;
// $portfolio_edit = '';
$setting = unserialize( get_option( 'portfolio_designer_lite_settings' ) );

if ( ! empty( $setting ) ) {
	foreach ( $setting as $key => $value ) {
		$key                       = str_replace( 'portfolio_', '', $key );
		$portfolio_setting[ $key ] = $value;
	}
} else {
	$portfolio_setting = array();
}

$portfolio_edit = true;


$font_family = array( 'Default' );
?>

<div class="wrap pdl-admin">
	<div class="pdl-home">
		<div class="pdl-list">
			<div class="pdl-list-header">
				<h4><strong><?php _e( 'Portfolio Designer Lite Settings', 'portfolio-designer-lite' ); ?></strong></h4>
			</div>
		</div>
	</div>
	<?php
	$view_post_link = ( isset( $portfolio_setting['page_display'] ) && $portfolio_setting['page_display'] != 0 ) ? '<span class="page_link"> <a target="_blank" href="' . get_permalink( $portfolio_setting['page_display'] ) . '"> ' . __( 'View Portfolio', 'portfolio-designer-lite' ) . ' </a></span>' : '';
	if ( isset( $_GET['action'] ) && 'save' == esc_attr( $_GET['action'] ) ) {
		echo '<div id="notice-2" class="updated" ><p>' . __( 'Portfolio Designer lite settings updated.', 'portfolio-designer-lite' ) . ' ' . $view_post_link . '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></p></div>';
	}
	?>
	<div class="preview-screen"></div>
	<form method="post" action="?page=portfolio_lite_settings&action=save" class="pdl-form-class" name="pdl_add_portfolio" id="pdl_add_portfolio">
		<?php
		wp_nonce_field( 'add-portfolio-layout-submit', 'add-portfolio-layout-submit-nonce' );
		$page = '';
		if ( isset( $_GET['page'] ) && $_GET['page'] != '' ) {
			$page = esc_attr( $_GET['page'] );
			?>
			<input type="hidden" name="portfoliooriginalpage" class="portfoliooriginalpage" value="<?php echo $page; ?>" />
			<?php
		}
		?>
		<div class="portdesign-preview-box" id="portdesign-preview-box"></div>
		<div id="poststuff" class="pdl-settings-wrappers port_poststuff">
			<div class="pdl-header-wrapper">
				<div class="pdl-logo-wrapper pull-left">
					<img src="<?php echo PORT_LITE_PLUGIN_URL . 'images/logo.png'; ?>">
				</div>
				<div class="pull-right">
					<a id="pdl-btn-show-preview" title="<?php _e( 'Show Preview', 'portfolio-designer-lite' ); ?>" class="button portfolio_show_preview pdl-button pdl-is-green pro-feature" href="#">
						<span><?php _e( 'Show Preview', 'portfolio-designer-lite' ); ?></span>
					</a>
					<a id="pdl-btn-show-submit" title="<?php _e( 'Save Changes', 'portfolio-designer-lite' ); ?>" class="show_portfolio_save button submit_fixed button button-primary"  href="#" >
						<span><?php _e( 'Save Changes', 'portfolio-designer-lite' ); ?></span>
					</a>
					<a id="pdl-btn-reset-submit" title="<?php _e( 'Reset Changes', 'portfolio-designer-lite' ); ?>" class="portfolio_reset_settings button submit_fixed button button-default"  href="#" >
						<span><?php _e( 'Reset Changes', 'portfolio-designer-lite' ); ?></span>
					</a>
				</div>
			</div>
			<div class="postbox-container">
				<div class="pdl-menu-setting">
					<ul class="port-setting-handle">
						<?php
						$current_tab = get_user_option( 'pdselectedtab_' . $page );
						$current_tab = esc_attr( $current_tab );
						if ( $current_tab == '' ) {
							$current_tab = 'portdesigngeneral';
						}
						?>
						<li class="portdesigngeneral
						<?php
						if ( $current_tab == 'portdesigngeneral' ) {
							echo ' port-active-tab';}
?>
" data-show="portdesigngeneral">
							<i class="fas fa-cogs"></i>
							<span><?php _e( 'General Settings', 'portfolio-designer-lite' ); ?></span>
						</li>
						<li class="portdesignquery
						<?php
						if ( $current_tab == 'portdesignquery' ) {
							echo ' port-active-tab';}
?>
" data-show="portdesignquery">
							<i class="fab fa-quora"></i>
							<span><?php _e( 'Query Settings', 'portfolio-designer-lite' ); ?></span>
						</li>
						<li class="portdesignlayout
						<?php
						if ( $current_tab == 'portdesignlayout' ) {
							echo ' port-active-tab';}
?>
" data-show="portdesignlayout">
							<i class="fas fa-th-large"></i>
							<span><?php _e( 'Layout Settings', 'portfolio-designer-lite' ); ?></span>
						</li>
						<li class="portdesignthumbnail
						<?php
						if ( $current_tab == 'portdesignthumbnail' ) {
							echo ' port-active-tab';}
?>
" data-show="portdesignthumbnail">
							<i class="fas fa-image"></i>
							<span><?php _e( 'Thumbnail Settings', 'portfolio-designer-lite' ); ?></span>
						</li>
						<li class="portdesignfilter
						<?php
						if ( $current_tab == 'portdesignfilter' ) {
							echo ' port-active-tab';}
?>
" data-show="portdesignfilter">
							<i class="fas fa-filter"></i>
							<span><?php _e( 'Filter Settings', 'portfolio-designer-lite' ); ?></span>
						</li>
						<li class="portdesignpagination
						<?php
						if ( $current_tab == 'portdesignpagination' ) {
							echo ' port-active-tab';}
?>
" data-show="portdesignpagination">
							<i class="fas fa-ellipsis-h"></i>
							<span><?php _e( 'Pagination Settings', 'portfolio-designer-lite' ); ?></span>
						</li>
						<li class="pdlsocial
						<?php
						if ( $current_tab == 'pdlsocial' ) {
							echo ' port-active-tab';}
?>
" data-show="pdlsocial">
							<i class="fas fa-share-alt"></i>
							<span><?php _e( 'Social Share Settings', 'portfolio-designer-lite' ); ?></span>
						</li>
						<li class="portdesignstyle
						<?php
						if ( $current_tab == 'portdesignstyle' ) {
							echo ' port-active-tab';}
?>
" data-show="portdesignstyle">
							<i class="fas fa-paint-brush"></i>
							<span><?php _e( 'Style Settings', 'portfolio-designer-lite' ); ?></span>
						</li>
					</ul>
				</div>
				<div id="portdesigngeneral" 
				<?php
				if ( $current_tab == 'portdesigngeneral' ) {
					echo ' style="display: block;"';}
?>
 class="postbox postbox-with-fw-options pdltab">
					<div class="inside">
						<table>
							<tbody>
								<tr>
									<td> <label for="portfolio_layout_post"><?php _e( 'Select Post Type for Portfolio', 'portfolio-designer-lite' ); ?></label></td>
									<td>
										<?php
										$args       = array(
											'public' => true,
										);
										$output     = 'objects';
										$operator   = 'and';
										$post_types = get_post_types( $args, $output, $operator );
										?>
										<div class="select-cover">
											<select name="portfolio_layout_post" id="portfolio_layout_post">
												<?php
												foreach ( $post_types as $post_type ) {
													if ( $post_type->name != 'attachment' ) {
														?>
														<option value="<?php echo $post_type->name; ?>" <?php echo ( isset( $portfolio_setting['layout_post'] ) && $portfolio_setting['layout_post'] == $post_type->name ) ? 'selected="selected"' : ''; ?> ><?php echo $post_type->labels->name; ?></option>
														<?php
													}
												}
												?>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<td>
										<label for="portfolio_page_display"><?php _e( 'Display Portfolio on page', 'portfolio-designer-lite' ); ?></label>
									</td>
									<td>
										<div class="select-cover">
											<?php
											if ( ! isset( $portfolio_setting['page_display'] ) ) {
												$selected_template = '';
											} else {
												$selected_template = $portfolio_setting['page_display'];
											}
											echo wp_dropdown_pages(
												array(
													'name' => 'portfolio_page_display',
													'echo' => 0,
													'depth' => -1,
													'show_option_none' => '-- ' . __( 'Select Page', 'portfolio-designer-lite' ) . ' --',
													'option_none_value' => '0',
													'selected' => $selected_template,
												)
											);
											?>
										</div>
										<div>
											<p> <strong><?php _e( 'Note', 'portfolio-designer-lite' ); ?>: </strong> 
												<?php
													_e( 'You are about to select the page for your portfolio layout, you will lose your page content. There is no undo. Think about it!', 'portfolio-designer-lite' );
												?>
												</p>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div id="portdesignquery" 
				<?php
				if ( $current_tab == 'portdesignquery' ) {
					echo ' style="display: block;"';}
?>
 class="postbox postbox-with-fw-options pdltab">
					<div class="inside">
						<table>
							<?php
							$portfolio_layout_post = ( isset( $portfolio_setting['layout_post'] ) && isset( $portfolio_setting['layout_post'] ) != '' ) ? esc_attr( $portfolio_setting['layout_post'] ) : 'sol_portfolio';
							$portfolio_taxonomy    = get_object_taxonomies( $portfolio_layout_post, 'objects' );
							?>
							<tr class="portfolio_taxonomy_tr">
								<?php
								if ( ! empty( $portfolio_taxonomy ) ) {
									?>
									<td>
										<label for="portfolio_taxonomy"><?php _e( 'Select Taxonomy Type to Filter Posts', 'portfolio-designer-lite' ); ?></label>
									</td>
									<td>
										<div class="select-cover">
											<select id="portfolio_taxonomy" name="portfolio_taxonomy">
												<?php
												foreach ( $portfolio_taxonomy as $slug => $name ) {
													if ( $slug != 'post_format' ) {
														?>
														<option value="<?php echo $slug; ?>" <?php echo ( isset( $portfolio_setting['taxonomy'] ) && $portfolio_setting['taxonomy'] == $slug ) ? 'selected="selected"' : ''; ?>><?php echo $name->labels->name; ?></option>
														<?php
													}
												}
												?>
											</select>
										</div>
									</td>
									<?php
								}
								?>
							</tr>
							<?php
							$portfolio_taxonomy = ( isset( $portfolio_setting['taxonomy'] ) && $portfolio_setting['taxonomy'] != '' ) ? esc_attr( $portfolio_setting['taxonomy'] ) : 'portfolio-category';
							$terms              = get_terms( $portfolio_taxonomy, array( 'hide_empty' => false ) );
							if ( ! isset( $portfolio_setting['taxonomy'] ) ) {
								$portfolio_setting['taxonomy'] = '';
							}
							?>
							<tr class="portfolio_categories_tr">
								<?php
								if ( ! empty( $terms ) ) {
									?>
									<td>
										<label for="portfolio_categories"><?php _e( 'Select Terms to Filter Posts', 'portfolio-designer-lite' ); ?></label>
									</td>

									<td>
										<div class="select-cover">
											<select id="portfolio_categories" name="portfolio_categories[]" class="chosen-select" multiple="multiple" data-placeholder="Choose Terms" style="width:100%;">
												<?php
												foreach ( $terms as $value ) {
													?>
													<option value="<?php echo $value->slug; ?>" <?php echo ( ! empty( $portfolio_setting['categories'] ) && in_array( $value->slug, $portfolio_setting['categories'] ) ) ? 'selected="selected"' : ''; ?> ><?php echo $value->name; ?></option>
													<?php
												}
												?>
											</select>
										</div>
									</td>
									<?php
								}
								?>
							</tr>
							<tr class="advance_filter_option">
                                <td class='pro-feature'>
                                    <label for="portfolio_number_post" class="porfolio-title">
                                        <div class="porfolio-title-tooltip"> <?php _e('Terms filter option with and/or operator', 'portfolio-designer-lite'); ?></div>
                                        <?php _e('Terms filter with', 'portfolio-designer-lite'); ?>
                                    </label>
                                </td>
                                <td class='pro-feature'>
                                    <fieldset class="portfolio-social-options portfolio_social_icon_style buttonset buttonset-hide" data-hide='1'>
                                        <input id="portfolio_filter_with_0" name="portfolio_filter_with" type="radio" value="1" checked />
                                        <label for="portfolio_filter_with_0" checked ><?php _e('OR', 'portfolio-designer-lite'); ?></label>
                                        <input id="portfolio_filter_with_1" name="portfolio_filter_with" type="radio" value="0" />
                                        <label for="portfolio_filter_with_1" ><?php _e('AND', 'portfolio-designer-lite'); ?></label>
                                    </fieldset>
                                </td>
                            </tr>
							<tr>
								<td>
									<label for="portfolio_post"> <?php _e( 'Select Posts', 'portfolio-designer-lite' ); ?> </label>
								</td>
								<td class="portfolio_post_td">
									<div class="select-cover">
										<select id="portfolio_post" name="portfolio_post[]" class="chosen-select" multiple="multiple" data-placeholder="Choose Posts" style="width:100%;">
											<?php
											$args = array(
												'post_type' => 'post',
												'posts_per_page' => -1,
											);

											if ( isset( $portfolio_setting['layout_post'] ) ) {
												$args['post_type'] = $portfolio_setting['layout_post'];
											}

											if ( isset( $portfolio_setting['categories'] ) && ! empty( $portfolio_setting['categories'] ) ) {
												$args['tax_query'] = array(
													'relation' => 'or',
													array(
														'taxonomy' => $portfolio_setting['taxonomy'],
														'field' => 'slug',
														'terms' => $portfolio_setting['categories'],
													),
												);
											}
											$the_query = new WP_Query( $args );
											if ( $the_query->have_posts() ) {
												while ( $the_query->have_posts() ) {
													$the_query->the_post();
													?>
													 <option value="<?php echo get_the_ID(); ?>" <?php echo ( ! empty( $portfolio_setting['post'] ) && in_array( get_the_ID(), $portfolio_setting['post'] ) ) ? 'selected="selected"' : ''; ?>><?php echo get_the_title(); ?></option> 
													<?php
												}
											}
											?>
										</select>
									</div>
									<p><?php _e( 'Leave blank if you want to show all posts.', 'portfolio-designer-lite' ); ?></p>
								</td>
							</tr>
							<tr class="portfolio_number_post_tr">
								<td>
									<label for="portfolio_number_post"> <?php _e( 'Display Number of Posts', 'portfolio-designer-lite' ); ?> </label>
								</td>
								<td>
									<div class="input-number-cover">
										<input id="portfolio_number_post" type="number" name="portfolio_number_post" class="numberOnly" value="<?php echo ( isset( $portfolio_setting['number_post'] ) && $portfolio_setting['number_post'] != '' ) ? $portfolio_setting['number_post'] : 10; ?>" min="1"/>
									</div>
									<p><?php _e( 'Number of posts to be shown in showcase page.', 'portfolio-designer-lite' ); ?></p>
								</td>
							</tr>

							<tr>
								<td>
									<label for="portfolio_order_by"><?php _e( 'Order By', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<?php $portfolio_order_by = ( isset( $portfolio_setting['order_by'] ) && $portfolio_setting['order_by'] != '' ) ? $portfolio_setting['order_by'] : 'date'; ?>
									<div class="select-cover">
										<select id="portfolio_order_by" name="portfolio_order_by">
											<option value="author" <?php echo ( $portfolio_order_by == 'author' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Author', 'portfolio-designer-lite' ); ?></option>
											<option value="comment_count" <?php echo ( $portfolio_order_by == 'comment_count' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Comment Count', 'portfolio-designer-lite' ); ?></option>
											<option value="date"<?php echo ( $portfolio_order_by == 'date' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Date', 'portfolio-designer-lite' ); ?></option>
											<option value="ID" <?php echo ( $portfolio_order_by == 'ID' ) ? 'selected="selected"' : ''; ?>><?php _e( 'ID', 'portfolio-designer-lite' ); ?></option>
											<option value="modified" <?php echo ( $portfolio_order_by == 'modified' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Modified Date', 'portfolio-designer-lite' ); ?></option>
											<option value="name" <?php echo ( $portfolio_order_by == 'name' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Name', 'portfolio-designer-lite' ); ?></option>
											<option value="title" <?php echo ( $portfolio_order_by == 'title' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Title', 'portfolio-designer-lite' ); ?></option>
											<option value="rand" <?php echo ( $portfolio_order_by == 'rand' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Random', 'portfolio-designer-lite' ); ?></option>
										</select>
									</div>
									<p><?php _e( 'Parameter to sort posts.', 'portfolio-designer-lite' ); ?></p>
								</td>
							</tr>
							<tr>
								<td>
									<label for="portfolio_order"> <?php _e( 'Order', 'portfolio-designer-lite' ); ?> </label>
								</td>
								<td>
									<?php
									$portfolio_order = ( isset( $portfolio_setting['order'] ) && $portfolio_setting['order'] != '' ) ? $portfolio_setting['order'] : 'DESC';
									?>
									<div class="select-cover">
										<select id="portfolio_order" name="portfolio_order">
											<option value="ASC" <?php echo ( $portfolio_order == 'ASC' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Ascending Order', 'portfolio-designer-lite' ); ?></option>
											<option value="DESC" <?php echo ( $portfolio_order == 'DESC' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Descending Order', 'portfolio-designer-lite' ); ?></option>
										</select>
									</div>
									<p><?php _e( 'Descending order from highest to lowest values ( 3,2,1; c,b,a ) or Ascending order from lowest to highest values ( 1,2,3; a,b,c).', 'portfolio-designer-lite' ); ?></p>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div id="portdesignlayout" 
				<?php
				if ( $current_tab == 'portdesignlayout' ) {
					echo ' style="display: block;"';}
?>
 class="postbox postbox-with-fw-options pdltab">
					<div class="inside">
						<table>
							<tr>
								<td>
									<label for="portfolio_layout_type"><?php _e( 'Select Layout Type', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<?php $portfolio_layout_type = ( isset( $portfolio_setting['layout_type'] ) && $portfolio_setting['layout_type'] != '' ) ? $portfolio_setting['layout_type'] : 'grid'; ?>
									<div class="select-cover">
										<select id="portfolio_layout_type" name="portfolio_layout_type">
											<option value="grid" <?php echo ( $portfolio_layout_type == 'gride' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Grid Layout', 'portfolio-designer-lite' ); ?></option>
											<option value="masonary" <?php echo ( $portfolio_layout_type == 'masonary' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Masonry Layout', 'portfolio-designer-lite' ); ?></option>
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<td class="pro-feature">
									<label for="portfolio_column_layout"> <?php _e( 'Select Column Layout(Desktop)', 'portfolio-designer-lite' ); ?> </label>
								</td>
								<td class="pro-feature">
									<?php $portfolio_column_layout = ( isset( $portfolio_setting['column_layout'] ) && $portfolio_setting['column_layout'] != '' ) ? $portfolio_setting['column_layout'] : '3'; ?>
									<div class="select-cover">
										<select id="portfolio_column_layout" name="">
											<option value="3" <?php echo ( $portfolio_column_layout == 3 ) ? 'selected="selected"' : ''; ?>><?php _e( 'Select Column', 'portfolio-designer-lite' ); ?></option>
										</select>
									</div>
									<p><?php _e( 'Column layout for (Desktop - Above 980px)', 'portfolio-designer-lite' ); ?></p>
								</td>
							</tr>
                            <tr class="column_tr">
                                <td class="pro-feature">
                                    <label for="portfolio_column_layout_ipad" class="porfolio-title">
                                        <?php _e('Select Column Layout(ipad)', 'portfolio-designer-lite'); ?>
                                    </label>
                                </td>
                                <td class="pro-feature">
                                    <div class="select-cover">
                                        <select id="portfolio_column_layout" name="portfolio_column_layout_ipad">
                                            <option value="3" selected ><?php _e('3 Columns', 'portfolio-designer-lite') ?></option>
                                        </select>
                                    </div>
                                    <p><?php _e('Column layout for (iPad - 720px - 980px) ', 'portfolio-designer-lite') ?></p>
                                </td>
                            </tr>
                            <tr class="column_tr">
                                <td class="pro-feature">
                                    <label for="portfolio_column_layout_tablet" class="porfolio-title">
                                        <?php _e('Select Column Layout(Tablet)', 'portfolio-designer-lite'); ?>
                                    </label>
                                </td>
                                <td class="pro-feature">
                                    <div class="select-cover">
                                        <select id="portfolio_column_layout" name="portfolio_column_layout_tablet">
                                            <option value="2" selected ><?php _e('2 Columns', 'portfolio-designer-lite') ?></option>
                                        </select>
                                    </div>
                                    <p><?php _e('Column layout for (Tablet - 480px - 720px)', 'portfolio-designer-lite') ?></p>
                                </td>
                            </tr>
                            <tr class="column_tr">
                                <td class="pro-feature">
                                    <label for="portfolio_column_layout_mobile" class="porfolio-title">
                                        <?php _e('Select Column Layout(Mobile)', 'portfolio-designer-lite'); ?>
                                    </label>
                                </td>
                                <td class="pro-feature">
                                    <div class="select-cover">
                                        <select id="portfolio_column_layout" name="portfolio_column_layout_mobile">
                                            <option value="1" selected ><?php _e('1 Column', 'portfolio-designer-lite') ?></option>
                                        </select>
                                    </div>
                                    <p><?php _e('Column layout for (Mobile - Smaller Than 480px) ', 'portfolio-designer-lite') ?></p>
                                </td>
                            </tr>
							<tr class="portfolio_simple_layout">
								<td>
									<label for="portfolio_column_space"><?php _e( 'Select Column Spaces', 'portfolio-designer-lite' ); ?></label>
								</td>								
								<td>
									<div class="input-number-cover large-input">
										<input id="portfolio_column_space" name="portfolio_column_space" class="numberOnly" type="number" value="<?php echo ( isset( $portfolio_setting['column_space'] ) && $portfolio_setting['column_space'] != '' ) ? $portfolio_setting['column_space'] : '5'; ?>" min="0" />
									</div>
									<div class="select-cover small-select pro-feature">
										<select name="portfolio_column_space_unit" id="portfolio_column_space_unit">
											<option value="px" selected >px</option>
											<option value="em">em</option>
											<option value="%">%</option>
											<option value="cm">cm</option>
											<option value="ex">ex</option>
											<option value="mm">mm</option>
											<option value="in">in</option>
											<option value="pt">pt</option>
											<option value="pc">pc</option>
										</select>
									</div>
									<p><?php _e( 'Horizontal space between two posts.', 'portfolio-designer-lite' ); ?></p>
								</td>
							</tr>
							<tr class="portfolio_simple_layout">
								<td>
									<label for="portfolio_row_space"><?php _e( 'Select Rows Spaces', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<div class="input-number-cover large-input">
										<input id="portfolio_row_space" name="portfolio_row_space" class="numberOnly" type="number" value="<?php echo ( isset( $portfolio_setting['row_space'] ) && $portfolio_setting['row_space'] != '' ) ? $portfolio_setting['row_space'] : '5'; ?>" min="0" />
									</div>
									<div class="select-cover small-select pro-feature">
										<select name="portfolio_row_space_unit" id="portfolio_row_space_unit">
											<option value="px" selected >px</option>
											<option value="em">em</option>
											<option value="%">%</option>
											<option value="cm">cm</option>
											<option value="ex">ex</option>
											<option value="mm">mm</option>
											<option value="in">in</option>
											<option value="pt">pt</option>
											<option value="pc">pc</option>
										</select>
									</div>
									<p><?php _e( 'Vertical space between two posts.', 'portfolio-designer-lite' ); ?></p>
								</td>
							</tr>

						</table>
					</div>
				</div>
				<div id="portdesignthumbnail" 
				<?php
				if ( $current_tab == 'portdesignthumbnail' ) {
					echo ' style="display: block;"';}
?>
 class="postbox postbox-with-fw-options pdltab">
					<div class="inside">
						<table>
							<tr>
								<td>
									<label for="portfolio_thumb_size"> <?php _e( 'Select Thumbnail Size', 'portfolio-designer-lite' ); ?> </label>
								</td>
								<td>
									<?php $portfolio_thumb_size = ( isset( $portfolio_setting['thumb_size'] ) && $portfolio_setting['thumb_size'] != '' ) ? $portfolio_setting['thumb_size'] : 'full'; ?>
									<div class="select-cover">
										<select id="portfolio_thumb_size" name="portfolio_thumb_size">
											<option value="full" <?php echo ( $portfolio_thumb_size == 'full' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Original Resolution', 'portfolio-designer-lite' ); ?></option>
											<?php
											global $_wp_additional_image_sizes;
											$thumb_sizes = array();
											foreach ( get_intermediate_image_sizes() as $s ) {
												$thumb_sizes [ $s ] = array( 0, 0 );
												if ( in_array( $s, array( 'thumbnail', 'medium', 'large' ) ) ) {
													?>
													<option value="<?php echo $s; ?>"  <?php echo ( $portfolio_thumb_size == $s ) ? 'selected="selected"' : ''; ?>> <?php echo $s . ' (' . get_option( $s . '_size_w' ) . 'x' . get_option( $s . '_size_h' ) . ')'; ?> </option>
													<?php
												} else {
													if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
														?>
														<option value="<?php echo $s; ?>"  <?php echo ( $portfolio_thumb_size == $s ) ? 'selected="selected"' : ''; ?>> <?php echo $s . ' (' . $_wp_additional_image_sizes[ $s ]['width'] . 'x' . $_wp_additional_image_sizes[ $s ]['height'] . ')'; ?> </option>
														<?php
													}
												}
											}
											?>
										</select>
									</div>
									<p>
										<strong><?php _e( 'Note', 'portfolio-designer-lite' ); ?>: </strong>
										<?php _e( 'The original resolution is loaded if a thumbnail size doesn\'t exist in an image. ', 'portfolio-designer-lite' ); ?>
									</p>
								</td>
							</tr>

							<tr>
								<td>
									<label><?php _e( 'Select Default Image', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<span class="portfolio_default_image_holder">
										<?php
										if ( isset( $portfolio_setting['default_image_src'] ) && $portfolio_setting['default_image_src'] != '' ) {
											echo '<img src="' . $portfolio_setting['default_image_src'] . '"/>';
										}
										?>
									</span>
									<?php if ( isset( $portfolio_setting['default_image_src'] ) && $portfolio_setting['default_image_src'] != '' ) { ?>
										<input id="portfolio-image-action-button" class="button pdl-remove_image_button" type="button" value="<?php esc_attr_e( 'Remove Image', 'portfolio-designer-lite' ); ?>">
									<?php } else { ?>
										<input class="button pdl-upload_image_button" type="button" value="<?php esc_attr_e( 'Upload Image', 'portfolio-designer-lite' ); ?>">
									<?php } ?>
									<input type="hidden" value="<?php echo isset( $portfolio_setting['default_image_id'] ) ? intval( $portfolio_setting['default_image_id'] ) : ''; ?>" name="portfolio_default_image_id" id="portfolio_default_image_id">
									<input type="hidden" value="<?php echo isset( $portfolio_setting['default_image_src'] ) ? esc_attr( $portfolio_setting['default_image_src'] ) : ''; ?>" name="portfolio_default_image_src" id="portfolio_default_image_src">
								</td>
							</tr>
							<tr>
								<td>
									<label for="portfolio_enable_overlay"><?php _e( 'Image Overlay Effect', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<label class="desc_checkbox">
										<input id="portfolio_enable_overlay" name="portfolio_enable_overlay" type="checkbox" value="1" <?php echo ( isset( $portfolio_setting['enable_overlay'] ) && $portfolio_setting['enable_overlay'] == 1 ) ? 'checked="checked"' : ''; ?>/>
										<?php _e( 'Apply Image Overlay Effects', 'portfolio-designer-lite' ); ?>
									</label>
								</td>
							</tr>
							<tr class="portfolio_overlay_tr">
								<td>
									<label for="portfolio_image_effect"> <?php _e( 'Select Mouse Hover Effect', 'portfolio-designer-lite' ); ?> </label>
								</td>
								<td>
									<?php $portfolio_image_effect = ( isset( $portfolio_setting['image_effect'] ) && $portfolio_setting['image_effect'] != '' ) ? $portfolio_setting['image_effect'] : 'right_top_corner'; ?>
									<div class="select-cover">
										<select id="portfolio_image_effect" name="portfolio_image_effect">
											<option value="effect_1" <?php echo ( $portfolio_image_effect == 'effect_1' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Zoom Out', 'portfolio-designer-lite' ); ?></option>
											<option value="effect_2" <?php echo ( $portfolio_image_effect == 'effect_2' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Overlay Slide', 'portfolio-designer-lite' ); ?></option>
											<option value="effect_3" <?php echo ( $portfolio_image_effect == 'effect_3' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Corner Slide', 'portfolio-designer-lite' ); ?></option>
											<option value="effect_4" <?php echo ( $portfolio_image_effect == 'effect_4' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Rotating Zoom Out', 'portfolio-designer-lite' ); ?></option>
											<option value="right_corner" <?php echo ( $portfolio_image_effect == 'right_corner' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Right Corner', 'portfolio-designer-lite' ); ?></option>
											<option value="left_corner" <?php echo ( $portfolio_image_effect == 'left_corner' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Left Corner', 'portfolio-designer-lite' ); ?></option>
											<option value="depth_in" <?php echo ( $portfolio_image_effect == 'depth_in' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Depth Zoom In', 'portfolio-designer-lite' ); ?></option>
											<option value="depth_out" <?php echo ( $portfolio_image_effect == 'depth_out' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Depth Zoom Out', 'portfolio-designer-lite' ); ?></option>
											<option value="depth_rorator" <?php echo ($portfolio_image_effect == 'depth_rorator') ? 'selected="selected"' : ''; ?>><?php _e('Depth Rotator', 'portfolio-designer-lite'); ?></option>
                                            <option value="rotator_effect" <?php echo ($portfolio_image_effect == 'rotator_effect') ? 'selected="selected"' : ''; ?>><?php _e('Rotator', 'portfolio-designer-lite'); ?></option>
                                            <option value="slide_top" <?php echo ($portfolio_image_effect == 'slide_top') ? 'selected="selected"' : ''; ?>><?php _e('Slide Top', 'portfolio-designer-lite'); ?></option>
                                            <option value="slide_right" <?php echo ($portfolio_image_effect == 'slide_right') ? 'selected="selected"' : ''; ?>><?php _e('Slide Right', 'portfolio-designer-lite'); ?></option>
                                            <option value="slide_bottom" <?php echo ($portfolio_image_effect == 'slide_bottom') ? 'selected="selected"' : ''; ?>><?php _e('Slide Bottom', 'portfolio-designer-lite'); ?></option>
                                            <option value="slide_left" <?php echo ($portfolio_image_effect == 'slide_left') ? 'selected="selected"' : ''; ?>><?php _e('Slide Left', 'portfolio-designer-lite'); ?></option>
                                            <option value="enclose_zoomin" <?php echo ($portfolio_image_effect == 'enclose_zoomin') ? 'selected="selected"' : ''; ?>><?php _e('Enclose ZoomIn', 'portfolio-designer-lite'); ?></option>
                                            <option value="enclose_zoomout" <?php echo ($portfolio_image_effect == 'enclose_zoomout') ? 'selected="selected"' : ''; ?>><?php _e('Enclose ZoomOut', 'portfolio-designer-lite'); ?></option>
                                            <option value="enclose_fadein" <?php echo ($portfolio_image_effect == 'enclose_fadein') ? 'selected="selected"' : ''; ?>><?php _e('Enclose FaddeIn', 'portfolio-designer-lite'); ?></option>
                                            <option value="enclose_fadeout" <?php echo ($portfolio_image_effect == 'enclose_fadeout') ? 'selected="selected"' : ''; ?>><?php _e('Enclose FaddeOut', 'portfolio-designer-lite'); ?></option>
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label for="portfolio_content_position"><?php _e( 'Select Content Position', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<?php $portfolio_content_position = ( isset( $portfolio_setting['content_position'] ) && $portfolio_setting['content_position'] != '' ) ? $portfolio_setting['content_position'] : 'overlay_image'; ?>
									<div class="select-cover">
										<select id="portfolio_content_position" name="portfolio_content_position">
											<option value="overlay_image" <?php echo ( $portfolio_content_position == 'overlay_image' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Overlay on Image', 'portfolio-designer-lite' ); ?></option>
											<option value="bottom_image" <?php echo ( $portfolio_content_position == 'bottom_image' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Bottom of Image', 'portfolio-designer-lite' ); ?></option>
											<option value="left_side" <?php echo ( $portfolio_content_position == 'left_side' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Left side of Image', 'portfolio-designer-lite' ); ?></option>
											<option value="right_side" <?php echo ( $portfolio_content_position == 'right_side' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Right side of Image', 'portfolio-designer-lite' ); ?></option>
										</select>
									</div>
								</td>
							</tr>
							<tr class="portfolio_border_radius_tr">
                                <td>
                                    <label for="portfolio_border_radius" class="porfolio-title">
                                        <?php _e('Select Border Radius', 'portfolio-designer-lite'); ?>
                                    </label>
                                </td>
                                <td>
                                    <div class="font_size_cover">
                                        <div class="pull-left">
                                            <div class="grid_col_space range_slider_fontsize" id="portfolio_border_radius_slider" data-value="<?php echo (isset($portfolio_setting['border_radius']) && $portfolio_setting['border_radius'] != '') ? $portfolio_setting['border_radius'] : 0; ?>"></div>
                                        </div>
                                        <div class="pull-right">
                                            <div class="slide_val input-number-cover">
                                                <input id="portfolio_border_radius" name="portfolio_border_radius" type="number" class="numberOnly range-slider__value" value="<?php echo (isset($portfolio_setting['border_radius']) && $portfolio_setting['border_radius'] != '') ? $portfolio_setting['border_radius'] : 0; ?>" min="0"/>
                                            </div>
                                            <div class="select-cover font-size-cover">
                                                <select id="portfolio_border_radius_unit" name="portfolio_border_radius_unit">
                                                    <option value="px" selected>px</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>							
							<tr class="portfolio_summary_tr">
								<td>
									<label for="portfolio_summary"><?php _e( 'Display Summary in Words', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<div class="input-number-cover">
										<input id="portfolio_summary" name="portfolio_summary" type="number" class="numberOnly" min="0" value="<?php echo ( isset( $portfolio_setting['summary'] ) && $portfolio_setting['summary'] != '' ) ? $portfolio_setting['summary'] : 0; ?>"/>
									</div>
									<p><b><?php _e( 'Note', 'portfolio-designer-lite' ); ?>: </b><?php _e( 'Set ZERO for disable summary.', 'portfolio-designer-lite' ); ?></p>
								</td>
							</tr>
							<tr>
								<td>
									<label for="portfolio_enable_popup_link"> <?php _e( 'Show Image Popup Link', 'portfolio-designer-lite' ); ?> </label>
								</td>
								<td>
									<label class="desc_checkbox">
										<input id="portfolio_enable_popup_link" name="portfolio_enable_popup_link" type="checkbox" value="1" <?php echo ( isset( $portfolio_setting['enable_popup_link'] ) && $portfolio_setting['enable_popup_link'] == 1 ) ? 'checked="checked"' : ''; ?>/>
										<?php _e( 'Display popup link when hover on image', 'portfolio-designer-lite' ); ?>
									</label>
								</td>
							</tr>
							<tr class="portfolio_popup_tr search_icon_td">
                                <td class='pro-feature'>
                                    <label for="portfolio_search_icon" class="porfolio-title">
                                        <?php _e('Select Image Popup icon from list', 'portfolio-designer-lite'); ?>
                                    </label>
                                </td>
                                <td class='pro-feature'>
                                    <div class="pd-form-control">
                                        <div class="pd-selected-icon" title="fa fa-search">
                                            <i class="fa fa-search"></i>
                                        </div>
                                    </div>
                                    <input class="button search_icon_open" type="button" value="<?php esc_attr_e('Select Gallery Icon', 'portfolio-designer-lite'); ?>">
                                </td>
                            </tr>
							<tr class='portfolio_popup_project_tr'>
								<td>
									<label for="portfolio_image_link"> <?php _e( 'Project URL Open In', 'portfolio-designer-lite' ); ?> </label>
								</td>
								<td>
									<?php $portfolio_image_link = ( isset( $portfolio_setting['image_link'] ) && $portfolio_setting['image_link'] != '' ) ? $portfolio_setting['image_link'] : 'disable'; ?>
									<div class="select-cover">
										<select id="portfolio_image_link" name="portfolio_image_link">
											<option value="disable" <?php echo ( $portfolio_image_link == 'disable' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Disable', 'portfolio-designer-lite' ); ?></option>
											<option value="same_tab" <?php echo ( $portfolio_image_link == 'same_tab' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Same Tab', 'portfolio-designer-lite' ); ?></option>
											<option value="new_tab" <?php echo ( $portfolio_image_link == 'new_tab' ) ? 'selected="selected"' : ''; ?>><?php _e( 'New Tab', 'portfolio-designer-lite' ); ?></option>
										</select>
									</div>
									<p>
										<?php _e( 'The project URL while adding single custom post type.', 'portfolio-designer-lite' ); ?>
										<br/>
										<b><?php _e( 'Note', 'portfolio-designer-lite' ); ?>: </b>
										<?php _e( 'This will work only for custom post type generated by this plugin, on image hover.', 'portfolio-designer-lite' ); ?>
									</p>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div id="portdesignfilter" 
				<?php
				if ( $current_tab == 'portdesignfilter' ) {
					echo ' style="display: block;"';}
?>
 class="postbox postbox-with-fw-options pdltab">
					<div class="inside">
						<table>
							<tr>
								<td>
                                	<label for="portfolio_enable_filter" class="porfolio-title">
                                        <?php _e('Enable Filter?', 'portfolio-designer-lite'); ?>
                                    </label>
                                </td>
                                <td>
                                    <?php
                                    $enable_filter = 0;
                                    if (isset($portfolio_setting['enable_filter'])) {
                                        $enable_filter = ($portfolio_setting['enable_filter'] == 1) ? 1 : 0;
                                    }
                                    ?>
                                    <fieldset class="portfolio-social-options portfolio_enable_filter buttonset buttonset-hide" data-hide='1'>
                                        <input id="portfolio_enable_filter_1" name="portfolio_enable_filter" type="radio" value="1" <?php checked(1, $enable_filter); ?> />
                                        <label for="portfolio_enable_filter_1" <?php checked(1, $enable_filter); ?>><?php _e('Yes', 'portfolio-designer-lite'); ?></label>
                                        <input id="portfolio_enable_filter_0" name="portfolio_enable_filter" type="radio" value="0" <?php checked(0, $enable_filter); ?>/>
                                        <label for="portfolio_enable_filter_0" <?php checked(0, $enable_filter); ?>><?php _e('No', 'portfolio-designer-lite'); ?></label>
                                    </fieldset>
                                    <p>
                                        <b><?php _e('Note: ', 'portfolio-designer-lite'); ?></b>
                                        <?php _e('If you enable filter then Pagination Settings does not apply', 'portfolio-designer-lite'); ?>
                                    </p>
                                </td>
							</tr>
							<tr class="filter_data_tr">
								<td>
									<label for="portfolio_show_all_txt"><?php _e( 'Text of "Show All" filter tag', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<input id="portfolio_show_all_txt" name="portfolio_show_all_txt" type="text" placeholder="<?php _e( 'Show All', 'portfolio-designer-lite' ); ?>" value="<?php echo ( isset( $portfolio_setting['show_all_txt'] ) && $portfolio_setting['show_all_txt'] != '' ) ? esc_attr( $portfolio_setting['show_all_txt'] ) : __( 'Show All', 'portfolio-designer-lite' ); ?>" />
									<p>
										<b><?php _e( 'Note', 'portfolio-designer-lite' ); ?>: </b>
										<?php _e( 'If you will set blank then default text will be "Show All".', 'portfolio-designer-lite' ); ?>
									</p>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div id="portdesignpagination" 
				<?php
				if ( $current_tab == 'portdesignpagination' ) {
					echo ' style="display: block;"';}
?>
 class="postbox postbox-with-fw-options pdltab">
					<div class="inside">
						<table>
							<tr>
								<td>
									<label for="portfolio_enable_pagination"><?php _e( 'Enable Pagination?', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<label class="desc_checkbox">
										<input type="checkbox" id="portfolio_enable_pagination" name="portfolio_enable_pagination" value="1"  <?php echo ( isset( $portfolio_setting['enable_pagination'] ) && $portfolio_setting['enable_pagination'] == 1 ) ? 'checked="checked"' : ''; ?> />
									</label>
								</td>
							</tr>
							<tr class="portfolio_pagination_tr">
								<td class="pro-feature">
									<label for="portfolio_pagination_type"><?php _e( 'Pagination Type', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td class="pro-feature">
									<?php $portfolio_pagination_type = ( isset( $portfolio_setting['pagination_type'] ) && $portfolio_setting['pagination_type'] != '' ) ? $portfolio_setting['pagination_type'] : 'pagination'; ?>
									<div class="select-cover">
										<select id="portfolio_pagination_type" name="">
											<option value="pagination" selected="selected"><?php _e( 'Pagination', 'portfolio-designer-lite' ); ?></option>
										</select>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div id="pdlsocial" 
				<?php
				if ( $current_tab == 'pdlsocial' ) {
					echo ' style="display: block;"';}
?>
 class="postbox postbox-with-fw-options pdltab">
					<div class="inside">
						<table class="pdl-social-theme">
							<tbody>
								<tr class="keep-it-on">
									<td><label class="portfolio_enable_social_share_settings" ><?php _e( 'Social Share Links', 'portfolio-designer-lite' ); ?></label></td>
									<td>
										<?php
										if ( isset( $portfolio_setting['enable_social_share_settings'] ) ) {
											$portfolio_enable_social_share_settings = $portfolio_setting['enable_social_share_settings'];
										} else {
											$portfolio_enable_social_share_settings = 0;
										}
										?>
										<label><input type="checkbox" id="portfolio_enable_social_share_settings" name="portfolio_enable_social_share_settings" value="1"  <?php echo ( $portfolio_enable_social_share_settings == 1 ) ? 'checked' : ''; ?> />
											<?php _e( 'Enable Social Share Settings', 'portfolio-designer-lite' ); ?></label>
									</td>
								</tr>
								<tr>
									<td class="pro-feature"><label  class="portfolio_social_icon_display_position" ><?php _e( 'Social Icon Display Position', 'portfolio-designer-lite' ); ?></label></td>
									<td class="pro-feature">
										<?php
										$portfolio_social_icon_display_position = 1;
										if ( isset( $portfolio_setting['social_icon_display_position'] ) ) {
											$portfolio_social_icon_display_position = $portfolio_setting['social_icon_display_position'];
										}
										?>

										<fieldset class="pdl-social-options portfolio_social_icon_display_position buttonset buttonset-hide" data-hide='1'>
											<input id="portfolio_social_icon_display_position_0" name="portfolio_social_icon_display_position" type="radio" value="0" <?php checked( 0, $portfolio_social_icon_display_position ); ?>/>
											<label for="portfolio_social_icon_display_position_0" <?php checked( 0, $portfolio_social_icon_display_position ); ?>><?php _e( 'Before Loop', 'portfolio-designer-lite' ); ?></label>
											<input id="portfolio_social_icon_display_position_1" name="portfolio_social_icon_display_position" type="radio" value="1" <?php checked( 1, $portfolio_social_icon_display_position ); ?> />
											<label for="portfolio_social_icon_display_position_1" <?php checked( 1, $portfolio_social_icon_display_position ); ?>><?php _e( 'After Loop', 'portfolio-designer-lite' ); ?></label>
										</fieldset>

									</td>
								</tr>
								<tr>
									<td><label class="portfolio_social_icon_alignment" ><?php _e( 'Select Social Icon Alignment', 'portfolio-designer-lite' ); ?></label></td>
									<td>
										<?php
										$portfolio_social_icon_alignment = 'bottom';
										if ( isset( $portfolio_setting['social_icon_alignment'] ) ) {
											$portfolio_social_icon_alignment = esc_attr( $portfolio_setting['social_icon_alignment'] );
										}
										?>
										<div class="typo-field">
											<div class="select-cover">
												<select name="portfolio_social_icon_alignment" id="portfolio_social_icon_alignment">
													<option value="left" <?php echo selected( 'left', $portfolio_social_icon_alignment ); ?>><?php _e( 'Left', 'portfolio-designer-lite' ); ?></option>
													<option value="center" <?php echo selected( 'center', $portfolio_social_icon_alignment ); ?>><?php _e( 'Center', 'portfolio-designer-lite' ); ?></option>
													<option value="right" <?php echo selected( 'right', $portfolio_social_icon_alignment ); ?>><?php _e( 'Right', 'portfolio-designer-lite' ); ?></option>
												</select>
											</div>
										</div>
									</td>
								</tr>
								<tr>
									<td class="pro-feature"><label  class="portfolio_social_style" ><?php _e( 'Social Share Style', 'portfolio-designer-lite' ); ?></label></td>
									<td class="pro-feature">
										<?php
										$portfolio_social_style = 1;
										if ( isset( $portfolio_setting['social_style'] ) ) {
											$portfolio_social_style = $portfolio_setting['social_style'];
										}
										?>

										<fieldset class="pdl-social-options portfolio_social_style buttonset buttonset-hide" data-hide='1'>
											<input id="portfolio_social_style_1" name="portfolio_social_style" type="radio" value="1"  />
											<label for="portfolio_social_style_1" ><?php _e( 'Default', 'portfolio-designer-lite' ); ?></label>
											<input id="portfolio_social_style_0" name="portfolio_social_style" type="radio" value="0" <?php echo "checked='checked'"; ?>/>
											<label for="portfolio_social_style_0" <?php echo "checked='checked'"; ?>><?php _e( 'Custom', 'portfolio-designer-lite' ); ?></label>
										</fieldset>

									</td>
								</tr>
								<tr class="social_share_theme_tr">
									<td class="pro-feature"><?php _e( 'Available Icon Themes', 'portfolio-designer-lite' ); ?></td>
									<td class="pro-feature social-share-td">
										<div class="social-share-theme">
											<?php for ( $i = 1; $i <= 10; $i++ ) { ?>
												<div class="social-cover social_share_theme_<?php echo $i; ?>">
													<label>
														<input type="radio" id="default_icon_theme_<?php echo $i; ?>" value="" name="default_icon_theme" />
														<span class="pdl-social-icons facebook-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons gmail-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons twitter-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons linkdin-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons pin-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons whatsup-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons telegram-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons pocket-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons mail-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons reddit-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons tumblr-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons skype-icon pdl_theme_wrapper"></span>
														<span class="pdl-social-icons wordpress-icon pdl_theme_wrapper"></span>
													</label>
												</div>
											<?php } ?>
										</div>
									</td>
								</tr>
								<tr class="portfolio-social-style-on">
									<td><label  class="portfolio_social_icon_style" ><?php _e( 'Shape of Social Icon', 'portfolio-designer-lite' ); ?></label></td>
									<td>
										<?php
										$portfolio_social_icon_style = 1;
										if ( isset( $portfolio_setting['social_icon_style'] ) ) {
											$portfolio_social_icon_style = $portfolio_setting['social_icon_style'];
										}
										?>

										<fieldset class="pdl-social-options portfolio_social_icon_style buttonset buttonset-hide" data-hide='1'>
											<input id="portfolio_social_icon_style_1" name="portfolio_social_icon_style" type="radio" value="1" <?php checked( 1, $portfolio_social_icon_style ); ?> />
											<label for="portfolio_social_icon_style_1" <?php checked( 1, $portfolio_social_icon_style ); ?>><?php _e( 'Square', 'portfolio-designer-lite' ); ?></label>
											<input id="portfolio_social_icon_style_0" name="portfolio_social_icon_style" type="radio" value="0" <?php checked( 0, $portfolio_social_icon_style ); ?>/>
											<label for="portfolio_social_icon_style_0" <?php checked( 0, $portfolio_social_icon_style ); ?>><?php _e( 'Circle', 'portfolio-designer-lite' ); ?></label>
										</fieldset>

									</td>
								</tr>
								<tr class="portfolio-social-style-on">
									<td><label  class="portfolio_social_icon_size" ><?php _e( 'Size of Social Icon', 'portfolio-designer-lite' ); ?></td>
									<td>
										<?php
										$portfolio_social_icon_size = 1;
										if ( isset( $portfolio_setting['social_icon_size'] ) ) {
											$portfolio_social_icon_size = $portfolio_setting['social_icon_size'];
										}
										?>

										<fieldset class="pdl-social-options portfolio_social_icon_size buttonset buttonset-hide" data-hide='1'>
											<input id="portfolio_social_icon_size_1" name="portfolio_social_icon_size" type="radio" value="1" <?php checked( 1, $portfolio_social_icon_size ); ?> />
											<label for="portfolio_social_icon_size_1" <?php checked( 1, $portfolio_social_icon_size ); ?>><?php _e( 'Small', 'portfolio-designer-lite' ); ?></label>
											<input id="portfolio_social_icon_size_0" name="portfolio_social_icon_size" type="radio" value="0" <?php checked( 0, $portfolio_social_icon_size ); ?>/>
											<label for="portfolio_social_icon_size_0" <?php checked( 0, $portfolio_social_icon_size ); ?>><?php _e( 'Large', 'portfolio-designer-lite' ); ?></label>
										</fieldset>

									</td>
								</tr>
								<tr>
									<td><label class="portfolio_facebook_link" ><?php _e( 'Show Facebook Share Link', 'portfolio-designer-lite' ); ?></label></td>
									<td>
										<?php
										$portfolio_facebook_link = 1;
										if ( isset( $portfolio_setting['facebook_link'] ) ) {
											$portfolio_facebook_link = $portfolio_setting['facebook_link'];
										}

										$portfolio_facebook_link_with_count = 1;
										if ( isset( $portfolio_setting['facebook_link_with_count'] ) ) {
											$portfolio_facebook_link_with_count = $portfolio_setting['facebook_link_with_count'];
										}
										?>

										<fieldset class="pdl-social-options portfolio_facebook_link buttonset buttonset-hide" data-hide='1'>
											<input id="portfolio_facebook_link_1" name="portfolio_facebook_link" type="radio" value="1" <?php checked( 1, $portfolio_facebook_link ); ?> />
											<label for="portfolio_facebook_link_1" <?php checked( 1, $portfolio_facebook_link ); ?>><?php _e( 'Yes', 'portfolio-designer-lite' ); ?></label>
											<input id="portfolio_facebook_link_0" name="portfolio_facebook_link" type="radio" value="0" <?php checked( 0, $portfolio_facebook_link ); ?>/>
											<label for="portfolio_facebook_link_0" <?php checked( 0, $portfolio_facebook_link ); ?>><?php _e( 'No', 'portfolio-designer-lite' ); ?></label>
										</fieldset>
									</td>
								</tr>
								<tr>
									<td><label class="portfolio_twitter_link" ><?php _e( 'Show Twitter Share Link', 'portfolio-designer-lite' ); ?></label></td>
									<td>
										<?php
										$portfolio_twitter_link = 1;
										if ( isset( $portfolio_setting['twitter_link'] ) ) {
											$portfolio_twitter_link = $portfolio_setting['twitter_link'];
										}
										?>
										<fieldset class="pdl-social-options portfolio_twitter_link buttonset buttonset-hide" data-hide='1'>
											<input id="portfolio_twitter_link_1" name="portfolio_twitter_link" type="radio" value="1" <?php checked( 1, $portfolio_twitter_link ); ?> />
											<label for="portfolio_twitter_link_1" <?php checked( 1, $portfolio_twitter_link ); ?>><?php _e( 'Yes', 'portfolio-designer-lite' ); ?></label>
											<input id="portfolio_twitter_link_0" name="portfolio_twitter_link" type="radio" value="0" <?php checked( 0, $portfolio_twitter_link ); ?>/>
											<label for="portfolio_twitter_link_0" <?php checked( 0, $portfolio_twitter_link ); ?>><?php _e( 'No', 'portfolio-designer-lite' ); ?></label>
										</fieldset>
									</td>
								</tr>
								<tr>
									<td><label class="portfolio_google_link" ><?php _e( 'Show Google+ Share Link', 'portfolio-designer-lite' ); ?></label></td>
									<td>
										<?php
										$portfolio_google_link = 1;
										if ( isset( $portfolio_setting['google_link'] ) ) {
											$portfolio_google_link = $portfolio_setting['google_link'];
										}

										$portfolio_google_link_with_count = 1;
										if ( isset( $portfolio_setting['google_link_with_count'] ) ) {
											$portfolio_google_link_with_count = $portfolio_setting['google_link_with_count'];
										}
										?>

										<fieldset class="pdl-social-options portfolio_google_link buttonset buttonset-hide" data-hide='1'>
											<input id="portfolio_google_link_1" name="portfolio_google_link" type="radio" value="1" <?php checked( 1, $portfolio_google_link ); ?> />
											<label for="portfolio_google_link_1" <?php checked( 1, $portfolio_google_link ); ?>><?php _e( 'Yes', 'portfolio-designer-lite' ); ?></label>
											<input id="portfolio_google_link_0" name="portfolio_google_link" type="radio" value="0" <?php checked( 0, $portfolio_google_link ); ?>/>
											<label for="portfolio_google_link_0" <?php checked( 0, $portfolio_google_link ); ?>><?php _e( 'No', 'portfolio-designer-lite' ); ?></label>
										</fieldset>
									</td>
								</tr>
								<tr>
									<td><label class="portfolio_linkedin_link" ><?php _e( 'Show Linkedin Share Link', 'portfolio-designer-lite' ); ?></td>
									<td>
										<?php
										$portfolio_linkedin_link = 1;
										if ( isset( $portfolio_setting['linkedin_link'] ) ) {
											$portfolio_linkedin_link = $portfolio_setting['linkedin_link'];
										}

										$portfolio_linkedin_link_with_count = 1;
										if ( isset( $portfolio_setting['linkedin_link_with_count'] ) ) {
											$portfolio_linkedin_link_with_count = $portfolio_setting['linkedin_link_with_count'];
										}
										?>

										<fieldset class="pdl-social-options portfolio_linkedin_link buttonset buttonset-hide" data-hide='1'>
											<input id="portfolio_linkedin_link_1" name="portfolio_linkedin_link" type="radio" value="1" <?php checked( 1, $portfolio_linkedin_link ); ?> />
											<label for="portfolio_linkedin_link_1" <?php checked( 1, $portfolio_linkedin_link ); ?>><?php _e( 'Yes', 'portfolio-designer-lite' ); ?></label>
											<input id="portfolio_linkedin_link_0" name="portfolio_linkedin_link" type="radio" value="0" <?php checked( 0, $portfolio_linkedin_link ); ?>/>
											<label for="portfolio_linkedin_link_0" <?php checked( 0, $portfolio_linkedin_link ); ?>><?php _e( 'No', 'portfolio-designer-lite' ); ?></label>
										</fieldset>

										<label class="portfolio_linkedin_link_with_count pro-feature">
											<input id="portfolio_linkedin_link_with_count" name="portfolio_linkedin_link_with_count" type="checkbox" value="1" 
											<?php
											if ( $portfolio_linkedin_link_with_count == 1 && $portfolio_linkedin_link == 1 ) {
												echo '';
											}
											?>
											 /> <?php _e( 'Hide Linkedin Share Count', 'portfolio-designer-lite' ); ?>
										</label>
									</td>
								</tr>
								<tr>
									<td><label class="portfolio_pinterest_link" ><?php _e( 'Show Pinterest Share link', 'portfolio-designer-lite' ); ?></label></td>
									<td>
										<?php
										$portfolio_pinterest_link = 1;
										if ( isset( $portfolio_setting['pinterest_link'] ) ) {
											$portfolio_pinterest_link = $portfolio_setting['pinterest_link'];
										}

										$portfolio_pinterest_link_with_count = 1;
										if ( isset( $portfolio_setting['pinterest_link_with_count'] ) ) {
											$portfolio_pinterest_link_with_count = $portfolio_setting['pinterest_link_with_count'];
										}
										?>

										<fieldset class="pdl-social-options portfolio_pinterest_link buttonset buttonset-hide" data-hide='1'>
											<input id="portfolio_pinterest_link_1" name="portfolio_pinterest_link" type="radio" value="1" <?php checked( 1, $portfolio_pinterest_link ); ?> />
											<label for="portfolio_pinterest_link_1" <?php checked( 1, $portfolio_pinterest_link ); ?>><?php _e( 'Yes', 'portfolio-designer-lite' ); ?></label>
											<input id="portfolio_pinterest_link_0" name="portfolio_pinterest_link" type="radio" value="0" <?php checked( 0, $portfolio_pinterest_link ); ?>/>
											<label for="portfolio_pinterest_link_0" <?php checked( 0, $portfolio_pinterest_link ); ?>><?php _e( 'No', 'portfolio-designer-lite' ); ?></label>
										</fieldset>

										<label class="portfolio_pinterest_link_with_count pro-feature">
											<input id="portfolio_pinterest_link_with_count" name="portfolio_pinterest_link_with_count" type="checkbox" value="1" 
											<?php
											if ( $portfolio_pinterest_link_with_count == 1 && $portfolio_pinterest_link == 1 ) {
												echo '';
											}
											?>
											 /> <?php _e( 'Hide Pinterest Share Count', 'portfolio-designer-lite' ); ?>
										</label>
									</td>
								</tr>
							</tbody>
						</table>


					</div>
				</div>
				<div id="portdesignstyle" 
				<?php
				if ( $current_tab == 'portdesignstyle' ) {
					echo ' style="display: block;"';}
?>
 class="postbox postbox-with-fw-options pdltab">
					<div class="inside">						
						<h3 class="portfolio-headding"><?php _e( 'Title Typography', 'portfolio-designer-lite' ); ?></h3>
						<table>
							<tr>
								<td>
									<label><?php _e( 'Select Title Font Color', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<input id="portfolio_title_font_color" name="portfolio_title_font_color" type="text" data-alpha="true" class="portfolio-color-picker color-picker" value="<?php echo ( isset( $portfolio_setting['title_font_color'] ) && $portfolio_setting['title_font_color'] != '' ) ? esc_attr( $portfolio_setting['title_font_color'] ) : ''; ?>"/>
								</td>
							</tr>
							<tr>
                                <td class='pro-feature'>
                                    <label for="portfolio_title_font" class="porfolio-title">
                                        <?php _e('Select Title Font Family', 'portfolio-designer-lite'); ?>
                                    </label>
                                </td>
                                <td class='pro-feature'>
                                    <?php
                                    $title_font = isset($portfolio_setting['title_font']) ? $portfolio_setting['title_font'] : '';
                                    $title_font_type = isset($portfolio_setting['title_font_type']) ? $portfolio_setting['title_font_type'] : '';
                                    ?>
                                    <div class="select-cover">
                                        <input type="text" class="hidden" value="<?php echo $title_font_type; ?>" name="portfolio_title_font_type" id="portfolio_title_font_type"/>
                                        <select id="portfolio_title_font" name="portfolio_title_font">
											<option value="">
											<?php
												_e( 'Default', 'portfolio-designer-lite' );
												?>
											</option>
										</select>
                                    </div>
                                </td>
                            </tr>
							<tr>
                                <td>
                                    <label for="portfolio_title_font_size" class="porfolio-title">
                                        <?php _e('Select Title Font Size', 'portfolio-designer-lite'); ?>
                                    </label>
                                </td>
                                <td>
                                    <div class="font_size_cover">
                                        <div class="pull-left">
                                            <div class="grid_col_space range_slider_fontsize" id="portfolio_title_font_size_slider" data-value="<?php echo (isset($portfolio_setting['title_font_size']) && $portfolio_setting['title_font_size'] != '') ? $portfolio_setting['title_font_size'] : 0; ?>"></div>
                                        </div>
                                        <div class="pull-right">
                                            <div class="slide_val input-number-cover">
                                                <input id="portfolio_title_font_size" name="portfolio_title_font_size" type="number" class="numberOnly range-slider__value" value="<?php echo (isset($portfolio_setting['title_font_size']) && $portfolio_setting['title_font_size'] != '') ? $portfolio_setting['title_font_size'] : 0; ?>" min="0"/>
                                            </div>
                                            <div class="select-cover font-size-cover pro-feature">
                                                <select id="portfolio_title_font_unit" name="portfolio_title_font_unit">
                                                    <option value="px">px</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <p><b><?php _e('Note: ', 'portfolio-designer-lite'); ?></b><?php _e('If want to default font size set value ZERO.', 'portfolio-designer-lite'); ?></p>
                                </td>
                            </tr>
						</table>
						<div class="pdl-typography-wrapper">
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_title_font_weight"><?php _e( 'Font Weight', 'portfolio-designer-lite' ); ?></label></div>
								<div class="select-cover">
									<?php $title_font_weight = ( isset( $portfolio_setting['title_font_weight'] ) && $portfolio_setting['title_font_weight'] != '' ) ? $portfolio_setting['title_font_weight'] : 'normal'; ?>
									<select id="portfolio_title_font_weight" name="">
										<option value="normal" <?php echo ( $title_font_weight == 'normal' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Normal', 'portfolio-designer-lite' ); ?></option>
										<option value="100" <?php echo ( $title_font_weight == '100' ) ? 'selected="selected"' : ''; ?> >100</option>
										<option value="200" <?php echo ( $title_font_weight == '200' ) ? 'selected="selected"' : ''; ?>>200</option>
										<option value="300" <?php echo ( $title_font_weight == '300' ) ? 'selected="selected"' : ''; ?>>300</option>
										<option value="400" <?php echo ( $title_font_weight == '400' ) ? 'selected="selected"' : ''; ?>>400</option>
										<option value="500" <?php echo ( $title_font_weight == '500' ) ? 'selected="selected"' : ''; ?>>500</option>
										<option value="600" <?php echo ( $title_font_weight == '600' ) ? 'selected="selected"' : ''; ?>>600</option>
										<option value="700" <?php echo ( $title_font_weight == '700' ) ? 'selected="selected"' : ''; ?>>700</option>
										<option value="800" <?php echo ( $title_font_weight == '800' ) ? 'selected="selected"' : ''; ?>>800</option>
										<option value="900" <?php echo ( $title_font_weight == '900' ) ? 'selected="selected"' : ''; ?>>900</option>
										<option value="bold" <?php echo ( $title_font_weight == 'bold' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Bold', 'portfolio-designer-lite' ); ?></option>
									</select>
								</div>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_title_font_text_transform"><?php _e( 'Text Transform', 'portfolio-designer-lite' ); ?></label></div>
								<div class="select-cover">
									<?php $title_font_text_transform = ( isset( $portfolio_setting['title_font_text_transform'] ) && $portfolio_setting['title_font_text_transform'] != '' ) ? $portfolio_setting['title_font_text_transform'] : 'none'; ?>
									<select id="portfolio_title_font_text_transform" name="">
										<option value="none" <?php echo ( $title_font_text_transform == 'none' ) ? 'selected="selected"' : ''; ?>><?php _e( 'None', 'portfolio-designer-lite' ); ?></option>
										<option value="capitalize" <?php echo ( $title_font_text_transform == 'capitalize' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Capitalize', 'portfolio-designer-lite' ); ?></option>
										<option value="uppercase" <?php echo ( $title_font_text_transform == 'uppercase' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Uppercase', 'portfolio-designer-lite' ); ?></option>
										<option value="lowercase" <?php echo ( $title_font_text_transform == 'lowercase' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Lowercase', 'portfolio-designer-lite' ); ?></option>
										<option value="full-width" <?php echo ( $title_font_text_transform == 'full-width' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Full Width', 'portfolio-designer-lite' ); ?></option>
									</select>
								</div>
							</div>							
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_title_font_text_decoration"><?php _e( 'Text Decoration', 'portfolio-designer-lite' ); ?></label></div>
								<div class="select-cover">
									<?php $title_font_text_decoration = ( isset( $portfolio_setting['title_font_text_decoration'] ) && $portfolio_setting['title_font_text_decoration'] != '' ) ? $portfolio_setting['title_font_text_decoration'] : 'none'; ?>
									<select id="portfolio_title_font_text_decoration" name="portfolio_title_font_text_decoration">
										<option value="none" <?php echo ( $title_font_text_decoration == 'none' ) ? 'selected="selected"' : ''; ?>><?php _e( 'None', 'portfolio-designer-lite' ); ?></option>
										<option value="underline" <?php echo ( $title_font_text_decoration == 'underline' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Underline', 'portfolio-designer-lite' ); ?></option>
										<option value="overline" <?php echo ( $title_font_text_decoration == 'overline' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Overline', 'portfolio-designer-lite' ); ?></option>
										<option value="line-through" <?php echo ( $title_font_text_decoration == 'line-through' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Line Through', 'portfolio-designer-lite' ); ?></option>
									</select>
								</div>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_title_font_italic_style"><?php _e( 'Italic Style ', 'portfolio-designer-lite' ); ?></label></div>
								<input id="portfolio_title_font_italic_style" name="portfolio_title_font_italic_style" type="checkbox" value="1" <?php echo ( isset( $portfolio_setting['title_font_italic_style'] ) && $portfolio_setting['title_font_italic_style'] == 1 ) ? 'checked="checked"' : ''; ?>/>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_title_font_line_height"><?php _e( 'Line Height', 'portfolio-designer-lite' ); ?></label></div>
								<div class="input-number-cover">
									<?php $title_font_line_height = ( isset( $portfolio_setting['title_font_line_height'] ) && $portfolio_setting['title_font_line_height'] != '' ) ? $portfolio_setting['title_font_line_height'] : 1.5; ?>
									<input id="portfolio_title_font_line_height" name="" type="number" class="numberOnly" value="<?php echo $title_font_line_height; ?>" min="0" step="0.1"/>
								</div>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_title_font_letter_spacing"><?php _e( 'Letter Spacing (px)', 'portfolio-designer-lite' ); ?></label></div>
								<div class="input-number-cover">
									<?php $title_font_letter_spacing = ( isset( $portfolio_setting['title_font_letter_spacing'] ) && $portfolio_setting['title_font_letter_spacing'] != '' ) ? $portfolio_setting['title_font_letter_spacing'] : 0; ?>
									<input id="portfolio_title_font_letter_spacing" name="" type="number" class="numberOnly" value="<?php echo intval( $title_font_letter_spacing ); ?>" min="0"/>
								</div>
							</div>
						</div>
						<div class="section-seprator"></div>
						<h3 class='portfolio-headding'><?php _e( 'Content Typography', 'portfolio-designer-lite' ); ?></h3>
						<table>
							<tr>
								<td>
									<label for="portfolio_content_font_color"><?php _e( 'Select Content Font Color', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<input id="portfolio_content_font_color" name="portfolio_content_font_color" type="text" data-alpha="true" class="portfolio-color-picker color-picker" value="<?php echo ( isset( $portfolio_setting['content_font_color'] ) && $portfolio_setting['content_font_color'] != '' ) ? esc_attr( $portfolio_setting['content_font_color'] ) : ''; ?>" />
								</td>
							</tr>
							<tr>
								<td class='pro-feature'>
									<label for="portfolio_content_font"><?php _e( 'Select Content Font Family', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td class='pro-feature'>
									<?php
									$content_font      = isset( $portfolio_setting['content_font'] ) ? $portfolio_setting['content_font'] : '';
									$content_font_type = isset( $portfolio_setting['content_font_type'] ) ? $portfolio_setting['content_font_type'] : '';
									?>
									<div class="select-cover">
										<input type="text" class="hidden" value="<?php echo esc_attr( $content_font_type ); ?>" name="portfolio_content_font_type" id="portfolio_content_font_type"/>
										<select id="portfolio_content_font" name="">
											<option value="">
											<?php
												_e( 'Default', 'portfolio-designer-lite' );
												?>
												</option>
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label for="portfolio_content_font_size">
										<?php _e('Select Content Font Size', 'portfolio-designer-lite'); ?>
									</label>
								</td>
								<td>
									<div class="font_size_cover">
										<div class="pull-left">
											<div class="grid_col_space range_slider_fontsize" id="portfolio_content_font_size_slider" data-value="<?php echo ( isset( $portfolio_setting['content_font_size'] ) && $portfolio_setting['content_font_size'] != '' ) ? esc_attr( $portfolio_setting['content_font_size'] ) : 0; ?>"></div>
										</div>
										<div class="pull-right">
											<div class="slide_val input-number-cover">
												<input id="portfolio_content_font_size" name="portfolio_content_font_size" type="number" class="numberOnly range-slider__value" value="<?php echo ( isset( $portfolio_setting['content_font_size'] ) && $portfolio_setting['content_font_size'] != '' ) ? esc_attr( $portfolio_setting['content_font_size'] ) : 0; ?>" min="0"/>
											</div>
											<div class="select-cover font-size-cover pro-feature">
                                                <select id="portfolio_content_font_unit" name="portfolio_content_font_unit">
                                                    <option value="px">px</option>
                                                    <option value="em">em</option>
                                                    <option value="%">%</option>
                                                    <option value="cm">cm</option>
                                                    <option value="ex">ex</option>
                                                    <option value="mm">mm</option>
                                                    <option value="in">in</option>
                                                    <option value="pt">pt</option>
                                                    <option value="pc">pc</option>
                                                </select>
                                            </div>
										</div>
									</div>
								</td>
							</tr>
						</table>
						<div class="pdl-typography-wrapper">
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_content_font_weight"><?php _e( 'Font Weight', 'portfolio-designer-lite' ); ?></label></div>
								<div class="select-cover">
									<?php $content_font_weight = ( isset( $portfolio_setting['content_font_weight'] ) && $portfolio_setting['content_font_weight'] != '' ) ? $portfolio_setting['content_font_weight'] : 'normal'; ?>
									<select id="portfolio_content_font_weight" name="">
										<option value="100" <?php echo ( $content_font_weight == '100' ) ? 'selected="selected"' : ''; ?> >100</option>
										<option value="200" <?php echo ( $content_font_weight == '200' ) ? 'selected="selected"' : ''; ?>>200</option>
										<option value="300" <?php echo ( $content_font_weight == '300' ) ? 'selected="selected"' : ''; ?>>300</option>
										<option value="400" <?php echo ( $content_font_weight == '400' ) ? 'selected="selected"' : ''; ?>>400</option>
										<option value="500" <?php echo ( $content_font_weight == '500' ) ? 'selected="selected"' : ''; ?>>500</option>
										<option value="600" <?php echo ( $content_font_weight == '600' ) ? 'selected="selected"' : ''; ?>>600</option>
										<option value="700" <?php echo ( $content_font_weight == '700' ) ? 'selected="selected"' : ''; ?>>700</option>
										<option value="800" <?php echo ( $content_font_weight == '800' ) ? 'selected="selected"' : ''; ?>>800</option>
										<option value="900" <?php echo ( $content_font_weight == '900' ) ? 'selected="selected"' : ''; ?>>900</option>
										<option value="bold" <?php echo ( $content_font_weight == 'bold' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Bold', 'portfolio-designer-lite' ); ?></option>
										<option value="normal" <?php echo ( $content_font_weight == 'normal' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Normal', 'portfolio-designer-lite' ); ?></option>
									</select>
								</div>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_content_font_text_transform"><?php _e( 'Text Transform', 'portfolio-designer-lite' ); ?></label></div>
								<div class="select-cover">
									<?php $content_font_text_transform = ( isset( $portfolio_setting['content_font_text_transform'] ) && $portfolio_setting['content_font_text_transform'] != '' ) ? $portfolio_setting['content_font_text_transform'] : 'none'; ?>
									<select id="portfolio_content_font_text_transform" name="portfolio_content_font_text_transform">
										<option value="none" <?php echo ( $content_font_text_transform == 'none' ) ? 'selected="selected"' : ''; ?>><?php _e( 'None', 'portfolio-designer-lite' ); ?></option>
										<option value="capitalize" <?php echo ( $content_font_text_transform == 'capitalize' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Capitalize', 'portfolio-designer-lite' ); ?></option>
										<option value="uppercase" <?php echo ( $content_font_text_transform == 'uppercase' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Uppercase', 'portfolio-designer-lite' ); ?></option>
										<option value="lowercase" <?php echo ( $content_font_text_transform == 'lowercase' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Lowercase', 'portfolio-designer-lite' ); ?></option>
										<option value="full-width" <?php echo ( $content_font_text_transform == 'full-width' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Full Width', 'portfolio-designer-lite' ); ?></option>
									</select>
								</div>
							</div>							
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"> <label for="portfolio_content_font_text_decoration"><?php _e( 'Text Decoration', 'portfolio-designer-lite' ); ?></label></div>
								<div class="select-cover">
									<?php $content_font_text_decoration = ( isset( $portfolio_setting['content_font_text_decoration'] ) && $portfolio_setting['content_font_text_decoration'] != '' ) ? $portfolio_setting['content_font_text_decoration'] : 'none'; ?>
									<select id="portfolio_content_font_text_decoration" name="">
										<option value="none" <?php echo ( $content_font_text_decoration == 'none' ) ? 'selected="selected"' : ''; ?>><?php _e( 'None', 'portfolio-designer-lite' ); ?></option>
										<option value="underline" <?php echo ( $content_font_text_decoration == 'underline' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Underline', 'portfolio-designer-lite' ); ?></option>
										<option value="overline" <?php echo ( $content_font_text_decoration == 'overline' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Overline', 'portfolio-designer-lite' ); ?></option>
										<option value="line-through" <?php echo ( $title_font_text_decoration == 'line-through' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Line Through', 'portfolio-designer-lite' ); ?></option>
									</select>
								</div>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_content_font_italic_style"><?php _e( 'Italic Style ', 'portfolio-designer-lite' ); ?></label></div>
								<div class="">
									<input id="portfolio_content_font_italic_style" name="" type="checkbox" value="1" <?php echo ( isset( $portfolio_setting['content_font_italic_style'] ) && $portfolio_setting['content_font_italic_style'] == 1 ) ? 'checked="checked"' : ''; ?>/>
								</div>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_content_font_line_height"><?php _e( 'Line Height', 'portfolio-designer-lite' ); ?></label></div>
								<div class="input-number-cover">
									<?php $content_font_line_height = ( isset( $portfolio_setting['content_font_line_height'] ) && $portfolio_setting['content_font_line_height'] != '' ) ? $portfolio_setting['content_font_line_height'] : 1.5; ?>
									<input id="portfolio_content_font_line_height" name="" type="number" class="numberOnly" value="<?php echo esc_attr( $content_font_line_height ); ?>" min="0" step="0.1" />
								</div>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_content_font_letter_spacing"><?php _e( 'Letter Spacing (px)', 'portfolio-designer-lite' ); ?></label></div>
								<div class="input-number-cover">
									<?php $content_font_letter_spacing = ( isset( $portfolio_setting['content_font_letter_spacing'] ) && $portfolio_setting['content_font_letter_spacing'] != '' ) ? $portfolio_setting['content_font_letter_spacing'] : 0; ?>
									<input id="portfolio_content_font_letter_spacing" name="" type="number" class="numberOnly" value="<?php echo esc_attr( $content_font_letter_spacing ); ?>" min="0"/>
								</div>
							</div>
						</div>
						<div class="section-seprator"></div>
						<h3 class='pdl-headding'><?php _e( 'Content background Typography', 'portfolio-designer-lite' ); ?></h3>
						<table>
							<tr>
								<td>
									<label for="content_background_color"><?php _e( 'Content Background Color', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<input id="content_background_color" name="content_background_color" type="text" data-alpha="true" class="portfolio-color-picker color-picker" value="<?php echo ( isset( $portfolio_setting['content_background_color'] ) && $portfolio_setting['content_background_color'] != '' ) ? esc_attr( $portfolio_setting['content_background_color'] ) : ''; ?>" />
								</td>
							</tr>
							<tr>
								<td class="pro-feature">
									<label for="portfolio_overlay_background_color"><?php _e( 'Image Hover Background Color', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td class="pro-feature">
									<input id="portfolio_overlay_background_color" name="portfolio_overlay_background_color" type="text" data-alpha="true" class="portfolio-color-picker color-picker" value="<?php echo ( isset( $portfolio_setting['overlay_background_color'] ) && $portfolio_setting['overlay_background_color'] != '' ) ? esc_attr( $portfolio_setting['overlay_background_color'] ) : ''; ?>" />
								</td>
							</tr>
							<tr>
								<td>
									<label for="portfolio_custom_css"><?php _e( 'Custom CSS', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<textarea id="portfolio_custom_css" name="portfolio_custom_css"><?php echo ( isset( $portfolio_setting['custom_css'] ) && $portfolio_setting['custom_css'] != '' ) ? $portfolio_setting['custom_css'] : ''; ?></textarea>
								</td>
							</tr>
						</table>
						<div class="section-seprator"></div>
						<h3 class='pdl-headding'><?php _e( 'Meta Typography', 'portfolio-designer-lite' ); ?></h3>
						<table>
							<tr>
								<td><label for="portfolio_meta_font_color"><?php _e( 'Select Meta Font Color', 'portfolio-designer-lite' ); ?></label></td>
								<td>
									<input id="portfolio_meta_font_color" name="portfolio_meta_font_color" type="text" data-alpha="true" class="portfolio-color-picker color-picker" value="<?php echo isset( $portfolio_setting['meta_font_color'] ) ? esc_attr( $portfolio_setting['meta_font_color'] ) : '#222'; ?>" />
								</td>
							</tr>
							<tr>
								<td class='pro-feature'><label for="portfolio_meta_font"><?php _e( 'Select Meta Font Family', 'portfolio-designer-lite' ); ?></label></td>
								<td class='pro-feature'>
									<?php
									$meta_font      = isset( $portfolio_setting['meta_font'] ) ? $portfolio_setting['meta_font'] : '';
									$meta_font_type = isset( $portfolio_setting['meta_font_type'] ) ? $portfolio_setting['meta_font_type'] : '';
									?>
									<div class="select-cover">
										<input type="text" class="hidden" value="<?php echo esc_attr( $meta_font_type ); ?>" name="portfolio_meta_font_type" id="portfolio_meta_font_type" />
										<select id="portfolio_meta_font" name="">
											<option value=""><?php _e( 'Default', 'portfolio-designer-lite' ); ?></option>
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<td><label for="portfolio_meta_font_size"><?php _e( 'Select Meta Font Size', 'portfolio-designer-lite' ); ?></label></td>
								<td>
									<div class="font_size_cover">
										<div class="pull-left">
											<div class="grid_col_space range_slider_fontsize" id="portfolio_meta_font_size_slider" data-value="<?php echo ( isset( $portfolio_setting['meta_font_size'] ) && $portfolio_setting['meta_font_size'] != '' ) ? $portfolio_setting['meta_font_size'] : 0; ?>"></div>
										</div>
										<div class="pull-right">
											<div class="slide_val input-number-cover">
												<input id="portfolio_meta_font_size" name="portfolio_meta_font_size" type="number" class="numberOnly range-slider__value" value="<?php echo ( isset( $portfolio_setting['meta_font_size'] ) && $portfolio_setting['meta_font_size'] != '' ) ? esc_attr( $portfolio_setting['meta_font_size'] ) : 0; ?>" min="0"/>
											</div>
											<div class="select-cover font-size-cover pro-feature">
                                                <select id="portfolio_meta_font_unit" name="portfolio_meta_font_unit">
                                                    <option value="px">px</option>
                                                    <option value="em">em</option>
                                                    <option value="%">%</option>
                                                    <option value="cm">cm</option>
                                                    <option value="ex">ex</option>
                                                    <option value="mm">mm</option>
                                                    <option value="in">in</option>
                                                    <option value="pt">pt</option>
                                                    <option value="pc">pc</option>
                                                </select>
                                            </div>
										</div>
									</div>
								</td>
							</tr>
						</table>
						<div class="pdl-typography-wrapper">
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_meta_font_weight"><?php _e( 'Font Weight', 'portfolio-designer-lite' ); ?></label></div>
								<div class="select-cover">
									<?php $meta_font_weight = ( isset( $portfolio_setting['meta_font_weight'] ) && $portfolio_setting['meta_font_weight'] != '' ) ? $portfolio_setting['meta_font_weight'] : 'normal'; ?>
									<select id="portfolio_meta_font_weight" name="">
										<option value="100" <?php echo ( $meta_font_weight == '100' ) ? 'selected="selected"' : ''; ?> >100</option>
										<option value="200" <?php echo ( $meta_font_weight == '200' ) ? 'selected="selected"' : ''; ?>>200</option>
										<option value="300" <?php echo ( $meta_font_weight == '300' ) ? 'selected="selected"' : ''; ?>>300</option>
										<option value="400" <?php echo ( $meta_font_weight == '400' ) ? 'selected="selected"' : ''; ?>>400</option>
										<option value="500" <?php echo ( $meta_font_weight == '500' ) ? 'selected="selected"' : ''; ?>>500</option>
										<option value="600" <?php echo ( $meta_font_weight == '600' ) ? 'selected="selected"' : ''; ?>>600</option>
										<option value="700" <?php echo ( $meta_font_weight == '700' ) ? 'selected="selected"' : ''; ?>>700</option>
										<option value="800" <?php echo ( $meta_font_weight == '800' ) ? 'selected="selected"' : ''; ?>>800</option>
										<option value="900" <?php echo ( $meta_font_weight == '900' ) ? 'selected="selected"' : ''; ?>>900</option>
										<option value="bold" <?php echo ( $meta_font_weight == 'bold' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Bold', 'portfolio-designer-lite' ); ?></option>
										<option value="normal" <?php echo ( $meta_font_weight == 'normal' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Normal', 'portfolio-designer-lite' ); ?></option>
									</select>
								</div>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_meta_font_text_transform"><?php _e( 'Text Transform', 'portfolio-designer-lite' ); ?></label></div>
								<div class="select-cover">
									<?php $meta_font_text_transform = ( isset( $portfolio_setting['meta_font_text_transform'] ) && $portfolio_setting['meta_font_text_transform'] != '' ) ? $portfolio_setting['meta_font_text_transform'] : 'none'; ?>
									<select id="portfolio_meta_font_text_transform" name="">
										<option value="none" <?php echo ( $meta_font_text_transform == 'none' ) ? 'selected="selected"' : ''; ?>><?php _e( 'None', 'portfolio-designer-lite' ); ?></option>
										<option value="capitalize" <?php echo ( $meta_font_text_transform == 'capitalize' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Capitalize', 'portfolio-designer-lite' ); ?></option>
										<option value="uppercase" <?php echo ( $meta_font_text_transform == 'uppercase' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Uppercase', 'portfolio-designer-lite' ); ?></option>
										<option value="lowercase" <?php echo ( $meta_font_text_transform == 'lowercase' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Lowercase', 'portfolio-designer-lite' ); ?></option>
										<option value="full-width" <?php echo ( $meta_font_text_transform == 'full-width' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Full Width', 'portfolio-designer-lite' ); ?></option>
									</select>
								</div>
							</div>							
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_meta_font_text_decoration"><?php _e( 'Text Decoration', 'portfolio-designer-lite' ); ?></label></div>
								<div class="select-cover">
									<?php $meta_font_text_decoration = ( isset( $portfolio_setting['meta_font_text_decoration'] ) && $portfolio_setting['meta_font_text_decoration'] != '' ) ? $portfolio_setting['meta_font_text_decoration'] : 'none'; ?>
									<select id="portfolio_meta_font_text_decoration" name="">
										<option value="none" <?php echo ( $meta_font_text_decoration == 'none' ) ? 'selected="selected"' : ''; ?>><?php _e( 'None', 'portfolio-designer-lite' ); ?></option>
										<option value="underline" <?php echo ( $meta_font_text_decoration == 'underline' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Underline', 'portfolio-designer-lite' ); ?></option>
										<option value="overline" <?php echo ( $meta_font_text_decoration == 'overline' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Overline', 'portfolio-designer-lite' ); ?></option>
										<option value="line-through" <?php echo ( $title_font_text_decoration == 'line-through' ) ? 'selected="selected"' : ''; ?>><?php _e( 'Line Through', 'portfolio-designer-lite' ); ?></option>
									</select>
								</div>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_meta_font_italic_style"><?php _e( 'Italic Style ', 'portfolio-designer-lite' ); ?></label></div>
								<input id="portfolio_meta_font_italic_style" name="" type="checkbox" value="1" <?php echo ( isset( $portfolio_setting['meta_font_italic_style'] ) && $portfolio_setting['meta_font_italic_style'] == 1 ) ? 'checked="checked"' : ''; ?>/>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_meta_font_line_height"><?php _e( 'Line Height', 'portfolio-designer-lite' ); ?></label></div>
								<div class="input-number-cover">
									<?php $meta_font_line_height = ( isset( $portfolio_setting['meta_font_line_height'] ) && $portfolio_setting['meta_font_line_height'] != '' ) ? $portfolio_setting['meta_font_line_height'] : 1.5; ?>
									<input id="portfolio_meta_font_line_height" name="" type="number" class="numberOnly" value="<?php echo esc_attr( $meta_font_line_height ); ?>" min="0" step="0.1" />
								</div>
							</div>
							<div class="pdl-typography-cover pro-feature">
								<div class="pdl-label"><label for="portfolio_meta_font_letter_spacing"><?php _e( 'Letter Spacing (px)', 'portfolio-designer-lite' ); ?></label></div>
								<div class="input-number-cover">
									<?php $meta_font_letter_spacing = ( isset( $portfolio_setting['meta_font_letter_spacing'] ) && $portfolio_setting['meta_font_letter_spacing'] != '' ) ? $portfolio_setting['meta_font_letter_spacing'] : 0; ?>
									<input id="portfolio_meta_font_letter_spacing" name="" type="number" class="numberOnly" value="<?php echo esc_attr( $meta_font_letter_spacing ); ?>" min="0"/>
								</div>
							</div>
						</div>
						<div class="section-seprator filter_data_tr"></div>
						<h3 class='pdl-headding filter_data_tr'><?php _e( 'Filter Typography', 'portfolio-designer-lite' ); ?></h3>
						<table>
							<tr class="filter_data_tr">
								<td>
									<label><?php _e( 'Filter Text Padding', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td class="filter_text_padding">
									<?php
									$portfolio_filter_padding_top    = ( isset( $portfolio_setting['filter_padding_top'] ) && $portfolio_setting['filter_padding_top'] != '' ) ? $portfolio_setting['filter_padding_top'] : 5;
									$portfolio_filter_padding_right  = ( isset( $portfolio_setting['filter_padding_top'] ) && $portfolio_setting['filter_padding_right'] != '' ) ? $portfolio_setting['filter_padding_right'] : 15;
									$portfolio_filter_padding_bottom = ( isset( $portfolio_setting['filter_padding_top'] ) && $portfolio_setting['filter_padding_bottom'] != '' ) ? $portfolio_setting['filter_padding_bottom'] : 5;
									$portfolio_filter_padding_left   = ( isset( $portfolio_setting['filter_padding_top'] ) && $portfolio_setting['filter_padding_left'] != '' ) ? $portfolio_setting['filter_padding_left'] : 15;
									?>
									<div class="input-number-cover">
										<input name="portfolio_filter_padding_top" type="number" class="numberOnly" value="<?php echo esc_attr( $portfolio_filter_padding_top ); ?>" min="0"/>
									</div>
									<div class="input-number-cover">
										<input name="portfolio_filter_padding_right" type="number" class="numberOnly" value="<?php echo esc_attr( $portfolio_filter_padding_right ); ?>" min="0"/>
									</div>
									<div class="input-number-cover">
										<input name="portfolio_filter_padding_bottom" type="number" class="numberOnly" value="<?php echo esc_attr( $portfolio_filter_padding_bottom ); ?>" min="0"/>
									</div>
									<div class="input-number-cover">
										<input name="portfolio_filter_padding_left" type="number" class="numberOnly" value="<?php echo esc_attr( $portfolio_filter_padding_left ); ?>" min="0"/>
									</div>
									<span>px</span>
								</td>
							</tr>
							<tr class="filter_data_tr">
								<td>
									<label><?php _e( 'Filter Text Border', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td class="filter_text_border">
									<?php
									$portfolio_filter_border_width = ( isset( $portfolio_setting['filter_border_width'] ) ) ? $portfolio_setting['filter_border_width'] : 1;
									$portfolio_filter_border_style = ( isset( $portfolio_setting['filter_border_style'] ) ) ? $portfolio_setting['filter_border_style'] : 'solid';
									?>
									<div class="input-number-cover">
										<input name="portfolio_filter_border_width" type="number" class="numberOnly" value="<?php echo esc_attr( $portfolio_filter_border_width ); ?>" min="0"/>
									</div>
									<span>px</span>
									<div class="select-cover small-select">
										<select name="portfolio_filter_border_style">
											<option <?php selected( $portfolio_filter_border_style, 'none' ); ?> value="none"><?php _e( 'None', 'portfolio-designer-lite' ); ?></option>
											<option <?php selected( $portfolio_filter_border_style, 'solid' ); ?> value="solid"><?php _e( 'Solid', 'portfolio-designer-lite' ); ?></option>
											<option <?php selected( $portfolio_filter_border_style, 'dotted' ); ?> value="dotted"><?php _e( 'Dotted', 'portfolio-designer-lite' ); ?></option>
											<option <?php selected( $portfolio_filter_border_style, 'dashed' ); ?> value="dashed"><?php _e( 'Dashed', 'portfolio-designer-lite' ); ?></option>
											<option <?php selected( $portfolio_filter_border_style, 'double' ); ?> value="double"><?php _e( 'Double', 'portfolio-designer-lite' ); ?></option>
										</select>
									</div>
									<div class="set-background">
										<input id="portfolio_filter_border_color" name="portfolio_filter_border_color" type="text" data-alpha="true" class="portfolio-color-picker color-picker" value="<?php echo ( isset( $portfolio_setting['filter_border_color'] ) ) ? esc_attr( $portfolio_setting['filter_border_color'] ) : '#000000'; ?>"/>
									</div>
								</td>
							</tr>
							<tr class="filter_data_tr">
								<td>
									<label><?php _e( 'Filter Text & Border Hover Color', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<input id="portfolio_filter_text_border_color" name="portfolio_filter_text_border_color" type="text" data-alpha="true" class="portfolio-color-picker color-picker" value="<?php echo ( isset( $portfolio_setting['filter_text_border_color'] ) ) ? esc_attr( $portfolio_setting['filter_text_border_color'] ) : '#3BC391'; ?>"/>
								</td>
							</tr>
							<tr class="filter_data_tr">
								<td>
									<label><?php _e( 'Filter Text Background Color', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<input id="portfolio_filter_text_back_color" name="portfolio_filter_text_back_color" type="text" data-alpha="true" class="portfolio-color-picker color-picker" value="<?php echo ( isset( $portfolio_setting['filter_text_back_color'] ) ) ? esc_attr( $portfolio_setting['filter_text_back_color'] ) : ''; ?>"/>
								</td>
							</tr>
							<tr class="filter_data_tr">
								<td>
									<label><?php _e( 'Filter Text Background Hover Color', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<input id="portfolio_filter_text_back_hover_color" name="portfolio_filter_text_back_hover_color" type="text" data-alpha="true" class="portfolio-color-picker color-picker" value="<?php echo ( isset( $portfolio_setting['filter_text_back_hover_color'] ) ) ? esc_attr( $portfolio_setting['filter_text_back_hover_color'] ) : ''; ?>"/>
								</td>
							</tr>							
						</table>
						<div class="section-seprator"></div>
						<h3 class='pdl-headding'><?php _e( 'Button Typography', 'portfolio-designer-lite' ); ?></h3>
						<table>
							<tr>
								<td class='pro-feature'>
									<label for="portfolio_button_font"><?php _e( 'Button Font Family', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td class='pro-feature'>
									<?php
									$button_font      = isset( $portfolio_setting['button_font'] ) ? $portfolio_setting['button_font'] : '';
									$button_font_type = isset( $portfolio_setting['button_font_type'] ) ? $portfolio_setting['button_font_type'] : '';
									?>
									<div class="select-cover">
										<input type="text" class="hidden" value="<?php echo $button_font_type; ?>" name="portfolio_button_font_type" id="portfolio_button_font_type" />
										<select id="portfolio_button_font" name="">
											<option value="">
											<?php
												_e( 'Default', 'portfolio-designer-lite' );
												?>
												</option>
										</select>
									</div>
								</td>
							</tr>
							<tr>
								<td class="pro-feature">
									<label for="portfolio_button_font_size"><?php _e( 'Button Font Size', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td class="pro-feature">
									<div class="font_size_cover">
										<div class="pull-left">
											<div class="grid_col_space range_slider_fontsize" id="portfolio_button_font_size_slider" data-value="<?php echo ( isset( $portfolio_setting['button_font_size'] ) && $portfolio_setting['button_font_size'] != '' ) ? esc_attr( $portfolio_setting['button_font_size'] ) : 0; ?>"></div>
										</div>
										<div class="pull-right">
											<div class="slide_val input-number-cover">
												<input id="portfolio_button_font_size" name="" type="number" class="numberOnly range-slider__value" value="<?php echo ( isset( $portfolio_setting['button_font_size'] ) && $portfolio_setting['button_font_size'] != '' ) ? esc_attr( $portfolio_setting['button_font_size'] ) : 0; ?>" min="0"/>
											</div>
											<div class="select-cover font-size-cover">
                                                <select id="portfolio_button_font_unit" name="portfolio_button_font_unit">
                                                    <option value="px">px</option>
                                                </select>
                                            </div>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td class='pro-feature'>
									<label for="portfolio_button_type"><?php _e( 'Button Type', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td class='pro-feature'>
									<?php
									$button_type = 'rectangle';
									if ( isset( $portfolio_setting['button_type'] ) ) {
										// $button_type = $portfolio_setting['button_type'];
									}
									?>
									<fieldset class="pdl-social-options portfolio_button_type buttonset buttonset-hide" data-hide='1'>
										<input id="portfolio_button_type_0" name="portfolio_button_type" type="radio" value="rectangle" <?php checked( 'rectangle', $button_type ); ?>/>
										<label for="portfolio_button_type_0" <?php checked( 0, $button_type ); ?>><?php _e( 'Rectangle', 'portfolio-designer-lite' ); ?></label>
										<input id="portfolio_button_type_1" name="portfolio_button_type" type="radio" value="oval" <?php checked( 'oval', $button_type ); ?> />
										<label for="portfolio_button_type_1" <?php checked( 1, $button_type ); ?>><?php _e( 'Oval', 'portfolio-designer-lite' ); ?></label>
									</fieldset>
								</td>
							</tr>
							<tr>
								<td class='pro-feature'>
									<label for="portfolio_button_radius"><?php _e( 'Button Radius', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td class='pro-feature'>
									<div class="font_size_cover">
										<div class="pull-left">
											<div class="grid_col_space range_slider_fontsize" id="portfolio_button_radius_slider" data-value="<?php echo ( isset( $portfolio_setting['button_radius'] ) && $portfolio_setting['button_radius'] != '' ) ? esc_attr( $portfolio_setting['button_radius'] ) : 40; ?>"></div>
										</div>
										<div class="pull-right">
											<div class="slide_val input-number-cover">
												<input id="portfolio_button_radius" name="" type="number" class="numberOnly range-slider__value" value="<?php echo ( isset( $portfolio_setting['button_radius'] ) && $portfolio_setting['button_radius'] != '' ) ? esc_attr( $portfolio_setting['button_radius'] ) : 40; ?>" min="0"/>
											</div>
											<div class="select-cover font-size-cover">
                                                <select id="portfolio_button_radius_unit" name="portfolio_button_radius_unit">
                                                    <option value="px">px</option>
                                                    <option value="%">%</option>
                                                </select>
                                            </div>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<label for="portfolio_button_font_color"><?php _e( 'Button Font Color', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td>
									<input id="portfolio_button_font_color" name="portfolio_button_font_color" type="text" data-alpha="true" class="portfolio-color-picker color-picker" value="<?php echo ( isset( $portfolio_setting['button_font_color'] ) && $portfolio_setting['button_font_color'] != '' ) ? esc_attr( $portfolio_setting['button_font_color'] ) : ''; ?>" />
									<p><?php _e( 'This color used for example button icon color.', 'portfolio-designer-lite' ); ?></p>
								</td>
							</tr>
							<tr>
								<td class="pro-feature">
									<label for="portfolio_button_background_color"><?php _e( 'Button Background Color', 'portfolio-designer-lite' ); ?></label>
								</td>
								<td class="pro-feature">
									<input id="portfolio_button_background_color" name="" type="text" data-alpha="true" class="portfolio-color-picker color-picker" value="<?php echo ( isset( $portfolio_setting['button_background_color'] ) && $portfolio_setting['button_background_color'] != '' ) ? esc_attr( $portfolio_setting['button_background_color'] ) : '#000000'; ?>" />
									<p><?php _e( 'This color used for example button icon background color.', 'portfolio-designer-lite' ); ?></p>
								</td>
							</tr>
						</table>						
					</div>
				</div>
			</div>
		</div>
		<input type="submit" class="hide"  name="addPortfolioDesigner" id="addPortfolioDesigner" />

	</form>
	<div id="pdl-advertisement-popup" style="display:none">
		<div class="portfolio-advertisement-cover">
			<a class="portfolio-advertisement-link" target="_blank" href="<?php echo esc_url( 'https://www.solwininfotech.com/product/wordpress-plugins/portfolio-designer/' ); ?>">
				<img src="<?php echo PORT_LITE_PLUGIN_URL . '/images/portfolio_advertisement_popup.png'; ?>" />
			</a>
		</div>
	</div>
</div>
