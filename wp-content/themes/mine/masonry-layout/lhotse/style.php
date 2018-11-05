<?php $mediaUrl = wmlp_layout_url(__FILE__); // Donot remove this ?>
<style>
.wmle_container .wmle_item{margin:<?php echo $layoutSettings['image_gap']; ?>;}
.wmle_container .wmle_item .wpme_image{ position:relative;}
.wmle_container .wmle_item .wpme_image a{ display:block; padding:0px;}
.wmle_container .wmle_item .wpme_image img{border-radius:0px !important; box-shadow:none !important; display:block; margin:auto;}
.wmle_container .wmle_item:hover .wpme_image img{opacity: <?php echo $layoutSettings['image_opacity_onhover']; ?>;filter: alpha(opacity=<?php echo $layoutSettings['image_opacity_onhover'] * 100; ?>);}
.wmle_container .wmle_item .wmle_post_title{font-size:<?php echo $layoutSettings['title_font_size']; ?>;color:<?php echo $layoutSettings['title_font_color']; ?>; line-height:1.3; padding:10px 10px 10px 10px; font-weight:bold; display:none; top:0px; position:absolute;-webkit-transition: all 500ms ease;-moz-transition: all 500ms ease;-ms-transition: all 500ms ease;-o-transition: all 500ms ease;transition: all 500ms ease;text-shadow: <?php echo implode(' ',$layoutSettings['title_text_shadow']); ?>;}
.wmle_container .wmle_item:hover .wmle_post_title{ display:block;}
.wmle_container .wmle_item .wmle_post_title a{ color:inherit; text-decoration:none;}
.wmle_container .wmle_item .wmle_social_share{padding:10px; position:absolute;bottom:0px; display:none;-webkit-transition: all 500ms ease;-moz-transition: all 500ms ease;-ms-transition: all 500ms ease;-o-transition: all 500ms ease;transition: all 500ms ease;}
.wmle_container .wmle_item:hover .wmle_social_share{ display:block;}
.wmle_container .wmle_item .wmle_social_share a{background: -webkit-linear-gradient(#fff, #f0f0f0);background: -o-linear-gradient(#fff, #f0f0f0);background: -moz-linear-gradient(#fff, #f0f0f0);background: linear-gradient(#fff, #f0f0f0);display:inline-block; width:20px; height:20px; overflow:hidden; text-indent:-200px; background-repeat:no-repeat; background-position:center; margin-right:2px;border:1px solid #999999; background-color:#f3f3f3; padding:3px;-webkit-border-radius: 4px;border-radius: 4px;}
.wmle_container .wmle_item .wmle_social_share a.fb{background-image:url(<?php echo $mediaUrl ?>/facebook.png);}
.wmle_container .wmle_item .wmle_social_share a.tw{background-image:url(<?php echo $mediaUrl ?>/twitter.png);}
.wmle_container .wmle_item .wmle_social_share a.in{background-image:url(<?php echo $mediaUrl ?>/linkedin.png);}
.wmle_container .wmle_item .wmle_social_share a.pi{background-image:url(<?php echo $mediaUrl ?>/pinterest.png);}
.wmle_container .wmle_item .wmle_social_share a.gp{background-image:url(<?php echo $mediaUrl ?>/gplus.png);}
.wmle_loadmore .wmle_loadmore_btn{ display:inline-block; padding:7px 30px;border:2px solid #454545; margin:5px;color:#454545; text-decoration:none;text-transform:uppercase;}
</style>