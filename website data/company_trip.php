<?
include_once('common.php');
include_once('generalFunctions.php');
$script="Trips";
$tbl_name 	= 'register_driver';
 $generalobj->check_member_login();
 $abc = 'admin,company';
 $url = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
 $generalobj->setRole($abc,$url);
$action=(isset($_REQUEST['action'])?$_REQUEST['action']:'');
$ssql='';
$deafultLang = $_SESSION['sess_lang'];
if($action!='')
{
	$startDate=$_REQUEST['startDate'];
	$endDate=$_REQUEST['endDate'];
	if($startDate!=''){
		$ssql.=" AND Date(t.tTripRequestDate) >='".$startDate."'";
	}
	if($endDate!=''){
		$ssql.=" AND Date(t.tTripRequestDate) <='".$endDate."'";
	}
}
$sql = "SELECT t.tVehicleTypeData,u.vName, u.vLastName,t.vCancelReason, t.tEndDate, t.tTripRequestDate, t.eCancelledBy, t.vRideNo, t.iActive,d.vAvgRating, t.fOutStandingAmount, t.iFare, d.iDriverId, t.tSaddress, t.tDaddress,t.fTripGenerateFare, t.iRentalPackageId,t.eType, t.eHailTrip, t.fHotelCommision, t.iCancelReasonId, d.vName AS name, d.vLastName AS lname,t.eCarType, t.vTimezone, t.iTripId,t.fTax1,t.fTax2,vt.vVehicleType_".$deafultLang." as vVehicleType,vt.vRentalAlias_".$deafultLang." as vRentalVehicleTypeName, t.fCommision,t.fTripGenerateFare,t.fTipPrice, t.fCancellationFare, t.eCancelled FROM register_driver d RIGHT JOIN trips t ON d.iDriverId = t.iDriverId LEFT JOIN vehicle_type vt ON vt.iVehicleTypeId = t.iVehicleTypeId LEFT JOIN  register_user u ON t.iUserId = u.iUserId WHERE d.iCompanyId = '".$_SESSION['sess_iUserId']."'".$ssql." AND t.eSystem = 'General' ORDER BY t.iTripId DESC";
$db_trip = $obj->MySQLSelect($sql);

$Today=Date('Y-m-d');
$tdate=date("d")-1;
$mdate=date("d");
$Yesterday = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-1,date("Y")));

$curryearFDate = date("Y-m-d",mktime(0,0,0,'1','1',date("Y")));
$curryearTDate = date("Y-m-d",mktime(0,0,0,"12","31",date("Y")));
$prevyearFDate = date("Y-m-d",mktime(0,0,0,'1','1',date("Y")-1));
$prevyearTDate = date("Y-m-d",mktime(0,0,0,"12","31",date("Y")-1));

$currmonthFDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$tdate,date("Y")));
$currmonthTDate = date("Y-m-d",mktime(0,0,0,date("m")+1,date("d")-$mdate,date("Y")));
$prevmonthFDate = date("Y-m-d",mktime(0,0,0,date("m")-1,date("d")-$tdate,date("Y")));
$prevmonthTDate = date("Y-m-d",mktime(0,0,0,date("m"),date("d")-$mdate,date("Y")));

$monday = date( 'Y-m-d', strtotime( 'sunday this week -1 week' ) );
$sunday = date( 'Y-m-d', strtotime( 'saturday this week' ) );

