<?php $mediaUrl = wmlp_layout_url(__FILE__); // Donot remove this ?>
<style>
.wmle_container .wmle_item{box-shadow:<?php echo implode(' ',$layoutSettings['box_shadow']); ?>; margin:10px;-webkit-border-radius: <?php echo implode(' ',$layoutSettings['box_border']); ?>; border-radius: <?php echo implode(' ',$layoutSettings['border_radius']); ?>;}
.wmle_container .wmle_item .wpme_image a{ display:block; padding:0px;}
.wmle_container .wmle_item .wpme_image img{box-shadow:none !important; display:block; margin:auto;-webkit-border-radius: <?php echo $layoutSettings['border_radius'][0]; ?> <?php echo $layoutSettings['border_radius'][1]; ?> 0 0;
border-radius: <?php echo $layoutSettings['border_radius'][0]; ?> <?php echo $layoutSettings['border_radius'][1]; ?> 0 0;}
.wmle_container .wmle_item .wmle_post_title{ font-size:<?php echo $layoutSettings['title_font_size']; ?>;color:<?php echo $layoutSettings['title_font_color']; ?>; line-height:1.3; padding:10px 10px 10px 10px; font-weight:bold; background:<?php echo $layoutSettings['title_bg_color']; ?>;}
.wmle_container .wmle_item:hover .wmle_post_title{ background:<?php echo $layoutSettings['title_bg_hover_color']; ?>; color:<?php echo $layoutSettings['title_font_hover_color']; ?>;}
.wmle_container .wmle_item .wmle_post_title a{ color:inherit; text-decoration:none;}
.wmle_container .wmle_item .wmle_post_meta{background:<?php echo $layoutSettings['social_bg_color']; ?>; padding:7px 10px 7px 10px; font-size:12px; border-top:<?php echo implode(' ',$layoutSettings['social_bar_separator']); ?>;color:<?php echo $layoutSettings['social_font_color']; ?>;}
.wmle_container .wmle_item .wmle_post_meta a{ text-decoration:none; color:inherit;}
.wmle_container .wmle_item .wmle_social_share{display:block; float:left; height:20px;}
.wmle_container .wmle_item .wmle_comment_count{display:block; float:right;}
.wmle_container .wmle_item .wmle_social_share a{display:inline-block; width:20px; height:20px; overflow:hidden; text-indent:-200px; background-repeat:no-repeat; background-position:center;}
.wmle_container .wmle_item .wmle_social_share a.fb{background-image:url(<?php echo $mediaUrl ?>/facebook.png);}
.wmle_container .wmle_item .wmle_social_share a.tw{background-image:url(<?php echo $mediaUrl ?>/twitter.png);}
.wmle_container .wmle_item .wmle_social_share a.in{background-image:url(<?php echo $mediaUrl ?>/linkedin.png);}
.wmle_container .wmle_item .wmle_social_share a.pi{background-image:url(<?php echo $mediaUrl ?>/pinterest.png);}
.wmle_container .wmle_item .wmle_social_share a.gp{background-image:url(<?php echo $mediaUrl ?>/gplus.png);}
.wmle_loadmore .wmle_loadmore_btn{ display:inline-block; padding:7px 30px;border:2px solid #454545; margin:5px;color:#454545; text-decoration:none;text-transform:uppercase;}
</style>