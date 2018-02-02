<?php

if($_SERVER['SERVER_NAME']=="panelshop.com"){
    $wsdl = new SoapClient("http://65.123.167.216/BuyDesign/ConfiguratorService/ConfiguratorWebService.svc?wsdl");
    $instance = "BuyDesign";
    $name = "BD1030097_Prod";
}else{
    $wsdl = new SoapClient("http://65.123.167.216/BuyDesign/ConfiguratorService/ConfiguratorWebService.svc?wsdl");
    $instance = "BuyDesign";
    $name = "BD1030097_Prod";
}
$detailIDheaderID = explode("|", $_GET['detailIDheaderID']);

$xml = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://tdci.com/BuyDesign/8.0/Configurator/WebService/" xmlns:tdci="http://schemas.datacontract.org/2004/07/TDCI.Configurator.ServiceContract">
   <soapenv:Header/>
   <soapenv:Body>
      <web:FinishInteractiveConfiguration>
         <web:application>
               <tdci:Instance>$instance</tdci:Instance>
               <tdci:Name>$name</tdci:Name>
         </web:application>
         <web:headerDetail>
            <tdci:DetailId>{$detailIDheaderID[0]}</tdci:DetailId>
            <tdci:HeaderId>{$detailIDheaderID[1]}</tdci:HeaderId>
         </web:headerDetail>
      </web:FinishInteractiveConfiguration>
   </soapenv:Body>
</soapenv:Envelope>
XML;

if($_SERVER['SERVER_NAME']=="panelshop.com"){
    $response = $wsdl->__doRequest($xml,"http://65.123.167.216/BuyDesign/ConfiguratorService/ConfiguratorWebService.svc","http://tdci.com/BuyDesign/8.0/Configurator/WebService/ConfiguratorWebServiceProxy/FinishInteractiveConfiguration","1.1",0);
}else{
    $response = $wsdl->__doRequest($xml,"http://65.123.167.216/BuyDesign/ConfiguratorService/ConfiguratorWebService.svc","http://tdci.com/BuyDesign/8.0/Configurator/WebService/ConfiguratorWebServiceProxy/FinishInteractiveConfiguration","1.1",0);
}

$rawResponse = simplexml_load_string($response);
$rawResponse = $rawResponse->asXML();

$response = str_replace("s:", "", $response);
$response = str_replace("a:", "", $response);
$response = str_replace("i:", "", $response);
$response = str_replace('xmlns="http://schemas.xmlsoap.org/soap/envelope/"', "", $response);
$response = str_replace('xmlns="http://tdci.com/BuyDesign/8.0/Configurator/WebService/"', "", $response);
$response = str_replace('xmlns="http://schemas.datacontract.org/2004/07/TDCI.Configurator.ServiceContract"', "", $response);
$response = str_replace('xmlns="http://www.w3.org/2001/XMLSchema-instance"', "", $response);
$response = str_replace('xmlna="http://schemas.datacontract.org/2004/07/TDCI.Configurator.ServiceContract"', "", $response);
$response = str_replace('xmlni="http://www.w3.org/2001/XMLSchema-instance"', "", $response);
$response = str_replace('xmlnb="http://schemas.microsoft.com/2003/10/Serialization/Arrays"', "", $response);

$configurationXML = simplexml_load_string($response, NULL, NULL, "http://schemas.xmlsoap.org/soap/envelope/");
$resultDetails = $configurationXML->xpath("/Envelope/Body/FinishInteractiveConfigurationResponse/FinishInteractiveConfigurationResult/CommitParameters/CommitParameter");
$smartPartDetails = $configurationXML->xpath("/Envelope/Body/FinishInteractiveConfigurationResponse/FinishInteractiveConfigurationResult/OrderDetails");
$schematics = array();

