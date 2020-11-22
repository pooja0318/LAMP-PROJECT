<?php

function softDeleteContactConfirm($ct_id ){
?>
	<h3>Delete Contact</h3>
    
	<form method="POST" >
		<input type="hidden" name="ct_delete_id" value="<?php echo $ct_id; ?>">
	<table>
	<tr>
		<td colspan="2">Are you sure you want to delete this contact ?</td>		
	</tr>
	<tr>
		<td><input type="submit" name="ct_b_confirm" value="Confrim"></td>
		<td><input type="submit" name="ct_b_cancel" value="Cancel"></td>
	</tr>
	</table>
	</form>
<?php
}

function softDeleteContact($db_conn, $ct_id){
	$field_data = array();
	$qry_ct = "update contact set ct_deleted = ?";
	$field_data[] = "Y";
	$qry_ct .= " where ct_id = ?";
	$field_data[] = $ct_id;
	$stmt = $db_conn->prepare($qry_ct);
	if (!$stmt){
		echo "<p>Error in contact prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
		exit(1);
	}
	$status = $stmt->execute($field_data);
	if (!$status){
		echo "<p>Error in contact execute: ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
		exit(1);
	}
	unset($field_data);
}
?>
