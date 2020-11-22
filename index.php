<?php 
/*
		Name:    index.php 
		Purpose: to create a contact details form for users.The use of index.php file is, all forms within the site are to be presented to the user as being part of the index.php file, session part to connect all the pages and the coding of DELETE button.s
		Author:  GROUP_16 (Amin, Ashka, a_amin88020
				 Arora, Kudrat, k_arora74481
				 Barot, Dhruv Anil, d_barot
				 Patel, Pooja Manojbhai, p_patel87671
				 Patel, Meetkumar Nareshkumar, m_patel87661
				 Shah, Yuti Dixitbhai, y_shah94230)
*/
	session_start(); 
	if (!isset($_SESSION['mode'])){
		$_SESSION['mode'] = "Display";
	}
	require_once("./includes/db_connection.php"); 
	require_once("./includes/displayContacts.php"); 
	require_once("./includes/displayContactDetails.php"); 
	require_once("./includes/formContactType.php");
	require_once("./includes/formContactName.php");
	require_once("./includes/formContactAddress.php");
	require_once("./includes/formContactPhone.php");
	require_once("./includes/formContactEmail.php");
	require_once("./includes/formContactWeb.php");
	require_once("./includes/formContactNote.php");
	require_once("./includes/formContactSave.php");
	require_once("./includes/formContactEdit.php");
	require_once("./includes/clearAddContactFromSession.php");
	require_once("./includes/clearEditContactFromSession.php");
	require_once("./includes/editContactToSession.php");
	require_once("./includes/displayErrors.php");
	require_once("./includes/softDeleteContact.php");
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Contact List</title>
	</head>
	<body>
<?php
if ((isset($_POST['ct_b_filter']) && ($_POST['ct_b_filter'] == "Filter")) || (isset($_POST['ct_b_filter_clear']) && ($_POST['ct_b_filter_clear'] == "Clear Filter"))){
	$_SESSION['mode'] = "Display";
} else if (isset($_POST['ct_b_add']) && ($_POST['ct_b_add'] == "Add New Contact")){
	$_SESSION['mode'] = "Add";
	$_SESSION['add_part'] = 0;
} else if (isset($_POST['ct_b_edit']) && ($_POST['ct_b_edit'] == "Edit")){
	$_SESSION['mode'] = "Edit";
	$_SESSION['add_part'] = 0;
	if(isset($_POST['list_select'][0]) && $_POST['list_select'][0]!=""){
		$ct_id = $_POST['list_select'][0];
		$db_conn = connectDB();
		editContactToSession($db_conn,$ct_id);
	} else {
		echo "<script>alert('Please select record to edit contact.')</script>";
		$_SESSION['mode'] = "Display";
	}
} else if (isset($_POST['ct_b_delete']) && ($_POST['ct_b_delete'] == "Delete")){
	$_SESSION['mode'] = "Delete";
} else if (isset($_POST['ct_b_view']) && ($_POST['ct_b_view'] == "View Details")){
	$_SESSION['mode'] = "View";
} else if (isset($_POST['ct_b_cancel']) && ($_POST['ct_b_cancel'] == "Cancel")){
	if ($_SESSION['mode'] == "Add"){
		$_SESSION['add_part'] = 0;
		clearAddContactFromSession();
	} else if ($_SESSION['mode'] == "Edit"){
		$_SESSION['add_part'] = 0;
		clearEditContactFromSession();
	}
	$_SESSION['mode'] = "Display";
} else if (isset($_POST['ct_b_back']) && ($_POST['ct_b_back'] == "Back") && ($_SESSION['mode'] == "View")){
	if(isset($_SESSION['ct_detail_id'])){
		unset($_SESSION['ct_detail_id']);
	}
	$_SESSION['mode'] = "Display";
}