foreach ($resultDetails as $details){
    switch ($details->Name){
        case "CfgDescription":
            $description = urlencode($details->SimpleValue);
            break;
        case "ConfiguredPrice":
            $price = $details->SimpleValue;
            break;
        case "WEIGHT":
            $weight = $details->SimpleValue;
            break;
        case "GEN":
            $schematics["General Schematic (Other)"] = strip_tags($details->SimpleValue->asXML());
            break;
        case "GEN_IMAGE":
            $schematics["General Schematic"] = strip_tags($details->SimpleValue->asXML());
            break;
        case "ENC_IMAGE":
            $schematics["Enclosure Detail"] = strip_tags($details->SimpleValue->asXML());
            break;
        case "DEV_IMAGE":
            $schematics["Device Spec Sheet"] = strip_tags($details->SimpleValue->asXML());
            break;
        case "CTRL_IMAGE":
            $schematics["Control Image"] = strip_tags($details->SimpleValue->asXML());
            break;
        case "ENC_IMAGE2":
            $schematics["Enclosure Detail (Other)"] = strip_tags($details->SimpleValue->asXML());
            break;
    }
}

foreach ($smartPartDetails[0]->Detail as $smartPart){
    if($smartPart->Description=="Smart Part" || $smartPart->Description=="SMART_PART"){
        $smartPartNumber = $smartPart->SimpleValue;
    }
}

$schematics = urlencode(serialize($schematics));
$privateKey = encrypt($price);

switch ($_SERVER['SERVER_NAME']){
    case "panelshop.ybclients.com":
        $redirectURL = "http://panelshop.ybclients.com/configurator?description=$description&price=$price&weight=$weight&headerID={$detailIDheaderID[1]}&detailID={$detailIDheaderID[0]}&productID={$detailIDheaderID[2]}&partName={$detailIDheaderID[3]}&partNamespace={$detailIDheaderID[4]}&smartPartNumber=$smartPartNumber&schematics=$schematics&privateKey=$privateKey";
        break;

    case "joebad.dev":
        $redirectURL = "http://joebad.dev/panelshop/configurator?description=$description&price=$price&weight=$weight&headerID={$detailIDheaderID[1]}&detailID={$detailIDheaderID[0]}&productID={$detailIDheaderID[2]}&partName={$detailIDheaderID[3]}&partNamespace={$detailIDheaderID[4]}&smartPartNumber=$smartPartNumber&schematics=$schematics&privateKey=$privateKey";
        break;

    case "panelshop.com":
        $redirectURL = "http://panelshop.com/configurator?description=$description&price=$price&weight=$weight&headerID={$detailIDheaderID[1]}&detailID={$detailIDheaderID[0]}&productID={$detailIDheaderID[2]}&partName={$detailIDheaderID[3]}&partNamespace={$detailIDheaderID[4]}&smartPartNumber=$smartPartNumber&schematics=$schematics&privateKey=$privateKey";
        break;
}

function encrypt($value)
{
    return base64_encode(md5("9V&#{X,9F.u>!)Tg[+%*Wz#U}}[Twf~(m5~$@~R[@)c&<g".$value));
}
$response = "Description: $description /n Encrypted Price: $price /n Weight: $weight /n Header ID: $detailIDheaderID[1] /n Detail ID: $detailIDheaderID[0] /n Product ID: detailIDheaderID[2] /n Part Name: $detailIDheaderID[3] /n Part Namespace: $detailIDheaderID[4] /n Smart Part Number: $smartPartNumber";
$encoded = urlencode($response);
switch($_SERVER['SERVER_NAME']){
    case "ps.joebad.com":
        $redirectHost = "http://ps.joebad.com";
        break;
    default:
        $redirectHost = "http://ps.local:8080/";
        break;
}

$redirectURL = $redirectHost . "/result.php/?response=$encoded";

echo <<<HTML
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js " ></script>
<!--$rawResponse-->
<script>
jQuery(document).ready(function($){
    if (top.location!= self.location) {
        top.location = '$redirectURL';
    }
});
</script>
HTML;
