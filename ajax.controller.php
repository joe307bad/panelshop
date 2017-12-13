<?php
include("./begin.configuration.php");

if (isset($_POST['action'])) {

    switch ($_POST['action']) {

        case "start-configuration":
            $partInfo = $_POST['partInfo'];
            if ($configurationInfo=startConfiguration($partInfo['partName'],$partInfo['partNamespace'],$partInfo['productID'])) {
                echo json_encode($configurationInfo);
            }
            break;

//        case "send-to-cart":
//            $partInfo = $_POST['finishPartInfo'];
//            if ($productAdded=sendToCart($partInfo)) {
//                echo json_encode(array("message"=>"success"));
//            }
//            break;
//
//        case "reconfigure":
//            $theIDs = $_POST['theIDs'];
//            if ($configurationInfo=reconfigure($theIDs)) {
//                echo json_encode($configurationInfo);
//            }
//            break;
    }
}