$Pmonday = date( 'Y-m-d', strtotime('sunday this week -2 week'));
$Psunday = date( 'Y-m-d', strtotime('saturday this week -1 week'));
if($host_system == 'cubetaxiplus') {
  $canceled_icon = "canceled-invoice.png";
  $invoice_icon = "driver-view-icon.png";
} else if($host_system == 'ufxforall') {
  $canceled_icon = "ufxforall-canceled-invoice.png";
  $invoice_icon = "ufxforall-driver-view-icon.png";
} else if($host_system == 'uberridedelivery4') {
  $canceled_icon = "ride-delivery-canceled-invoice.png";
  $invoice_icon = "ride-delivery-driver-view-icon.png";
} else if($host_system == 'uberdelivery4') {
  $canceled_icon = "delivery-canceled-invoice.png";
  $invoice_icon = "delivery-driver-view-icon.png";
} else {
  $invoice_icon = "driver-view-icon.png";
  $canceled_icon = "canceled-invoice.png";
}
$vehilceTypeArr = array();
$getVehicleTypes = $obj->MySQLSelect("SELECT iVehicleTypeId,vVehicleType_" . $deafultLang . " AS vehicleType FROM vehicle_type WHERE 1=1");
for ($r = 0; $r < count($getVehicleTypes); $r++) {
    $vehilceTypeArr[$getVehicleTypes[$r]['iVehicleTypeId']] = $getVehicleTypes[$r]['vehicleType'];
}
?>
<!DOCTYPE html>
<html lang="en" dir="<?=(isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "")?$_SESSION['eDirectionCode']:'ltr';?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?=$SITE_NAME?> | <?=$langage_lbl['LBL_HEADER_TRIPS_TXT']; ?></title>
    <!-- Default Top Script and css -->
    <?php include_once("top/top_script.php");?>
   
    <!-- <link href="assets/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet" /> -->
    <!-- End: Default Top Script and css-->
