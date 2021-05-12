<?php
session_start();
include_once 'common.php';
//$generalobj->go_to_home();

$sql = "select count('iDriverId') as Total from home_driver where eStatus='Active'";
$count_driver = $obj->MySQLSelect($sql);

if ($count_driver[0]['Total'] > 4) {
    $ssql = " order by rand()";
} else {
    $ssql = " order by iDisplayOrder";
}
$sql = "select * from home_driver where eStatus='Active' $ssql limit 4";
$db_home_drv = $obj->MySQLSelect($sql);
//echo "<pre>";print_r($db_home_drv);exit;
//for default country
$sql = "SELECT vCountry from country where eStatus = 'Active' and vCountryCode = '$DEFAULT_COUNTRY_CODE_WEB'";
$db_def_con = $obj->MySQLSelect($sql);
?>
<!DOCTYPE html>
<html lang="en" dir="<?= (isset($_SESSION['eDirectionCode']) && $_SESSION['eDirectionCode'] != "") ? $_SESSION['eDirectionCode'] : 'ltr'; ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <!--   <title><?= $SITE_NAME ?> | Login Page</title>-->
        <title><?php echo $meta_arr['meta_title']; ?></title>
        <!-- Default Top Script and css -->
        <?php include_once("top/top_script.php"); ?>
        <link rel="stylesheet" type="text/css" href="assets/css/tooltip-one/css/tooltipster.css" />
        <script type="text/javascript" src="assets/css/tooltip-one/js/jquery.tooltipster.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&language=en&key=<?= $GOOGLE_SEVER_API_KEY_WEB ?>"></script>
        <!-- End: Default Top Script and css-->
    </head>
    <body>
        <!-- home page -->
        <div id="main-uber-page">
            <!-- Left Menu -->
            <?php include_once("top/left_menu.php"); ?>
            <!-- End: Left Menu-->
            <!-- Top Menu -->
            <?php include_once("top/header_topbar.php"); ?>
            <!-- End: Top Menu-->
            <!-- contact page-->
            <? if ($APP_TYPE == "Ride" || $APP_TYPE == "Ride-Delivery" || $APP_TYPE == "Ride-Delivery-UberX" || $APP_TYPE == "Delivery") { ?>
                <!-- -->
                <div class="get-fare-estimation">
                    <div class="get-fare-estimation-inner">
                        <div class="get-free1">
                            <div class="get-fare-estimation-left ">
                                <h3><?= $langage_lbl['LBL_GET_FARE_ESTIMATION_TXT']; ?></h3>
                                <span>
                                    <form name="_fare_estimate_form" id="_fare_estimate_form" method="post" >
                                        <input type="hidden" name="distance" id="distance" value="">
                                        <input type="hidden" name="duration" id="duration" value="">
                                        <input type="hidden" name="from_lat_long" id="from_lat_long" value="" >
                                        <input type="hidden" name="from_lat" id="from_lat" value="" >
                                        <input type="hidden" name="from_long" id="from_long" value="" >
                                        <input type="hidden" name="to_lat_long" id="to_lat_long" value="" >
                                        <input type="hidden" name="to_lat" id="to_lat" value="" >
                                        <input type="hidden" name="to_long" id="to_long" value="" >
                                        <input type="hidden" name="location_found" id="location_found" value="" >
                                        <? if ($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'Ride-Delivery') { ?>
                                            <div class="col-lg-12 get-fare-estimation1">
                                                <div class="col-lg-4 input-av">
                                                    <strong><input type="radio" name="eType" value="Ride" checked id="eType_1" onchange="get_details()" class="input-av-a" ><label for="eType_1"></label></strong><?php echo $langage_lbl['LBL_RIDE_TXT']; ?>
                                                </div>
                                                <div class="col-lg-4 input-av">
                                                    <strong><input type="radio" name="eType" value="Deliver" id="eType_2" onchange="get_details()" class="input-av-a"><label for="eType_2"></label></strong><?php echo $langage_lbl['LBL_DELIVERY_TXT']; ?>
                                                </div>
                                            </div>
                                            <br/><br/>
                                        <? } ?>
                                        <b><input name="vPickup" type="text" id="from" placeholder="<?= $langage_lbl['LBL_HOME_ADD_PICKUP_LOC']; ?>" class="trip-start" /></b>
                                        <b><input name="vDest" type="text" id="to" placeholder="<?= $langage_lbl['LBL_ADD_DESTINATION_LOCATION_TXT']; ?>" class="trip-end" /><button type="button"><i aria-hidden="true" class="fa fa-arrow-right"></i></button></b>
                                    </form>
                                </span>
                                <div style="display:" id="setEstimate_figure">
                                </div>
                                <div class="" id="imageIcons" style="width:100%;display:none">
                                    <div align="center">                                                                       
                                        <img src="default.gif">                                                              
                                        <span>Retrieving details,please Wait...</span>                       
                                    </div>                                                                                 
                                </div>
                                <!-- <a href="#"><em>calculate</em></a>-->
                            </div>
                            <div class="home-page-map" id="map-canvas"></div>
                        </div>
                        <div style="clear:both;"></div>
                    </div>
                </div>
            <? } ?>
            <!-- footer part -->
            <?php include_once('footer/footer_home.php'); ?>
            <!-- footer part end -->
            <!-- -->
            <div style="clear:both;"></div>
        </div>
        <!-- home page end-->
        <!-- Footer Script -->
        <?php include_once('top/footer_script.php'); ?>
        <!-- End: Footer Script -->
        <script>
            var map;
            var geocoder;
            var autocomplete_from;
            var autocomplete_to;
            var cliclTriggerArr = [];
            function initialize() {
                geocoder = new google.maps.Geocoder();
                var mapOptions = {
                    zoom: 4,
                    //center: new google.maps.LatLng('20.1849963', '64.4125062')
                };
                map = new google.maps.Map(document.getElementById('map-canvas'),
                        mapOptions);

                var location = '<?= $db_def_con[0]['vCountry'] ?>';
                geocoder = new google.maps.Geocoder();
                geocoder.geocode({'address': location}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        //console.log(results);
                        map.setCenter(results[0].geometry.location);
                    } else {
                        alert("Could not find location: " + location);
                    }
                });
            }

            $(document).ready(function () {
                $("#setEstimate_figure").hide();
                google.maps.event.addDomListener(window, 'load', initialize);
            });


            $(function () {

                var from = document.getElementById('from');
                autocomplete_from = new google.maps.places.Autocomplete(from);
                google.maps.event.addListener(autocomplete_from, 'place_changed', function () {
                    var place = autocomplete_from.getPlace();
                    $("#from_lat_long").val(place.geometry.location);
                    $("#from_lat").val(place.geometry.location.lat());
                    $("#from_long").val(place.geometry.location.lng());
                    go_for_action();
                });

                var to = document.getElementById('to');
                autocomplete_to = new google.maps.places.Autocomplete(to);
                google.maps.event.addListener(autocomplete_to, 'place_changed', function () {
                    var place = autocomplete_to.getPlace();
                    $("#to_lat_long").val(place.geometry.location);
                    $("#to_lat").val(place.geometry.location.lat());
                    $("#to_long").val(place.geometry.location.lng());
                    go_for_action();
                });

                function go_for_action() {
                    if ($("#from").val() != '' && $("#to").val() == '') {
                        show_location($("#from_lat").val(), $("#from_long").val());
                    }
                    if ($("#to").val() != '' && $("#from").val() == '') {
                        show_location($("#to_lat").val(), $("#to_long").val());
                    }
                    if ($("#from").val() != '' && $("#to").val() != '') {
                        from_to($("#from").val(), $("#to").val());
                    }
                }
            });
        </script>
        <script type="text/javascript" src="assets/js/gmap3.js"></script>
        <script type="text/javascript">
            var chk_route;
            function show_location(set, dest) {
                //alert("show_location");
                clearThat();
                $('#map-canvas').gmap3({
                    marker: {
                        latLng: [set, dest]
                    },
                    map: {
                        options: {
                            zoom: 16
                        }
                    }
                });
            }

            function clearThat() {
                var opts = {};
                opts.name = ["marker", "directionsrenderer"];
                opts.first = true;
                $('#map-canvas').gmap3({clear: opts});
            }

            function from_to(from, to) {

                clearThat();
                if (from == '')
                    from = $('#from').val();
                if (to == '')
                    to = $('#to').val();
                if (from != '' && to != '') {

                    var fromLatlongs = $("#from_lat").val() + ", " + $("#from_long").val();
                    var toLatlongs = $("#to_lat").val() + ", " + $("#to_long").val();

                    $("#map-canvas").gmap3({
                        getroute: {
                            options: {
                                origin: fromLatlongs,
                                destination: toLatlongs,
                                travelMode: google.maps.DirectionsTravelMode.DRIVING
                            },
                            callback: function (results, status) {
                                // console.log(results);
                                chk_route = status;
                                if (!results) {
                                    alert('<?= $langage_lbl['LBL_PLEASE_ENTER_VALID_LOCATION'] ?>');
                                    return false;
                                }
                                $(this).gmap3({
                                    map: {
                                        options: {
                                            zoom: 8,
                                            //center: [51.511214, -0.119824]
                                            center: [58.0000, 20.0000]
                                        }
                                    },
                                    directionsrenderer: {
                                        options: {
                                            directions: results
                                        }
                                    }
                                });
                            }
                        }
                    });
                    get_details();
                }
            }
            function get_details() {
                var fromLatlongs = $("#from_lat").val() + ", " + $("#from_long").val();
                var toLatlongs = $("#to_lat").val() + ", " + $("#to_long").val();

                if ($("#from_lat").val() != "" && $("#to_lat").val() != "") {
                    $("#setEstimate_figure").hide();
                    $("#imageIcons").show();

                    $("#map-canvas").gmap3({
                        // alert('hello');
                        getdistance: {
                            options: {
                                origins: fromLatlongs,
                                destinations: toLatlongs,
                                travelMode: google.maps.TravelMode.DRIVING
                            },
                            callback: function (results, status) {
                                //$('.get-fare-estimation-left').addClass('new-dd001');

                                var html = "";
                                if (results) {
<? if ($APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Ride-Delivery-UberX') { ?>
                                        var eType = $("input[name='eType']:checked").val();
    <?
} else {
    if ($APP_TYPE == 'Delivery') {
        $eTYPE = 'Deliver';
    } else {
        $eTYPE = $APP_TYPE;
    }
    ?>
                                        var eType = '<?= $eTYPE; ?>';
<? } ?>
                                    // console.log(results);
                                    for (var i = 0; i < results.rows.length; i++) {
                                        var elements = results.rows[i].elements;
                                        for (var j = 0; j < elements.length; j++) {
                                            // console.log(elements);	
                                            switch (elements[j].status) {
                                                case "OK":
                                                    html += elements[j].distance.text + " (" + elements[j].duration.text + ")<br />";
                                                    document.getElementById("distance").value = elements[j].distance.value;
                                                    document.getElementById("duration").value = elements[j].duration.value;
                                                    var dist_fare = parseInt(elements[j].distance.value, 10) / parseInt(1000, 10);
                                                    $('#dist_fare').text(Math.round(dist_fare));
                                                    var time_fare = parseInt(elements[j].duration.value, 10) / parseInt(60, 10);
                                                    $('#time_fare').text(Math.round(time_fare));
                                                    var vehicleId = $('#iVehicleTypeId').val();
                                                    var fromLoc = $('#from').val();
                                                    var from_lat = $('#from_lat').val();
                                                    var from_long = $('#from_long').val();
                                                    var to_lat = $('#to_lat').val();
                                                    var to_long = $('#to_long').val();
                                                    $.ajax({
                                                        type: "POST",
                                                        url: 'ajax_find_estimate.php',
                                                        data: {dist_fare: dist_fare, time_fare: time_fare, fromLoc: fromLoc, from_lat: from_lat, from_long: from_long, to_lat: to_lat, to_long: to_long, eType: eType},
                                                        dataType: 'html',
                                                        success: function (dataHtml)
                                                        {
                                                            $("#imageIcons").hide();
                                                            $("#setEstimate_figure").show();
                                                            $("#setEstimate_figure").html(dataHtml);
                                                        }
                                                    });
                                                    document.getElementById("location_found").value = 1;
                                                    break;
                                                case "NOT_FOUND":
                                                    document.getElementById("location_found").value = 0;
                                                    break;
                                                case "ZERO_RESULTS":
                                                    document.getElementById("location_found").value = 0;
                                                    break;
                                            }
                                        }
                                    }
                                } else {
                                    html = "error";
                                }
                                $("#results").html(html);

                            }
                        }
                    });
                }
            }
            // $('.tooltipster-base').
            $(document).on('click', '.get-fare-estimation-left ul li label img', function () {
                setTimeout(() => {
                    $('.tooltipster-base').addClass('custom-tooltip');
                }, 200);
            });
            function getVehicleFare(elem) {
                var iVehicleTypeId = $(elem).attr("data-id");
                var time_fare = $(elem).attr("data-time");
                var dist_fare = $(elem).attr("data-dist");
                var fromLoc = $('#from').val();
                var from_lat = $('#from_lat').val();
                var from_long = $('#from_long').val();
                var to_lat = $('#to_lat').val();
                var to_long = $('#to_long').val();
                var etype = $(elem).attr("data-etype");
                $.ajax({
                    type: "POST",
                    url: 'ajax_find_estimate.php',
                    data: {estimete: "single", iVehicleTypeId: iVehicleTypeId, dist_fare: dist_fare, time_fare: time_fare, fromLoc: fromLoc, from_lat: from_lat, from_long: from_long, to_lat: to_lat, to_long: to_long, eType: etype},
                    dataType: 'html',
                    success: function (dataHtml)
                    {
                        console.log(dataHtml);
                        dataHtml = dataHtml
                        openTooltip(iVehicleTypeId, dataHtml);
                        var checkId = cliclTriggerArr.indexOf(iVehicleTypeId);
                        if (checkId == "-1") {
                            $("#tooltip_" + iVehicleTypeId).trigger("click");
                            cliclTriggerArr.push(iVehicleTypeId);
                        }
                    }
                });
            }
            function openTooltip(iVehicleTypeId, dataHtml) {
                $("#tooltip_" + iVehicleTypeId).tooltipster({
                    animation: 'grow',
                    content: dataHtml,
                    multiple: true,
                    contentAsHTML: true,
                    contentCloning: true,
                    interactive: true,
                    // side: ['right', 'left','top', 'bottom'],
                    position: 'top',
                    trigger: 'click',
                    maxWidth: 490,
                    theme: 'tooltipster-pink',
                    functionReady: function (origin, tooltip) {
                        tooltip.on("click", function () {
                            tooltip.hide();
                        });
                    }
                });
            }

        </script>
    </body>
</html>
