<?php
include_once('../common.php');
if (!isset($generalobjAdmin)) {
    require_once(TPATH_CLASS . "class.general_admin.php");
    $generalobjAdmin = new General_admin();
}
////$generalobjAdmin->check_member_login();
if (!$userObj->hasPermission('view-organization')) {
    $userObj->redirect();
}
$script = 'Organization';
//Start Sorting
$sortby = isset($_REQUEST['sortby']) ? $_REQUEST['sortby'] : 0;
$order = isset($_REQUEST['order']) ? $_REQUEST['order'] : '';
$ord = ' ORDER BY c.iOrganizationId DESC';
if ($sortby == 1) {
    if ($order == 0)
        $ord = " ORDER BY c.vCompany ASC";
    else
        $ord = " ORDER BY c.vCompany DESC";
}
if ($sortby == 2) {
    if ($order == 0)
        $ord = " ORDER BY c.vEmail ASC";
    else
        $ord = " ORDER BY c.vEmail DESC";
}
if ($sortby == 3) {
    if ($order == 0)
        $ord = " ORDER BY `count` ASC";
    else
        $ord = " ORDER BY `count` DESC";
}
if ($sortby == 4) {
    if ($order == 0)
        $ord = " ORDER BY c.eStatus ASC";
    else
        $ord = " ORDER BY c.eStatus DESC";
}
//End Sorting
$cmp_ssql = "";
$dri_ssql = "";
if (SITE_TYPE == 'Demo') {
    $dri_ssql = " And rd.tRegistrationDate > '" . WEEK_DATE . "'";
}
// Start Search Parameters
$option = isset($_REQUEST['option']) ? $_REQUEST['option'] : "";
$keyword = isset($_REQUEST['keyword']) ? stripslashes($_REQUEST['keyword']) : "";
$searchDate = isset($_REQUEST['searchDate']) ? $_REQUEST['searchDate'] : "";
$eStatus = isset($_REQUEST['eStatus']) ? $_REQUEST['eStatus'] : "";
$ssql = '';
$orgTypeArr = $orgNameArr = array();
$orgType_sql = "SELECT vProfileName,iUserProfileMasterId FROM user_profile_master ORDER BY iUserProfileMasterId ASC";
$orgProfileData = $obj->MySQLSelect($orgType_sql);
//echo "<pre>";
//print_r($_SESSION['sess_lang']);die;
if ($default_lang == "") {
    $default_lang = "EN";
}
for ($p = 0; $p < count($orgProfileData); $p++) {
    $profileName = (array) json_decode($orgProfileData[$p]['vProfileName']);
    $organization = $profileName['vProfileName_' . $default_lang];
    $orgTypeArr[$orgProfileData[$p]['iUserProfileMasterId']] = $organization;
    $orgNameArr[$organization] = $orgProfileData[$p]['iUserProfileMasterId'];
}
if ($option == "iUserProfileMasterId") {
    $orgKeyword = $keyword;
    $keyword = $orgNameArr[$keyword];
}
if ($keyword != '') {
    $keyword_new = $keyword;
    $chracters = array("(", "+", ")");
    $removespacekeyword = preg_replace('/\s+/', '', $keyword);
    $keyword_new = trim(str_replace($chracters, "", $removespacekeyword));
    if (is_numeric($keyword_new)) {
        $keyword_new = $keyword_new;
    } else {
        $keyword_new = $keyword;
    }
    if ($option != '') {
        $option_new = $option;
        if ($option == 'MobileNumber') {
            $option_new = "CONCAT(c.vCode,'',c.vPhone)";
        }
        if ($eStatus != '') {
            $ssql .= " AND " . stripslashes($option_new) . " LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%' AND c.eStatus = '" . $generalobjAdmin->clean($eStatus) . "'";
        } else {
            $ssql .= " AND " . stripslashes($option_new) . " LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%'";
        }
    } else {
		#########Start - Fetch Profile Master Id#########
		$getProfileId = "SELECT iUserProfileMasterId FROM `user_profile_master` WHERE eStatus !='Deleted' AND vProfileName LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%'";
        $data_profile = $obj->MySQLSelect($getProfileId);
		$iUserProfileMasterIdIn = '';
		if(count($data_profile) > 0){
			$inArr = array();
			for($p=0;$p<count($data_profile);$p++){
				$inArr[] = $data_profile[$p]['iUserProfileMasterId'];
			}
			if(count($inArr) > 0){
			$iUserProfileMasterIdIn = " OR c.iUserProfileMasterId IN (".implode(',',$inArr).")";
			}
		}
		#########End - Fetch Profile Master Id#########
		
        if ($eStatus != '') {
            $ssql .= " AND (c.vCompany LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%' OR c.vEmail LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%' OR (concat(c.vCode,'',c.vPhone) LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%') ".$iUserProfileMasterIdIn.") AND c.eStatus = '" . $generalobjAdmin->clean($eStatus) . "'";
        } else {
            $ssql .= " AND (c.vCompany LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%' OR c.vEmail LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%' OR (concat(c.vCode,'',c.vPhone) LIKE '%" . $generalobjAdmin->clean($keyword_new) . "%') ".$iUserProfileMasterIdIn.")";
        }
    }
} else if ($eStatus != '' && $keyword == '') {
    $ssql .= " AND c.eStatus = '" . $generalobjAdmin->clean($eStatus) . "'";
}
if ($option == "iUserProfileMasterId") {
    $keyword = $orgKeyword;
}
// End Search Parameters
//Pagination Start
$per_page = $DISPLAY_RECORD_NUMBER; // number of results to show per page
if (!empty($eStatus)) {
    $eStatus_sql = "";
} else {
    $eStatus_sql = " AND eStatus != 'Deleted'";
}
//echo $ssql;die;
$sql = "SELECT COUNT(iOrganizationId) AS Total FROM organization AS c WHERE 1 = 1 $eStatus_sql $ssql $cmp_ssql";
$totalData = $obj->MySQLSelect($sql);
$total_results = $totalData[0]['Total'];
$total_pages = ceil($total_results / $per_page); //total pages we going to have
$show_page = 1;
//-------------if page is setcheck------------------//
if (isset($_GET['page'])) {
    $show_page = $_GET['page'];             //it will telles the current page
    if ($show_page > 0 && $show_page <= $total_pages) {
        $start = ($show_page - 1) * $per_page;
        $end = $start + $per_page;
    } else {
        // error - show first set of results
        $start = 0;
        $end = $per_page;
    }
} else {
    // if page isn't set, show first set of results
    $start = 0;
    $end = $per_page;
}
// display pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 0;
$tpages = $total_pages;
if ($page <= 0)
    $page = 1;