if(($_SESSION['mode'] == "Add") && ($_SERVER['REQUEST_METHOD'] == "GET")){ 
	switch ($_SESSION['add_part']) {
		case 0:
		case 1:
			formContactType();
			break;
		case 2:
			formContactName();
			break;
		case 3:
			formContactAddress();
			break;
		case 4:
			formContactPhone();
			break;
		case 5:
			formContactEmail();
			break;
		default:
	}
} else if($_SESSION['mode'] == "Add"){ 
	switch ($_SESSION['add_part']) {
		case 0:
			echo "<h1> Add New Contact </h1>\n";
			$_SESSION['add_part'] = 1;
			formContactType();
			break;
		case 1:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactType();
			if (count($err_msgs) > 0){
				displayErrors($err_msgs);
				formContactType();
			} else {
				contactTypePostToSession();
				$_SESSION['add_part'] = 2;
				formContactName();
			}
			break;
		case 2:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactName();
			if (count($err_msgs) > 0){
				displayErrors($err_msgs);
				formContactName();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactNamePostToSession();
				$_SESSION['add_part'] = 3;
				formContactAddress();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactNamePostToSession();
				$_SESSION['add_part'] = 1;
				formContactType();
			}
			break;
		case 3:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactAddress();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactAddress();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 4;
				formContactPhone();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactAddressPostToSession();
				$_SESSION['add_part'] = 4;
				formContactPhone();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactAddressPostToSession();
				$_SESSION['add_part'] = 2;
				formContactName();
			}
			break;
		case 4:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactPhone();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactPhone();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 5;
				formContactEmail();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactPhonePostToSession();
				$_SESSION['add_part'] = 5;
				formContactEmail();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactPhonePostToSession();
				$_SESSION['add_part'] = 3;
				formContactAddress();
			}
			break;
		case 5:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactEmail();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactEmail();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 6;
				formContactWeb();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactEmailPostToSession();
				$_SESSION['add_part'] = 6;
				formContactWeb();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactEmailPostToSession();
				$_SESSION['add_part'] = 4;
				formContactPhone();
			}
			break;
		case 6:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactWeb();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactWeb();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 7;
				formContactNote();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactWebPostToSession();
				$_SESSION['add_part'] = 7;
				formContactNote();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactWebPostToSession();
				$_SESSION['add_part'] = 5;
				formContactEmail();
			}
			break;
		case 7:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactNote();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactNote();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 8;
				formContactSave();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactNotePostToSession();
				$_SESSION['add_part'] = 8;
				formContactSave();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactNotePostToSession();
				$_SESSION['add_part'] = 6;
				formContactWeb();
			}
			break;
		case 8:
			if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Save")){
				$db_conn = connectDB();
				saveContact($db_conn);
				$db_conn = NULL;
				$_SESSION['add_part'] = 0;
				clearAddContactFromSession();
				$_SESSION['mode'] = "Display";
				formContactDisplay();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				echo "<h1> Add New Contact </h1>\n";
				$_SESSION['add_part'] = 7;
				formContactNote();
			}
			break;
		default:
	}
} else if(($_SESSION['mode'] == "Edit") && ($_SERVER['REQUEST_METHOD'] == "GET")){ 
	switch ($_SESSION['add_part']) {
		case 0:
		case 1:
			formContactType();
			break;
		case 2:
			formContactName();
			break;
		case 3:
			formContactAddress();
			break;
		case 4:
			formContactPhone();
			break;
		case 5:
			formContactEmail();
			break;
		default:
	}
} else if($_SESSION['mode'] == "Edit"){ 
	switch ($_SESSION['add_part']) {
		case 0:
			echo "<h1> Edit Contact </h1>\n";
			$_SESSION['add_part'] = 1;
			formContactType();
			break;
		case 1:
			echo "<h1> Edit Contact </h1>\n";
			$err_msgs = validateContactType();
			if (count($err_msgs) > 0){
				displayErrors($err_msgs);
				formContactType();
			} else {
				contactTypePostToSession();
				$_SESSION['add_part'] = 2;
				formContactName();
			}
			break;
		case 2:
			echo "<h1> Edit Contact </h1>\n";
			$err_msgs = validateContactName();
			if (count($err_msgs) > 0){
				displayErrors($err_msgs);
				formContactName();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactNamePostToSession();
				$_SESSION['add_part'] = 3;
				formContactAddress();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactNamePostToSession();
				$_SESSION['add_part'] = 1;
				formContactType();
			}
			break;
		case 3:
			echo "<h1> Edit Contact </h1>\n";
			$err_msgs = validateContactAddress();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactAddress();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 4;
				formContactPhone();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactAddressPostToSession();
				$_SESSION['add_part'] = 4;
				formContactPhone();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactAddressPostToSession();
				$_SESSION['add_part'] = 2;
				formContactName();
			}
			break;
		case 4:
			echo "<h1> Edit Contact </h1>\n";
			$err_msgs = validateContactPhone();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactPhone();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 5;
				formContactEmail();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactPhonePostToSession();
				$_SESSION['add_part'] = 5;
				formContactEmail();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactPhonePostToSession();
				$_SESSION['add_part'] = 3;
				formContactAddress();
			}
			break;
		case 5:
			echo "<h1> Edit Contact </h1>\n";
			$err_msgs = validateContactEmail();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactEmail();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 6;
				formContactWeb();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactEmailPostToSession();
				$_SESSION['add_part'] = 6;
				formContactWeb();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactEmailPostToSession();
				$_SESSION['add_part'] = 4;
				formContactPhone();
			}
			break;
		case 6:
			echo "<h1> Edit Contact </h1>\n";
			$err_msgs = validateContactWeb();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactWeb();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 7;
				formContactNote();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactWebPostToSession();
				$_SESSION['add_part'] = 7;
				formContactNote();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactWebPostToSession();
				$_SESSION['add_part'] = 5;
				formContactEmail();
			}
			break;
		case 7:
			echo "<h1> Edit Contact </h1>\n";
			$err_msgs = validateContactNote();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactNote();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 8;
				formContactEdit();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactNotePostToSession();
				$_SESSION['add_part'] = 8;
				formContactEdit();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactNotePostToSession();
				$_SESSION['add_part'] = 6;
				formContactWeb();
			}
			break;
		case 8:
			if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Save")){
				$db_conn = connectDB();
				editContact($db_conn);
				$db_conn = NULL;
				$_SESSION['add_part'] = 0;
				clearEditContactFromSession();
				$_SESSION['mode'] = "Display";
				formContactDisplay();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				echo "<h1> Edit Contact </h1>\n";
				$_SESSION['add_part'] = 7;
				formContactNote();
			}
			break;
		default:
	}
} else if($_SESSION['mode'] == "Delete"){
	if(isset($_POST['ct_delete_id']) && trim($_POST['ct_delete_id']) != ""){
		$ct_id = trim($_POST['ct_delete_id']);
		$db_conn = connectDB();
		softDeleteContact($db_conn, $ct_id);
		formContactDisplay();
	} else if(isset($_POST['list_select'][0]) && trim($_POST['list_select'][0]) != ""){
		$ct_id = $_POST['list_select'][0];
		softDeleteContactConfirm($ct_id);
	} else {
		echo "<script>alert('Please select record to delete contact.')</script>";
		formContactDisplay();
	}
} else if($_SESSION['mode'] == "View"){ 
	$db_conn = connectDB();
	if(isset($_POST['list_select'][0]) && trim($_POST['list_select'][0]) != ""){
		$ct_id = $_POST['list_select'][0];
		if(!isset($_SESSION['ct_detail_id']))$_SESSION['ct_detail_id'] = $ct_id;
		displayContactDetails($db_conn);
	} else {
		echo "<script>alert('Please select record to view contact details.')</script>";
		formContactDisplay();
	}
} else if($_SESSION['mode'] == "Display"){ 
	formContactDisplay();
} 
?>
	</body>
