<?php $mediaUrl = wmlp_layout_url(__FILE__); // Donot remove this ?>
<style>
.wmle_container .wmle_item{box-shadow:<?php echo implode(' ',$layoutSettings['box_shadow']); ?>;-webkit-border-radius: <?php echo implode(' ',$layoutSettings['border_radius']); ?>;border-radius: <?php echo implode(' ',$layoutSettings['border_radius']); ?>; margin:8px;}
.wmle_container .wmle_item .wpme_image{ position:relative;}
.wmle_container .wmle_item .wpme_image a{ display:block; padding:0px;}
.wmle_container .wmle_item .wpme_image img{box-shadow:none !important; display:block; margin:auto;
-webkit-border-radius: <?php echo $layoutSettings['border_radius'][0]; ?> <?php echo $layoutSettings['border_radius'][1]; ?> 0 0;
border-radius: <?php echo $layoutSettings['border_radius'][0]; ?> <?php echo $layoutSettings['border_radius'][1]; ?> 0 0;}
.wmle_container .wmle_item .wmle_post_title{ border-top:<?php echo implode(' ',$layoutSettings['title_border']); ?>; padding:10px; font-size:<?php echo $layoutSettings['title_font_size']; ?>; color:<?php echo $layoutSettings['title_font_color']; ?>; line-height:1.4;}
.wmle_container .wmle_item .wmle_post_title a{ color:inherit; text-decoration:none; display:block;}
.wmle_container .wmle_item .wmle_post_title .commentLink{ font-size:<?php echo $layoutSettings['comment_font_size']; ?>; color:<?php echo $layoutSettings['comment_font_color']; ?>; padding-top:5px;}
.wmle_container .wmle_item .wmle_social_share{padding:10px; position:absolute;top:0px; display:none;}
.wmle_container .wmle_item:hover .wmle_social_share{ display:block;}
.wmle_container .wmle_item .wmle_social_share a{background: -webkit-linear-gradient(#fff, #f0f0f0);background: -o-linear-gradient(#fff, #f0f0f0);background: -moz-linear-gradient(#fff, #f0f0f0);background: linear-gradient(#fff, #f0f0f0);display:inline-block; width:20px; height:20px; overflow:hidden; text-indent:-200px; background-repeat:no-repeat; background-position:center; margin-right:2px;border:1px solid #999999; background-color:#f3f3f3; padding:3px;-webkit-border-radius: 4px;border-radius: 4px;}
.wmle_container .wmle_item .wmle_social_share a.fb{background-image:url(<?php echo $mediaUrl ?>/facebook.png);}
.wmle_container .wmle_item .wmle_social_share a.tw{background-image:url(<?php echo $mediaUrl ?>/twitter.png);}
.wmle_container .wmle_item .wmle_social_share a.in{background-image:url(<?php echo $mediaUrl ?>/linkedin.png);}
.wmle_container .wmle_item .wmle_social_share a.pi{background-image:url(<?php echo $mediaUrl ?>/pinterest.png);}
.wmle_container .wmle_item .wmle_social_share a.gp{background-image:url(<?php echo $mediaUrl ?>/gplus.png);}
.wmle_container .wmle_item .wmle_post_meta{border-top:<?php echo implode(' ',$layoutSettings['author_top_border']); ?>; padding:10px;line-height:1.4;}
.wmle_container .wmle_item:hover .wmle_post_meta{ background:<?php echo $layoutSettings['author_hover_bg']; ?>;}
.wmle_container .wmle_item .wmle_avatar img{-webkit-border-radius: 100px;border-radius: 100px;-webkit-box-shadow: 0 0 0 0 #FFFFFF;box-shadow: 0 0 0 0 #FFFFFF;}
.wmle_container .wmle_item .wmle_post_meta a{ text-decoration:none; color:inherit;}
.wmle_container .wmle_item .wmle_avatar{ float:left; width:45px;}
.wmle_container .wmle_item .wmle_meta_content{ float:left;}
.wmle_container .wmle_item .wmle_meta_content .wmle_author_name{ color:<?php echo $layoutSettings['author_name_font_color']; ?>; font-weight:bold; text-transform:capitalize; font-size:<?php echo $layoutSettings['author_name_font_size']; ?>;}
.wmle_container .wmle_item .wmle_meta_content .wmpe_categories{ color:<?php echo $layoutSettings['categories_font_color']; ?>; font-size:<?php echo $layoutSettings['categories_font_size']; ?>; text-transform:uppercase; font-size:12px;}
.wmle_loadmore .wmle_loadmore_btn{ display:inline-block; padding:7px 30px;border:2px solid #454545; margin:5px;color:#454545; text-decoration:none;text-transform:uppercase;}
</style>