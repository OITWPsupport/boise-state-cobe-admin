<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// Gets and populates the Faculty Contact Info in Faculty and Research
function getFacultyPubsList($search) {
    global $wpdb;

    $query = "Select wp_aacsb_pubs.*, wp_personnel.employeeID, wp_personnel.displayName ";
    $query .= " from wp_aacsb_pubs left join wp_personnel on wp_aacsb_pubs.employeeID = wp_personnel.employeeID ";
    $query .= " where accreditation = 1 ";
    $query .= " order by wp_aacsb_pubs.employeeID, pub_order";
    if ($search != '') {
        $query = "Select wp_aacsb_pubs.*, wp_personnel.employeeID, displayName ";
        $query .= " from wp_aacsb_pubs left join wp_personnel on wp_aacsb_pubs.employeeID = wp_personnel.employeeID ";
        $query .= " where accreditation = 1 ";
        $query .= " and wp_personnel.lastName Like %s ";
        $query .= " order by pub_order";
  }
//	echo $query;
    $facresults = $wpdb->get_results($wpdb->prepare($query, "%{$search}%"));
    return $facresults;
}

?>
