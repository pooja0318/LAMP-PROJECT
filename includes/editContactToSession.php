<?php
function editContactToSession($db_conn, $ct_id){
	$qry = "select * from contact";
	$qry .= " left join contact_address on ct_id = ad_ct_id";
	$qry .= " left join contact_email on ct_id = em_ct_id";
	$qry .= " left join contact_phone on ct_id = ph_ct_id";
	$qry .= " left join contact_note on ct_id = no_ct_id";
	$qry .= " left join contact_web on ct_id = we_ct_id";
	
	$qry .= " where ct_id = ?";
	$stmt = $db_conn->prepare($qry);
	if (!$stmt){
		echo "<p>Error in display prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
		exit(1);
	}
	$status = $stmt->execute([$ct_id]);
	if ($status){
		if ($stmt->rowCount() > 0){
			$row = $stmt->fetch();
			$_SESSION['ct_type'] = $row['ct_type'];
			$_SESSION['ct_first_name'] = $row['ct_first_name'];
			$_SESSION['ct_last_name'] = $row['ct_last_name'];
			$_SESSION['ct_disp_name'] = $row['ct_disp_name'];
			$_SESSION['ad_type'] = $row['ad_type'];
			$_SESSION['ad_line_1'] = $row['ad_line_1'];
			$_SESSION['ad_line_2'] = $row['ad_line_2'];
			$_SESSION['ad_line_3'] = $row['ad_line_3'];
			$_SESSION['ad_city'] = $row['ad_city'];
			$_SESSION['ad_province'] = $row['ad_province'];
			$_SESSION['ad_post_code'] = $row['ad_post_code'];
			$_SESSION['ad_country'] = $row['ad_country'];
			$_SESSION['ph_type'] = $row['ph_type'];
			$_SESSION['ph_number'] = $row['ph_number'];
			$_SESSION['em_type'] = $row['em_type'];
			$_SESSION['em_email'] = $row['em_email'];
			$_SESSION['we_type'] = $row['we_type'];
			$_SESSION['we_url'] = $row['we_url'];
			$_SESSION['no_note'] = $row['no_note'];
			$_SESSION['ct_edit_id'] = $row['ct_id'];
		}
	}			
}
?>
