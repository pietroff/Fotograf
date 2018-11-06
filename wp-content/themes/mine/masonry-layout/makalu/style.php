<?php $mediaUrl = wmlp_layout_url(__FILE__); // Donot remove this ?>
<style>
.wmle_container .wmle_item{ background:<?php echo $layoutSettings['box_background_color']; ?>; box-shadow:0px 0px 0px 0px ;-webkit-border-radius: 0px 0px 0px 0px; border-radius: 0px 0px 0px 0px; padding:10px;margin:<?php echo $layoutSettings['box_gap']; ?>;}
.wmle_container .wmle_item .wpme_image{ position:relative; padding-bottom:10px;}
.wmle_container .wmle_item .wpme_image img{ border-radius:0px;}
.wmle_container .wmle_item .wmle_masonary_box{ background:<?php echo $layoutSettings['inner_box_background']; ?>; border:<?php echo implode(' ',$layoutSettings['inner_box_border']); ?>; box-shadow:0px 0px 0px 0px ;-webkit-border-radius: 0px 0px 0px 0px; border-radius: 0px 0px 0px 0px;padding:10px;}
.wmle_container .wmle_item .wmle_masonary_box .wmle_post_title{ text-align:center; font-size:<?php echo $layoutSettings['title_font_size']; ?>; color:<?php echo $layoutSettings['title_font_color']; ?>; font-weight:bold; line-height:1.4; border-bottom:<?php echo implode(' ',$layoutSettings['title_border_separator']); ?>; padding:0px 0px 10px 0px;}
.wmle_container .wmle_item .wmle_post_title a{ color:inherit; text-decoration:none; }
.wmle_container .wmle_item .wmle_masonary_box .wmle_post_excerpt{ font-size:<?php echo $layoutSettings['excerpt_font_size']; ?>; color:<?php echo $layoutSettings['excerpt_font_color']; ?>; padding:10px 0px 0px; line-height:1.4;}
.wmle_container .wmle_item .wmle_masonary_box .wmle_post_excerpt p{ margin:0px; padding:0px;}
.wmle_container .wmle_item .wmle_post_meta{  padding:8px 0px 5px 0px; border-bottom:<?php echo implode(' ',$layoutSettings['post_meta_border_separator']); ?>; margin:0px 0px 7px 0px;}
.wmle_container .wmle_item .wmle_post_meta .wmle_author_name{ font-size:<?php echo $layoutSettings['author_font_size']; ?>; color:<?php echo $layoutSettings['author_font_color']; ?>; line-height:1.3; float:left;}
.wmle_container .wmle_item .wmle_post_meta .wmle_author_name a{ color:inherit; text-decoration:none;}
.wmle_container .wmle_item .wmle_post_meta .wmle_author_name span{ font-style:italic; font-weight:bold; color:inherit; }
.wmle_container .wmle_item .wmle_post_meta .wmle_date{ font-size:<?php echo $layoutSettings['date_font_size']; ?>; color:<?php echo $layoutSettings['date_font_color']; ?>; line-height:1.3; float:right;}
.wmle_container .wmle_item .wmle_social_share{ display:block; float:left;}
.wmle_container .wmle_item .wmle_social_share a{display:inline-block; width:13px; height:12px; overflow:hidden; text-indent:-200px; background-repeat:no-repeat; background-position:center; margin-right:6px;border:none; background-color:inherit;-webkit-border-radius: 0px;border-radius: 0px;}
.wmle_container .wmle_item .wmle_social_share a.fb{ background-image:url(<?php echo $mediaUrl ?>/facebook.png);}
.wmle_container .wmle_item .wmle_social_share a.tw{ background-image:url(<?php echo $mediaUrl ?>/twitter.png);}
.wmle_container .wmle_item .wmle_social_share a.gp{ background-image:url(<?php echo $mediaUrl ?>/gplus.png);}
.wmle_container .wmle_item .wmle_social_share a.pi{ background-image:url(<?php echo $mediaUrl ?>/pinterest.png);}
.wmle_container .wmle_item .wmle_social_share a.in{ background-image:url(<?php echo $mediaUrl ?>/linkedin.png);}
.wmle_container .wmle_item .wmle_post_category{ font-size:<?php echo $layoutSettings['category_font_size']; ?>; color:<?php echo $layoutSettings['category_font_color']; ?>; line-height:1.5; display:block; float:right;}
.wmle_container .wmle_item .wmle_post_category a{ text-decoration:none; color:inherit;}
.wmle_loadmore .wmle_loadmore_btn{ display:inline-block; padding:7px 30px;border:2px solid #454545; margin:5px;color:#454545; text-decoration:none;text-transform:uppercase;}
</style>