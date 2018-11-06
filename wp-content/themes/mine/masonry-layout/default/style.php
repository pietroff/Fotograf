<style>
.wmle_container .wmle_item{border:<?php echo implode(' ',$layoutSettings['box_border']); ?>; margin:5px; padding:5px;-webkit-box-shadow: <?php echo implode(' ',$layoutSettings['box_shadow']); ?>;
box-shadow: <?php echo implode(' ',$layoutSettings['box_shadow']); ?>;}
.wmle_container .wmle_item .wpme_image{ text-align:center;}
.wmle_container .wmle_item .wpme_image img{border-radius:0px !important; box-shadow:none !important;}
.wmle_container .wmle_item .wmle_post_meta{color:<?php echo $layoutSettings['post_meta_font_color']; ?>; font-size:<?php echo $layoutSettings['post_meta_font_size']; ?>;line-height:1.5; padding-bottom:6px;}
.wmle_container .wmle_item .wmle_post_meta a{ color:inherit; text-decoration:none;}
.wmle_container .wmle_item .wmle_post_meta a:hover{ text-decoration:underline;}
.wmle_container .wmle_item .wmle_post_title{ font-size:<?php echo $layoutSettings['post_title_font_size']; ?>; color:<?php echo $layoutSettings['post_title_font_color']; ?>; line-height:1.5; padding-bottom:6px;border-bottom:1px solid #f1f1f1; border-top:1px solid #f1f1f1; padding-top:5px; padding-bottom:5px; font-weight:bold;}
.wmle_container .wmle_item .wmle_post_title a{ color:inherit; text-decoration:none;}
.wmle_container .wmle_item .wmle_post_excerpt{font-size:<?php echo $layoutSettings['post_excerpt_font_size']; ?>; color:<?php echo $layoutSettings['post_excerpt_font_color']; ?>; padding-top:10px; padding-bottom:10px;}
.wmle_container .wmle_item .wmle_post_excerpt p{ line-height:1.5;}
.wmle_container .wmle_item .wmle_post_excerpt p:last-child{ padding-bottom:0px; margin-bottom:0px;}
.wmle_loadmore .wmle_loadmore_btn{ display:inline-block; padding:7px 30px;border:2px solid #454545; margin:5px;color:#454545; text-decoration:none;text-transform:uppercase;}
</style>