<?php

/*
* Functions for COBE Committee Admin plugin (COBE AC)
* 
*/
function cobe_committee() {
	global $wpdb;
	?>
	<style type="text/css" > #asterisk {color: red; } </style>
	<?php
	switch ($_GET['action']) {
		
	case 'editEmployee' :
	case 'editEmployee' :
		$imgPath = COBEURL . "/wp-content/themes/bsu-cobe-faculty/images/facultyImages/";
		$cvPath = COBEURL . "/wp-content/themes/bsu-cobe-faculty/facultyCV/";
		$employeeID = (int)$_GET['empID'];
		//			echo $employeeID;
		$query = "Select * From wp_committee Where employeeID=%d";
		//			echo $query;
		$info = $wpdb->get_results($wpdb->prepare($query, $employeeID));
		//			print_r($info);
		?>

		<div class="wrap" id="profile-page" >
		<h2>Edit Employee</h2>
		<h3><?php echo $info[0]->firstName." ".$info[0]->middleInitial." ".$info[0]->lastName ?></h3>
		<form action="admin.php?page=committee&amp;action=updatecommittee" method="post" 
		enctype="multipart/form-data" name="editEmpl" id="your-profile" >
		<span id="asterisk">Required *</span>
		<table class="form-table">
		<tr>
		<th><label for="employeeID"><?php _e('Employee ID'); ?><span id="asterisk" >*</span></label></th>
		<td><input type="text" name="employeeID" id="user_login" value="<?php echo $info[0]->employeeID; ?>" disabled="disabled" class="regular-text" /> <?php _e('The Employee ID cannot be changed here.(See Site Admin to change)'); ?></td>
		</tr>
		<tr>
		<th><label for="firstName"><?php _e('First name') ?><span id="asterisk" >*</span></label></th>
		<td><input type="text" name="firstName" id="first_name" value="<?php echo $info[0]->firstName ?>" class="regular-text" /></td>
		</tr>
		
		<tr>
		<th><label for="middleInitial"><?php _e('Middle Initial') ?></label></th>
		<td><input type="text" name="middleInitial"  value="<?php echo $info[0]->middleInitial ?>" class="regular-text" /></td>
		</tr>
		
		<tr>
		<th><label for="lastName"><?php _e('Last name') ?><span id="asterisk" >*</span></label></th>
		<td><input type="text" name="lastName" id="last_name" value="<?php echo $info[0]->lastName ?>" class="regular-text" /></td>
		</tr>
		
		<tr>
		<th><label for="displayName">Display Name</label></th>
		<td><input type="text" name="displayName" id="" value="<?php echo $info[0]->displayName ?>" class="regular-text" /></td>
		</tr>
		
		<tr>
		<th><label for="title">Title at Company or Boise State Major</label></th>
		<td><input type="text" name="title" id="" value="<?php echo $info[0]->title ?>" class="regular-text" /></td>
		</tr>
		<tr>
		<th><label for="company">Company Name (for CoBE AC only)</label></th>
		<td><input type="text" name="company" id="" value="<?php echo $info[0]->company ?>" class="regular-text" /></td>
		</tr>
		
		<tr>
		<th><label for="category">Committee<span id="asterisk" >*</span></label></th>
		<td><select  name="category" id="" >
		<option value="cobeac" 
		<?php echo ($info[0]->Category == 'cobeac')? "selected='selected'":""; ?> >
		CoBE AC</option>
		<option value="studentac" 
		<?php echo ($info[0]->Category == 'stduentac')? "selected='selected'":""; ?> >
		Student AC</option>
		</select></td>
		</tr>					
		<tr>
		<th><label for="pix">Photo (File Name)</label></th>
		<td ><input style="vertical-align:top;" type="text" name="pix" id="pix" value="<?php echo $info[0]->pix ?>" class="regular-text" />
		<img height="200px" width="160px" name="facPhoto" src="<?php echo $imgPath.$info[0]->pix ?>" /></td>
		</tr>

		<tr>
		<th><label for="file">Upload Photo:</label></th>
		<td><input type="file" name="file"  id="photoFile" 
		onchange="document.forms['editEmpl']['pix'].value=(this.value);
									document.facPhoto.src=(this.value);
									" /></td>
		
		</tr>
		
		<tr>
		<th><label for="listFirst">List this Person First on Department Page</label></th>
		<td><input type="radio" name="listFirst" id="" value=1  
		<?php echo ($info[0]->listFirst)? "checked='checked'": ""; ?>/>Yes
		<input type="radio" name="listFirst" id="" value=0  
		<?php echo (!$info[0]->listFirst)? "checked='checked'": ""; ?>/>No	</td>
		</tr>

		<tr>
		<th><label for="published">Publish on Web Site<span id="asterisk" >*</span></label></th>
		<td><input type="radio" name="published" id="" value=1  
		<?php echo ($info[0]->published)? "checked='checked'": ""; ?>/>Yes
		<input type="radio" name="published" id="" value=0  
		<?php echo (!$info[0]->published)? "checked='checked'": ""; ?>/>No	</td>
		</tr>
		
		</table>
		<input type="hidden" name="emplID" value="<?php echo $info[0]->employeeID; ?>" />
		<input type="hidden" name="filePath" value="<?php echo $imgPath; ?>" />
		<input type="hidden" name="cvPath" value="<?php echo $cvPath; ?>" />
		<p class="submit">
		<input class="button" type="submit" name="go" value="Save"/></p>	
		</form>
		</div>
		<?php 
		break;
	case 'updatecommittee':
		//	check_admin_referer('editblog');
		$dirPath = ABSPATH . "/wp-content/themes/bsu-cobe-faculty/images/facultyImages";
		$cvDirPath = ABSPATH . "/wp-content/themes/bsu-cobe-faculty/facultyCV";

		if( empty( $_POST ) )
		wp_die( __('You probably need to go back to the <a href="admin.php?page=committee">committee page</a>') );
		//	print_r($_FILES);
		$photoUpload = cobeUpload($_FILES, $dirPath, '', 'file');
			
		if ($photoUpload === true)
			echo cobeWPMessage($_FILES['file']['name'] . ' was successfully uploaded.');
		else
			echo cobeWPMessage($photoUpload, 'error');
		
		$cvUpload = cobeUpload($_FILES, $cvDirPath, '', 'cvfile');
		
		if ($cvUpload === true)
			echo cobeWPMessage($_FILES['cvfile']['name']  . ' was sucessfully uploaded.');
		else
			echo cobeWPMessage($photoUpload, 'error');

		$_POST = cobeEscapePost($_POST);
		$name = $_POST['firstName']." ".$_POST['lastName'];
		// update committee table
		$updateQuery = "UPDATE wp_committee SET 
					firstName       = '".$_POST['firstName']."',
					middleInitial   = '".$_POST['middleInitial']."',
					lastName      	= '".$_POST['lastName']."',
					displayName     = '".$_POST['displayName']."',
					title       	= '".$_POST['title']."',
					company   		= '".$_POST['company']."',
					Category     = '".$_POST['Category']."',
					pix         	= '".$_POST['pix']."' ,
					listFirst       = '".$_POST['listFirst']."' ,
					published      	= '".$_POST['published']."' 
					WHERE  employeeID = '".$_POST['emplID']."'"; 
		//				echo $updateQuery;
		$result = $wpdb->query($updateQuery);
		// now insert or update education using REPLACE
		?>
		<div id="message" class="updated fade"><p>
		<?php echo $name."'s information was updated.";?>
		</p>
		<a href="admin.php?page=committee">Continue</a>
		</div> <?php 
		break;
		
	case 'insertcommittee':
		//			check_admin_referer('editblog');
		$dirPath = ABSPATH . "/wp-content/themes/bsu-cobe-faculty/images/facultyImages";
		$cvDirPath = ABSPATH . "/wp-content/themes/bsu-cobe-faculty/facultyCV";
		
		if( empty( $_POST ) )
		wp_die( __('You probably need to go back to the <a href="admin.php?page=committee">committee page</a>') );
		//				print_r($_POST);
		
		$photoUpload = cobeUpload($_FILES, $dirPath, '', 'file');
			
		if ($photoUpload === true)
			echo cobeWPMessage($_FILES['file']['name'] . ' was successfully uploaded.');
		else
			echo cobeWPMessage($photoUpload, 'error');
		
		$cvUpload = cobeUpload($_FILES, $cvDirPath, '', 'cvfile');
		
		if ($cvUpload === true)
			echo cobeWPMessage($_FILES['cvfile']['name']  . ' was sucessfully uploaded.');
		else
			echo cobeWPMessage($photoUpload, 'error');	
		
		$_POST = cobeEscapePost($_POST);	
		$name = $_POST['firstName']." ".$_POST['lastName'];
		// update committee table
		$insertQuery = "INSERT INTO wp_committee VALUES( 
				'".$_POST['employeeID']."', '".$_POST['firstName']."', 
				'".$_POST['middleInitial']."','".$_POST['lastName']."', 
				'".$_POST['displayName']."', '".$_POST['title']."',
				'".$_POST['company']."', '".$_POST['Category']."',
				'".$_POST['pix']."', '".$_POST['listFirst']."', 
				'".$_POST['published']."')"; 
		//	echo $insertQuery;
		$result = $wpdb->query($insertQuery);
		?>
		<div id="message" class="updated fade"><p>
		<?php echo $name."'s information was added.";?>
		</p>
		<a href="admin.php?page=committee">Continue</a>
		</div> <?php 
		break;
		
	case 'addEmployee':
		?>
		<div class="wrap" id="profile-page" >
		<h2>Add Employee</h2>
		<form action="admin.php?page=committee&amp;action=insertEmployee" name="insertEmpl" method="post" enctype="multipart/form-data" id="your-profile" >
		
		<table class="form-table">
		<span id="asterisk">Required *</span>			
		<tr>
		<th><label for="employeeID">Employee ID</label></th>
		<td><input type="text" name="employeeID" id="user_login" value=""  class="regular-text" />The Employee ID is the Boise State ID</td>
		</tr>
		<tr>
		<th><label for="employeeID"><?php _e('Employee ID'); ?><span id="asterisk" >*</span></label></th>
		<td><input type="text" name="employeeID" id="user_login" value="" disabled="disabled" class="regular-text" /> <?php _e('The Employee ID cannot be changed here.(See Site Admin to change)'); ?></td>
		</tr>
		<tr>
		<th><label for="firstName"><?php _e('First name') ?><span id="asterisk" >*</span></label></th>
		<td><input type="text" name="firstName" id="first_name" value="" class="regular-text" /></td>
		</tr>
		
		<tr>
		<th><label for="middleInitial"><?php _e('Middle Initial') ?></label></th>
		<td><input type="text" name="middleInitial"  value="" class="regular-text" /></td>
		</tr>
		
		<tr>
		<th><label for="lastName"><?php _e('Last name') ?><span id="asterisk" >*</span></label></th>
		<td><input type="text" name="lastName" id="last_name" value="" class="regular-text" /></td>
		</tr>
		
		<tr>
		<th><label for="displayName">Display Name</label></th>
		<td><input type="text" name="displayName" id="" value="" class="regular-text" /></td>
		</tr>
		
		<tr>
		<th><label for="title">Title at Company or Boise State Major</label></th>
		<td><input type="text" name="title" id="" value="" class="regular-text" /></td>
		</tr>
		<tr>
		<th><label for="company">Company Name (for CoBE AC only)</label></th>
		<td><input type="text" name="company" id="" value="" class="regular-text" /></td>
		</tr>
		
		<tr>
		<th><label for="category">Committee<span id="asterisk" >*</span></label></th>
		<td><select  name="category" id="" >
		<option value="cobeac">
		CoBE AC</option>
		<option value="studentac">
		Student AC</option>
		</select></td>
		</tr>					
		<tr>
		<th><label for="file">Upload Photo:</label></th>
		<td><input type="file" name="file"  id="photoFile" 
		onchange="document.forms['editEmpl']['pix'].value=(this.value);
									document.facPhoto.src=(this.value);
									" /></td>
		
		</tr>
		
		<tr>
		<th><label for="listFirst">List this Person First on Department Page</label></th>
		<td><input type="radio" name="listFirst" id="" value=1  />Yes
		<input type="radio" name="listFirst" id="" value=0  />No	</td>
		</tr>

		<tr>
		<th><label for="published">Publish on Web Site<span id="asterisk" >*</span></label></th>
		<td><input type="radio" name="published" id="" value=1  />Yes
		<input type="radio" name="published" id="" value=0  />No	</td>
		</tr>
		
		</table>
		<p class="submit">
		<input class="button" type="submit" name="go" value="Save"/></p>	
		</form>
		</div>
		<?php 
		break;
		
		// Start of List of Employees
	default:
		
		?>
		<div class="wrap" style="position:relative;">
		<h2><?php _e('COBE committee Management')?></h2>
		
		<?php 
		$imgPath = COBEURL . "/wp-content/themes/bsu-cobe-faculty/images/facultyImages/";
		$sortOrder = "lastName";
		if (isset($_GET['cobeac'])) {
			$addWhere = " Category='cobeac'";
		}elseif (isset( $_GET['studentac'])) {
			$addWhere = " Category='studentac'";
		}else{
			$addWhere = "";
		}
		//	echo "List: ".$addWhere;
		// Pagination Info
		$apage = isset( $_GET['apage'] ) ? intval( $_GET['apage'] ) : 1;
		$num = isset( $_GET['num'] ) ? intval( $_GET['num'] ) : 15;
		$s = wp_specialchars( trim( $_GET[ 's' ] ) );
		$ss = "%{$s}%";
		
		if (!empty($s)){
			$total = $wpdb->get_var( empty($addWhere)?
			$wpdb->prepare("Select Count(employeeID) From wp_committee Where lastName LIKE %s", $ss) :
			$wpdb->prepare("Select Count(employeeID) From wp_committee Where lastName LIKE %s and {$addWhere}", $ss));
		}else{
			$total = $wpdb->get_var(  empty($addWhere)? "Select Count(employeeID) From wp_committee" :
			"Select Count(employeeID) From wp_committee Where {$addWhere}");
		}
		
		if (isset($_GET['firstName'])) {$sortOrder = "firstName";}
		
		$query  = "Select wp_committee.*, wp_depts.deptName From wp_committee ";
		$query .= "Join wp_depts on wp_committee.deptID = wp_depts.deptID  ";
		if (isset($_GET['s']) && empty($addWhere)) {
			$query .= "Where lastName LIKE %s ";
		}elseif (isset($_GET['s']) && !empty($addWhere)) {
			$query .= "Where lastName LIKE %s and ".$addWhere;
		}elseif (!isset($_GET['s']) && !empty($addWhere)){
			$query .= "Where ".$addWhere;
		}
		$query .= " Order By ".$sortOrder;
		$query .= " LIMIT ". intval(($apage -1) * $num). ", ". intval($num);
		
		$rows = $wpdb->get_results($wpdb->prepare($query, $ss));
		/*	echo "<pre>";
	print_r($rows);
	echo "</pre>";
	*/
		$url2 = "&amp;sortby=" . $_GET['sort_by'] . "&amp;s=";
		$url2 .= $_GET[ 's' ];
		
		$blog_navigation = paginate_links( array(
		'base' => add_query_arg( 'apage', '%#%' ).$url2,
		'format' => '',
		'total' => ceil($total / $num),
		'current' => $apage
		));
		
		?>
		<form action="admin.php" method="get" id="wpmu-search">
		<input type="hidden" name="page" value="committee" />
		<input type="text" name="s" value="<?php if (isset($_GET['s'])) echo stripslashes( wp_specialchars( $s, 1 ) ); ?>" size="17" />
		<input type="submit" class="button" name="last_name" value="<?php _e('Search by Last Name') ?>" />
		</form>
		
		


		<form id="form-committee-list" action="admin.php" method="post">

		<div class="tablenav">
		<?php if ( $blog_navigation ) echo "<div class='tablenav-pages'>$blog_navigation</div>"; ?>
		</div>
		
		<table width="100%" cellpadding="5" cellspacing="3" class="widefat">
		<tbody id="the-list" >
		<?php 
		$path = COBEURL . "/wp-content/plugins/cobe_admin/committee";
		foreach ($rows as $row) { ?>
			<tr> <td valign="top"> 
			<?php echo $row->lastName.", ".$row->firstName." ".$row->middleInitial;
			if ($row->suffix == "PhD" || $row->suffix == "JD" )
			{
				echo " , ".$row->suffix;
			} 
			
			?>
			<br />
			<?php echo $row->title;?><br />
			Display Name:&nbsp;<?php echo $row->displayName;?><br />
			<?php echo $row->deptName."<br />"; 
			echo "Category: ".$row->jobCategory."<br /><br />";
			echo "Photo: ".$row->pix;
			echo "<br /><br /><a href='admin.php?page=committee&amp;action=editEmployee
					&amp;empID={$row->employeeID}' class='row-actions' >".__('Edit')." </a>"; 
			?>
			</td>
			<td>
			<img src="<?php echo $imgPath.$row->pix ?>" />
			</td>
			<td width="20%" valign="top">
			</td><td>
			<?php 	
			echo ($row->published)?"<img src='$path/published.gif' /><br />":
			"<img src='$path/notpublished.gif' /><br />" ;
			echo "<br />List First: ";
			echo ($row->listFirst)?"Yes":"No";
			echo "</td></tr>";
		}
		?>
		</tbody>
		</table>
		</form>
		</div> 
		<center><p>This section is not ready for deployment.</p></center>
		<!-- wrap -->	
		<?php 
		break;
	}
}


?>