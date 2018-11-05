<?php $mediaUrl = wmlp_layout_url(__FILE__); // Donot remove this ?>
<style>
.wmle_container .wmle_item{margin:<?php echo $layoutSettings['image_gap']; ?>;}
.wmle_container .wmle_item .wpme_image{position:relative;}
.wmle_container .wmle_item .wpme_image a{display:block; padding:0px;}
.wmle_container .wmle_item .wpme_image img{border-radius:0px !important; box-shadow:none !important; display:block; margin:auto;}
.wmle_container .wmle_item .wmle_woocommerce_box{background:<?php echo $layoutSettings['box_background_color']; ?>; padding:5px 10px;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_header{ border-bottom:<?php echo implode(' ',$layoutSettings['title_border']); ?>; padding-bottom:5px; margin-bottom:10px;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_title{ color:<?php echo $layoutSettings['title_font_color']; ?>; font-size:<?php echo $layoutSettings['title_font_size']; ?>; text-align:center; font-weight:bold; line-height:1.4; padding:5px 0px 5px 0px;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_title a{ color:inherit; text-decoration:none;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_price{ text-align:center; padding:5px 0px 5px 0px; color:<?php echo $layoutSettings['price_font_color']; ?>; font-size:<?php echo $layoutSettings['price_font_size']; ?>;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_price ins{ background:none;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_price del{color:<?php echo $layoutSettings['old_price_color']; ?>;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_short_description{ font-size:<?php echo $layoutSettings['description_font_size']; ?>; color:<?php echo $layoutSettings['description_font_color']; ?>; padding-bottom:15px;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_add_to_cart p.product.woocommerce{ margin:0px; padding-bottom:10px;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_add_to_cart p.product.woocommerce ins,.wmle_container .wmle_item .wmle_woocommerce_box .wlme_add_to_cart p.product.woocommerce ins,.wmle_container .wmle_item .wmle_woocommerce_box .wlme_add_to_cart p.product.woocommerce span.amount{ display:none !important; height:0px !important; width:0px !important;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_add_to_cart a.button{-webkit-border-radius: <?php echo implode(' ',$layoutSettings['add_to_cart_round_corner']); ?>;border-radius: <?php echo implode(' ',$layoutSettings['add_to_cart_round_corner']); ?>;color: <?php echo $layoutSettings['add_to_cart_font_color']; ?>;font-size: <?php echo $layoutSettings['add_to_cart_font_size']; ?>;background: <?php echo $layoutSettings['add_to_cart_bg']; ?>;padding: 8px 20px 8px 20px;text-decoration: none; border:none; text-shadow:none;outline: 0; font-weight:normal;}   
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_add_to_cart a.button:hover{ color:<?php echo $layoutSettings['add_to_cart_font_hover_color']; ?>; background:<?php echo $layoutSettings['add_to_cart_bg_hover']; ?>;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_add_to_cart a.added:before{content:'';}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_add_to_cart a.loading:before{-webkit-border-radius: <?php echo implode(' ',$layoutSettings['add_to_cart_round_corner']); ?>;border-radius: <?php echo implode(' ',$layoutSettings['add_to_cart_round_corner']); ?>;}
.wmle_container .wmle_item .wmle_woocommerce_box .wlme_add_to_cart .added_to_cart{ color:<?php echo $layoutSettings['added_to_cart_font_color']; ?>; text-decoration:none; font-size:<?php echo $layoutSettings['added_to_cart_font_size']; ?>;}
.wmle_loadmore .wmle_loadmore_btn{ display:inline-block; padding:7px 30px;border:2px solid #454545; margin:5px;color:#454545; text-decoration:none;text-transform:uppercase;}
</style>