<?php

//added by SP as discussed with bmam on 28-6-2019
$adminUsersTxt = $langage_lbl_admin['LBL_ADMIN'];
if ($PACKAGE_TYPE == 'SHARK' && ($APP_TYPE == 'Ride' || $APP_TYPE == 'Ride-Delivery-UberX') && ONLYDELIVERALL == 'No') {
    $adminUsersTxt = $langage_lbl_admin['LBL_ADMIN'] . '/Hotel';
}
//var_dump($langage_lbl_admin['LBL_RESTAURANT_TXT_ADMIN']);
//var_dump($langage_lbl_admin);
$restaurantAdmin = "Store";
if(isset($langage_lbl_admin['LBL_RESTAURANT_TXT_ADMIN'])){
    $restaurantAdmin = $langage_lbl_admin['LBL_RESTAURANT_TXT_ADMIN'];
}
$menu = [
    [
        'title' => 'Dashboard',
        'url' => "dashboard.php",
        "icon" => 'fa fa-tachometer',
    ], [
        'title' => 'Site Statistics',
        'url' => "dashboard-a.php",
        "icon" => 'fa fa-sitemap',
        "active" => "site",
        "visible" => $userObj->hasPermission('view-site-statistics'),
    ], [
        'title' => 'Profile',
        'url' => "profile.php",
        "icon" => ['class' => "icon-user1", 'url' => 'images/icon/admin-icon.png'],
        "active" => "profile",
        "visible" => $userObj->hasPermission('manage-profile') && $_SESSION['sess_iGroupId'] != '1' /* && $APP_TYPE == 'Ride' */ && ( ENABLEHOTELPANEL == 'Yes' || ENABLEKIOSKPANEL == 'Yes'),
    ], [
        'title' => 'Create request',
        'url' => "create_request.php",
        "icon" => ['class' => "icon-user1", 'url' => 'images/manual-taxi-icon.png'],
        "active" => "booking",
        "visible" => $userObj->hasPermission('manage-create-request') && $_SESSION['sess_iGroupId'] != '1' /* && $APP_TYPE == 'Ride' */ && (ENABLEHOTELPANEL == 'Yes' || ENABLEKIOSKPANEL == 'Yes'),
    ], [
        'title' => 'Admin',
        'url' => "javascript:",
        "icon" => ['class' => "icon-user1", 'url' => "images/icon/admin-icon.png"],
        "visible" => ($userObj->hasRole(1) || $userObj->hasPermission('view-admin')),
        'children' => [
            [
                'title' => $adminUsersTxt,
                'url' => "admin.php",
                "icon" => "fa fa-certificate",
                "active" => "Admin",
            ], [
                'title' => 'Admin Groups',
                'url' => "admin_groups.php",
                "icon" => "fa fa-certificate",
                "active" => "AdminGroups",
                "visible" => $userObj->hasRole(1) && $PACKAGE_TYPE == 'SHARK',
            ], /* [
          'title' => 'Permissions',
          'url' => "admin_permissions.php",
          "icon" => "fa fa-certificate",
          "active" => "AdminPermissions",
          "visible" => $userObj->hasRole(1),
          ], */
        ],
    ], [
        'title' => 'Company',
        'url' => "company.php",
        "icon" => "fa fa-building-o",
        "active" => "Company",
        "visible" => $userObj->hasPermission('view-company'),
    ], [
        'title' => $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'],
        'url' => "driver.php",
        "icon" => ["class" => "icon-user1", "url" => "images/icon/driver-icon.png"],
        "active" => "Driver",
        "visible" => $userObj->hasPermission('view-providers'),
    ],
    [
        'title' => $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'] . " Vehicles",
        'url' => "vehicles.php",
        "icon" => "fa fa-taxi",
        "active" => "Vehicle",
        "visible" => ($userObj->hasPermission('view-provider-taxis') && $APP_TYPE != 'Ride-Delivery-UberX'),
    ], [
        'title' => $langage_lbl_admin['LBL_VEHICLE_TYPE_RENTAL_TXT'],
        'url' => "rental_vehicle_list.php",
        "icon" => "fa fa-dot-circle-o",
        "active" => "Rental Package",
        "visible" => ($userObj->hasPermission('view-rental-packages') && ($APP_TYPE == 'Ride' || $APP_TYPE == 'Ride-Delivery') && ENABLE_RENTAL_OPTION == 'Yes'),
    ], [
        'title' => $langage_lbl_admin['LBL_VEHICLE_TYPE_SMALL_TXT'],
        'url' => "vehicle_type.php",
        "icon" => ["class" => "icon-user1", "url" => "images/icon/vehicle-type-icon.png"],
        "active" => "VehicleType",
        "visible" => ($userObj->hasPermission('view-vehicle-type') && $APP_TYPE != 'Ride-Delivery-UberX'),
    ], [
        'title' => $langage_lbl_admin['LBL_DRIVER_SUBSCRIPTION'],
        'url' => "javascript:",
        "icon" => ['class' => "icon-user1", 'url' => "images/icon/subscription-icon.png"],
        "visible" => (($userObj->hasPermission('manage-driver-subscription') || $userObj->hasPermission('manage-driver-subscription-report')) && $DRIVER_SUBSCRIPTION_ENABLE == 'Yes' && ONLYDELIVERALL != "Yes"),
        'children' => [
            [
                'title' => $langage_lbl_admin['LBL_DRIVER_SUBSCRIPTION_PLAN'],
                'url' => "driver_subscription.php",
                "icon" => "fa fa-building-o",
                "active" => "DriverSubscriptionPlan",
                "visible" => ($userObj->hasPermission('manage-driver-subscription') && $DRIVER_SUBSCRIPTION_ENABLE == 'Yes' && ONLYDELIVERALL != "Yes"),
            ],
            [
                'title' => $langage_lbl_admin['LBL_DRIVER_SUBSCRIPTION_REPORT'],
                'url' => "driver_subscription_report.php",
                "icon" => ["class" => "icon-user1", "url" => "images/icon/subscriptionreport-icon.png"],
                "active" => "DriverSubscriptionReport",
                "visible" => ($userObj->hasPermission('manage-driver-subscription-report') && $DRIVER_SUBSCRIPTION_ENABLE == 'Yes' && ONLYDELIVERALL != "Yes"),
            ],
        ]
    ],
    [
        'title' => 'Service Category',
        "url" => "vehicle_category.php",
        "icon" => "fa fa-certificate",
        "active" => "VehicleCategory",
        "visible" => ($userObj->hasPermission('view-service-category') && $APP_TYPE != 'Ride-Delivery-UberX' && ($APP_TYPE != "Ride" && ONLYDELIVERALL != "Yes")),
    ],
    [
        'title' => "Manage Services",
        "icon" => "fa fa-wrench",
        "visible" => ($userObj->hasPermission('manage-services') && $APP_TYPE == 'Ride-Delivery-UberX'),
        'children' => [
            [
                'title' => 'Ride/ Delivery Vehicles',
                "icon" => "fa fa-taxi",
                "visible" => $userObj->hasPermission('manage-vehicles'),
                'children' => [
                    [
                        'title' => $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'] . ' Vehicles',
                        "url" => "vehicles.php",
                        "icon" => "fa fa-dot-circle-o",
                        "active" => "Vehicle",
                        "visible" => $userObj->hasPermission('view-provider-taxis'),
                    ], [
                        'title' => $langage_lbl_admin['LBL_VEHICLE_TYPE_SMALL_TXT'],
                        "url" => "vehicle_type.php",
                        "icon" => "fa fa-dot-circle-o",
                        "active" => "VehicleType",
                        "visible" => $userObj->hasPermission('view-vehicle-type'),
                    ], [
                        'title' => $langage_lbl_admin['LBL_VEHICLE_TYPE_RENTAL_TXT'],
                        "url" => "rental_vehicle_list.php",
                        "icon" => "fa fa-dot-circle-o",
                        "active" => "Rental Package",
                        "visible" => ($userObj->hasPermission('view-rental-packages') && ENABLE_RENTAL_OPTION == 'Yes'),
                    ],
                ],
            ], [
                'title' => 'Manage Other Services',
                "icon" => "fa fa-wrench",
                "visible" => $userObj->hasPermission(['view-service-category', 'view-service-type']),
                'children' => [
                    [
                        'title' => 'Service Category',
                        "url" => "vehicle_category.php",
                        "icon" => "fa fa-certificate",
                        "active" => "VehicleCategory",
                        "visible" => $userObj->hasPermission('view-service-category'),
                    ], [
                        'title' => "Service Type",
                        "url" => "service_type.php",
                        "icon" => "fa fa-wrench",
                        "active" => "ServiceType",
                        "visible" => $userObj->hasPermission('view-service-type'),
                    ],[
                        'title' => "Driver Service Requests",
                        "url" => "driver_service_request.php",
                        "icon" => "fa fa-wrench",
                        "active" => "DriverRequest",
                        "visible" => $userObj->hasPermission('view-driver-service-request') && ($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == "UberX"),
                    ],
                ],
            ],
        ],
    ], /* [
      'title' => "App Main Screen Settings",
      'url' => "app_home_settings.php",
      "icon" => "fa fa-globe",
      "active" => "App Main Screen Settings",
      "visible" => ($userObj->hasPermission('manage-app-main-screen-settings') && $APP_TYPE == 'Ride-Delivery-UberX'),
      ], */ [
        'title' => $langage_lbl_admin['LBL_RIDER_NAME_TXT_ADMIN'],
        'url' => "rider.php",
        "icon" => ["class" => "icon-group1", "url" => "images/rider-icon.png"],
        "active" => "Rider",
        "visible" => $userObj->hasPermission('view-users'),
    ], [
        'title' => "Manual Booking",
        'url' => "add_booking.php",
        "icon" => ["class" => "fa fa-taxi1", "url" => "images/manual-taxi-icon.png"],
        "active" => "booking",
        "visible" => ($userObj->hasPermission('manage-manual-booking') && $RIDE_LATER_BOOKING_ENABLED == 'Yes'),
    // && ($APP_TYPE != "Delivery" || ($APP_TYPE == "Delivery" && $APP_DELIVERY_MODE != "Multi"))
    ],  [ 
      'title'   => $langage_lbl_admin['LBL_MANUAL_STORE_ORDER_TXT'],
      'url'     => "../user-order-information?order=admin",
      "icon"    => ["class" => "fa fa-taxi1", "url" => "images/manual-taxi-icon.png"],
      "active"  => "store_order_book",
      "target"  => "blank",
      "visible" => ($userObj->hasPermission('manage-restaurant-order') && DELIVERALL == "Yes" && $MANUAL_STORE_ORDER_ADMIN_PANEL=="Yes"),
      ], [
        'title' => $langage_lbl_admin['LBL_RIDE_LATER_BOOKINGS_ADMIN'],
        'url' => "cab_booking.php",
        "icon" => ["class" => "icon-book1", "url" => "images/ride-later-bookings.png"],
        "active" => "CabBooking",
        "visible" => ($userObj->hasPermission('manage-ride-job-later-bookings') && $RIDE_LATER_BOOKING_ENABLED == 'Yes'),
    ], [
        'title' => $langage_lbl_admin['LBL_TRIPS_TXT_ADMIN'],
        'url' => "trip.php",
        "icon" => ["class" => "fa fa-exchange1", "url" => "images/trips-icon.png"],
        "active" => "Trips",
        "visible" => $userObj->hasPermission('manage-trip-jobs'),
    ], [
        'title' => "Manage Stores",
        "icon" => "fa fa-building-o",
        "visible" => ($userObj->hasPermission('manage-stores') && DELIVERALL == "Yes"),
        "li_attr" => ["style" => "background-color: hsla(21, 100%, 50%, 0.6);"],
        'children' => [
            [
                'title' => 'Store Dashboard',
                "url" => "store_dashboard.php",
                "icon" => "fa fa-tachometer",
                "active" => "Store Dashboard",
                "visible" => $userObj->hasPermission('manage-store-dashboard'),
            ], [
                'title' => $restaurantAdmin,
                "url" => "store.php",
                "icon" => "fa fa-building-o",
                "active" => "DeliverAllStore",
                "visible" => $userObj->hasPermission('view-store'),
            ], [
                'title' => $langage_lbl_admin['LBL_VEHICLE_TYPE_SMALL_TXT'],
                "url" => "store_vehicle_type.php",
                "icon" => "fa fa-taxi",
                "active" => "StoreVehicleType",
                "visible" => $userObj->hasPermission('view-vehicle-type'),
            ], [
                'title' => $restaurantAdmin . " Items",
                "icon" => "icon-food",
                "visible" => $userObj->hasPermission('manage-store-items'),
                'children' => [
                    [
                        'title' => "Item Category",
                        "url" => "food_menu.php",
                        "icon" => "fa fa-cutlery",
                        "active" => "FoodMenu",
                        "visible" => $userObj->hasPermission('view-item-categories'),
                    ], [
                        'title' => "Items",
                        "url" => "menu_item.php",
                        "icon" => "fa fa-list-alt",
                        "active" => "MenuItems",
                        "visible" => $userObj->hasPermission('view-item'),
                    ], [
                        'title' => "Item Type",
                        "url" => "cuisine.php",
                        "icon" => "fa fa-spoon",
                        "active" => "Cuisine",
                        "visible" => $userObj->hasPermission('view-item-type'),
                    ],
                ],
            ], [
                'title' => "Store Orders",
                "icon" => "fa fa-first-order",
                "visible" => $userObj->hasPermission('manage-orders'),
                'children' => [
                    [
                        'title' => "Processing",
                        "url" => "allorders.php?type=processing",
                        "icon" => "fa fa-fire",
                        "active" => "Processing Orders",
                        "visible" => $userObj->hasPermission('view-processing-orders'),
                    ], [
                        'title' => "Cancelled",
                        "url" => "cancelled_orders.php",
                        "icon" => "fa fa-undo",
                        "active" => "CancelledOrders",
                        "visible" => $userObj->hasPermission('view-cancelled-orders'),
                    ], [
                        'title' => "All Orders",
                        "url" => "allorders.php?type=allorders",
                        "icon" => "fa fa-list",
                        "active" => "All Orders",
                        "visible" => $userObj->hasPermission('view-all-orders'),
                    ],
                ],
            ], [
                'title' => "Site Earning",
                "icon" => "icon-money",
                "visible" => $userObj->hasPermission('manage-site-earning'),
                'children' => [
                    [
                        'title' => "Admin Earning",
                        "url" => "admin_payment_report.php",
                        "icon" => "fa fa-fire",
                        "active" => "Admin Payment_Report",
                        "visible" => $userObj->hasPermission('manage-admin-earning'),
                    ], [
                        'title' => "Payout to " . $restaurantAdmin,
                        "url" => "restaurants_pay_report.php",
                        "icon" => "fa fa-undo",
                        "active" => "Restaurant Payment Report",
                        "visible" => $userObj->hasPermission('manage-store-payment'),
                    ], [
                        'title' => "Payout to " . $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'],
                        "url" => "store_driver_pay_report.php",
                        "icon" => "fa fa-list",
                        "active" => "Deliverall Driver Payment Report",
                        "visible" => $userObj->hasPermission('manage-provider-payment'),
                    ], [
                        'title' => "Cancelled Order Report",
                        "url" => "cancelled_report.php",
                        "icon" => "fa fa-list",
                        "active" => "Cancelled Order Report",
                        "visible" => $userObj->hasPermission('manage-cancelled-order-report'),
                    ], [
                        'title' => "Insurance Report",
                        "icon" => "fa fa-globe",
                        "visible" => $PACKAGE_TYPE == 'SHARK' && $APP_TYPE != 'UberX' && strtoupper(ONLYDELIVERALL) != "YES",
                        'children' => [
                            [
                                'title' => "Idle Time",
                                "url" => "insurance_idle_report.php",
                                "icon" => "fa fa-taxi",
                                "active" => "Insurance_Idle_time_Report",
                                "visible" => $PACKAGE_TYPE == 'SHARK' && $APP_TYPE != 'UberX',
                            ], [
                                'title' => "After " . $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'] . " Accept",
                                "url" => "insurance_accept_report.php",
                                "icon" => "fa fa-taxi",
                                "active" => "Insurance_accept_trip_Report",
                                "visible" => $PACKAGE_TYPE == 'SHARK' && $APP_TYPE != 'UberX',
                            ], [
                                'title' => "After " . $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'] . " Start",
                                "url" => "insurance_trip_report.php",
                                "icon" => "fa fa-taxi",
                                "active" => "Insurance_start_trip_Report",
                                "visible" => $PACKAGE_TYPE == 'SHARK' && $APP_TYPE != 'UberX',
                            ]
                        ],
                    ]
                ],
            ], [
                'title' => "Utility",
                "icon" => "fa fa-wrench",
                "visible" => $userObj->hasPermission('manage-store-utility'),
                'children' => [
                    [
                        'title' => "DeliveryAll Service Category",
                        "url" => "service_category.php",
                        "icon" => "fa fa-question",
                        "active" => "service_category",
                        "visible" => $userObj->hasPermission('view-service-category') && ONLYDELIVERALL == 'Yes',
                    ], [
                        'title' => "Order Status",
                        "url" => "order_status.php",
                        "icon" => "fa fa-first-order",
                        "active" => "order_status",
                        "visible" => $userObj->hasPermission('view-order-status'),
                    ], [
                        'title' => "Delivery Charges",
                        "url" => "delivery_charges.php",
                        "icon" => "fa fa-map-signs",
                        "active" => "Delivery Charges",
                        "visible" => $userObj->hasPermission('view-delivery-charges'),
                    ], [
                        'title' => "Banner",
                        "url" => "store_banner.php",
                        "icon" => "icon-angle-right",
                        "active" => "Store Banner",
                        "visible" => $userObj->hasPermission('view-banner'),
                    ],
                /* [

                  'title'   => "Cancel Reason",

                  "url"     => "store_cancel_reason.php",

                  "icon"    => "fa fa-question",

                  "active"  => "cancel_reason",

                  "visible" => $userObj->hasPermission('view-cancel-reasons'),

                  ], */
                ],
            ], [
                'title' => "Orders Reviews",
                "url" => "store_review.php",
                "icon" => "icon-comments",
                "active" => "Store Review",
                "visible" => $userObj->hasPermission('manage-store-reviews'),
            ],
        ],
    ], [
        'title' => "PromoCode",
        "url" => "coupon.php",
        "icon" => ["class" => "fa fa-product-hunt1", "url" => "images/promo-code-icon.png"],
        "active" => "Coupon",
        "visible" => $userObj->hasPermission('view-promocode'),
    ], [
        'title' => "God's View",
        "url" => "map_godsview.php",
        "icon" => ["class" => "icon-map-marker1", "url" => "images/god-view-icon.png"],
        "active" => "LiveMap",
        "visible" => $userObj->hasPermission('manage-gods-view'),
    ], [
        'title' => "Heat View",
        "url" => "heatmap.php",
        "icon" => ["class" => "fa-header1", "url" => "images/heat-icon.png"],
        "active" => "Heat Map",
        "visible" => $userObj->hasPermission('manage-heat-view'),
    ], [
        'title' => "Reviews",
        "url" => "review.php",
        "icon" => ["class" => "icon-comments1", "url" => "images/reviews-icon.png"],
        "active" => "Review",
        "visible" => $userObj->hasPermission('manage-reviews'),
    ], [
        'title' => "Advertisement Banners",
        'url' => "advertise_banners.php",
        "icon" => "fa fa-bullhorn",
        "active" => "Advertisement Banners",
        "visible" => $userObj->hasPermission('view-advertise-banner') && ($ADVERTISEMENT_TYPE != 'Disable' && $PACKAGE_TYPE == 'SHARK'),
    ],
    [
        'title' => 'Decline/Cancelled Alert',
        'url' => "blocked_driver.php",
        "icon" => "fa fa-bullhorn",
        "active" => "Driver",
        "visible" => $userObj->hasPermission('view-blocked-driver')&& $PACKAGE_TYPE == 'SHARK' && strtoupper(ONLYDELIVERALL) != "YES", //This Module Enable For Shark Package As Per Discss With KS Sir By HJ On 05-11-2019
        'children' => [
            [
                'title' => 'Alert For ' . $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'],
                'url' => "blocked_driver.php",
                "icon" => "fa fa-user",
                "active" => "blockeddriver",
                "visible" => $userObj->hasPermission('view-blocked-driver'),
            ], [
                'title' => 'Alert For ' . $langage_lbl_admin['LBL_RIDER'],
                'url' => "blocked_rider.php",
                "icon" => "fa fa-user",
                "active" => "blockedrider",
                "visible" => $userObj->hasPermission('view-blocked-rider'),
            ]
        ],
    ],
    [
        'title' => "Reports",
        "icon" => ["class" => "icon-cogs1", "url" => "images/reports-icon.png"],
        "visible" => $userObj->hasPermission('manage-report'),
        'children' => [
            [
                'title' => "Payment Report",
                "url" => "payment_report.php",
                "icon" => "icon-money",
                "active" => "Payment_Report",
                "visible" => $userObj->hasPermission('manage-payment-report'),
            ], [
                'title' => "Hotel Payment Report",
                "url" => "hotel_payment_report.php",
                "icon" => "icon-money",
                "active" => "hotelPayment_Report",
                "visible" => $userObj->hasPermission('manage-hotel-payment-report') && (ENABLEHOTELPANEL == 'Yes' || ENABLEKIOSKPANEL == 'Yes' ),
            ], [
                'title' => "Referral Report",
                "url" => "referrer.php",
                "icon" => "fa fa-hand-peace-o",
                "active" => "referrer",
                "visible" => ($userObj->hasPermission('manage-referral-report') && $REFERRAL_SCHEME_ENABLE == 'Yes'),
            ], [
                'title' => "Wallet Report",
                "url" => "wallet_report.php",
                "icon" => "fa fa-google-wallet",
                "active" => "Wallet Report",
                "visible" => ($userObj->hasPermission('manage-user-wallet-report') && $WALLET_ENABLE == 'Yes'),
            ], [
                'title' => $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'] . " Payment Report",
                "url" => "driver_pay_report.php",
                "icon" => "icon-money",
                "active" => "Driver Payment Report",
                "visible" => $userObj->hasPermission('manage-provider-payment-report'),
            ], [
                'title' => $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'] . " Log Report",
                "url" => "driver_log_report.php",
                "icon" => "glyphicon glyphicon-list-alt",
                "active" => "Driver Log Report",
                "visible" => $userObj->hasPermission('manage-provider-log-report'),
            ], [
                'title' => "Cancelled " . $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'],
                "url" => "cancelled_trip.php",
                "icon" => "fa fa-exchange",
                "active" => "CancelledTrips",
                "visible" => $userObj->hasPermission('manage-cancelled-trip-job-report'),
            ], [
                'title' => $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'] . " Acceptance Report",
                "url" => "ride_acceptance_report.php",
                "icon" => "icon-group",
                "active" => "Driver Accept Report",
                "visible" => $userObj->hasPermission('manage-trip-job-request-acceptance-report'),
            ], [
                'title' => $langage_lbl_admin['LBL_TRIP_TXT_ADMIN'] . " Time Variance",
                "url" => "driver_trip_detail.php",
                "icon" => "fa fa-taxi",
                "active" => "Driver Trip Detail",
                "visible" => $userObj->hasPermission('manage-trip-job-time-variance-report'),
            ]
        ],
    ], [
        'title' => "Manage Locations",
        "icon" => ["class" => "fa fa-header1", "url" => "images/location-icon.png"],
        "visible" => $userObj->hasPermission('manage-locations'),
        'children' => [
            [
                'title' => "Geo Fence Location",
                "url" => "location.php",
                "icon" => "fa fa-map-marker",
                "active" => "Location",
                "visible" => $userObj->hasPermission('view-geo-fence-locations'),
            ],
            [
                'title' => "Restricted Area",
                "url" => "restricted_area.php",
                "icon" => "fa fa-map-signs",
                "active" => "Restricted Area",
                "visible" => $userObj->hasPermission('view-restricted-area'),
            ],
            [
                'title' => "Locationwise Fare",
                "url" => "locationwise_fare.php",
                "icon" => "fa fa-map-signs",
                "active" => "locationwise_fare",
                "visible" => $userObj->hasPermission('view-location-wise-fare') && $PACKAGE_TYPE != 'STANDARD' && $APP_TYPE != 'Delivery' && $APP_TYPE != 'UberX',
            ], [
                'title' => "Airport Surcharge",
                "url" => "airport_surcharge.php",
                "icon" => "fa fa-map-signs",
                "active" => "airportsurcharge_fare",
                "visible" => $userObj->hasPermission('view-airport-surcharge') && $ENABLE_AIRPORT_SURCHARGE_SECTION == "Yes" && $APP_TYPE != 'UberX' && $APP_TYPE != 'Delivery' && (ONLYDELIVERALL == 'No'),
            ],
        ],
    ], [
        'title' => "Settings",
        "icon" => ["class" => "icon-cogs1", "url" => "images/settings-icon.png"],
        "visible" => $userObj->hasPermission('manage-settings'),
        'children' => [
            [
                'title' => "General",
                "url" => "general.php",
                "icon" => "fa-cogs fa",
                "active" => "General",
                "visible" => $userObj->hasPermission('manage-general-settings'),
            ],
            [
                'title' => "Email Templates",
                "url" => "email_template.php",
                "icon" => "fa fa-envelope",
                "active" => "email_templates",
                "visible" => $userObj->hasPermission('view-email-templates'),
            ],
            [
                'title' => "SMS Templates",
                "url" => "sms_template.php",
                "icon" => "fa fa-comment",
                "active" => "sms_templates",
                "visible" => $userObj->hasPermission('view-sms-templates'),
            ],
            [
                'title' => "Manage Documents",
                "url" => "document_master_list.php",
                "icon" => "fa fa-file-text",
                "active" => "Document Master",
                "visible" => $userObj->hasPermission('view-documents'),
            ], [
                'title' => "Language Label",
                "icon" => "fa fa-language",
                "visible" => $userObj->hasPermission('manage-language-label') && (DELIVERALL == 'Yes' || ONLYDELIVERALL == 'Yes'),
                'children' => function () {

                    global $allservice_cat_data, $userObj;

                    $languages_childs = [
                        [
                            'title' => "General Label",
                            "url" => "languages.php",
                            "icon" => "fa fa-language",
                            "active" => "language_label",
                            "visible" => $userObj->hasPermission('view-general-label'),
                        ],
                    ];

                    if (count($allservice_cat_data) >= 1 && !empty($allservice_cat_data)) {

                        foreach ($allservice_cat_data as $key => $value) {

                            $languages_childs[] = [
                                'title' => $value['vServiceName'] . " Label",
                                'url' => "languages.php?selectedlanguage=" . $value['iServiceId'],
                                "icon" => 'fa fa-dot-circle-o',
                                "active" => "language_label_" . $value['iServiceId'],
                                "visible" => $userObj->hasPermission('view-general-label'),
                            ];
                        }
                    }

                    return $languages_childs;
                }
            ], [
                'title' => "Language Label",
                "url" => "languages.php",
                "icon" => "fa fa-language",
                "active" => "language_label",
                "visible" => $userObj->hasPermission('manage-language-label') && (!(DELIVERALL == 'Yes' || ONLYDELIVERALL == 'Yes')),
            ], [
                'title' => "Currency",
                "url" => "currency.php",
                "icon" => "fa fa-usd",
                "active" => "Currency",
                "visible" => $userObj->hasPermission('manage-currency'),
            ], [
                'title' => "Language",
                "url" => "language.php",
                "icon" => "fa fa-language",
                "active" => "Language",
                "visible" => $userObj->hasPermission('manage-language'),
            ], [
                'title' => "SEO Settings",
                "url" => "seo_setting.php",
                "icon" => "fa fa-info",
                "active" => "seo_setting",
                "visible" => $userObj->hasPermission('view-seo-setting'),
            ], [
                'title' => "Banner",
                "url" => "banner.php",
                "icon" => "icon-angle-right",
                "active" => "Banner",
                "visible" => ($userObj->hasPermission('view-banner') && $APP_TYPE == 'UberX'),
            ],
        ],
    ], [
        'title' => "Utility",
        "icon" => "fa fa-wrench",
        "visible" => $userObj->hasPermission('manage-utility'),
        'children' => [
            [
                'title' => "Localization",
                "icon" => "fa fa-globe",
                "visible" => $userObj->hasPermission('manage-localization'),
                'children' => [
                    [
                        'title' => "Country",
                        "url" => "country.php",
                        "icon" => "fa fa-dot-circle-o",
                        "active" => "country",
                        "visible" => $userObj->hasPermission('view-country'),
                    ],
                    [
                        'title' => "State",
                        "url" => "state.php",
                        "icon" => "fa fa-dot-circle-o",
                        "active" => "state",
                        "visible" => $userObj->hasPermission('view-state'),
                    ],
                    [
                        'title' => "City",
                        "url" => "city.php",
                        "icon" => "fa fa-dot-circle-o",
                        "active" => "city",
                        "visible" => $userObj->hasPermission('view-city') && ($SHOW_CITY_FIELD=='Yes'),
                    ],
                ],
            ], [
                'title' => "Pages",
                "url" => "page.php",
                "icon" => "fa fa-file-text-o",
                "active" => "page",
                "visible" => $userObj->hasPermission('view-pages'),
            ], [
                'title' => "Edit Home Page",
                "url" => "home_content_new.php",
                "icon" => "fa fa-file-text-o",
                "active" => "homecontent",
                "visible" => $userObj->hasPermission('view-home-page-content') && ($APP_TYPE == 'Ride-Delivery-UberX'),
            ], [
                'title' => "Edit Home Page",
                "url" => "home_content.php",
                "icon" => "fa fa-file-text-o",
                "active" => "home_content",
                "visible" => $userObj->hasPermission('view-home-page-content') && ($APP_TYPE != 'Ride-Delivery-UberX'),
            ], [
                'title' => "Our " . $langage_lbl_admin['LBL_DRIVER_TXT_ADMIN'],
                "url" => "home_driver.php",
                "icon" => "fa fa-users",
                "active" => "home_driver",
                "visible" => $userObj->hasPermission('view-our-provider') && ($APP_TYPE != 'Ride-Delivery-UberX' && $APP_TYPE != 'Ride'),
            ], [
                'title' => $langage_lbl_admin['LBL_CAR_MAKE_ADMIN'],
                "url" => "make.php",
                "icon" => "fa fa-car",
                "active" => "Make",
                "visible" => $userObj->hasPermission('view-vehicle-make') && ($APP_TYPE != 'UberX'),
            ], [
                'title' => $langage_lbl_admin['LBL_CAR_MODEL_ADMIN'],
                "url" => "model.php",
                "icon" => "fa fa-taxi",
                "active" => "Model",
                "visible" => $userObj->hasPermission('view-vehicle-model') && ($APP_TYPE != 'UberX'),
            ], [
                'title' => "Kiosk predefined destination",
                "url" => "visit.php",
                "icon" => "fa fa-map-marker",
                "active" => "Visit",
                "visible" => $userObj->hasPermission('view-visit') && ENABLEKIOSKPANEL == 'Yes' && strtoupper($PACKAGE_TYPE) == 'SHARK', //added by SP on 1-7-2019 by asking with KS
            ], [
                'title' => "Donation",
                "url" => "donation.php",
                "icon" => "fa fa-money",
                "active" => "Donation",
                "visible" => $userObj->hasPermission('view-donation') && ($DONATION == 'Yes' && $DONATION_ENABLE == "Yes"),
            ], [
                'title' => "Hotel Banner",
                "url" => "hotel_banner.php",
                "icon" => "fa fa-bullhorn",
                "active" => "hotel_banners",
                "visible" => $userObj->hasPermission('view-banner') && ENABLEKIOSKPANEL == 'Yes' && strtoupper($PACKAGE_TYPE) == 'SHARK', //added by SP on 1-7-2019 by asking with KS
            ], [
                'title' => "News",
                "url" => "news.php",
                "icon" => "fa fa-file-text-o",
                "active" => "news",
                "visible" => $userObj->hasPermission('view-news') && ($ENABLE_NEWS_SECTION == 'Yes'),
            ], [
                'title' => "Newsletter Subscribers",
                "url" => "newsletter.php",
                "icon" => "fa fa-file-text-o",
                "active" => "newsletters-subscribers",
                "visible" => $userObj->hasPermission('manage-newsletter') && ($ENABLE_NEWSLETTERS_SUBSCRIPTION_SECTION == 'Yes'),
            ], [
                'title' => "Faq",
                "url" => "faq.php",
                "icon" => "fa fa-question",
                "active" => "Faq",
                "visible" => $userObj->hasPermission('view-faq'),
            ], [
                'title' => "Faq Categories",
                "url" => "faq_categories.php",
                "icon" => "fa fa-question-circle-o",
                "active" => "faq_categories",
                "visible" => $userObj->hasPermission('view-faq-categories'),
            ], [
                'title' => "Help Topics",
                "url" => "help_detail.php",
                "icon" => "fa fa-question",
                "active" => "help_detail",
                "visible" => $userObj->hasPermission('view-help-detail'),
            ], [
                'title' => "Help Topic Categories",
                "url" => "help_detail_categories.php",
                "icon" => "fa fa-question-circle-o",
                "active" => "help_detail_categories",
                "visible" => $userObj->hasPermission('view-help-detail-category'),
            ],
            [
                'title' => "Cancel Reason",
                "url" => "cancellation_reason.php",
                "icon" => "fa fa-question",
                "active" => "cancel_reason",
                "visible" => $userObj->hasPermission('view-cancel-reasons'),
            ],
            [
                'title' => $langage_lbl_admin['LBL_PACKAGE_TYPE_ADMIN'],
                "url" => "package_type.php",
                "icon" => "fa fa-globe",
                "active" => "Package",
                 "visible" => $userObj->hasPermission('view-package-type') && ($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Delivery') && ONLYDELIVERALL != "Yes", 
            ], [
                'title' => "Send Push-Notification",
                "url" => "send_notifications.php",
                "icon" => "fa fa-globe",
                "active" => "Push Notification",
                "visible" => $userObj->hasPermission('manage-send-push-notification'),
            ], [
                'title' => "DB Backup",
                "url" => "backup.php",
                "icon" => "fa fa-database",
                "active" => "Back-up",
                "visible" => $userObj->hasPermission('view-db-backup'),
            ],
            [
                'title' => 'System Diagnostic',
                'url' => "system_diagnostic.php",
                "icon" => 'fa fa-sitemap',
                "active" => "site",
                "visible" => isset($_SESSION['SessionUserType']) && $_SESSION['SessionUserType'] != 'hotel', //added by SP on 1-7-2019 for not showing this module to hotel
            ] ,
          [
          'title' => 'Expired Documents',
          'url' => "expired_documents.php",
          "icon" => 'fa fa-file',
          "active" => "Expired Documents",
          "visible" => $userObj->hasPermission('expired-documents'),
          ] ,
        ],
    ], [
        'title' => "Manage Ride Profiles",
        "icon" => "fa fa-taxi",
        "visible" => $userObj->hasPermission('manage-ride-profiles') && ($ENABLE_CORPORATE_PROFILE == 'Yes' && ($APP_TYPE == 'Ride-Delivery-UberX' || $APP_TYPE == 'Ride-Delivery' || $APP_TYPE == 'Ride') ),
        'children' => [
            [
                'title' => "Organization",
                "url" => "organization.php",
                "icon" => "fa fa-globe",
                "active" => "Organization",
                "visible" => $userObj->hasPermission('view-organization'),
            ], [
                'title' => "Ride Profile Type",
                "url" => "user_profile_master.php",
                "icon" => "fa fa-file-text-o",
                "active" => "RideProfileType",
                "visible" => $userObj->hasPermission('view-user-profile'),
            ], [
                'title' => "Business Trip Reason",
                "url" => "trip_reason.php",
                "icon" => "fa fa-book",
                "active" => "BusinessTripReason",
                "visible" => $userObj->hasPermission('view-trip-reason'),
            ], [
                'title' => "Organization Payment Report",
                "url" => "org_payment_report.php",
                "icon" => "fa fa-taxi",
                "active" => "OrganizationPaymentReport",
                "visible" => $userObj->hasPermission('manage-organization-payment-report'),
            ]
        ],
    ],
    [
        'title' => "Logout",
        "url" => "logout.php",
        "icon" => ["class" => "icon-signin1", "url" => "images/logout-icon.png"],
    ],
];

return $menu;

