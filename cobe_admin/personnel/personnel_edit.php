<?php

function cobe_personnel() {
	global $wpdb;
	global $blog_id;
	
	$action = (!empty($_GET['action'])) ? $_GET['action'] : '';
	$facultyAllowedPages = array('editEmployee', 'updatePersonnel');
	
	/*if ($blog_id != 1) {
		if (!in_array($action, $facultyAllowedPages))
			$action = 'editEmployee';
	}*/
	?>
	<style type="text/css" > #asterisk {color: red; } </style>
	<?php
	switch ($action) {
		
		case 'editEmployee' :
			$imgPath = COBEURL . "/wp-content/themes/bsu-cobe-faculty/images/facultyImages/";
			$cvPath = COBEURL . "/wp-content/themes/bsu-cobe-faculty/facultyCV/";
			$employeeID = 0;
			
			if (isset($_GET['empID']))
				$employeeID = (int)$_GET['empID'];
			else {
  				$employeeInfo = getFaculty2(get_bloginfo('name')); 
				$employeeID = $employeeInfo[0]->employeeID;
			}
			
			$query = "Select * From wp_personnel Where employeeID=%d";
//			echo $query;
			$info = $wpdb->get_results($wpdb->prepare($query, $employeeID));
//			print_r($info);
			?>

			<div class="wrap" id="profile-page" >
			<h2>Edit Employee</h2>
			<h3><?php echo $info[0]->firstName." ".$info[0]->middleInitial." ".$info[0]->lastName ?></h3>
			<form action="admin.php?page=personnel&amp;action=updatePersonnel" method="post" 
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
				<th><label for="displayName">Display</label></th>
				<td><input type="text" name="displayName" id="" value="<?php echo $info[0]->displayName ?>" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="suffix"><?php _e('PhD or JD') ?></label></th>
				<td><input type="radio" name="suffix" id="" value="PhD"
						<?php echo ($info[0]->suffix == "PhD")? "checked='checked'": ""; ?>
							 class="radio" /> PhD &nbsp;
				<input type="radio" name="suffix" id="" value="JD" 
						<?php echo ($info[0]->suffix == "JD")? "checked='checked'": ""; ?>
							class="radio" />JD &nbsp;
				<input type="radio" name="suffix" id="" value="None" 
						<?php echo ($info[0]->suffix == "None")? "checked='checked'": ""; ?>
							class="radio" />None </td>
			</tr>
			
			<tr>
				<th><label for="deptID"><?php _e('Department') ?><span id="asterisk" >*</span></label></th>
				<td>
					<select name="deptID" id="">
                                            <option id="" value="0" selected='selected'> None </option>
					<?php
						$depts = $wpdb->get_results("Select * From wp_depts");
						foreach ($depts as $dept) {
					?>
						<option id="" value="<?php echo $dept->deptID; ?>" 
						<?php echo ($dept->deptID == $info[0]->deptID)? "selected='selected'": "" ?>>
						<?php echo $dept->deptName; ?></option>
						<?php  }?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="deptID2"><?php _e('Department 2') ?></label></th>
				<td>
					<select name="deptID2" id="">
						<option id="" value="0" selected='selected'> None </option>
					<?php
						foreach ($depts as $dept) {
					?>
						<option id="" value="<?php echo $dept->deptID; ?>" 
						<?php echo ($dept->deptID == $info[0]->deptID2)? "selected='selected'": "" ?>>
						<?php echo $dept->deptName; ?></option>
						<?php  }?>

					</select>
				</td>
			</tr>
			<tr>
				<th><label for="deptID3"><?php _e('Department 3') ?></label></th>
				<td>
					<select name="deptID3" id="">
						<option id="" value="0" selected='selected'> None </option>
					<?php
						foreach ($depts as $dept) {
					?>
						<option id="" value="<?php echo $dept->deptID; ?>" 
						<?php echo ($dept->deptID == $info[0]->deptID3)? "selected='selected'": "" ?>>
						<?php echo $dept->deptName; ?></option>
						<?php  }?>

					</select>
				</td>
			</tr>
                        <tr><th><h3>Administration</h3></th><td></td>
                        </tr>
                        <tr>
                        				<th><label for="adminID"><?php _e('Area') ?></label></th>
				<td>
					<select name="adminID" id="">
						<option id="" value="0" selected='selected'> None </option>
					<?php
                                                $areas = $wpdb->get_results("Select * From wp_AdminAreas");
						foreach ($areas as $area) {
					?>
						<option id="" value="<?php echo $area->adminID; ?>" 
						<?php echo ($area->adminID == $info[0]->adminID)? "selected='selected'": "" ?>>
						<?php echo $area->adminName; ?></option>
						<?php  }?>

					</select>
				</td>
			</tr>
                        <tr><th><h3>Centers</h3></th><td></td>
                        </tr>
			<tr>
				<th><label for="centerID"><?php _e('Center') ?></label></th>
				<td>
					<select name="centerID" id="">
						<option id="" value="0" selected='selected'> None </option>
					<?php
                                                $centers = $wpdb->get_results("Select * From wp_Centers");
						foreach ($centers as $center) {
					?>
						<option id="" value="<?php echo $center->centerID; ?>" 
						<?php echo ($center->centerID == $info[0]->centerID)? "selected='selected'": "" ?>>
						<?php echo $center->centerName; ?></option>
						<?php  }?>

					</select>
				</td>
			</tr>
			
                        <tr>
				<th><label for="title">Job Title</label><span id="asterisk" >*</span></th>
				<td><input type="text" name="title" id="" value="<?php echo $info[0]->title ?>" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="blogName">Web Site (Blog Name)</label></th>
				<td><input type="text" name="blogName" id="" value="<?php echo $info[0]->blogName ?>" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="email">Email<span id="asterisk" >*</span></label></th>
				<td><input type="text" name="email" id="" value="<?php echo $info[0]->email ?>" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="phone">Phone<span id="asterisk" >*</span></label></th>
				<td><input type="text" name="phone" id="" value="<?php echo $info[0]->phone ?>" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="faxNo">Fax Number</label></th>
				<td><input type="text" name="faxNo" id="" value="<?php echo $info[0]->faxNo ?>" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="mailStop">Mail Stop</label></th>
				<td><input type="text" name="mailStop" id="" value="<?php echo $info[0]->mailStop ?>" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="jobCategory">Job Classification<span id="asterisk" >*</span></label></th>
				<td><select  name="jobCategory" id="" >
				 	<option value="faculty" 
				 		<?php echo ($info[0]->jobCategory == 'faculty')? "selected='selected'":""; ?> >
				 		Faculty</option>
				 	<option value="staff" 
				 		<?php echo ($info[0]->jobCategory == 'staff')? "selected='selected'":""; ?> >
				 	 	Staff</option>
				 </select></td>
			</tr>
			
			<tr>
				<th><label for="officNo">Office Number</label></th>
				<td><input type="text" name="officeNo" id="" value="<?php echo $info[0]->officeNo ?>" class="regular-text" /></td>
			</tr>
			
			
			<tr>
                        
				<th><label for="officeHours">Office Hours</label></th>
				<td><input type="text" name="officeHours" id="" value="<?php echo $info[0]->officeHours ?>" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="bio">Bio </label></th>
				<td>
				<textarea rows="6" cols="70" name="bio"><?php _e($info[0]->bio) ?></textarea>
				</td>
			</tr>
			
			<tr>
				<th><label for="education">Education (Use a semicolon to separate entries)</label></th>
				<td>
				<textarea rows="3" cols="70" name="education"><?php _e($info[0]->education) ?></textarea>
				</td>
			</tr>
			
			<tr>
				<th><label for="selectPublications">Select Publications (Use a semicolon to separate entries)</label></th>
				<td>
				<textarea rows="6" cols="70" name="selectPublications"><?php _e($info[0]->selectPublications) ?></textarea>
				</td>
			</tr>
			
			<tr>
				<th><label for="awards">Awards (Use a semicolon to separate entries)</label></th>
				<td>
				<textarea rows="5" cols="70" name="awards"><?php _e($info[0]->awards)?></textarea></td>
			</tr>
			
			<tr>
				<th><label for="teachingAreas">Teaching Areas (Use a semicolon to separate entries)</label></th>
				<td>
				<textarea rows="2" cols="70" name="teachingAreas"><?php _e($info[0]->teachingAreas)?></textarea></td>
			</tr>
						
			<tr>
				<th><label for="cv">CV (File Name)</label></th>
				<td ><input style="vertical-align:top;" type="text" name="cv" id="cv" value="<?php echo $info[0]->cv ?>" class="regular-text" />
                     <?php if ($info[0]->cv != '') { ?>
                     <a href="<?php echo $cvPath.$info[0]->cv ?>">previously uploaded CV</a>
                     <?php }?>
				</td>
			</tr>

			<tr>
				<th><label for="file">Upload CV:</label></th>
				<td><input type="file" name="cvfile"  id="cvFile" 
						onchange="document.forms['editEmpl']['cv'].value=(this.value); document.facCV.src=(this.value);" /></td>
			</tr>		
		<tr><th></th><td><p style="color: red;">Note: Please use <a href="http://firefox.com">Mozilla Firefox</a> temporarily to upload CV's!</p><p style="color: red;">If you have questions, please contact <a href="mailto:cobeweb@boisestate.edu">cobeweb@boisestate.edu</a>.</p></td></tr>
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
			<tr><th></th><td><p style="color: red;">Note: Please use <a href="http://firefox.com">Mozilla Firefox</a> temporarily to upload Photos's!</p><p style="color: red;">If you have questions, please contact <a href="mailto:cobeweb@boisestate.edu">cobeweb@boisestate.edu</a>.</p></td></tr>
			<?php if ($blog_id == 1) { ?>
			<tr>
				<th><label for="isDeptContact">This Person is the Department Contact</label></th>
				<td><input type="radio" name="isDeptContact" id="" value=1  
							<?php echo ($info[0]->isDeptContact)? "checked='checked'": ""; ?>/>Yes
					<input type="radio" name="isDeptContact" id="" value=0  
							<?php echo (!$info[0]->isDeptContact)? "checked='checked'": ""; ?>/>No	</td>
			</tr>

                        <tr>
				<th><label for="isDeptHead">This Person is the Head of the Department </label></th>
				<td><input type="radio" name="isDeptHead" id="" value=1  
							<?php echo ($info[0]->isDeptHead)? "checked='checked'": ""; ?>/>Yes
					<input type="radio" name="isDeptHead" id="" value=0  
							<?php echo (!$info[0]->isDeptHead)? "checked='checked'": ""; ?>/>No	</td>
			</tr>

			<tr>
				<th><label for="listFirst">List this Person First on Department Page</label></th>
				<td><input type="radio" name="listFirst" id="" value=1  
							<?php echo ($info[0]->listFirst)? "checked='checked'": ""; ?>/>Yes
					<input type="radio" name="listFirst" id="" value=0  
							<?php echo (!$info[0]->listFirst)? "checked='checked'": ""; ?>/>No	</td>
			</tr>
			<?php } ?>
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
		case 'updatePersonnel':
//	check_admin_referer('editblog');
			$dirPath = ABSPATH . "/wp-content/themes/bsu-cobe-faculty/images/facultyImages";
			$cvDirPath = ABSPATH . "/wp-content/themes/bsu-cobe-faculty/facultyCV";

			if( empty( $_POST ) )
				wp_die( __('You probably need to go back to the <a href="admin.php?page=personnel">personnel page</a>') );
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
			$cvdbname = basename($_POST['cv']);
			$pixdbname = basename($_POST['pix']);

			$name = $_POST['firstName']." ".$_POST['lastName'];
			// update personnel table
			$updateQuery = "UPDATE wp_personnel SET 
					deptID       	= '".$_POST['deptID']."',
					deptID2       	= '".$_POST['deptID2']."',
					deptID3       	= '".$_POST['deptID3']."',
					adminID       	= '".$_POST['adminID']."',
					centerID       	= '".$_POST['centerID']."',
					firstName       = '".$_POST['firstName']."',
					middleInitial   = '".$_POST['middleInitial']."',
					lastName      	= '".$_POST['lastName']."',
					displayName     = '".$_POST['displayName']."',
					suffix     		= '".$_POST['suffix']."',
					title       	= '".$_POST['title']."',
					blogName      	= '".$_POST['blogName']."',
					email         	= '".$_POST['email']."' ,
					phone       	= '".$_POST['phone']."',
					faxNo      		= '".$_POST['faxNo']."',
					mailStop   		= '".$_POST['mailStop']."',
					jobCategory     = '".$_POST['jobCategory']."',
					officeNo   		= '".$_POST['officeNo']."',
					officeHours    	= '".$_POST['officeHours']."',
					bio		    	= '".$_POST['bio']."',
					education    	= '".$_POST['education']."',
					selectPublications = '".$_POST['selectPublications']."',
					awards    		= '".$_POST['awards']."',
					teachingAreas	= '".$_POST['teachingAreas']."',
					pix         	= '".$pixdbname."' ,
					cv              = '".$cvdbname."' ,
					isDeptContact       = '".$_POST['isDeptContact']."' ,
					isDeptHead       = '".$_POST['isDeptHead']."' ,
					listFirst       = '".$_POST['listFirst']."' ,
					published      	= '".$_POST['published']."'" ;
					
					
			$updateQuery .= " WHERE employeeID = '".$_POST['emplID']."'"; 
				echo $updateQuery;
			$result = $wpdb->query($updateQuery);
			// now insert or update education using REPLACE
				?>
		<div id="message" class="updated fade"><p>
			<?php echo $name."'s information was updated.";?>
		</p>
		<a href="admin.php?page=personnel">Continue</a>
		</div> <?php 
				break;
				
		case 'insertEmployee':
			$dirPath = ABSPATH . "/wp-content/themes/bsu-cobe-faculty/images/facultyImages";
			$cvDirPath = ABSPATH . "/wp-content/themes/bsu-cobe-faculty/facultyCV";
			
			if( empty( $_POST ) )
				wp_die( __('You probably need to go back to the <a href="admin.php?page=personnel">personnel page</a>') );

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
			// update personnel table
                        // 0's for areaID and accreditation and aacsb_cv  -- handled on the AACSB side
			$insertQuery = "INSERT INTO wp_personnel VALUES( 
				'".$_POST['employeeID']."', '".$_POST['deptID']."',
				'".$_POST['deptID2']."', '".$_POST['deptID3']."',
				'".$_POST['adminID']."', '".$_POST['centerID']."',
                                '0','0',  
				'".$_POST['firstName']."', '".$_POST['middleInitial']."',
				'".$_POST['lastName']."', '".$_POST['displayName']."',
				'".$_POST['suffix']."', '".$_POST['title']."',
				'".$_POST['blogName']."' , '".$_POST['email']."',
				'".$_POST['phone']."', '".$_POST['faxNo']."',
				'".$_POST['mailStop']."', '".$_POST['jobCategory']."',
				'".$_POST['officeNo']."', '".$_POST['officeHours']."',
				'".$_POST['bio']."', '".$_POST['education']."' , 
				'".$_POST['selectPublications']."', '".$_POST['awards']."' , 
				'".$_POST['teachingAreas']."' , '".$_POST['cv']."' , '', 
				'".$_POST['pix']."', '".$_POST['listFirst']."', 
				'".$_POST['isDeptContact']."', '".$_POST['isDeptHead']."', 
				'".$_POST['published']."')"; 
				
				//echo $insertQuery;
				$result = $wpdb->query($insertQuery);
				?>
		<div id="message" class="updated fade"><p>
			<?php echo $name."'s information was added.";?>
		</p>
		<a href="admin.php?page=personnel">Continue</a>
		</div> <?php 
				break;
				
		case 'addEmployee':
			?>
			<div class="wrap" id="profile-page" >
				<h2>Add Employee</h2>
			<form action="admin.php?page=personnel&amp;action=insertEmployee" enctype="multipart/form-data" name="insertEmpl" method="post" id="your-profile" >
			
			<table class="form-table">
			<span id="asterisk">Required *</span>			
			<tr>
				<th><label for="employeeID">Employee ID</label></th>
				<td><input type="text" name="employeeID" id="user_login" value=""  class="regular-text" />The Employee ID is the Boise State ID</td>
			</tr>
			<tr>
				<th><label for="firstName">First name</label><span id="asterisk" >*</span></th>
				<td><input type="text" name="firstName" id="first_name" value="" class="regular-text" />Same as PeopleSoft Name for First and Last</td>
			</tr>
			
			<tr>
				<th><label for="middleInitial">Middle Initial</label></th>
				<td><input type="text" name="middleInitial"  value="" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="lastName">Last name</label><span id="asterisk" >*</span></th>
				<td><input type="text" name="lastName" id="last_name" value="" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="displayName">Display Name</label></th>
				<td><input type="text" name="displayName" id="" value="" class="regular-text" />How you want the name displayed on site.</td>
			</tr>
			
			
			<tr>
				<th><label for="suffix">PhD or JD</label></th>
				<td><input type="radio" name="suffix" id="" value="" class="radio" /> PhD &nbsp;
				<input type="radio" name="suffix" id="" value="" class="radio" />JD &nbsp;
				<input type="radio" name="suffix" id="" value="" class="radio" />None </td>
			</tr>
                        <tr><th><h3>Academic Departments</h3></th><td></td>
                        </tr>
			
			<tr>
				<th><label for="deptID">Department</label><span id="asterisk" >*</span></th>
				<td>
					<select name="deptID" id="">
					<?php
						$depts = $wpdb->get_results("Select * From wp_depts");
						foreach ($depts as $dept) {
					?>
						<option id="" value="<?php echo $dept->deptID; ?>" >
						<?php echo $dept->deptName; ?></option>
						<?php  }?>
					   <option id="" value="0">None</option>
					</select>
				</td>
			</tr>

			<tr>
				<th><label for="deptID2">Department 2</label></th>
				<td>
					<select name="deptID2" id="">
					   <option id="" value="0">None</option>
					<?php
						foreach ($depts as $dept) {
					?>
						<option id="" value="<?php echo $dept->deptID; ?>" >
						<?php echo $dept->deptName; ?></option>
						<?php  }?>
					</select>
				</td>
			</tr>
			<tr>
				<th><label for="deptID3">Department 3</label></th>
				<td>
					<select name="deptID3" id="">
					   <option id="" value="0">None</option>
					<?php
						foreach ($depts as $dept) {
					?>
						<option id="" value="<?php echo $dept->deptID; ?>" >
						<?php echo $dept->deptName; ?></option>
						<?php  }?>
					</select>
				</td>
			</tr>
                        <tr><th><h3>Administration</h3></th><td></td>
                        </tr>
			<tr>
				<th><label for="adminID"><?php _e('Area') ?></label></th>
				<td>
					<select name="adminID" id="">
						<option id="" value="0" selected='selected'> None </option>
					<?php
                                                $areas = $wpdb->get_results("Select * From wp_AdminAreas");

                                                foreach ($areas as $area) {
					?>
						<option id="" value="<?php echo $area->adminID; ?>" >
						<?php echo $area->adminName; ?></option>
						<?php  }?>

					</select>
				</td>
			</tr>
                        <tr><th><h3>Centers</h3></th><td></td>
                        </tr>
			<tr>
				<th><label for="centerID"><?php _e('Center') ?></label></th>
				<td>
					<select name="centerID" id="">
						<option id="" value="0" selected='selected'> None </option>
					<?php
                                                $centers = $wpdb->get_results("Select * From wp_Centers");
						foreach ($centers as $center) {
					?>
						<option id="" value="<?php echo $center->centerID; ?>" >
						<?php echo $center->centerName; ?></option>
						<?php  }?>

					</select>
				</td>
			</tr>
			<tr>
				<th><label for="title">Job Title</label><span id="asterisk" >*</span></th>
				<td><input type="text" name="title" id="" value="" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="blogName">Web Site (Blog Name)</label></th>
				<td><input type="text" name="blogName" id="" value="" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="email">Email</label><span id="asterisk" >*</span></th>
				<td><input type="text" name="email" id="" value="" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="phone">Phone</label><span id="asterisk" >*</span></th>
				<td><input type="text" name="phone" id="" value="" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="faxNo">Fax Number</label></th>
				<td><input type="text" name="faxNo" id="" value="" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="mailStop">Mail Stop</label></th>
				<td><input type="text" name="mailStop" id="" value="" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="jobCategory">Job Category</label><span id="asterisk" >*</span></th>
				<td><input type="radio"  name="jobCategory" value="faculty" class="radio" />Faculty&nbsp;
				 	<input type="radio"  name="jobCategory" value="staff" class="radio" />Staff
				 </td>
			</tr>
			
			<tr>
				<th><label for="officeNo">Office Number</label></th>
				<td><input type="text" name="officeNo" id="" value="" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="officeHours">Office Hours</label></th>
				<td><input type="text" name="officeHours" id="" value="" class="regular-text" /></td>
			</tr>
			
			<tr>
				<th><label for="bio">Bio </label></th>
				<td>
				<textarea rows="6" cols="70" name="bio"></textarea>
				</td>
			</tr>
			
			<tr>
				<th><label for="education">Education (Use a semicolon to seperate entries)</label></th>
				<td>
				<textarea rows="5" cols="70" name="education"></textarea>
				</td>
			</tr>
			
			<tr>
				<th><label for="selectPublications">Select Publications (Use a semicolon to seperate entries)</label></th>
				<td>
				<textarea rows="10" cols="70" name="selectPublications"></textarea>
				</td>
			</tr>
			
			<tr>
				<th><label for="awards">Awards (Use a semicolon to seperate entries)</label></th>
				<td>
				<textarea rows="8" cols="70" name="awards"></textarea></td>
			</tr>
			
			<tr>
				<th><label for="teachingAreas">Teaching Areas (Use a semicolon to seperate entries)</label></th>
				<td>
				<textarea rows="5" cols="70" name="teachingAreas"></textarea>
				</td>
			</tr>
			<tr>
				<th><label for="cv">CV (File Name)</label></th>
				<td ><input style="vertical-align:top;" type="text" name="cv" id="cv" class="regular-text" />
				<img height="200px" width="160px" name="facCV" src="" /></td>
			</tr>

			<tr>
				<th><label for="cvfile">Upload CV: (PDF prefered)</label></th>
				<td><input type="file" name="cvfile"  id="cvFile" 
						onchange="document.forms['insertEmpl']['cv'].value=(this.value);
									document.facCV.src=(this.value);
									" /></td>
			
			</tr>			
			<tr>
				<th><label for="pix">Photo (File Name)</label></th>
				<td ><input style="vertical-align:top;" type="text" name="pix" id="pix" value="blank.jpg" class="regular-text" />
				<img height="200px" width="160px" name="facPhoto" src="" /></td>
			</tr>

			<tr>
				<th><label for="file">Upload Photo:</label></th>
				<td><input type="file" name="file"  id="photoFile" 
						onchange="document.forms['insertEmpl']['pix'].value=(this.value);
									document.facPhoto.src=(this.value);
									" /></td>
			
			</tr>
			
			<tr>
				<th><label for="isDeptContact">This Person is the Department Contact</label></th>
				<td><input type="radio" name="isDeptContact" id="" value=1 />Yes
					<input type="radio" name="isDeptContact" id="" value=0 />No	</td>
			</tr>

                        <tr>
				<th><label for="isDeptHead">This Person is the Head of the Department </label></th>
				<td><input type="radio" name="isDeptHead" id="" value=1 />Yes
					<input type="radio" name="isDeptHead" id="" value=0 />No	</td>
			</tr>

			<tr>
				<th><label for="listFirst">List this Person First on Department Page</label></th>
				<td><input type="radio" name="listFirst" id="" value=1 />Yes
					<input type="radio" name="listFirst" id="" value=0 />No	</td>
			</tr>

			<tr>
				<th><label for="published">Publish on Web Site</label><span id="asterisk" >*</span></th>
				<td><input type="radio" name="published" id="" value=1 />Yes
					<input type="radio" name="published" id="" value=0 />No	</td>
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
	<h2><?php _e('COBE Personnel Management')?></h2>
	
	<?php 
	$imgPath = COBEURL . "/wp-content/themes/bsu-cobe-faculty/images/facultyImages/";
	$sortOrder = "lastName";
	if (isset($_GET['faculty'])) {
		$addWhere = " jobCategory='faculty'";
	}elseif (isset( $_GET['staff'])) {
		$addWhere = " jobCategory='staff'";
	}else{
		$addWhere = "";
	}
//	echo "List: ".$addWhere;
	// Pagination Info
	$apage = isset( $_GET['apage'] ) ? intval( $_GET['apage'] ) : 1;
	$num = isset( $_GET['num'] ) ? intval( $_GET['num'] ) : 15;
	$s = wp_specialchars( trim( $_GET[ 's' ] ) );
	$ss =  "%{$s}%";
	
	if (!empty($s)){
		$total = $wpdb->get_var( empty($addWhere)?
				 $wpdb->prepare("Select Count(employeeID) From wp_personnel Where lastName LIKE %s", $ss) :
				 $wpdb->prepare("Select Count(employeeID) From wp_personnel Where lastName LIKE %s and {$addWhere}", $ss));
	}else{
		$total = $wpdb->get_var(  empty($addWhere)? "Select Count(employeeID) From wp_personnel" :
													"Select Count(employeeID) From wp_personnel Where {$addWhere}");
	}
	
	if (isset($_GET['firstName'])) {$sortOrder = "firstName";}
	
	$query  = "Select wp_personnel.*, wp_depts.deptName From wp_personnel ";
	$query .= "Left Outer Join wp_depts on wp_personnel.deptID = wp_depts.deptID  ";
	$query .= "Left Outer Join wp_AdminAreas on wp_personnel.adminID = wp_AdminAreas.adminID  ";
	$query .= "Left Outer Join wp_Centers on wp_personnel.centerID = wp_Centers.centerID  ";
	if (isset($_GET['s']) && empty($addWhere)) {
		$query .= "Where lastName LIKE %s ";
	}elseif (isset($_GET['s']) && !empty($addWhere)) {
		$query .= "Where lastName LIKE %s and ".$addWhere;
	}elseif (!isset($_GET['s']) && !empty($addWhere)){
		$query .= "Where ".$addWhere;
	}
	$query .= " Order By ".$sortOrder;
	$query .= " LIMIT ". intval(($apage -1) * $num). ", ". intval($num);
//echo "<br />".$query."<br />";	
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
		<input type="hidden" name="page" value="personnel" />
		<input type="text" name="s" value="<?php if (isset($_GET['s'])) echo stripslashes( wp_specialchars( $s, 1 ) ); ?>" size="17" />
		<input type="submit" class="button" name="last_name" value="<?php _e('Search personnel by Last Name') ?>" />
	</form>
	
		
	<form action="admin.php" method="get" >
		<input type="hidden" name="page" value="personnel" />
		<input type="submit"  name="all" value="<?php _e('List All') ?>"
		<?php echo ($addWhere== "")?  "class='button-highlighted'": "class='button'";?> />
		<input type="submit"  name="faculty" value="<?php _e('Faculty Only') ?>"
		<?php echo (isset($_GET['faculty']) )?  "class='button-highlighted'": "class='button'";?> />
		<input type="submit"  name="staff" value="<?php _e('Staff only') ?>" 
		<?php echo (isset($_GET['staff']) )?  "class='button-highlighted'": "class='button'";?> />
		<input type="submit" value="<?php _e('Sort By Last') ?>" name="lastName" 
		<?php echo ($sortOrder=='lastName')?  "class='button-highlighted'": "class='button-secondary'";?> />
		<input type="submit" value="<?php _e('Sort By First') ?>" name="firstName" 
		<?php echo ($sortOrder=='firstName')?  "class='button-highlighted'": "class='button-secondary'";?> />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button"  name="addEmployee" value="<?php _e('Add Employee') ?>" 
			   class="button" onclick="location.href='admin.php?page=personnel&action=addEmployee'"/>
	</form>

	<form id="form-personnel-list" action="admin.php" method="post">

	<div class="tablenav">
		<?php if ( $blog_navigation ) echo "<div class='tablenav-pages'>$blog_navigation</div>"; ?>
	</div>
		
	<table width="100%" cellpadding="5" cellspacing="3" class="widefat">
		<tbody id="the-list" >
		<?php 
		$path = COBEURL . "/wp-content/plugins/cobe_admin/personnel";
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
			echo "Job Category: ".$row->jobCategory."<br /><br />";
			echo $row->email."<br />";
			echo "Office No: ".$row->officeNo."<br />";
			echo "Hours: ".$row->officeHours."<br>";
			echo "Phone: ".$row->phone."<br />";
			echo "Fax: ".$row->faxNo."<br />";
			echo "MS: ".$row->mailStop."<br />";
			echo "Web Site: ".$row->blogName."<br />";  
			echo "CV: ".$row->cv."<br />";
			echo "Photo: ".$row->pix;
			echo "<br /><br /><a href='admin.php?page=personnel&amp;action=editEmployee
					&amp;empID={$row->employeeID}' class='row-actions' >".__('Edit')." </a>"; 
			?>
			</td>
			<td>
			 <img src="<?php echo $imgPath.$row->pix ?>" />
			</td>
			<td width="20%" valign="top">
			<strong>Bio:</strong><br />
			<?php echo $row->bio; ?><br />
			<strong>Education:</strong> <br />
			<?php echo $row->education; ?><br />
			<strong>Select Publications:</strong><br />
			<?php echo $row->selectPublications; ?>
			</td>
			<td width="20%" valign="top"> 
			<strong>Awards:</strong> <br />
			<?php echo $row->awards; ?><br />
			<strong>Teaching Areas:</strong><br />
			<?php echo $row->teachingAreas; ?>
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
	<div class="tablenav">
		<?php if ( $blog_navigation ) echo "<div class='tablenav-pages'>$blog_navigation</div>"; ?>
	</div>
		
	</form>
</div> <!-- wrap -->	
<?php 
		break;
	}
}


?>