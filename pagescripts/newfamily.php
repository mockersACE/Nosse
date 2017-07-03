<?php
/** this line prevents direct access to the PHP file **/
defined( 'ABSPATH' ) or die( 'No direct access' );


//** setup form drop down **//
add_filter("gform_pre_render", populate_dropdown); //1 is the GF Form ID
add_filter("gform_admin_pre_render", populate_dropdown);

function populate_dropdown($form){
  global $encryption_key;
  global $wpdb;

  $commissioners = $wpdb->get_results($wpdb->prepare("SELECT AES_DECRYPT(body , %s) AS 'commissioner' FROM nd_COMMISSIONERS WHERE status = 'Active'", $encryption_key));

  $commissionerslist = array();
  foreach ($commissioners as $commissioners) {
    $commissionerslist[] = array("text" => $commissioners->commissioner, "value" => $commissioners->commissioner);
  }

  foreach($form["fields"] as &$field){
    if($field["id"] == 6){
      $field["choices"] = $commissionerslist;
  }
  }

  $users = array();
	$metas = get_users();
  if (is_array($metas)){
    foreach($metas as $meta)  $users[] = array("value" => $meta->display_name, "text" => $meta->display_name);
  }

  $users[] = array("text" => "Select later", "value" => "Unselected");

	foreach($form["fields"] as &$field)
		if($field["id"] == 7){
			$field["choices"] = $users;
	}

return $form;
}

add_action("gform_after_submission", "push_fields", 10, 2);
function push_fields($entry, $form){
#reads and stores as variables all the fields from the entry form
	$surname = $entry["1"];
	$phone = $entry["4"];
  $addr_street = $entry["2.1"];
  $addr_street2 = $entry["2.2"];
  $addr_city = $entry["2.3"];
  $addr_postcode = $entry["2.5"];
  $commissioner = $entry["6"];
  $commissiondetail = $entry["10"];
  $keyworker = $entry["7"];
  $flags_da = $entry["9.1"];
  $falgs_di = $entry["9.2"];
  $flags_cp = $entry["9.3"];
  $flags_pets = $entry["9.4"];
  $date = date("Y-m-d");


	#setup wpdb
	global $wpdb;

	#get user IDs - needed for wpdatatables and logging
  $current_user = wp_get_current_user();
  $display_name = $current_user->display_name;
  $userid = $current_user->ID;


  #encrypt all data for families and insert
  global $encryption_key;
  $wpdb->query($wpdb->prepare("INSERT INTO nd_FAMILIES (surname, phone, addr_street, addr_street2, addr_city, addr_postcode, date_added, addedby_name, addedby_ID) VALUES (AES_ENCRYPT(%s,%s), AES_ENCRYPT(%s,%s), AES_ENCRYPT(%s,%s), AES_ENCRYPT(%s,%s), AES_ENCRYPT(%s,%s), AES_ENCRYPT(%s,%s), %s, AES_ENCRYPT(%s,%s), AES_ENCRYPT(%s,%s))", $surname, $encryption_key, $phone, $encryption_key, $addr_street, $encryption_key, $addr_street2, $encryption_key, $addr_city, $encryption_key, $addr_postcode, $encryption_key, $date, $display_name, $encryption_key, $userid, $encryption_key));

  #update profile
  $fam_ID = $wpdb->get_var("SELECT fam_ID FROM nd_FAMILIES ORDER BY fam_ID DESC LIMIT 0,1");
  $url = get_site_url();
  $searchurl = $url . "/profile/?=" . $fam_ID . " || " . $surname;
  $wpdb->query($wpdb->prepare("UPDATE nd_FAMILIES SET search=AES_ENCRYPT(%s,%s) WHERE fam_ID= %s", $searchurl, $encryption_key, $fam_ID));


  #$comm_ID = $$wpdb->get_var($wpdb->prepare("SELECT comm_ID FROM nd_COMMISSIONERS WHERE body = %s", $commissioner)

  #enter commision detail
  #$wpdb->insert('nd_PACKAGESCOMM',
  #    array(
  #      'fam_ID'
  #    )








}


 ?>
