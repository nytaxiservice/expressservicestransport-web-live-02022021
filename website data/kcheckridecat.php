<?php
include_once("common.php");

print_r(CheckRideDeliveryFeatureDisable());

function CheckRideDeliveryFeatureDisable() {
    global $obj, $APP_TYPE, $generalobj;
    $eShowRideVehicles = "No";
    $eShowDeliveryVehicles = "No";
    $eShowDeliverAllVehicles = "No";
    $RideDeliveryBothFeatureDisable = "No";

    $eMotoRideEnable = "Yes";
    $eMotoDeliveryEnable = "Yes";
    $eRentalEnable = "Yes";
    $eMotoRentalEnable = "Yes";
	
    if ($APP_TYPE == "Ride-Delivery-UberX" || $APP_TYPE == "Ride-Delivery" || $APP_TYPE == "Delivery") {
    	$eMotoRideEnable = "No";
        $eMotoDeliveryEnable = "No";
        $eRentalEnable = "No";
        $eMotoRentalEnable = "No";

    	$ssql = '';
    	if($APP_TYPE == "Ride-Delivery" || $APP_TYPE == "Delivery"){
    		$ssql .= " AND eFor = 'DeliveryCategory' AND eCatType ='MoreDelivery'";
    	}

        $vCatSQL= "SELECT iVehicleCategoryId,eStatus,eCatType,iParentId,eFor  FROM vehicle_category_ride_delivery WHERE eCatType != 'ServiceProvider' ";
        $rideDeliveryIconData = $obj->MySQLSelect($vCatSQL);

		########### Stage 1 ###########
		if($APP_TYPE == "Ride-Delivery-UberX") {
			for($i=0;$i<count($rideDeliveryIconData);$i++){
				$data_temp = $rideDeliveryIconData[$i];
				if($data_temp['eCatType'] == "Ride" || $data_temp['eCatType'] == "MotoRide" || $data_temp['eCatType'] == "Rental" || $data_temp['eCatType'] == "MotoRental"){
					$iParentId_tmp = $data_temp['iParentId'];
					$eStatus_tmp = $data_temp['eStatus'];
					if($eStatus_tmp == "Active" && ($iParentId_tmp == 0 || $iParentId_tmp == "0")){
						$eShowRideVehicles = "Yes";
						break;
					}
				} else if ($data_temp['eCatType'] == "Delivery" || $data_temp['eCatType'] == "MotoDelivery"){
					$iParentId_tmp = $data_temp['iParentId'];
					$eStatus_tmp = $data_temp['eStatus'];
					if($eStatus_tmp == "Active" && ($iParentId_tmp == 0 || $iParentId_tmp == "0")){
						$eShowDeliveryVehicles = "Yes";
						break;
					}
				} else if ($data_temp['eCatType'] == "DeliverAll"){
					$iParentId_tmp = $data_temp['iParentId'];
					$eStatus_tmp = $data_temp['eStatus'];
					if($eStatus_tmp == "Active" && ($iParentId_tmp == 0 || $iParentId_tmp == "0")){
						$eShowDeliverAllVehicles = "Yes";
						break;
					}
				}

			}
		}

		// Ride Enable Checking
		########## Stage 1 ############ Get Main Category #######
		if($eShowRideVehicles == "No"){
			$main_category_ids = array();
			$count_main_category = 0;
			for($i=0;$i<count($rideDeliveryIconData);$i++){
				$data_temp = $rideDeliveryIconData[$i];
				if($data_temp['eCatType'] == "MoreDelivery" && $data_temp['eStatus'] == "Active"){
					$main_category_ids[$count_main_category] = $data_temp['iVehicleCategoryId'];
					$count_main_category++;
				}
			}

			if(count($main_category_ids) > 0){
				$sub_category_ids = array();
				$count_sub_category = 0;
				foreach ($main_category_ids as $k => $val) {
					for($i=0;$i<count($rideDeliveryIconData);$i++){
						$data_temp = $rideDeliveryIconData[$i];
						if($data_temp['eStatus'] == "Active" && $data_temp['iParentId'] == $val){
							$sub_category_ids[$count_sub_category] = $data_temp['iVehicleCategoryId'];
							$count_sub_category++;
						}
					}
				}

				if(count($sub_category_ids) > 0){
					$ssub_category_ids = array();
					$tempsubcat = array();
					$count_ssub_category = 0;

					foreach ($sub_category_ids as $k => $val) {
						for($i=0;$i<count($rideDeliveryIconData);$i++){
							$data_temp = $rideDeliveryIconData[$i];
							if($data_temp['eStatus'] == "Active" && $data_temp['iParentId'] == $val){
								$ssub_category_ids[$count_ssub_category] = $data_temp['iVehicleCategoryId'];
								$count_ssub_category++;
							} else if($data_temp['iParentId'] == $val){
								$tempsubcat[$count_ssub_category] = $data_temp['iVehicleCategoryId'];
								$count_ssub_deliverycategory++;
							}
						}
					}
					if( count($tempsubcat) == 0 || count($ssub_category_ids) > 0){
						$eShowRideVehicles	= "Yes";
					}
				}

			}

		}

		// Delivery Enable Checking
		########### Stage 1 ###########
		if($eShowDeliveryVehicles == "No"){
			$main_category_ids = array();
			$count_main_category = 0;
			for($i=0;$i<count($rideDeliveryIconData);$i++){
				$data_temp = $rideDeliveryIconData[$i];
				if($data_temp['eCatType'] == "MoreDelivery" && $data_temp['eFor'] == "DeliveryCategory" && $data_temp['eStatus'] == "Active"){
					$main_category_ids[$count_main_category] = $data_temp['iVehicleCategoryId'];
					$count_main_category++;
				}
			}
			// 178
			########## Stage 2 ############ Get Main Category #######
			if(count($main_category_ids) > 0){
				$sub_deliverycategory_ids = array();
				$count_sub_category = 0;
				foreach ($main_category_ids as $k => $val) {
					for($i=0;$i<count($rideDeliveryIconData);$i++){
						$data_temp = $rideDeliveryIconData[$i];
						if($data_temp['eStatus'] == "Active" && $data_temp['iParentId'] == $val){
							$sub_deliverycategory_ids[$count_sub_category] = $data_temp['iVehicleCategoryId'];
							$count_sub_category++;
						}
					}
				}

				if(count($sub_deliverycategory_ids) > 0){
					$ssub_deliverycategory_ids = array();
					$tempsubcat = array();
					$count_ssub_deliverycategory = 0;

					foreach ($sub_deliverycategory_ids as $k => $val) {
						for($i=0;$i<count($rideDeliveryIconData);$i++){
							$data_temp = $rideDeliveryIconData[$i];
							if($data_temp['eStatus'] == "Active" && $data_temp['iParentId'] == $val){
								$ssub_deliverycategory_ids[$count_ssub_deliverycategory] = $data_temp['iVehicleCategoryId'];
								$count_ssub_deliverycategory++;
							} else if($data_temp['iParentId'] == $val){
								$tempsubcat[$count_ssub_deliverycategory] = $data_temp['iVehicleCategoryId'];
								$count_ssub_deliverycategory++;
							}
						}
					}

					if(count($tempsubcat) == 0 || count($ssub_deliverycategory_ids) > 0) {
						$eShowDeliveryVehicles	= "Yes";
					}
				}
			}

		}

		// Deliverall Enable Checking
		########### Stage 1 ###########
		if($eShowDeliverAllVehicles == "No"){
			$main_Deliverallcategory_ids = array();
			$count_mainDeliverall_category = 0;
			for($i=0;$i<count($rideDeliveryIconData);$i++){
				$data_temp = $rideDeliveryIconData[$i];
				if($data_temp['eCatType'] == "MoreDelivery" && $data_temp['eFor'] == "DeliverAllCategory" && $data_temp['eStatus'] == "Active"){
					$main_Deliverallcategory_ids[$count_mainDeliverall_category] = $data_temp['iVehicleCategoryId'];
					$count_mainDeliverall_category++;
				}
			}
		}
		// 185
		########## Stage 2 ############ Get Main Category #######
		if(count($main_Deliverallcategory_ids) > 0){
			$sub_deliverAllcategory_ids = array();
			$count_deliverallsub_category = 0;
			foreach ($main_Deliverallcategory_ids as $k => $val) {
				for($i=0;$i<count($rideDeliveryIconData);$i++){
					$data_temp = $rideDeliveryIconData[$i];
					if($data_temp['eStatus'] == "Active" && $data_temp['iParentId'] == $val){
						$sub_deliverAllcategory_ids[$count_deliverallsub_category] = $data_temp['iVehicleCategoryId'];
						$count_deliverallsub_category++;
					}
				}
			}
			
			if(count($sub_deliverAllcategory_ids) > 0){
				$ssub_deliverallcategory_ids = array();
				$tempsubcat = array();
				$count_ssub_deliverallcategory = 0;
				foreach ($sub_deliverAllcategory_ids as $k => $val) {
					for($i=0;$i<count($rideDeliveryIconData);$i++){
						$data_temp = $rideDeliveryIconData[$i];
						if($data_temp['eStatus'] == "Active" && $data_temp['iParentId'] == $val){
							$ssub_deliverallcategory_ids[$count_ssub_deliverallcategory] = $data_temp['iVehicleCategoryId'];
							$count_ssub_deliverallcategory++;
						} elseif ($data_temp['iParentId'] == $val) {
							$tempsubcat[$count_ssub_deliverallcategory] = $data_temp['iVehicleCategoryId'];
							$count_ssub_deliverallcategory++;
						}
					}
				}
				
				if(count($tempsubcat) == 0 || count($ssub_deliverallcategory_ids) > 0){
					$eShowDeliverAllVehicles	= "Yes";
				}

			}

		}

        for($i=0;$i<count($rideDeliveryIconData);$i++){
            $data_temp = $rideDeliveryIconData[$i];
            if($data_temp['eCatType'] == "MotoRide" && $data_temp['eStatus'] == "Active"){
                $eMotoRideEnable = "Yes";
            } else if($data_temp['eCatType'] == "MotoDelivery" && $data_temp['eStatus'] == "Active"){
                $eMotoDeliveryEnable = "Yes";
            } else if($data_temp['eCatType'] == "Rental" && $data_temp['eStatus'] == "Active"){
                $eRentalEnable = "Yes";
            } else if($data_temp['eCatType'] == "MotoRental" && $data_temp['eStatus'] == "Active"){
                $eMotoRentalEnable = "Yes";
            }
        }
		
    }
	
    if ($eShowRideVehicles == "No" && $eShowDeliveryVehicles == "No") {
        $RideDeliveryBothFeatureDisable = "Yes";
    }
    $returnArr['eShowRideVehicles'] = $eShowRideVehicles;
    $returnArr['eShowDeliveryVehicles'] = $eShowDeliveryVehicles;
    $returnArr['eShowDeliverAllVehicles'] = $eShowDeliverAllVehicles;
    $returnArr['RideDeliveryBothFeatureDisable'] = $RideDeliveryBothFeatureDisable;

    $returnArr['eMotoRideEnable'] = $eMotoRideEnable;
    $returnArr['eMotoDeliveryEnable'] = $eMotoDeliveryEnable;
    $returnArr['eRentalEnable'] = $eRentalEnable;
    $returnArr['eMotoRentalEnable'] = $eMotoRentalEnable;
    return $returnArr;
}