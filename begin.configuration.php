<?php

function startConfiguration($partName, $partNamespace, $productID)
{
    if (! session_id()) {
        session_start();
    }
    
    if (! isset($_SESSION['headerID'])) {
        $_SESSION['headerID'] = uniqid();
    }
    
    $_SESSION['detailID'] = $partName . "-" . $partNamespace . "-" . uniqid();
    switch ($_SERVER['SERVER_NAME']) {
        case "ps.joebad.com":
            $redirectHost = "http://ps.joebad.com";
            break;
        case "panelshop.com":
            $redirectHost = "http://panelshop.com/new-configurator";
            break;
        default:
            $redirectHost = "http://ps.local:8080";
            break;
    }
    if ($_SERVER['SERVER_NAME'] == "panelshop.ybclients.com") {
        $redirectURL = urlencode("http://panelshop.ybclients.com/wp-content/plugins/panelshop-configurator/helper/finish.configuration.php?detailIDheaderID={$_SESSION['detailID']}|{$_SESSION['headerID']}|$productID|$partName|$partNamespace");
    } else if ($_SERVER['SERVER_NAME'] == "joebad.dev") {
        $redirectURL = urlencode("http://joebad.dev/panelshop/wp-content/plugins/panelshop-configurator/helper/finish.configuration.php?detailIDheaderID={$_SESSION['detailID']}|{$_SESSION['headerID']}|$productID|$partName|$partNamespace");
    } else if ($_SERVER['SERVER_NAME'] == "panelshop.com") {
        $redirectURL = urlencode("http://panelshop.com/wp-content/plugins/panelshop-configurator/helper/finish.configuration.php?detailIDheaderID={$_SESSION['detailID']}|{$_SESSION['headerID']}|$productID|$partName|$partNamespace");
    }
    
    $redirectURL = urlencode($redirectHost . "/finish.configuration.php?detailIDheaderID={$_SESSION['detailID']}|{$_SESSION['headerID']}|$productID|$partName|$partNamespace");
    
    if ($_SERVER['SERVER_NAME'] == "panelshop.com") {
        $wsdl = new SoapClient("http://65.123.167.216/BuyDesign/ConfiguratorService/ConfiguratorWebService.svc?wsdl");
        $instance = "BuyDesign";
        $name = "BD1030097_Prod";
    } else {
        $wsdl = new SoapClient("http://65.123.167.216/BuyDesign/ConfiguratorService/ConfiguratorWebService.svc?wsdl");
        $instance = "BuyDesign";
        $name = "BD1030097_Prod";
    }
    
    $xml = <<<XML
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:web="http://tdci.com/BuyDesign/8.0/Configurator/WebService/" xmlns:tdci="http://schemas.datacontract.org/2004/07/TDCI.Configurator.ServiceContract">
   <soapenv:Header/>
   <soapenv:Body>
      <web:PrepareForInteractiveConfiguration>
         <web:inputParameters>
            <tdci:Application>
               <tdci:Instance>$instance</tdci:Instance>
               <tdci:Name>$name</tdci:Name>
            </tdci:Application>
            <tdci:HeaderDetail>
               <tdci:DetailId>{$_SESSION['detailID']}</tdci:DetailId>
               <tdci:HeaderId>{$_SESSION['headerID']}</tdci:HeaderId>
            </tdci:HeaderDetail>
            <tdci:IntegrationParameters>
               <tdci:IntegrationParameter>
                  <tdci:IsNull>false</tdci:IsNull>
                  <tdci:Name>CurrencyCode</tdci:Name>
                  <tdci:SimpleValue>USD</tdci:SimpleValue>
                  <tdci:Type>String</tdci:Type>
               </tdci:IntegrationParameter>
               <tdci:IntegrationParameter>
                  <tdci:Name>BillTo</tdci:Name>
                  <tdci:SimpleValue>abc123</tdci:SimpleValue>
               </tdci:IntegrationParameter>
               <tdci:IntegrationParameter>
                  <tdci:Name>Quantity</tdci:Name>
                  <tdci:SimpleValue>1</tdci:SimpleValue>
               </tdci:IntegrationParameter>
            </tdci:IntegrationParameters>
            <tdci:Mode>InteractiveRuleset</tdci:Mode>
            <tdci:Part>
               <tdci:Name>$partName</tdci:Name>
               <tdci:Namespace>$partNamespace</tdci:Namespace>
            </tdci:Part>
            <tdci:Profile>Default</tdci:Profile>
            <tdci:SourceHeaderDetail>
               <tdci:DetailId>{$_SESSION['detailID']}</tdci:DetailId>
               <tdci:HeaderId>{$_SESSION['headerID']}</tdci:HeaderId>
            </tdci:SourceHeaderDetail>
            <tdci:VariantKey></tdci:VariantKey>
         </web:inputParameters>
         <web:pageCaption>Test Config</web:pageCaption>
         <web:redirectUrl>$redirectURL</web:redirectUrl>
      </web:PrepareForInteractiveConfiguration>
   </soapenv:Body>
</soapenv:Envelope>
XML;
    
    if ($_SERVER['SERVER_NAME'] == "panelshop.com") {
        $response = $wsdl->__doRequest($xml, "http://65.123.167.216/BuyDesign/ConfiguratorService/ConfiguratorWebService.svc", "http://tdci.com/BuyDesign/8.0/Configurator/WebService/ConfiguratorWebServiceProxy/PrepareForInteractiveConfiguration", "1.1", 0);
    } else {
        $response = $wsdl->__doRequest($xml, "http://65.123.167.216/BuyDesign/ConfiguratorService/ConfiguratorWebService.svc", "http://tdci.com/BuyDesign/8.0/Configurator/WebService/ConfiguratorWebServiceProxy/PrepareForInteractiveConfiguration", "1.1", 0);
    }
    
    $response = $wsdl->__doRequest($xml, "http://65.123.167.216/BuyDesign/ConfiguratorService/ConfiguratorWebService.svc", "http://tdci.com/BuyDesign/8.0/Configurator/WebService/ConfiguratorWebServiceProxy/PrepareForInteractiveConfiguration", "1.1", 0);
    
    $soap = simplexml_load_string($response);
    
    $response = $soap->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->PrepareForInteractiveConfigurationResponse;
    $url = str_replace("&amp;", "AMPERSAND", str_replace("</PrepareForInteractiveConfigurationResult>", "", str_replace("<PrepareForInteractiveConfigurationResult>", "", $response->PrepareForInteractiveConfigurationResult->asXML())));
    
    return array(
        "configuratorURL" => $url,
        "detailID" => $_SESSION['detailID'],
        "headerID" => $_SESSION['headerID'],
        "partName" => $partName,
        "partNamespace" => $partNamespace,
        "productID" => $productID
    );
}