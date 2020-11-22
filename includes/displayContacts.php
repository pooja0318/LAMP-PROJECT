<?php 

function displayContacts($db_conn){

	$qry = "select ct_id, ct_disp_name, ad_city from contact left join contact_address on ct_id = ad_ct_id";
	$identifier_array = array();
	$qry .=" where ct_deleted = :ct_softDeleted";
	$identifier_array[':ct_softDeleted'] = "N";
	if (isSet($_SESSION['ct_filter'])){ 
		if((strlen($_SESSION['ct_filter']) > 0)){
			$qry .= " and ct_disp_name like :ct_filter";
			$identifier_array[':ct_filter'] = '%'.$_SESSION['ct_filter']."%";
		}
	}	
	$qry .= " order by ct_disp_name;";
	$stmt = $db_conn->prepare($qry);
	if (!$stmt){
		echo "<p>Error in display prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
		exit(1);
	}
	$status = $stmt->execute($identifier_array);
	if ($status){
		if ($stmt->rowCount() > 0){
?>
			<table border="1">
			<tr><th>Select</th><th>Name</th><th>Location</th></tr>
<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
			<tr>
			<td><input type="radio" name="list_select[]" value="<?php echo $row['ct_id']; ?>"></td>
			<td><?php echo $row['ct_disp_name']; ?></td>
			<td><?php echo $row['ad_city']; ?></td>
			</tr>
<?php } ?>
			</table>
<?php
		} else {
			echo "<div>\n";
			echo "<p>No contacts to display</p>\n";
			echo "</div>\n";
		}
	} else {
		echo "<p>Error in display execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
		exit(1);
	}
}
?>

