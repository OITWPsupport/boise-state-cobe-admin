<?php

//This page is using some functions located in themes/bsu-cobe-2/functions.php
function cobe_semesters() {
	global $wpdb;

	echo "<h2>Semester Management</h2>";

	$semesters = getSemesterList();
	$currentSemester = getCurrentSemester($semesters);
	$navCourse = $_GET['course'];
	$navSemester = $_GET['semester'];
	
	switch($_GET['action']) {
		
		case "viewSemesters":
		default:
			?>
			<div class="wrap" style="position:relative;">
			<h2><?php _e('COBE Semester Management')?></h2>
			
			<?php 
			$semRows = $semesters;

			// Pagination Info
			$apage = isset( $_GET['apage'] ) ? intval( $_GET['apage'] ) : 1;
			$num = isset( $_GET['num'] ) ? intval( $_GET['num'] ) : 25;
			

			$url2 = "&amp;sortby=" . $_GET['sort_by'];
			
			$blog_navigation = paginate_links( array(
				'base' => add_query_arg( 'apage', '%#%' ).$url2,
				'format' => '',
				'total' => ceil($total / $num),
				'current' => $apage
				));
			
			?>
			
			<form action="admin.php" method="get" name="semesterForm" >
				<input type="hidden" name="page" value="courses" />
				<input type="hidden" name="action" value="viewCourses" />

				<input type="button"  name="addSemester" value="Add Semester" 
					   class="button" onclick="location.href='admin.php?page=semesters&action=Add'"/>
			</form>
	<div class="tablenav">
		<?php if ( $blog_navigation ) echo "<div class='tablenav-pages'>$blog_navigation</div>"; ?>
	</div>

			<table  width="100%" cellpadding="5" cellspacing="1" class="widefat">
			  <tr>
	    		<th scope="col">ID</th>
	   			<th scope="col">Full Name</th>
	    		<th scope="col">Dates</th>
	    		<th scope="col">Exam Dates</th>
	    		<th scope="col">Notes</th>
	   			<th scope="col">Published</th>
	    		<th scope="col">Current</th>

	  		</tr>
	  		<?php foreach ($semRows as $row) {?>
				<tr>
				<td><?php echo $row->semID;
					_e("<br /><a href='admin.php?page=semesters&amp;action=editSemester
					&amp;semID={$row->semID}&amp;semNo={$row->semNo}' >".__('Edit')." </a>");?></td>
				<td><?php echo $row->semFullName;?></td>
				<td><?php echo $row->semDates; ?></td>
				<td><?php echo $row->placementExamDates;?></td>
				<td><?php echo $row->semNotes;?></td>
				<td><?php if ($row->semPublished == "1") {echo Yes;} elseif ($row->semPublished == "0") {echo No;}?></td>
				<td><?php if ($row->semCurrent == "1") {echo Yes;} elseif ($row->semCurrent == "0") {echo No;}?></td>
			<?php }?>
			
			</table>
			</div>
			<?php 
	  		
			break;
			
		case "editSemester":

			$semNo = $_GET['semNo'];
            $semID = $_GET['semID'];
			$semQuery = "SELECT * FROM wp_semester WHERE semNo=%d AND semID=%d";
			$semRows = $wpdb->get_results($wpdb->prepare($semQuery, $semNo, $semID));
//	print_r($semQuery);

			?>

			<div class="wrap" id="profile-page" >
			<h2><?php _e('Edit Semester'); ?></h2>
			<form action="admin.php?page=semesters&amp;action=updateSemester&amp;semNo=<?php echo $semNo;?>" method="post" 
					enctype="multipart/form-data" name="editSemester" id="your-profile" >
					
			<table style="text-align:left;">

				<tr>
	    		<th scope="row"><label for="semFullName">Full Name</label></th>
				<td><input type="text" name="semFullName" value="<?php echo $semRows[0]->semFullName;?>" />  Example:  Fall 1983</td></tr>
				<tr>
	    		<th scope="row"><label for="semID">ID</label></th>
				<td><input type="text" Name="semID" value="<?php echo $semRows[0]->semID;?>" />  Example:  FA83</td></tr>
				<tr>
	    		<th scope="row"><label for="semDates">Dates</label></th>
				<td><input type="text" name="semDates" value="<?php echo $semRows[0]->semDates;?>"
				style="width:400px; height:40px;" /></td></tr>
				<tr>
	    		<th scope="row"><label for="placementExamDates">Placement Exam Dates</label></th>
				<td><input type="text" name="placementExamDates" value="<?php echo $semRows[0]->placementExamDates;?>"
				style="width:400px; height:40px;" /></td></tr>
				<tr>
	   			<th scope="row"><label for="semNotes">Semester Notes</label></th>
				<td><input type="text" name="semNotes" value="<?php echo $semRows[0]->semNotes;?>"
				style="width:400px; height:40px;" /></td></tr>
	   			<tr>
	   			<th scope="row"><label for=semPublished">Published</label></th>
				<td><input type="radio" name="semPublished" 
				      <?php if ($semRows[0]->semPublished == "1") {echo CHECKED;}?> value="1">Yes
				    <input type="radio" name="semPublished" 
				      <?php if ($semRows[0]->semPublished == "0") {echo CHECKED;}?> value="0">No</td></tr>
				<tr>
					   			<tr>
	   			<th scope="row"><label for=semCurrent">Current</label></th>
				<td><input type="radio" name="semCurrent" 
				      <?php if ($semRows[0]->semCurrent == "1") {echo CHECKED;}?> value="1">Yes
				    <input type="radio" name="semCurrent" 
				      <?php if ($semRows[0]->semCurrent == "0") {echo CHECKED;}?> value="0">No</td></tr>
				</table>
			<p class="submit">
			<input class="button" type="submit" name="go" value="<?php _e('Update')?>"/></p>	
			</form>
			</div>
			<?php 
			break;
			
		case "updateSemester":
			
			$semester = cobeEscapePost($_POST);
			
			$updateQuery = "UPDATE wp_semester SET ";
			$updateQuery .= "semID = '".$semester['semID']."', ";
			$updateQuery .= "semFullName = '".$semester['semFullName']."', ";
			$updateQuery .= "semDates= '".$semester['semDates']."', ";
			$updateQuery .= "placementExamDates= '".$semester['placementExamDates']."', ";
			$updateQuery .= "semPublished= '".$semester['semPublished']."', ";
			$updateQuery .= "semCurrent= '".$semester['semCurrent']."', ";
			$updateQuery .= "semNotes= '".$semester['semNotes']."' ";						
			$updateQuery .= "WHERE semNo='".$_GET['semNo']."'"; 
//	echo $updateQuery."<br />";
	$result = $wpdb->query($updateQuery);
//	print_r($result);echo "<br />";
			
			?>
			<div id="message" class="updated fade"><p>
				<?php echo "The ".$semester['semFullName']." information was updated.";
			$viewSemesterLink = "</p>";
			$viewSemesterLink .= "<a href=\"admin.php?page=semesters&action=viewSemesters";
			$viewSemesterLink .= "\">Continue</a>";
			$viewSemesterLink .= "</div>";
			echo $viewSemesterLink; 
			break;
		
		case "Add":
			?>
			<div class="wrap" id="profile-page" >
			<h2><?php _e('Add Semester'); ?></h2>
			<form action="admin.php?page=semesters&amp;action=insertSemester" method="post" 
					enctype="multipart/form-data" name="Add" id="your-profile" >
					
			<table style="text-align:left;">

				<tr>
	    		<th scope="row"><label for="semFullName">Full Name</label></th>
				<td><input type="text" name="semFullName" value="<?php echo $semRows[0]->semID;?>" /> Example:  Fall 1983</td></tr>
				<tr>
	    		<th scope="row"><label for="semID">ID</label></th>
				<td><input type="text" Name="semID" value="<?php echo $semRows[0]->semID;?>" /> Example: FA83</td></tr>
				<tr>
	    		<th scope="row"><label for="semDates">Dates</label></th>
				<td><input type="text" name="semDates" value="<?php echo $semRows[0]->semDates;?>"
				style="width:400px; height:40px;" /></td></tr>
				<tr>
	    		<th scope="row"><label for="placementExamDates">Placement Exam Dates</label></th>
				<td><input type="text" name="placementExamDates" value="<?php echo $semRows[0]->placementExamDates;?>"
				style="width:400px; height:40px;" /></td></tr>
				<tr>
	   			<th scope="row"><label for="semNotes">Semester Notes</label></th>
				<td><input type="text" name="semNotes" value="<?php echo $semRows[0]->semNotes;?>"
				style="width:400px; height:40px;" /></td></tr>
	   			<tr>
	   			<th scope="row"><label for=semPublished">Published</label></th>
				<td><input type="radio" name="semPublished" 
				      <?php if ($semRows[0]->semPublished == "1") {echo CHECKED;}?> value="1">Yes
				    <input type="radio" name="semPublished" 
				      <?php if ($semRows[0]->semPublished == "0") {echo CHECKED;}?> value="0">No</td></tr>
				<tr>
					   			<tr>
	   			<th scope="row"><label for=semCurrent">Current</label></th>
				<td><input type="radio" name="semCurrent" 
				      <?php if ($semRows[0]->semCurrent == "1") {echo CHECKED;}?> value="1">Yes
				    <input type="radio" name="semCurrent" 
				      <?php if ($semRows[0]->semCurrent == "0") {echo CHECKED;}?> value="0">No</td></tr>
				</table>
			<p class="submit">
			<input class="button" type="submit" name="go" value="<?php _e('Update')?>"/></p>	
			</form>
			</div>
			<?php 
			break;
			
		case "insertSemester":
			$semester = cobeEscapePost($_POST);
			$insertQuery = "INSERT INTO wp_semester 
				(semID, semFullName, semDates, placementExamDates, semPublished, semCurrent, semNotes) VALUES( 
				'".$semester['semID']."', '".$semester['semFullName']."',
				'".$semester['semDates']."', '".$semester['placementExamDates']."',
				'".$semester['semPublished']."', '".$semester['semCurrent']."',
				'".$semester['semNotes']."')";

			$result = $wpdb->query($insertQuery);
			
						?>
			<div id="message" class="updated fade"><p>
				<?php echo "The ".$semester['semFullName']." information was added.";
			$viewSemesterLink = "</p>";
			$viewSemesterLink .= "<a href=\"admin.php?page=semesters&action=viewSemesters";
			$viewSemesterLink .= "\">Continue</a>";
			$viewSemesterLink .= "</div>";
			echo $viewSemesterLink; 
			break;

    }
}





?>