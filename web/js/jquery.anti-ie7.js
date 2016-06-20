/*
 * 
 Anti IE7 */

function anti_ie7(){	
	if(jQuery.browser.msie && jQuery.browser.version < 9){
		var container = jQuery('<div id="anti-ie7"></div>');
		container.animate({opacity: "0.7"}, "fast").hide();
		jQuery('body').append(container);		
		jQuery('#anti-ie7').html('<p class="titreAntiIe">Vous utilisez une ancienne version de Internet Explorer.</p><p>Pour des raisons de s&eacute;curit&eacute; et afin d&acute;optimiser l&acute;utilisation de ce site, merci de mettre à jour votre navigateur.</p><a href="http://windows.microsoft.com/fr-fr/internet-explorer/download-ie" class="btn-ie" target="_blank">T&eacute;l&eacute;charger IE11</a><a class="close" href="javascript:void(0)" onclick="javascript:jQuery(\'#anti-ie7\').slideUp(\'fast\',function(){ jQuery(\'#anti-ie7\').hide(); });">fermer</a>');
		container.slideDown('slow');
	}
}