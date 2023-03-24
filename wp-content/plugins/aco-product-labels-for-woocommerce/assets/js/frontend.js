jQuery(window).load(function () {
    //Detail Page Badge
    var badge = jQuery('.acoplw-hidden-wrap');
	var flag = false;
	if ( badge.length >= 1 ) { // Check for badges
		var badgeCont = badge.find('.acoplw-badge').clone();
		jQuery('.woocommerce-product-gallery, .woocommerce-product-gallery--with-images').each( function (index, cont) { 
			var position = jQuery(this);
            jQuery(this).css({'positon':'relative'});
            jQuery(badgeCont).prependTo(jQuery(position).parent());
            jQuery(position).appendTo(badgeCont);
			flag = true;
		});
		if (!flag) { 
            jQuery('.images').each( function (index, cont) {
                if ( !flag ) { 
                    var position = jQuery(this);
                    jQuery(this).css({'positon':'relative'});
                    jQuery(badgeCont).prependTo(jQuery(position).parent());
                    jQuery(position).appendTo(badgeCont);
                    flag = true;
                }
            });
        } else {
            badge.remove();
        }
	}

});  