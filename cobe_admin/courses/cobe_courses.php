<?php

function cobe_courses() {
	global $wpdb;

	echo "<h2>Course Management</h2>";
	switch($_GET['action']) {
		
		case "viewCourses":
			?>
			<div class="wrap" style="position:relative;">
			<h2><?php _e('COBE Course Management')?></h2>
			
			<?php 
			$semQuery = "Select semID From wp_semester Where semPublished=1 ";
			$semRows = $wpdb->get_results($semQuery);
//print_r($_GET);
//			$search = (isset($_GET['course']))? $_GET['course']: "";
//echo isset($_GET['semester'])?"yes":"No";
//echo "--".strlen($_GET['semester'])."--<br />";
			$semester = $wpdb->escape($_GET['semester']);
			$course = $wpdb->escape($_GET['course']);

			$sortOrder = "wp_semester.semNo, wp_courses.crsCourse, wp_courses.crsSection";
			
			if ($semester != ""  &&   $course =="" ) {
				$addWhere = "  wp_courses.semID='{$semester}'";
			}elseif ($course != "" && $semester == "") {
				$addWhere = " wp_courses.crsCourse Like '%{$course}%'";
			}elseif  ($_GET['course'] != "" && $_GET['semester'] != "" ) {
				$addWhere = " wp_courses.crsCourse Like '%{$course}%' and  wp_courses.semID='{$semester}'";
			}else{
				$addWhere = "";
			}
//	echo "List: ".$addWhere;
			// Pagination Info
			$apage = isset( $_GET['apage'] ) ? intval( $_GET['apage'] ) : 1;
			$num = isset( $_GET['num'] ) ? intval( $_GET['num'] ) : 25;
			
			$total = $wpdb->get_var(  empty($addWhere)? "Select Count(courseID) From wp_courses" :
										"Select Count(courseID) From wp_courses Where {$addWhere}");

			$query  = "Select wp_courses.*, wp_semester.*, wp_course_title.crsTitle,  ";
			$query .= " wp_personnel.firstName, wp_personnel.lastName, wp_depts.deptAbbrev ";
			$query .= " From wp_courses ";
			$query .= "Inner Join wp_semester on wp_courses.semID = wp_semester.semID  ";
			$query .= "Inner Join wp_course_title on wp_courses.crsCourse = wp_course_title.crsCourse  ";
			$query .= "Inner Join wp_personnel on wp_courses.employeeID = wp_personnel.employeeID  ";
			$query .= "Inner Join wp_depts on wp_courses.deptID = wp_depts.deptID  ";
			if (!$addWhere == ""){
				$query .= "Where ".$addWhere;
			}
			$query .= " Order By ".$sortOrder;
			$query .= " LIMIT ". intval(($apage -1) * $num). ", ". intval($num);
//			echo $query;			
			$rows = $wpdb->get_results($query);
		/*	echo "<pre>";
			print_r($rows);
			echo "</pre>";
			*/
			$url2 = "&amp;sortby=" . $_GET['sort_by'];
			
			$blog_navigation = paginate_links( array(
				'base' => add_query_arg( 'apage', '%#%' ).$url2,
				'format' => '',
				'total' => ceil($total / $num),
				'current' => $apage
				));
			
			?>

			<form action="admin.php" method="get" name="courseForm" >
				<input type="hidden" name="page" value="courses" />
				<input type="hidden" name="action" value="viewCourses" />
				<label for="course">Filter by Course *</label>
				<input type="text"  name="course" value="<?php echo $_GET['course']?>"
						onchange="Javascript:document.courseForm.submit()" />
				&nbsp;&nbsp;&nbsp;
				<label for="semester">Filter by Semester ^</label>
				<select name="semester" onchange="Javascript:document.courseForm.submit()">
					<option  value=""  >Select a Semester</option>
					<?php
					foreach ($semRows as $semRow) {
						echo("<option value ='".$semRow->semID."' ");
						if(isset($_GET['semester']) && $_GET['semester'] == $semRow->semID ){ 
							echo "selected='selected'>".$semRow->semID."</option>";
						}else {
							echo	">".$semRow->semID."</option>";
						}
					}
					?>
				</select>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="button"  name="addCourse" value="Add Course" 
					   class="button" onclick="location.href='admin.php?page=courses&action=singleAdd'"/>
			</form>
	<div class="tablenav">
		<?php if ( $blog_navigation ) echo "<div class='tablenav-pages'>$blog_navigation</div>"; ?>
	</div>

			<table  width="100%" cellpadding="5" cellspacing="1" class="widefat">
			  <tr>
	    		<th scope="col">Semester^</th>
	   			<th scope="col">Instructor</th>
	    		<th scope="col">Course *</th>
	    		<th scope="col">Section</th>
	   			<th scope="col">Title</th>
	    		<th scope="col">Times</th>
	    		<th scope="col">Dept.</th>
	   			<th scope="col">Room</th>
	   			<th scope="col">Syllabus</th>
	   			<th scope="col">Course Web</th>
	   			<th scope="col">Notes</th>
	  		</tr>
	  		<?php foreach ($rows as $row) {?>
				<tr>
				<td><?php echo $row->semID;?></td>
				<td><?php echo $row->firstName." ".$row->lastName ?></td>
				<td><?php echo $row->crsCourse;
					_e("<br /><a href='admin.php?page=courses&amp;action=editCourse
					&amp;crsCourse={$row->crsCourse}&amp;crsSection={$row->crsSection}&amp;semNo={$row->semID}' 
					class='row-actions' >".__('Edit')." </a>"); 
				
				?></td>
				<td><?php echo $row->crsSection;?></td>
				<td><?php echo $row->crsTitle;?></td>
				<td><?php echo $row->crsTimes;?></td>
				<td><?php echo $row->deptAbbrev;?></td>
				<td><?php echo $row->crsRoom;?></td>
				<td><?php echo $row->crsSyllabus;?></td>
				<td><?php echo $row->crsWeb;?></td>
				<td><?php echo $row->crsNotes;?></td></tr>
			<?php }?>
			
			</table>
			</div>
			<?php 
	  		
			break;
			
		case "editCourse":
//	print_r($_GET);
			$crsCourse = $wpdb->escape($_GET['crsCourse']);
			$crsSection = $wpdb->escape($_GET['crsSection']);
			$semNo = $wpdb->escape($_GET['semNo']);

			$semQuery = "Select semID From wp_semester Where semPublished=1 ";
			$semRows = $wpdb->get_results($semQuery);

			$facQuery = "Select employeeID, firstName, lastName From wp_personnel Where jobCategory='faculty' ";
			$facRows = $wpdb->get_results($facQuery);
			
			$deptQuery = "Select deptID, deptAbbrev From wp_depts ";
			$deptRows = $wpdb->get_results($deptQuery);
			
			$query = "Select * From wp_courses Where ";
			$query .= " crsCourse='".$crsCourse."' and crsSection='".$crsSection;
			$query .= "' and semID='".$semNo."'";
			$courseData = $wpdb->get_results($query);
			
//	print_r($deptRows);
			?>

			<div class="wrap" id="profile-page" >
			<h2><?php _e('Edit Course'); ?></h2>
			<form action="admin.php?page=courses&amp;action=updateCourse" method="post" 
					enctype="multipart/form-data" name="editCourse" id="your-profile" >
					
			<table style="text-align:left;">
				<tr>
	    		<th scope="row"><label for="semID">Semester</label></th>
				<td><select name="semID" >
					<?php foreach ($semRows as $sem) {?>
					<option value="<?php echo $sem->semID; ?>" 
							<?php echo ($courseData[0]->semID == $sem->semID)?" selected='selected'":"";?> >
							<?php echo $sem->semID;?>
					</option>
					<?php } ?>
				</select></td></tr>
				<tr>
	   			<th scope="row"><label for="employeeID">Instructor</label></th>
				<td><select name="employeeID">
					<?php foreach ($facRows as $fac) {?>
					<option value="<?php echo $fac->employeeID; ?>"
							<?php echo ($fac->employeeID == $courseData[0]->employeeID)?" selected='selected'":"";?> >
							<?php echo $fac->firstName." ".$fac->lastName;?>
					</option>
					<?php } ?>
				</select></td></tr>
				<tr>
	    		<th scope="row"><label for="crsCourse">Course</label></th>
				<td><input type="text" name="crsCourse" value="<?php echo $courseData[0]->crsCourse;?>" /></td></tr>
				<tr>
	    		<th scope="row"><label for="crsSection">Section</label></th>
				<td><input type="text" Name="crsSection" value="<?php echo $courseData[0]->crsSection;?>" /></td></tr>
				<tr>
	    		<th scope="row"><label for="crsTimes">Times</label></th>
				<td><input type="text" name="crsTimes" value="<?php echo $courseData[0]->crsTimes;?>" /></td></tr>
				<tr>
	    		<th scope="row"><label for="deptID">Dept.</label></th>
				<td><select name="deptID" >
					<?php foreach ($deptRows as $dept) {?>
					<option value="<?php echo $dept->deptID; ?>" <?php echo ($dept->deptID == $courseData[0]->deptID)?" selected='selected'":"";?> >
							<?php echo $dept->deptAbbrev;?>
					</option>
					<?php } ?>
				</select></td></tr>
				<tr>
	   			<th scope="row"><label for="crsRoom">Room</label></th>
				<td><input type="text" name="crsRoom" value="<?php echo $courseData[0]->crsRoom;?>" /></td></tr>
				<tr>
	   			<th scope="row"><label for="crsSyllabus">Syllabus</label></th>
				<td><input type="text" name="crsSyllabus" value="<?php echo $courseData[0]->crsSyllabus;?>" /></td></tr>
				<tr>
				<th><label for="file"><?php _e('Upload Syllabus:') ?></label></th>
				<td><input type="file" name="file"  id="syllFile" 
						onchange="document.forms['editCourse']['crsSyllabus'].value=(this.value);" /></td>
				</tr>
				<tr>
	   			<th scope="row"><label for="crsWeb">Course Web</label></th>
				<td><input type="text" name="crsWeb" value="<?php echo $courseData[0]->crsWeb;?>" /></td></tr>
				<tr>
	   			<th scope="row"><label for="crsNotes">Notes</label></th>
				<td><input type="text" name="crsNotes" value="<?php echo $courseData[0]->crsNotes;?>" /></td></tr>
			</table>
			<input type="hidden" name="courseID" value="<?php echo $courseData[0]->courseID; ?>" />
			<p class="submit">
			<input class="button" type="submit" name="go" value="<?php _e('Update')?>"/></p>	
			</form>
			</div>
			<?php 
			break;
			
		case "updateCourse":
			$course = cobeEscapePost($_POST);
//			print_r($_FILES);
			$syllDir = COBEPATH . "/courses/syllabi/" . $course['semID'];
			//echo $syllDir;
			$upload = cobeUpload($_FILES, $syllDir, '', 'file');
			
			if ($upload === true)
				echo cobeWPMessage($_FILES['file']['name'] . ' was successfully uploaded.');
			else
				echo cobeWPMessage($upload, 'error');

			$updateQuery = "Update wp_courses Set";
			$updateQuery .= " employeeID = '".$course['employeeID']."', ";
			$updateQuery .= "deptID = '".$course['deptID']."', ";
			$updateQuery .= "crsCourse = '".$course['crsCourse']."', ";
			$updateQuery .= "crsSection = '".$course['crsSection']."', ";
			$updateQuery .= "crsTimes = '".$course['crsTimes']."', ";
			$updateQuery .= "crsRoom = '".$course['crsRoom']."', ";
			$updateQuery .= "crsSyllabus = '".$course['crsSyllabus']."', ";					
			$updateQuery .= "crsWeb = '".$course['crsWeb']."', ";
			$updateQuery .= "crsNotes = '".$course['crsNotes']."', ";
			$updateQuery .= "semID = '".$course['semID']."' Where courseID='".$course['courseID']."'"; 
//	echo $updateQuery."<br />";
	$result = $wpdb->query($updateQuery);
//	print_r($result);echo "<br />";
			
			?>
			<div id="message" class="updated fade"><p>
				<?php echo $course['crsCourse']."-".$course['crsSection']." for ".$course['semID']." information was updated.";?>
			</p>
			<a href="admin.php?page=courses&action=viewCourses">Continue</a>
			</div> <?php 
			break;
		
		case "singleAdd":
			$semQuery = "Select semID, semCurrent From wp_semester Where semPublished=1 ";
			$semRows = $wpdb->get_results($semQuery);

			$facQuery = "Select employeeID, firstName, lastName From wp_personnel Where jobCategory='faculty' Order By firstName";
//echo $facQuery."<br />";
			$facRows = $wpdb->get_results($facQuery);
			
			$deptQuery = "Select deptID, deptAbbrev From wp_depts ";
			$deptRows = $wpdb->get_results($deptQuery);
			?>
			<div class="wrap" id="profile-page" >
			<h2>Add Course</h2>
			<form action="admin.php?page=courses&amp;action=insertCourse" method="post" 
					enctype="multipart/form-data" name="addCourse" id="your-profile" >
					
			<table style="text-align:left;">
				<tr>
	    		<th scope="row"><label for="semID">Semester</label></th>
				<td><select name="semID" >
					<?php foreach ($semRows as $sem) {?>
					<option value="<?php echo $sem->semID; ?>" 
							<?php echo ($sem->semCurrent)?" selected='selected'":"";?> >
							<?php echo $sem->semID;?>
					</option>
					<?php } ?>
				</select></td></tr>
				<tr>
	   			<th scope="row"><label for="employeeID">Instructor</label></th>
				<td><select name="employeeID">
					<?php foreach ($facRows as $fac) {?>
					<option value="<?php echo $fac->employeeID; ?>" >
							<?php echo $fac->firstName." ".$fac->lastName;?>
					</option>
					<?php } ?>
				</select></td></tr>
				<tr>
	    		<th scope="row"><label for="crsCourse">Course</label></th>
				<td><input type="text" name="crsCourse" value="" /></td></tr>
				<tr>
	    		<th scope="row"><label for="crsSection">Section</label></th>
				<td><input type="text" Name="crsSection" value="" /></td></tr>
				<tr>
	    		<th scope="row"><label for="crsTimes">Times</label></th>
				<td><input type="text" name="crsTimes" value="" /></td></tr>
				<tr>
	    		<th scope="row"><label for="deptID">Dept.</label></th>
				<td><select name="deptID" >
					<?php foreach ($deptRows as $dept) {?>
					<option value="<?php echo $dept->deptID; ?>"  >
							<?php echo $dept->deptAbbrev;?>
					</option>
					<?php } ?>
				</select></td></tr>
				<tr>
	   			<th scope="row"><label for="crsRoom">Room</label></th>
				<td><input type="text" name="crsRoom" value="" /></td></tr>
				<tr>
	   			<th scope="row"><label for="crsSyllabus">Syllabus</label></th>
				<td><input type="text" name="crsSyllabus" value="" /></td></tr>
				<tr>
				<th><label for="file">Upload Syllabus</label></th>
				<td><input type="file" name="file"  id="syllFile" 
						onchange="document.forms['addCourse']['crsSyllabus'].value=(this.value);" /></td>
				</tr>
				<tr>
	   			<th scope="row"><label for="crsWeb">Course Web</label></th>
				<td><input type="text" name="crsWeb" value="" /></td></tr>
				<tr>
	   			<th scope="row"><label for="crsNotes">Notes</label></th>
				<td><input type="text" name="crsNotes" value="" /></td></tr>
			</table>
			<input type="hidden" name="courseID" value="" />
			<p class="submit">
			<input class="button" type="submit" name="go" value="Update"/></p>	
			</form>
			</div>
			<?php 
			break;
			
		case "insertCourse":
			$course = cobeEscapePost($_POST);
//			print_r($_FILES);
			$syllDir = COBEPATH . "/courses/syllabi/".$course['semID'];
			$upload = cobeUpload($_FILES, $syllDir, '', 'file');
			
			if ($upload === true)
				echo cobeWPMessage($_FILES['file']['name'] . ' was successfully uploaded.');
			else
				echo cobeWPMessage($upload, 'error');

			$insertQuery = "INSERT INTO wp_courses 
				(employeeID, deptID, crsCourse, crsSection, crsTimes,
				 crsRoom, crsSyllabus, crsWeb, crsNotes, semID) VALUES( 
				'".$course['employeeID']."', '".$course['deptID']."',
				'".$course['crsCourse']."', '".$course['crsSection']."',
				'".$course['crsTimes']."', '".$course['crsRoom']."',
				'".$course['crsSyllabus']."', '".$course['crsWeb']."',
				'".$course['crsNotes']."', '".$course['semID']."')"; 
//			echo $insertQuery."<br />";
			$result = $wpdb->query($insertQuery);
			
			break;

		case "import":
			
			$querySem = "Select semNo, semID From wp_semester Where semPublished = 1";
			$semesters = $wpdb->get_results($querySem);
//			print_r($semesters);
			if (!current_user_can('upload_files'))
				wp_die(__('You do not have permission to upload files.'));
			?>
			<h3><?php _e('Import Excel Course File')?></h3>
			<hr />
			<div class="wrap">
			<h3><?php _e('Step 1:Import Courses')?></h3>
			<form action="admin.php?page=courses&amp;action=import" method="post" enctype="multipart/form-data" >
				<p><label for="file">Enter Excel 2007 File:</label>
				<input type="file" name="file" /></p>
				(i.e. - FA08AcctCourses.xslx)<br /><br />
				<input type="submit" value="Upload File" />
			</form>
			<?php 
//			print_r($_FILES['file']);
			$upload = cobeUpload($_FILES, COBEPATH . "/courses/Files");
			
			if ($upload === true)
				echo cobeWPMessage($_FILES['file']['name'] . ' was successfully uploaded.');
			else
				echo cobeWPMessage($upload, 'error');
			?>
			<hr />
			<h3><?php _e('Step 2:')?></h3>
			<form action="admin.php?page=courses&amp;action=viewImport" method="post" id="load-files" >
				
				<p><label for="semID">Select Semester:</label>
				<select name="semID" >
					<?php foreach ($semesters as $sem) {?>
					<option value="<?php echo $sem->semID; ?>" >
							<?php echo $sem->semID;?>
					</option>
					<?php } ?>
				</select>
				</p>
				<input type="hidden" name="page" value="courses" />
				<input type="hidden" name="file" value="<?php _e($_FILES['file']['name']) ?>" />
				<p class="submit">
				<input class="button" type="submit" name="go" value="<?php _e('Go')?>"/></p>	
				
			</form>
			</div>
<?php 			
		break;
		
	case "viewImport":
		$file =  $_POST['file'];
		$semID = $_POST['semID'];
		define( 'COM_FILE_BASE', dirname(__FILE__) . '/Files' );
		$filePath =  COM_FILE_BASE ;
/*		echo "<pre>";
		print_r($filePath);
		echo "</pre>"; */
		$path = dirname(__FILE__);
		set_include_path(get_include_path(). PATH_SEPARATOR . $path );
		/** PHPExcel_IOFactory */
		require_once("PHPExcel/IOFactory.php");
		
		$loadFile = $filePath."/".$file;
	//echo $loadFile;
		if (!file_exists($loadFile)) {
			exit("File ".$loadFile." not found.\n");
		}
		
//		echo date('H:i:s') . " Loading Excel 2007 file ".$file."\n";
		$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		$objPHPExcel = $objReader->load($loadFile);
		$sheetCount = $objPHPExcel->getSheetCount();
//		echo $sheetCount."<br />";
		$courses = Array();
		
		$objPHPExcel->setActiveSheetIndex(0);
		$sheet = $objPHPExcel->getActiveSheet();
			
		$rows = $sheet->getHighestRow();
//	echo $rows."<br />";
		$columns = $sheet->getHighestColumn();
		$title = $sheet->getTitle();
		if (!isset($deptArea))
			$deptArea = $sheet->getCell("A".(1))->getValue();
		//			echo "<br />".$title.", Rows: ".$rows.", Col: ".$columns."  Sheet Count: ".$sheetCount;
		parseSheet($sheet, $rows, $courses, $semID);
		
		$deptQuery = "Select deptID From wp_dept_area Where areaAbbrev = %s";
//		echo $deptQuery."<br />";
		$return = $wpdb->get_results($wpdb->prepare($deptQuery, $deptArea));
		$deptID = $return[0]->deptID;
		
/*		echo "<pre>";
		  print_r($courses);
		echo "</pre>"; */
		
		$count = sizeOf($courses); 
		?>
		<h3><?php _e('Courses Imported')?></h3>
		
		<div class="wrap">
		<form action="admin.php?page=courses&amp;action=batchAdd" method="post" name="viewCoursesForm" 
			id="admin-form" >
		
		
		<h3><?php _e('Verify these are the correct entries before selecting - SAVE!')?></h3>
		<p id="info"><?php _e('<font color="red">*</font> If there is no ID number after the instructor name,
				there is no entry for that faculty member.<br /> Please add that instructor into Personnel first or 
				this procedure will fail. An Employee ID number is required!')?></p>
		<table  width="100%" cellpadding="5" cellspacing="1" class="widefat">
		  <tr>
    		<th scope="col">Course</th>
    		<th scope="col">Section</th>
   			<th scope="col">Instructor</th>
   			<th scope="col">ID <font color="red">*</font></th>
    		<th scope="col">Times</th>
   			<th scope="col">Room</th>
    		<th scope="col">Semester</th>
  		</tr>

		
		<?php
		$i = 0;		
		foreach($courses as $course )
		{ ?>
			<tr><td><input type='text' size="12" name='courses[<?php echo $i; ?>][crsCourse]' 
					value="<?php echo $course['crsCourse'];?>" /></td>
			<td><input type='text' size= "5" name='courses[<?php echo $i;?>][crsSection]' 
					value="<?php echo $course['crsSection'];?>" /></td>
			<td><input type='text'name='courses[<?php echo $i;?>][faculty]' 
					value="<?php echo $course['faculty'];?>" /></td>
			<td><input type='text' size="12" name='courses[<?php echo $i;?>][employeeID]' 
					value="<?php echo $course['employeeID'];?>" /></td>
			<td><input type='text' size="25" name='courses[<?php echo $i;?>][crsTimes]' 
					value="<?php echo $course['crsTimes'];?>" /></td>
			<td><input type='text' size="10" name='courses[<?php echo $i;?>][crsRoom]' 
					value="<?php echo $course['crsRoom'];?>" /></td>
			<td><input type='text' size="5" name='courses[<?php echo $i;?>][semID]' 
					value="<?php echo $course['semID'];?>" />
				<input type='hidden' name='courses[<?php echo $i;?>][deptID]' 
					value="<?php echo $deptID;?>" /></td></tr>
		<?php $i++;	
		}
		 ?>
		</table>
		<input type="hidden" name="file" value="<?php echo $file ?>" />
		<p class="submit">
		<input class="button" type="submit" name="go" value="Save"/></p>	
		
		</form>
	</div>
		
		<?php 
		break;
		
	case "batchAdd":
		if( empty( $_POST ) )
			wp_die( __('You probably need to go back to the <a href="admin.php?page=personnel">personnel page</a>') );
//				print_r($_POST);
			$post_array = $_POST['courses'];
			$file = $_POST['file'];
			$continue = validatePost($post_array);
//	echo "Continue ".$continue;
			
			if ($continue == "Added" )
			{
				?>
				<div id="message" class="updated fade"><p>
					<?php _e('Courses from '.$file.' added.') ?>
				</p>
				<a href="admin.php?page=courses">Continue</a>
				</div> <?php 
			}else{ ?>
				<div id="message" class="updated fade"><p>
					<?php _e('Course(s) from '.$file.' were not added.<br /><br />');
					 foreach ($continue as $missing){
						_e($missing."<br />");
					 }
					echo "<br /><br />Please add those faculty to Personnel.";
					?>
				<a href="admin.php?page=courses">Return to Courses</a>&nbsp;&nbsp;
				<a href="admin.php?page=personnel">Go to Personnel</a>
				</p></div>
				<?php 	
			}
		break;
	
	default:
?>
	
	<div class="wrap" >
	
		<form action="admin.php?page=courses" method="post" id="admin-form" >
			<input type="hidden" name="page" value="courses" /><br />
			<label for="view"><?php _e('To Edit/Add Courses:') ?></label><br />
			<input type="button" name="view" value="<?php _e('View Courses') ?>" 
			   class="button" onclick="location.href='admin.php?page=courses&action=viewCourses'"/><br /><br />
			<label for="view"><?php _e('To Import Courses From an Excel Spreadsheet:') ?></label><br />
			<input type="button" name="import" value="<?php _e('Import From Excel') ?>" 
			   class="button" onclick="location.href='admin.php?page=courses&action=import'"/>
		</form>
		<br />
		<div style="border: 5px ridge #000000;" >
			<h2>Instructions</h2>
			<p>To Import a list of courses, go to and log in to <strong>http://broncoweb.boisestate.edu</strong>.
			In <em>Self Service/Student Center</em> select the 
			<em>SEARCH FOR CLASSES</em> button. Now click the <em>View Course Listing by
			Subject</em> link. Select the correct Semester and then one of the correct 
			<strong>Subject Areas (ACCT, ECON, SCM, BUSSTAT, etc)</strong>. Once the 
			list is created (see below), select everything from the Class header down 
			to the last item of the last row. (NOTE: If you do not select the column headers, 
			Excel will paste everything	into the first cell.) Copy &amp; paste (Right-Click on the first cell - A1 -
			and select <em>Paste Special</em>. Select <em>Text</em>.) the list to 
			<strong>Excel 2007</strong>. Delete the first three rows that contain the column headers. They 
			are only needed to get Excel to pate as a table. Each subject area can be on its own worksheet 
			or all subject areas can be on one worksheet. The <strong>Cap, Total Enroll, and Seats Avail</strong> 
			fields do not need to be included but if they are, it will not effect
			import. Ensure that the <strong>Room Number</strong> is included, though.</p>
			
			<img src ="<?php echo COBEURL; ?>/wp-content/plugins/cobe_admin/courses/courses_batch.jpg" />
		</div>
	</div>

<?php 


	}
}

function parseSheet($sheet, $rows, &$courses,  $semID)
{
	for ($i = 1; $i <= $rows; $i++)
	{
		$courses[$i-1]['crsCourse'] = trim($sheet->getCell("A".($i))->getValue()).
						trim($sheet->getCell("B".($i))->getValue());
		$tempSect = $sheet->getCell("C".($i))->getValue();
		
		// Format string with leading "0" characters, "001",
		if (strlen($tempSect) >= 3) 
		{
			$courses[$i-1]['crsSection'] = $tempSect;
		}elseif (strlen($tempSect) == 2){
			$courses[$i-1]['crsSection'] ="0".$tempSect ;
		}else{
		 $courses[$i-1]['crsSection'] ="00".$tempSect ;
		}
		$courses[$i-1]['faculty'] = $sheet->getCell("E".$i)->getValue();
		$courses[$i-1]['employeeID'] = getFacId($sheet->getCell("E".$i)->getValue()) ;
		$courses[$i-1]['crsTimes'] = $sheet->getCell("F".$i)->getValue()." ".$sheet->getCell("G".$i)->getValue().
								   " - ".$sheet->getCell("H".$i)->getValue();
		$courses[$i-1]['crsRoom'] = $sheet->getCell("J".$i)->getValue();
		$courses[$i-1]['semID'] = $semID;
		
		
	}
	
}


//Associates FacultyID to lastName
function getFacId( $faculty ) {
	global $wpdb;
	
	$lastFirst = split(",", $faculty);
	$first = $wpdb->escape(split(" ", ltrim($lastFirst[1])));
	
	$last = $wpdb->escape($lastFirst[0]);
	$nameQuery = 'SELECT employeeID FROM wp_personnel ';
	$nameQuery .= 'WHERE firstName ="'. trim($first[0]).'" AND lastName ="'.$last.'"';
//return $nameQuery;
	$rows = $wpdb->get_results($nameQuery);
/*	echo "<pre>";
	  print_r($rows);
	echo "</pre>"; */
	if ($rows != null){
		$row = $rows[0];
		return $row->employeeID; 
	}else{
		return NULL;
	}
}

function validatePost( $postArray )
{
	global $wpdb;
	$possibles= Array();

/*echo "<pre>";
print_r($postArray);
echo "</pre>";
*/
	// Check all first. Record possible names and return those.
	foreach ($postArray as $course)
	{
//	echo $course["faculty"]."<br />";
		
		if ($course["employeeID"] == "")
		{
			$pos = strpos($course["faculty"], ",");
			$last = substr($course["faculty"],0,$pos);
//	echo "  -  ".$last."<br />";	
			$idQuery = "SELECT firstName, lastName, employeeID FROM wp_personnel ";
			$idQuery .= "WHERE lastName LIKE %s";
//	echo $idQuery."<br />";	
			$rows = $wpdb->get_results($wpdb->prepare($idQuery, "%$last%"));
			if ($rows != null)
			{ //print_r($rows);
				foreach ($rows as $row){
					$possibles[$p] = "Employee ID: ".$row->employeeID." - Name: ".$row->firstName.
									" ".$row->lastName." could be the same as ".$course['crsCourse']." - ".$course['faculty'];
					$p++;
				}
				
			}else{
				$possibles[$p] = "Course ".$course["crsCourse"]."-".$course["crsSection"].
							" -- ".$course["faculty"]."was not Found in the Personnel Database<br />";
				$p++;
			}
			
		}
	}
	if (!empty($possibles)){
		return $possibles;
	}
	
	//Do all if passes
	foreach ($postArray as $course)
	{
		addCourse($course);
	}
	return "Added";
}


function addCourse($course) {
	// update personnel table
	global $wpdb;
	$course = cobeEscapePost($course);
	
	$insertQuery = "INSERT INTO wp_courses 
		(employeeID, deptID, crsCourse, crsSection, crsTimes, crsRoom, semID) VALUES( 
		'".$course['employeeID']."', '".$course['deptID']."',
		'".$course['crsCourse']."', '".$course['crsSection']."',
		'".$course['crsTimes']."', '".$course['crsRoom']."',
		'".$course['semID']."')"; 
//	echo $insertQuery."<br />";
	$result = $wpdb->query($insertQuery);
//	print_r($result);echo "<br />";


	
}


?>