</head>
<body>
  <!-- home page -->
    <div id="main-uber-page">
    <!-- Left Menu -->
    <?php include_once("top/left_menu.php");?>
    <!-- End: Left Menu-->
        <!-- Top Menu -->
        <?php include_once("top/header_topbar.php");?>
        <!-- End: Top Menu-->
        <!-- contact page-->
		<div class="page-contant">
			<div class="page-contant-inner">
			  	<h2 class="header-page"><?=$langage_lbl['LBL_COMPANY_TRIP_HEADER_TRIPS_TXT']; ?></h2>
		  		<!-- trips page -->
			  	<div class="trips-page">
			  		<form name="search" action="" method="post" onSubmit="return checkvalid()">
			  		<input type="hidden" name="action" value="search" />
				    	<div class="Posted-date">
				      		<h3><?=$langage_lbl['LBL_COMPANY_TRIP_SEARCH_RIDES_POSTED_BY_DATE']; ?></h3>
				      		<span>
                                    <input type="text" id="dp4" name="startDate" placeholder="<?= $langage_lbl['LBL_WALLET_FROM_DATE']; ?>" class="form-control" value="" readonly style="cursor:default; background-color: #fff"/>
                                    <input type="text" id="dp5" name="endDate" placeholder="<?= $langage_lbl['LBL_WALLET_TO_DATE']; ?>" class="form-control" value="" readonly style="cursor:default; background-color: #fff"/>
					      	</span>
				      	</div>
				    	<div class="time-period">
				      		<h3><?=$langage_lbl['LBL_COMPANY_TRIP_SEARCH_RIDES_POSTED_BY_TIME_PERIOD']; ?></h3>
				      		<span>
								<a onClick="return todayDate('dp4','dp5');"><?=$langage_lbl['LBL_COMPANY_TRIP_Today']; ?></a>
								<a onClick="return yesterdayDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Yesterday']; ?></a>
								<a onClick="return currentweekDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Current_Week']; ?></a>
								<a onClick="return previousweekDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Previous_Week']; ?></a>
								<a onClick="return currentmonthDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Current_Month']; ?></a>
								<a onClick="return previousmonthDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Previous Month']; ?></a>
								<a onClick="return currentyearDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMAPNY_TRIP_Current_Year']; ?></a>
								<a onClick="return previousyearDate('dFDate','dTDate');"><?=$langage_lbl['LBL_COMPANY_TRIP_Previous_Year']; ?></a>
				      		</span> 
				      		<b><button class="driver-trip-btn"><?=$langage_lbl['LBL_COMPANY_TRIP_Search']; ?></button>
				      		<button onClick="reset();" class="driver-trip-btn"><?=$langage_lbl['LBL_MYTRIP_RESET']; ?></button></b> 
			      		</div>
		      		</form>
			    	<div class="trips-table"> 
			      		<div class="trips-table-inner">
                        <div class="driver-trip-table">
			        		<table width="100%" border="0" cellpadding="0" cellspacing="1" id="dataTables-example">
			          			<thead>
									<tr>
									<?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){ ?>
										<th><?=$langage_lbl_admin['LBL_TRIP_JOB_TYPE_FRONT'];?></th>
										<?php } ?>
										<th width="17%"><?=$langage_lbl['LBL_MYTRIP_RIDE_NO_TXT']; ?></th>
	        							<th><?=$langage_lbl['LBL_Pick_Up']; ?></th>
										<th><?=$langage_lbl['LBL_COMPANY_TRIP_DRIVER']; ?></th>
										<th><?=$langage_lbl['LBL_COMPANY_TRIP_RIDER']; ?></th>
										<th><?=$langage_lbl['LBL_COMPANY_TRIP_Trip_Date']; ?></th>
										<th><?=$langage_lbl['LBL_COMPANY_TRIP_FARE_TXT']; ?></th>
										<th><?=$langage_lbl['LBL_COMPANY_TRIP_Car_Type']; ?></th>
										<th><?=$langage_lbl['LBL_COMPANY_TRIP_View_Invoice']; ?></th>
									</tr>
								</thead>
								<tbody>
								<? 
									for($i=0;$i<count($db_trip);$i++)
									{
										if ($db_trip[$i]['iRentalPackageId'] > 0) {
                                            if(!empty($db_trip[$i]['vRentalVehicleTypeName'])){
                                                $car = $db_trip[$i]['vRentalVehicleTypeName'];
                                            } else {
                                                $car = $db_trip[$i]['vVehicleTyp'];
                                            }
                                        } else {
                                            $car = $db_trip[$i]['vVehicleType'];
                                        }
                                        //Added By HJ On 08-02-2019 For Get Main Category Name When Multiple Service Selected Start
                                        $viewService = 0;
                                        $seriveJson = "";
                                        if (isset($db_trip[$i]['tVehicleTypeData']) && $db_trip[$i]['tVehicleTypeData'] != "" && $car == "") {
                                            $viewService = 1;
                                            $seriveJson = $db_trip[$i]['tVehicleTypeData'];
                                        }
                                        //Added By HJ On 08-02-2019 For Get Main Category Name When Multiple Service Selected End

										$eType = $db_trip[$i]['eType'];
										$link_page = "invoice.php";
										if($eType == 'Ride'){
											$trip_type = 'Ride';
										} else if($eType == 'UberX') {
											$trip_type = 'Other Services';
										} else if($eType == 'Multi-Delivery') {
											$trip_type = 'Multi-Delivery';
											$link_page = "invoice_multi_delivery.php";
										} else {
											$trip_type = 'Delivery';
										}
										$systemTimeZone = date_default_timezone_get();
										if($db_trip[$i]['tTripRequestDate']!= "" && $db_trip[$i]['vTimeZone'] != "")  {
										    $dBookingDate = converToTz($db_trip[$i]['tTripRequestDate'],$db_trip[$i]['vTimeZone'],$systemTimeZone);
										} else {
										    $dBookingDate = $db_trip[$i]['tTripRequestDate'];
										}
								?>
									<tr class="gradeA">
									<?php if($APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery'){ ?>
										<td ><? 
										if($db_trip[$i]['eHailTrip'] == "Yes" && $db_trip[$i]['iRentalPackageId'] > 0){
                                            echo "Rental " . $trip_type."<br/> ( Hail )";
                                        } else if($db_trip[$i]['iRentalPackageId'] > 0){
                                            echo "Rental " . $trip_type;
                                        } else if ($db_trip[$i]['eHailTrip'] == "Yes") {
                                            echo "Hail ".$trip_type;
                                        } else {
                                            echo $trip_type;
                                        }

/*										if($db_trip[$i]['eHailTrip'] != "Yes"){
											echo $trip_type;
										}else{
											echo "Hail ".$trip_type;
										}*/
										?></td>
									<?php } ?>
										<td align="center"><?=$db_trip[$i]['vRideNo'];?></td>
										<?php if($APP_TYPE == 'UberX'){ ?>
											<td width="25%"><?=$db_trip[$i]['tSaddress'];?></td>
										<?php } else { 
											if(!empty($db_trip[$i]['tDaddress'])) {?>
												<td width="25%"><?=$db_trip[$i]['tSaddress'].' -> '.$db_trip[$i]['tDaddress'];?></td>
											<?php } else { ?>
												<td width="25%"><?=$db_trip[$i]['tSaddress'];?></td>
											<?php }
										} ?> 
										<td>
											<?=$generalobj->clearName($db_trip[$i]['name']." ".$db_trip[$i]['lname']);?>
										</td>
										<td>
											<?=$generalobj->clearName($db_trip[$i]['vName']." ".$db_trip[$i]['vLastName']);?>
										</td>
										<td data-order="<?=$db_trip[$i]['iTripId']?>"><?= date('d-M-Y',strtotime($dBookingDate));?></td>
										<td align="center">

											<?php /*if($db_trip[$i]['fCancellationFare'] > 0 || ($db_trip[$i]['iActive'] == "Canceled" && $db_trip[$i]['fWalletDebit'] > 0)){

												$total_main_price = $db_trip[$i]['fCancellationFare'];
											} else {*/
												$total_main_price = ($db_trip[$i]['fTripGenerateFare'] + $db_trip[$i]['fTipPrice'] - $db_trip[$i]['fCommision']- $db_trip[$i]['fTax2']-$db_trip[$i]['fTax1'] - $db_trip[$i]['fOutStandingAmount']- $db_trip[$i]['fHotelCommision']);
											//}
												?>
											<?=$generalobj->trip_currency($total_main_price);?>
										</td>
										<td align="center">
											<!-- <?php if($db_trip[$i]['iRentalPackageId'] > 0){
												echo $db_trip[$i]['vRentalVehicleTypeName'];
											} else {
												echo $db_trip[$i]['vVehicleType'];
											}?> -->
											<?php if ($viewService == 1) { ?>
                                                            <button class="btn btn-success" data-trip="<?= $db_trip[$i]['vRideNo']; ?>" data-json='<?= $seriveJson; ?>' onclick="return showServiceModal(this);">
                                                                <i class="fa fa-certificate icon-white"><b> View Service</b></i>
                                                            </button>
											        <?php
											    } else {
											        echo $car;
											    }
											    ?>
										</td>
									<?php  if($db_trip[$i]['iActive'] == 'Canceled' && $db_trip[$i]['fTripGenerateFare'] <= 0){
										if($db_trip[$i]['iCancelReasonId'] > 0 || $db_trip[$i]['vCancelReason'] != '') { ?>
											<td class="center">
											<a href="#" data-toggle="modal" data-target="#uiModal1_<?=$db_trip[$i]['iTripId'];?>"> <!-- class="btn btn-info"  -->
												<img src="assets/img/<?php echo $canceled_icon;?>" title="<?=$langage_lbl['LBL_MYTRIP_CANCELED_TXT']; ?>">
											</a>
											</td> 
										<? } else {?>
											<td class="center">
											<img src="assets/img/<?php echo $canceled_icon;?>" title="<?=$langage_lbl['LBL_MYTRIP_CANCELED_TXT']; ?>">
											</td>
										<? } ?>

										<div class="modal fade" role="dialog" id="uiModal1_<?=$db_trip[$i]['iTripId'];?>" >
										  <div class="modal-dialog">
										    <!-- Modal content-->
										    <div class="modal-content">
										      <div class="modal-header">
										        <button type="button" class="close" data-dismiss="modal">&times;</button>
										        <h5 class="modal-title"><?= $langage_lbl['LBL_RIDE_TXT']." ". $langage_lbl['LBL_CANCEL_REASON'];?> </h5>
										      </div>
										      <div class="modal-body">
										        <h5><b><?= $langage_lbl['LBL_CANCEL_REASON'];?> : </b> 
										        	<? if($db_trip[$i]['iCancelReasonId'] > 0) {
															$cancelreasonarray = $generalobj->getCancelReason($db_trip[$i]['iCancelReasonId'],$_SESSION['sess_lang']);
															$db_trip[$i]['vCancelReason'] = $cancelreasonarray['vCancelReason'];
														} else {
															$db_trip[$i]['vCancelReason'] = $db_trip[$i]['vCancelReason'];
														}?> 
														<?= 
														stripcslashes($db_trip[$i]['vCancelReason']." ".$db_trip[$i]['vCancelComment']);?></h5>
												<h5><b><?= $langage_lbl['LBL_CANCEL_BY'];?>:</b> <?= stripcslashes($db_trip[$i]['eCancelledBy']) ;?></h5>
										      </div>
										      <div class="modal-footer">
										        <button type="button" class="btn btn-default" data-dismiss="modal"><?= $langage_lbl['LBL_CLOSE_TXT'];?></button>
										      </div>
										    </div>

										  </div>
										</div>
									<?php } else if(($db_trip[$i]['iActive'] == 'Finished' && $db_trip[$i]['eCancelled'] == "Yes") || ($db_trip[$i]['iActive'] == 'Canceled' && $db_trip[$i]['fTripGenerateFare'] > 0)) { ?>
									<td align="center" width="10%">
									  	<a target = "_blank" href="<?=$link_page?>?iTripId=<?=base64_encode(base64_encode($db_trip[$i]['iTripId']))?>">
												<img alt="" src="assets/img/<?php echo $invoice_icon;?>">
										 </a>
										<div style="font-size: 12px;">Cancelled</div>
									</td>
									<? } else if($db_trip[$i]['iActive'] == 'Active' || $db_trip[$i]['iActive'] == 'On Going Trip') { ?>
											<td class="center">
												<?= $langage_lbl['LBL_ON_RIDES_ADMIN'];?>
											</td>
									<? } else {?>	
										<td align="center" width="10%">
										  <a target = "_blank" href="<?=$link_page?>?iTripId=<?=base64_encode(base64_encode($db_trip[$i]['iTripId']))?>">
												<img alt="" src="assets/img/<?php echo $invoice_icon;?>">
										 </a>
										</td>
									<?php } ?>
									</tr>
								<? } ?>		
								</tbody>
			        		</table>
			      		</div>	</div>
			    </div>
			    <!-- -->
			    <? //if(SITE_TYPE=="Demo"){?>
			    <!-- <div class="record-feature"> <span><strong>“Edit / Delete Record Feature�?</strong> has been disabled on the Demo Admin Version you are viewing now.
			      This feature will be enabled in the main product we will provide you.</span> </div>
			      <?php //}?> -->
			    <!-- -->
			  </div>
			  <!-- -->
			  <div style="clear:both;"></div>
			</div>
		</div>
    <!-- footer part -->
    <?php include_once('footer/footer_home.php');?>
    <!-- footer part end -->
        <!-- End:contact page-->
        <div style="clear:both;"></div>
    </div>
    <!-- home page end-->
            <div  class="modal fade" id="service_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" >
            <div class="modal-dialog" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 id="servicetitle">
                            <i style="margin:2px 5px 0 2px;"><img src="images/icon/driver-icon.png" alt=""></i>
                            Service Details
                            <button type="button" class="close" data-dismiss="modal">x</button>
                        </h4>
                    </div>
                    <div class="modal-body" style="max-height: 450px;overflow: auto;">
                        <div id="service_detail"></div>
                    </div>
                </div>
            </div>
        </div>
    <!-- Footer Script -->
    <?php include_once('top/footer_script.php');?>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/plugins/dataTables/jquery.dataTables.js"></script>
    <script type="text/javascript">
    	var typeArr = '<?= json_encode($vehilceTypeArr); ?>';
         $(document).ready(function () {
         	$( "#dp4" ).datepicker({
         		dateFormat: "yy-mm-dd",
         		changeYear: true,
     		  	changeMonth: true,
     		  	yearRange: "-100:+10"
         	});
         	$( "#dp5" ).datepicker({
         		dateFormat: "yy-mm-dd",
         		changeYear: true,
     		  	changeMonth: true,
     		  	yearRange: "-100:+10"
         	});
			 if('<?=$startDate?>'!=''){
				 $("#dp4").val('<?=$startDate?>');
				 $("#dp4").datepicker('refresh');
			 }
			 if('<?=$endDate?>'!=''){
				 $("#dp5").val('<?= $endDate;?>');
				 $("#dp5").datepicker('refresh');
			 }
			<?php  if($APP_TYPE == 'UberX' || $APP_TYPE == 'Delivery'){ ?>
			$('#dataTables-example').DataTable( {
			  "order": [[ 4, "desc" ]]
			    } );
			<?php } else { ?>
			$('#dataTables-example').DataTable( {
			  "order": [[ 5, "desc" ]]
			    } );
			<?php }  ?>
            //$('#dataTables-example').dataTable();
			// formInit();
         });
        function reset() {
			location.reload();
		}
		 function todayDate()
		 {
			 $("#dp4").val('<?= $Today;?>');
			 $("#dp5").val('<?= $Today;?>');
		 }
		 function yesterdayDate()
		 {
			 $("#dp4").val('<?= $Yesterday;?>');
			 $("#dp5").val('<?= $Yesterday;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');			 
		 }
		 function currentweekDate(dt,df)
		 {
			 $("#dp4").val('<?= $monday;?>');			 
			 $("#dp5").val('<?= $sunday;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
		 function previousweekDate(dt,df)
		 {
			 $("#dp4").val('<?= $Pmonday;?>');
			 $("#dp5").val('<?= $Psunday;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
		 function currentmonthDate(dt,df)
		 {
			 $("#dp4").val('<?= $currmonthFDate;?>');
			 $("#dp5").val('<?= $currmonthTDate;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
		 function previousmonthDate(dt,df)
		 {
			 $("#dp4").val('<?= $prevmonthFDate;?>');
			 $("#dp5").val('<?= $prevmonthTDate;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
		 function currentyearDate(dt,df)
		 {
			 $("#dp4").val('<?= $curryearFDate;?>');
			 $("#dp5").val('<?= $curryearTDate;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
		 function previousyearDate(dt,df)
		 {
			 $("#dp4").val('<?= $prevyearFDate;?>');
			 $("#dp5").val('<?= $prevyearTDate;?>');
			 $("#dp4").datepicker('refresh');
			 $("#dp5").datepicker('refresh');
		 }
	 	function checkvalid(){
			 if($("#dp5").val() < $("#dp4").val()){
				 //bootbox.alert("<h4>From date should be lesser than To date.</h4>");
			 	bootbox.dialog({
				 	message: "<h4><?php echo addslashes($langage_lbl['LBL_FROM_TO_DATE_ERROR_MSG']);?></h4>",
				 	buttons: {
				 		danger: {
				      		label: "OK",
				      		className: "btn-danger"
				   	 	}
			   	 	}
		   	 	});
			 	return false;
		 	}
	 	}
	 	function showServiceModal(elem) {
	        var tripJson = JSON.parse($(elem).attr("data-json"));
	        var rideNo = $(elem).attr("data-trip");
	        var typeNameArr = JSON.parse(typeArr)
	        var serviceHtml = "";
	        var srno = 1;
	        for (var g = 0; g < tripJson.length; g++) {
	            serviceHtml += "<p>" + srno + ") " + typeNameArr[tripJson[g]['iVehicleTypeId']] + "</p>";
	            srno++;
	        }
	        $("#service_detail").html(serviceHtml);
	        $("#servicetitle").text("Service Details : " + rideNo);
	        $("#service_modal").modal('show');
	        return false;
	    }
    </script>
    
    <script type="text/javascript">
    $(document).ready(function(){
        $("[name='dataTables-example_length']").each(function(){
            $(this).wrap("<em class='select-wrapper'></em>");
            $(this).after("<em class='holder'></em>");
        });
        $("[name='dataTables-example_length']").change(function(){
            var selectedOption = $(this).find(":selected").text();
            $(this).next(".holder").text(selectedOption);
        }).trigger('change');
    })
</script>
    <!-- End: Footer Script -->
</body>
</html>
