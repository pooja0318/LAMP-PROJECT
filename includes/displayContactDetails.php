<?php 

function displayContactDetails($db_conn){
	$ct_id = $_SESSION['ct_detail_id'];
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
			$row = $stmt->fetch(); ?>
			
			<h3>Contact Details</h3>
			<form method="POST" >
			<table>
			<tr>
				<td>Contact Type</td>
				<td><?php echo $row['ct_type']; ?></td>
			</tr>
			<tr>
				<td>Display/Business Name</td>
				<td><?php echo $row['ct_disp_name']; ?></td>
			</tr>
			<tr>
				<td>First Name</td>
				<td><?php echo $row['ct_first_name']; ?></td>
			</tr>
			<tr>
				<td>Last Name</td>
				<td><?php echo $row['ct_last_name']?></td>
			</tr>
			<tr>
				<td><br></td>
				<td></td>
			</tr>
			<tr>
				<td>Address </td>
				<td><?php echo $row['ad_type']; ?>:<br>
					<?php echo $row['ad_line_1']; ?>, <?php echo $row['ad_line_2']; ?>
					<?php if(trim($row['ad_line_3'])!="") echo "<br>".$row['ad_line_3'];?>
					<?php echo $row['ad_city']; ?> <?php echo $row['ad_province']; ?> <?php echo $row['ad_post_code']; ?><br>
					<?php echo $row['ad_country']; ?>
				</td>
			</tr>
			<tr>
				<td><br></td>
				<td></td>
			</tr>
			<tr>
				<td>Phone Number</td>
				<td><?php echo $row['ph_type']; ?> : <?php echo $row['ph_number'];?></td>
			</tr>
			<tr>
				<td><br></td>
				<td></td>
			</tr>
			<tr>
				<td>Email </td>
				<td><?php echo $row['em_type']; ?> : <?php echo $row['em_email']; ?></td>
			</tr>
			<tr>
				<td><br></td>
				<td></td>
			</tr>
			<tr>
				<td>Web Site </td>
				<td><?php echo $row['we_type']; ?> : <?php echo $row['we_url']; ?></td>
			</tr>
			<tr>
				<td><br></td>
				<td></td>
			</tr>
			<tr>
				<td>Note</td>
				<td><?php echo $row['no_note']; ?></td>
			</tr>
			</table>
			<table>
			<tr>
			    <td><input type="submit" name="ct_b_back" value="Back"></td>
			    <td></td>
			</tr>
			</table>
			</form>
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


