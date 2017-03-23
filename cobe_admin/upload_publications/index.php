<?php

function cobe_uploadp() {
    global $wpdb;
    require_once 'functions.php';

    switch ($_GET['action']) {

        case 'manage':
            $search = $_POST['search'];
?>
            <h2>AACSB Publications</h2>
            <p><a href="admin.php?page=upload_publications">Upload a Publication</a></p>
            <form action="admin.php?page=upload_publications&amp;action=manage"
                  method="post" enctype="multipart/form-data">
                <input type="hidden" name="last_emp" value="<?php _e($row->employeeID) ?>" />

           Search by Last Name: &nbsp;<input name="search" type="text" />
                <input type="submit" value= "Search" />
                <table class="form-table">

        <?php
            $lastFac = "";
            $count = 1;
            // for the delete or edit images
            $imgPath = COBEURL . "/wp-content/plugins/cobe_admin/upload_publications/";
//echo "Search ".$search;
            $rows = getFacultyPubsList($search);

            foreach ($rows as $row) {

                if ($lastFac != $row->employeeID) {

                    _e("<tr><td colspan='7'> <h3>" . $row->displayName . "</h3></td></tr>");
                    
                }
        ?>
                <tr>
                <input type="hidden" name="pid<?php _e($row->id) ?>" value="<?php _e($row->id) ?>" />
                <td><?php _e($row->pub_order) ?> </td>
                <td><?php _e($row->title) ?></td>
                <td><?php _e($row->pub_year) ?></td>
                <td><?php _e($row->journal) ?></td>
                <td><?php _e($row->aacsb_pubs) ?></td>
                <td><a href="admin.php?page=upload_publications&action=edit&pid=<?php _e($row->id) ?> ">
                        <img alt="Edit" src="<?php _e($imgPath) ?>edit.png"   /></a>
                </td>
                <td><a href="admin.php?page=upload_publications&action=delete&pid=<?php _e($row->id) ?> "
                       onclick="return confirm('Are you sure you want to delete this Pub lication?')" >
                        <img alt="Delete" src="<?php _e($imgPath) ?>delete.png" /></a></td>
                </tr>
        <?php
                $lastFac = $row->employeeID;
            }
        ?>
        </table>
    </form>
<?php
            break;
        case edit:
//            print_r($_GET);
            $pid = $_GET['pid'];
//echo $pid;
            $query = "Select wp_aacsb_pubs.*, wp_personnel.displayName ";
            $query .= "from wp_aacsb_pubs left join wp_personnel on ";
            $query .= "wp_aacsb_pubs.employeeID = wp_personnel.employeeID where id= %d";
//            echo $query . "<br />";
            $info = $wpdb->get_results($wpdb->prepare($query, $pid));

//            print_r($info);
?>

            <div class="wrap" id="profile-page" >
                <h2>Edit Employee</h2>
                <h3><?php _e($info[0]->displayName) ?></h3>
                <form action="admin.php?page=upload_publications&amp;action=updatePub" method="post"
                      enctype="multipart/form-data" name="editPub" id="your-profile" >
                    <input type="hidden" name="id" value="<?php _e($info[0]->id) ?>" />
                    <input type="hidden" name="employeeID" value="<?php _e($info[0]->employeeID) ?>" />
                    <table class="form-table">
                        <tr>
                            <td><label class="description" for="pub_order">Publication Order from FQ</label>
                                <input id="pub_order" name="pub_order" class="element text medium"
                                       type="text" size="2" maxlength="2" value="<?php _e($info[0]->pub_order) ?>"/></td>
                        </tr>
                        <tr>
                            <td><label class="description" for="title">Title</label>
                                <input id="title" name="title" class="element text medium"
                                       type="text" size="100" maxlength="300" value="<?php _e($info[0]->title) ?>"/></td>
                        </tr>
                        <tr>
                            <td><label class="description" for="year">Year Published</label>
                                <input id="year" name="year" class="element text medium"
                                       type="text" size="4" maxlength="4" value="<?php _e($info[0]->pub_year) ?>"/></td>
                        </tr>
                        <tr>
                            <td><label class="description" for="journal">Journal Name</label>
                                <input id="journal" name="journal" class="element text medium"
                                       type="text" size="50" maxlength="50" value="<?php _e($info[0]->journal) ?>"/></td>
                        </tr>
                        <tr>
                            <td><label class="description" for="aacsb_pubs">Current File Name</label>
                                <input id="aacsb_pubs" name="aacsb_pubs" class="element text medium"
                                       type="text" size="50" maxlength="50" value="<?php _e($info[0]->aacsb_pubs) ?>"/></td>
                        </tr>
                        <tr>
                            <td><label class="description" for="aacsb_file">Upload a PDF</label>
                                <input type="file" name="aacsb_file"
                                       onchange="document.forms['editPub']['aacsb_pubs'].value=(this.value);"/> </td>
                        </tr>
                        <tr>
                            <td>
                                <input  type="submit" name="submit" value="Submit" /> 
                                <input type="button" name="Cancel" value="Cancel"
                                       onclick="window.location = 'admin.php?page=upload_publications&amp;action=manage' " />
                            </td>
                        </tr>

                    </table>
                </form>
            </div>
<?php
//            echo("Edited. <a href=\"admin.php?page=upload_publications&action=manage\">Click here to return.</a>");

            break;
        case 'delete':
//            print_r($_GET);
            $pid = $_GET['pid'];

            $query = "DELETE FROM wp_aacsb_pubs where id = %d ";
//  echo "<br />".$query;
           $result = $wpdb->query($wpdb->prepare($query, $pid));

            _e("Removed. <a href=\"admin.php?page=upload_publications&action=manage\">
                Click here to return.</a>");
            break;



        case 'updatePub':

            $dirPath = ABSPATH . "/wp-content/themes/bsu-cobe-accr/aacsbPubs/";
            if (empty($_POST))
                wp_die(__('You probably need to go back to the <a href="admin.php?page=upload_publications">AACSB Uploads page</a>'));
				
			//cobeUpload parameters: $files, $uploadDir, $fileName = '', $uploadElement = '', $fileTypes = array()
			$pubUpload = cobeUpload($_FILES, $dirPath);
			
			if ($pubUpload === true)
				echo cobeWPMessage($_FILES['aacsb_file']['name'] . ' was successfully uploaded.');
			else
				echo cobeWPMessage($pubUpload, 'error');
			
			$_POST = cobeEscapePost($_POST);

            $insertQuery = "UPDATE wp_aacsb_pubs SET
				employeeID  = '" . $_POST['employeeID'] . "',
				title       = '" . $_POST['title'] . "',
                                pub_year        = '" . $_POST['year'] . "',
                                journal     = '" . $_POST['journal'] . "',
                                aacsb_pubs  = '" . $_POST['aacsb_pubs'] ."',
                                pub_order  = '" . $_POST['pub_order'] ."'
                                where id = '".$_POST['id']."'" ;
 //           	echo $insertQuery;
           $result = $wpdb->query($insertQuery);
           _e ("Edit of the record was successful.
               <a href='admin.php?page=upload_publications&action=manage'>Continue</a> ");
?>  
<?php
            break;
        case 'insert':
            $dirPath = ABSPATH . "/wp-content/themes/bsu-cobe-accr/aacsbPubs/";
            $query = "Select displayName from wp_personnel where employeeID = '" . $wpdb->escape($_POST['employeeID']) . "'";
            $displayName = $wpdb->get_var($query);
            /* echo "displayName: ";
              print_r($displayName);
              echo "<br />Post:";
              print_r($_POST);
              echo "<br />Files: ";
              print_r($_FILES['aacsb_pubs']); */
            if (empty($_POST))
                wp_die(__('You probably need to go back to the <a href="admin.php?page=upload_publications">AACSB Uploads page</a>'));

            $pubUpload = cobeUpload($_FILES, $dirPath);
			
			if ($pubUpload === true)
				echo cobeWPMessage($_FILES['aacsb_file']['name'] . ' was successfully uploaded.');
			else
				echo cobeWPMessage($pubUpload, 'error');

            // insert record into wp_aacsb_pubs table
            $_POST = cobeEscapePost($_POST);
            
            $insertQuery = "INSERT INTO wp_aacsb_pubs VALUES(
				'','" . $_POST['employeeID'] . "', '" . $_POST['title'] . "',
				'" . $_POST['year'] . "', '" . $_POST['journal'] . "',
                                '" . $_POST['aacsb_pubs']. "', '" . $_POST['pub_order'] .  "')";
        //                   	echo $insertQuery;
            $result = $wpdb->query($insertQuery);
?>
            <div id="message" class="updated fade"><p>
<?php echo $displayName . "'s information was added."; ?>
                </p>
                <a href="admin.php?page=upload_publications">Continue</a>
            </div> <?php
            break;


        default:
?>
            <div id="form_container">
                <div id="content">
                    <h1>AACSB Publication Submission</h1>
                    <p><a href="admin.php?page=upload_publications&action=manage">Manage Submissions</a></p>
                    <form action="admin.php?page=upload_publications&amp;action=insert"
                          method="post" name="upload" enctype="multipart/form-data" >

                        <label class="description" for="employeeID">Faculty Name</label>
                                     <div><select name="employeeID" onchange="
                                                         document.forms['upload']['lastEmp'].value=(this.value);" >
                             <?php
                             $query = "Select employeeID, displayName from wp_personnel where accreditation=1 order by displayName";
                             $rows = $wpdb->get_results($query);
                             foreach ($rows as $row) {
                             ?>
                        <option value="<?php _e($row->employeeID) ?>"
<?php echo ($row->employeeID = $_POST['last_emp']) ? "selected='selected'" : "" ?> >
                            <?php _e($row->displayName) ?> </option>;
                        <?php } ?>
                     </select>
                 </div>
                        <br />
                 <label class="description" for="pub_order">Publication Order (From FQ)</label>
                 <div><input id="pub_order" name="pub_order" class="element text medium" type="text" size="2" maxlength="2" value=""/></div>
                 <label class="description" for="title">Title</label>
                 <div><input id="title" name="title" class="element text medium" type="text" size="300" maxlength="200" value=""/></div>
                 <label class="description" for="year">Year Published</label>
                 <div><input id="year" name="year" class="element text medium" type="text" size="4" maxlength="4" value=""/></div>
                 <label class="description" for="journal">Journal Name</label>
                 <div><input id="journal" name="journal" class="element text medium" type="text" size="50" maxlength="50" value=""/></div>
                 <label class="description" for="aacsb_pubs">Current File Name</label
                 <div><input id="aacsb_pubs" name="aacsb_pubs" class="element text medium"
                             type="text" size="50" maxlength="50" value=""/></div>
                 <label class="description" for="aacsb_file">Upload a PDF</label>
                 <div><input type="file" name="aacsb_file"
                             onchange="document.forms['upload']['aacsb_pubs'].value=(this.value);"/> </div>
                 <input type="hidden" name="lastEmp" value=""/>

                 <input  type="submit" name="submit" value="Submit" />

             </form>
         </div>
     </div>
<?php
                         }
                     }
?>