<?php
/** this line prevents direct access to the PHP file **/
defined( 'ABSPATH' ) or die( 'No direct access' );

add_action("gform_after_submission", "push_fields", 10, 2);
function push_fields($entry, $form){
#reads and stores as variables all the fields from the entry form
  $body = $entry["1"];
  $type = $entry["2"];
  $primary = $entry["6"];
  $addr_street = $entry["4.1"];
  $addr_street2 = $entry["4.2"];
  $addr_city = $entry["4.3"];
  $addr_postcode = $entry["4.5"];
  $email = $entry["8"];
  $phone = $entry["7"];

  $status = "Active";

  $date = date("Y-m-d");

	#setup wpdb
	global $wpdb;

	#get user IDs - needed for wpdatatables and logging
  $current_user = wp_get_current_user();
  $display_name = $current_user->display_name;
  $userid = $current_user->ID;

	#get user IDs - needed for wpdatatables and logging
  $current_user = wp_get_current_user();
  $display_name = $current_user->display_name;
  $userid = $current_user->ID;

  #encrypt data for commissioner and insert
  global $encryption_key;
  $wpdb->query($wpdb->prepare("INSERT INTO nd_COMMISSIONERS (type, body, date_added, primary_con, addr_street, addr_street2, addr_city, addr_postcode, email, phone, addedby_name, addedby_ID, status) VALUES (AES_ENCRYPT(%s,%s), AES_ENCRYPT(%s,%s), %s, AES_ENCRYPT(%s,%s),  AES_ENCRYPT(%s,%s),  AES_ENCRYPT(%s,%s), AES_ENCRYPT(%s,%s), AES_ENCRYPT(%s,%s), AES_ENCRYPT(%s,%s), AES_ENCRYPT(%s,%s), %s, %s, %s)", $type, $encryption_key, $body, $encryption_key, $date, $primary, $encryption_key, $addr_street, $encryption_key, $addr_street2, $encryption_key, $addr_city, $encryption_key, $addr_postcode, $encryption_key, $email, $encryption_key, $phone, $encryption_key, $display_name, $userid, $status));
}

 ?>