</html>

<?php
function formContactDisplay(){
	$db_conn = connectDB();
	$fvalue = "";
	if (isset($_POST['ct_b_filter']) && isset($_POST['ct_filter'])){
		$_SESSION['ct_filter'] = trim($_POST['ct_filter']);
		$fvalue = $_SESSION['ct_filter'];
	} else if (isset($_POST['ct_b_filter_clear'])){
		$_SESSION['ct_filter'] = "";
		$fvalue = $_SESSION['ct_filter'];
	} else if (isset($_SESSION['ct_filter'])){
		$fvalue = $_SESSION['ct_filter'];
	}
?>
		<h1> Contacts </h1>
		<div>
			<h2> Contacts </h2>
		</div>
		<div>
		<form method="POST">
		<table>
		<tr>
			<td><label for="ct_filter">Filter Value</label></td>
			<td><input type="text" name="ct_filter" id="ct_filter" value="<?php echo $fvalue; ?>"></td>
			<td><input type="submit" name="ct_b_filter" value="Filter">
			<td><input type="submit" name="ct_b_filter_clear" value="Clear Filter">
		</tr>
		</table>
		<br>
<?php
	displayContacts($db_conn);
	$db_conn = NULL;
?>
			<br>
			<table>
			<tr>
				<td><input type="submit" name ="ct_b_view" value="View Details"></td>
				<td><input type="submit" name ="ct_b_edit" value="Edit"></td>
				<td><input type="submit" name ="ct_b_delete" value="Delete"></td>
			</tr>
			<tr></tr>
			<tr>
				<td><input type="submit" name ="ct_b_add" value="Add New Contact"></td>
			</tr>
			</table>
		</form>
		</div>
<?php } ?>

