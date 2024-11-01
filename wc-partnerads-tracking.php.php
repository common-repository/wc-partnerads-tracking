<?php
/**
 * Plugin Name: Woocommerce PartnerAds Tracking Code
 * Plugin URI: http://kimvinberg.dk/
 * Description: Tilføjer PartnerAds Tracking kode til din WooCommerce Thank you side
 * Version: 1.0.3
 * Author: Kim Vinberg
 * Author URI: http://kimvinberg.dk/
 */


 add_action( 'admin_menu', 'KV_WPAT_add_admin_menu' );
 add_action( 'admin_init', 'KV_WPAT_settings_init' );


 function KV_WPAT_add_admin_menu(  ) {

 	add_options_page( 'KV_WoocommercePartnerAdsTracking', 'PartnerAds Tracking', 'manage_options', 'kv_woocommercepartneradstracking', 'KV_WPAT_options_page' );

 }


 function KV_WPAT_settings_init(  ) {

 	register_setting( 'pluginPage', 'KV_WPAT_settings' );

 	add_settings_section(
 		'KV_WPAT_pluginPage_section',
 		__( 'PartnerAds Tracking kode til WooCommerce', 'KV_WPAT' ),
 		'KV_WPAT_settings_section_callback',
 		'pluginPage'
 	);

 	add_settings_field(
 		'KV_WPAT_text_field_0',
 		__( 'Indtast dit program ID', 'KV_WPAT' ),
 		'KV_WPAT_text_field_0_render',
 		'pluginPage',
 		'KV_WPAT_pluginPage_section'
 	);


 }


 function KV_WPAT_text_field_0_render(  ) {

 	$options = get_option( 'KV_WPAT_settings' );
 	?>
 	<input type='text' name='KV_WPAT_settings[KV_WPAT_text_field_0]' value='<?php echo $options['KV_WPAT_text_field_0']; ?>'>
 	<?php

 }


 function KV_WPAT_settings_section_callback(  ) {

 //	echo __( 'This section description', 'KV_WPAT' );

 }


 function KV_WPAT_options_page(  ) {

 	?>
 	<form action='options.php' method='post'>

 		<h2>Indstillinger for plugin</h2>
		<p>Har du brug for hjælp kan du altid kontakte mig på mail@kimvinberg.dk</p>
 		<?php
 		settings_fields( 'pluginPage' );
 		do_settings_sections( 'pluginPage' );
 		submit_button();
 		?>

 	</form>
 	<?php

 }

// Lead tracking code - thankyou.php
function WoocommercePartnerAdsTrack( $order_id ) {

		$order = new WC_Order( $order_id );
		$ordertotal = $order->get_total();
		$shipping = ($order->get_total_shipping())*1.25;
		$total = $ordertotal - $shipping;
		$ordernumber = $order->get_order_number();
		$campaignID = get_option('KV_WPAT_settings');

		echo "<img src=\"https://www.partner-ads.com/dk/leadtrack.php?programid=".$campaignID[KV_WPAT_text_field_0]."&type=salg&ordrenummer=".substr($ordernumber, 1)."&varenummer=x&antal=1&omprsalg=".$total."\" width=\"1\" height=\"1\">";
		echo "<!-- dicm.dk woocommerce partner ads tracking plugin -->";

}

add_action("woocommerce_thankyou", "WoocommercePartnerAdsTrack");