//Pagination End
if (!empty($eStatus)) {
    $equery = "";
} else {
    $equery = " AND  c.eStatus != 'Deleted'";
}
$sql = "SELECT c.iOrganizationId, c.vCompany, c.vEmail,c.vCode,c.vPhone, c.eStatus,c.iUserProfileMasterId,c.ePaymentBy FROM organization AS c WHERE 1 = 1 $equery $ssql $cmp_ssql $ord LIMIT $start, $per_page";
$data_drv = $obj->MySQLSelect($sql);
$endRecord = count($data_drv);
//echo "<pre>";
//print_R($data_drv);die;
$sql1 = "SELECT doc_masterid as total FROM `document_master` WHERE `doc_usertype` ='company' AND status = 'Active'";
$doc_count_query = $obj->MySQLSelect($sql1);
$doc_count = count($doc_count_query);
$var_filter = "";
foreach ($_REQUEST as $key => $val) {
    if ($key != "tpages" && $key != 'page')
        $var_filter .= "&$key=" . stripslashes($val);
}
$reload = $_SERVER['PHP_SELF'] . "?tpages=" . $tpages . $var_filter;
?>
<!DOCTYPE html>
<html lang="en">
    <!-- BEGIN HEAD-->
    <head>
        <meta charset="UTF-8" />
        <title><?= $SITE_NAME ?> | Organization </title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <?php include_once('global_files.php'); ?>
    </head>
    <!-- END  HEAD-->

    <!-- BEGIN BODY-->
    <body class="padTop53 " >
        <!-- Main LOading -->
        <!-- MAIN WRAPPER -->
        <div id="wrap">
            <?php include_once('header.php'); ?>
            <?php include_once('left_menu.php'); ?>

            <!--PAGE CONTENT -->
            <div id="content">
                <div class="inner">
                    <div id="add-hide-show-div">
                        <div class="row">
                            <div class="col-lg-12">
                                <h2>Organization</h2>
                                <!--<input type="button" id="" value="ADD A DRIVER" class="add-btn">-->
                            </div>
                        </div>
                        <hr />
                    </div>
                    <?php include('valid_msg.php'); ?>
                    <form name="frmsearch" id="frmsearch" action="javascript:void(0);" method="post">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="admin-nir-table">
                            <tbody>
                                <tr>
                                    <td width="5%"><label for="textfield"><strong>Search:</strong></label></td>
                                    <td width="10%" class="padding-right10"><select name="option" id="option" class="form-control">
                                            <option value="">All</option>
                                            <option  value="c.vCompany" <?php
                                            if ($option == "c.vCompany") {
                                                echo "selected";
                                            }
                                            ?> >Organization Name</option>
                                            <option  value="iUserProfileMasterId" <?php
                                            if ($option == "iUserProfileMasterId") {
                                                echo "selected";
                                            }
                                            ?> >Organization Type</option>
                                            <option value="c.vEmail" <?php
                                            if ($option == 'c.vEmail') {
                                                echo "selected";
                                            }
                                            ?> >Email</option>
                                            <option value="MobileNumber" <?php
                                            if ($option == 'MobileNumber') {
                                                echo "selected";
                                            }
                                            ?> >Mobile</option>
                                            <!--<option value="c.eStatus" <?php
                                            if ($option == 'c.eStatus') {
                                                echo "selected";
                                            }
                                            ?> >Status</option>-->
                                        </select>
                                    </td>
                                    <td width="15%" class="searchform"><input type="Text" id="keyword" name="keyword" value="<?php echo $keyword; ?>"  class="form-control" /></td>

                                    <td width="12%" class="estatus_options" id="eStatus_options" >
                                        <select name="eStatus" id="estatus_value" class="form-control">
                                            <option value="" >Select Status</option>
                                            <option value='Active' <?php
                                            if ($eStatus == 'Active') {
                                                echo "selected";
                                            }
                                            ?> >Active</option>
                                            <option value="Inactive" <?php
                                            if ($eStatus == 'Inactive') {
                                                echo "selected";
                                            }
                                            ?> >Inactive</option>
                                            <option value="Deleted" <?php
                                            if ($eStatus == 'Deleted') {
                                                echo "selected";
                                            }
                                            ?> >Delete</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="submit" value="Search" class="btnalt button11" id="Search" name="Search" title="Search" />
                                        <input type="button" value="Reset" class="btnalt button11" onClick="window.location.href = 'organization.php'"/>
                                    </td>
                                    <?php if ($userObj->hasPermission('create-organization')) { ?>
                                        <td width="30%"><a class="add-btn" href="organization_action.php" style="text-align: center;">Add Organization</a></td>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>

                    </form>
                    <div class="table-list">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="admin-nir-export">
                                    <div class="changeStatus col-lg-12 option-box-left">
                                        <span class="col-lg-2 new-select001">
                                            <?php if ($userObj->hasPermission(['update-status-organization', 'delete-organization'])) { ?>
                                                <select name="changeStatus" id="changeStatus" class="form-control" onChange="ChangeStatusAll(this.value);">
                                                    <option value="" >Select Action</option>
                                                    <?php if ($userObj->hasPermission('update-status-organization')) { ?>
                                                        <option value='Active' <?php
                                                        if ($option == 'Active') {
                                                            echo "selected";
                                                        }
                                                        ?> >Activate</option>
                                                        <option value="Inactive" <?php
                                                        if ($option == 'Inactive') {
                                                            echo "selected";
                                                        }
                                                        ?> >Deactivate</option>
                                                            <?php } ?>
                                                            <?php if ($eStatus != 'Deleted' && $userObj->hasPermission('delete-organization')) { ?>
                                                        <option value="Deleted" <?php
                                                        if ($option == 'Delete') {
                                                            echo "selected";
                                                        }
                                                        ?> >Delete</option>
                                                            <?php } ?>
                                                </select>
                                            <?php } ?>
                                        </span>
                                    </div>
                                    <?php if (!empty($data_drv)) { ?>

                                        <div class="panel-heading">
                                            <form name="_export_form" id="_export_form" method="post" >
                                                <button type="button" onClick="showExportTypes('organization')">Export</button>
                                            </form>
                                        </div>

                                    <?php } ?>
                                </div>
                                <div style="clear:both;"></div>
                                <div class="table-responsive">
                                    <form class="_list_form" id="_list_form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th align="center" width="3%" style="text-align:center;"><input type="checkbox" id="setAllCheck" ></th>

                                                    <th width="20%"><a href="javascript:void(0);" onClick="Redirect(1,<?php
                                                        if ($sortby == '1') {
                                                            echo $order;
                                                        } else {
                                                            ?>0<?php } ?>)">Organization Name<?php
                                                                           if ($sortby == 1) {
                                                                               if ($order == 0) {
                                                                                   ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php
                                                                }
                                                            } else {
                                                                ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a>
                                                    </th>
                                                    <th width="15%">Organization Type</th>
                                                    <th width="15%">Payment Method</th>
                                                   <!--  <th width="6%" class='align-center'><a href="javascript:void(0);" onClick="Redirect(3,<?php
                                                    if ($sortby == '3') {
                                                        echo $order;
                                                    } else {
                                                        ?>0<?php } ?>)"><?php echo $langage_lbl_admin['LBL_DASHBOARD_DRIVERS_ADMIN']; ?><?php
                                                    if ($sortby == 3) {
                                                        if ($order == 0) {
                                                            ?>
                                                                                                                             <i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?>
                                                                                                                             <i class="fa fa-sort-amount-desc" aria-hidden="true"></i>
                                                            <?php
                                                        }
                                                    } else {
                                                        ?>  <i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                    -->


                                                    <th width="20%"><a href="javascript:void(0);" onClick="Redirect(2,<?php
                                                        if ($sortby == '2') {
                                                            echo $order;
                                                        } else {
                                                            ?>0<?php } ?>)">Email <?php
                                                                           if ($sortby == 2) {
                                                                               if ($order == 0) {
                                                                                   ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php
                                                                }
                                                            } else {
                                                                ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>

                                                    <th width="15%">Mobile</th>

                                                    <!--   <?php if ($doc_count != 0) { ?>
                                                                                  <th width="8%" class='align-center'>View/Edit Documents</th>
                                                    <?php } ?> -->

                                                    <th width="6%" class='align-center' style="text-align:center;"><a href="javascript:void(0);" onClick="Redirect(4,<?php
                                                        if ($sortby == '4') {
                                                            echo $order;
                                                        } else {
                                                            ?>0<?php } ?>)">Status <?php
                                                                                                                          if ($sortby == 4) {
                                                                                                                              if ($order == 0) {
                                                                                                                                  ?><i class="fa fa-sort-amount-asc" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-sort-amount-desc" aria-hidden="true"></i><?php
                                                                }
                                                            } else {
                                                                ?><i class="fa fa-sort" aria-hidden="true"></i> <?php } ?></a></th>
                                                    <th width="6%" align="center" style="text-align:center;">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($data_drv)) {
                                                    for ($i = 0; $i < count($data_drv); $i++) {
                                                        $default = '';
                                                        if ($data_drv[$i][' iOrganizationId'] == 1) {
                                                            $default = 'disabled';
                                                        }
                                                        $orgType = "";
                                                        if (isset($orgTypeArr[$data_drv[$i]['iUserProfileMasterId']])) {
                                                            $orgType = $orgTypeArr[$data_drv[$i]['iUserProfileMasterId']];
                                                        }
                                                        ?>
                                                        <?php
                                                        $payByName = $data_drv[$i]['ePaymentBy'];
                                                        if ($data_drv[$i]['ePaymentBy'] == "" || $data_drv[$i]['ePaymentBy'] == "Passenger") {
                                                            $payByName = $langage_lbl_admin['LBL_RIDER'];;
                                                        }
                                                        ?>
                                                        <tr class="gradeA">
                                                            <td align="center" style="text-align:center;"><input type="checkbox" id="checkbox" name="checkbox[]" <?php echo $default; ?> value="<?php echo $data_drv[$i]['iOrganizationId']; ?>" />&nbsp;</td>
                                                            <td>
                                                                <?= $generalobjAdmin->clearCmpName($data_drv[$i]['vCompany']); ?>
                                                            </td>
                                                            <td><? echo $orgType; ?></td>
                                                            <td>Pay By <? echo $payByName; ?></td>
                                                            <!--  <?php if ($data_drv[$i]['count'] == 0) { ?>
                                                                                             <td align="center"><? echo $data_drv[$i]['count']; ?></td>
                                                            <?php } else { ?>
                                                                                             <td align="center"><a href="driver.php?iCompanyId=<?= $data_drv[$i]['iCompanyId']; ?>" target="_blank"><? echo $data_drv[$i]['count']; ?></a></td>
                                                            <?php } ?> -->
                                                            <td><?= $generalobjAdmin->clearEmail($data_drv[$i]['vEmail']); ?></td>
                                                            <td>
                                                                <?php if (!empty($data_drv[$i]['vPhone'])) { ?>
                                                                    (+<?= $data_drv[$i]['vCode'] ?>) <?= $generalobjAdmin->clearPhone($data_drv[$i]['vPhone']); ?>
                                                                <?php } ?>   
                                                            </td>

                                                            <!--  <?php if ($doc_count != 0) { ?>
                                                                                         <td align="center" ><a href="organization_document_action.php?id=<?= $data_drv[$i]['iOrganizationId']; ?>&action=edit" target="_blank">
                                                                                                 <img src="img/edit-doc.png" alt="Edit Document" >
                                                                                             </a></td>
                                                            <?php } ?> -->

                                                            <td align="center" style="text-align:center;">
                                                                <?php
                                                                if ($data_drv[$i]['eStatus'] == 'Active') {
                                                                    $dis_img = "img/active-icon.png";
                                                                } else if ($data_drv[$i]['eStatus'] == 'Inactive') {
                                                                    $dis_img = "img/inactive-icon.png";
                                                                } else if ($data_drv[$i]['eStatus'] == 'Deleted') {
                                                                    $dis_img = "img/delete-icon.png";
                                                                }
                                                                ?>
                                                                <img src="<?= $dis_img; ?>" alt="image" data-toggle="tooltip" title="<?php echo $data_drv[$i]['eStatus']; ?>">
                                                            </td>

                                                            <td align="center" style="text-align:center;" class="action-btn001">


                                                                <div class="share-button share-button4 openHoverAction-class" style="display:block;">
                                                                    <label class="entypo-export"><span><img src="images/settings-icon.png" alt=""></span></label>
                                                                    <div class="social show-moreOptions openPops_<?= $data_drv[$i]['   iOrganizationId']; ?>">
                                                                        <ul>

                                                                            <li class="entypo-twitter" data-network="twitter"><a href="organization_action.php?id=<?= $data_drv[$i]['iOrganizationId']; ?>" data-toggle="tooltip" title="Edit">
                                                                                    <img src="img/edit-icon.png" alt="Edit">
                                                                                </a>
                                                                            </li>

                                                                            <?php if ($userObj->hasPermission('update-status-organization')) { ?>
                                                                                <li class="entypo-facebook" data-network="facebook"><a href="javascript:void(0);" onClick="changeStatus('<?php
                                                                                    echo $data_drv[$i]
                                                                                    ['iOrganizationId'];
                                                                                    ?>', 'Inactive')"  data-toggle="tooltip" title="Activate">
                                                                                        <img src="img/active-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >
                                                                                    </a></li>
                                                                                <li class="entypo-gplus" data-network="gplus"><a href="javascript:void(0);" onClick="changeStatus('<?php
                                                                                    echo $data_drv[$i]
                                                                                    ['iOrganizationId'];
                                                                                    ?>', 'Active')" data-toggle="tooltip" title="Deactivate">
                                                                                        <img src="img/inactive-icon.png" alt="<?php echo $data_drv[$i]['eStatus']; ?>" >
                                                                                    </a></li>
                                                                            <?php } ?>

                                                                            <?php if ($eStatus != 'Deleted' && $userObj->hasPermission('delete-organization')) { ?>
                                                                                <li class="entypo-gplus" data-network="gplus"><a href="javascript:void(0);" onClick="changeStatusDelete('<?php
                                                                                    echo $data_drv[$i]
                                                                                    ['iOrganizationId'];
                                                                                    ?>')"  data-toggle="tooltip" title="Delete">
                                                                                        <img src="img/delete-icon.png" alt="Delete" >
                                                                                    </a></li>
                                                                            <?php } ?>
                                                                        </ul>
                                                                    </div>
                                                                </div>

                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                } else {
                                                    ?>
                                                    <tr class="gradeA">
                                                        <td colspan="8"> No Records Found.</td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </form>
                                    <?php include('pagination_n.php'); ?>
                                </div>
                            </div> <!--TABLE-END-->
                        </div>
                    </div>
                    <div class="admin-notes">
                        <h4>Notes:</h4>
                        <ul>
                            <li>Organization module will list all Organizations on this page.</li>
                            <li>Admin can Activate / Deactivate / Delete any Oranization. Default Organization cannot be Activated / Deactivated / Deleted.</li>
                            <li>Admin can export data in XLS or PDF format.</li>
                            <!--  <li>This module will list the Organization registered as a Taxi/ Ride, Common Delivery, and Other Services.</li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <!--END PAGE CONTENT -->
        </div>
        <!--END MAIN WRAPPER -->
        <form name="pageForm" id="pageForm" action="action/organization.php" method="post" >
            <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
            <input type="hidden" name="tpages" id="tpages" value="<?php echo $tpages; ?>">
            <input type="hidden" name="iOrganizationId" id="iMainId01" value="" >
            <input type="hidden" name="eStatus" id="eStatus" value="<?php echo $eStatus; ?>" >
            <input type="hidden" name="status" id="status01" value="" >
            <input type="hidden" name="statusVal" id="statusVal" value="" >
            <input type="hidden" name="option" value="<?php echo $option; ?>" >
            <input type="hidden" name="keyword" value="<?php echo $keyword; ?>" >
            <input type="hidden" name="sortby" id="sortby" value="<?php echo $sortby; ?>" >
            <input type="hidden" name="order" id="order" value="<?php echo $order; ?>" >
            <input type="hidden" name="method" id="method" value="" >
        </form>

        <div  class="modal fade" id="detail_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4>
                            <i aria-hidden="true" class="fa fa-building-o" style="margin:2px 5px 0 2px;"></i>
                            Company Details
                            <button type="button" class="close" data-dismiss="modal">x</button>
                        </h4>
                    </div>
                    <div class="modal-body" style="max-height: 450px;overflow: auto;">
                        <div id="imageIcons" style="display:none">
                            <div align="center">                                                                       
                                <img src="default.gif"><br/>                                                            
                                <span>Retrieving details,please Wait...</span>                       
                            </div>    
                        </div>
                        <div id="comp_detail"></div>
                    </div>
                </div>
            </div>

        </div>
        <?php
        include_once('footer.php');
        ?>
        <script>
            /*    $(document).ready(function() {  
             $('#eStatus_options').hide(); 
             $('#option').each(function(){
             if (this.value == 'c.eStatus') {
             $('#eStatus_options').show(); 
             $('.searchform').hide(); 
             }
             });
             });
             $(function() {
             $('#option').change(function(){
             if($('#option').val() == 'c.eStatus') {
             $('#eStatus_options').show();
             $("input[name=keyword]").val("");
             $('.searchform').hide(); 
             } else {
             $('#eStatus_options').hide();
             $("#estatus_value").val("");
             $('.searchform').show();
             } 
             });
             });*/

            $("#setAllCheck").on('click', function () {
                if ($(this).prop("checked")) {
                    jQuery("#_list_form input[type=checkbox]").each(function () {
                        if ($(this).attr('disabled') != 'disabled') {
                            this.checked = 'true';
                        }
                    });
                } else {
                    jQuery("#_list_form input[type=checkbox]").each(function () {
                        this.checked = '';
                    });
                }
            });

            $("#Search").on('click', function () {
                //$('html').addClass('loading');
                var action = $("#_list_form").attr('action');
                //alert(action);
                var formValus = $("#frmsearch").serialize();
//                alert(action+formValus);
                window.location.href = action + "?" + formValus;
            });

            $('.entypo-export').click(function (e) {
                e.stopPropagation();
                var $this = $(this).parent().find('div');
                $(".openHoverAction-class div").not($this).removeClass('active');
                $this.toggleClass('active');
            });

            $(document).on("click", function (e) {
                if ($(e.target).is(".openHoverAction-class,.show-moreOptions,.entypo-export") === false) {
                    $(".show-moreOptions").removeClass("active");
                }
            });

            function show_company_details(organizationId) {
                $("#comp_detail").html('');
                $("#imageIcons").show();
                $("#detail_modal").modal('show');

                if (companyid != "") {
                    var request = $.ajax({
                        type: "POST",
                        url: "ajax_organization_details.php",
                        data: "iOrganizationId=" + organizationId,
                        datatype: "html",
                        success: function (data) {
                            $("#comp_detail").html(data);
                            $("#imageIcons").hide();
                        }
                    });
                }
            }

        </script>
    </body>
    <!-- END BODY-->
</html>