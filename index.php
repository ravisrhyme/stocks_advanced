<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");

if(isset($_GET['symbol_lookup'])) {
            $term = $_GET['symbol_lookup'];
            $url_detail = "http://dev.markitondemand.com/MODApis/Api/v2/Lookup/json?input=".$term;
            $json_info = file_get_contents($url_detail);
             echo $json_info;
}
if(isset($_GET['symbol_quote'])) {
            $symbol = $_GET['symbol_quote'];
            $url_detail = "http://dev.markitondemand.com/MODApis/Api/v2/Quote/json?symbol=".$symbol;
            $json_info = file_get_contents($url_detail);
            echo $json_info;
 }


if(isset($_GET['term'])) {
            $term = $_GET['term'];
            $url_detail = "http://dev.markitondemand.com/MODApis/Api/v2/Lookup/json?input=".$term;
            $json_info = file_get_contents($url_detail);
            $json_content = json_decode($json_info, true);
             
            $result = array();
            foreach ($json_content as $json_lookup_value) {
                $label = $json_lookup_value["Symbol"]." - ".$json_lookup_value["Name"]. "(".$json_lookup_value["Exchange"].")";
                $value = $json_lookup_value["Symbol"];
                $company = array("label" => $label, "value" => $value);
                array_push( $result, $company );
            }
    
echo json_encode( $result );
}

if(isset($_GET['symbol_chart'])) {
            $symbol = $_GET['symbol_chart'];
            $url_detail = "http://dev.markitondemand.com/MODApis/Api/v2/InteractiveChart/json?parameters={'Normalized':false,'NumberOfDays':1095,'DataPeriod':'Day','Elements':[{'Symbol':'$symbol','Type':'price','Params':['ohlc']}]}";
            $json_info = file_get_contents($url_detail);
            echo json_encode($json_info);
}

if(isset($_GET['symbol_newsfeed'])) {
    $symbol = $_GET['symbol_newsfeed'];
    $accountKey = 'BhIq7l+qsktUW452EjuZxb3OvMIsTdiczRh8a1EpZmI';
    $url_detail = "https://api.datamarket.azure.com/Bing/Search/v1/News?Query=";
    $request = $url_detail . urlencode( '\'' .$symbol. '\'');
    $request .= '&$format=json';

    $context = stream_context_create(array('http' => array(
                    'request_fulluri' => true,
                    'header'  => "Authorization: Basic " . base64_encode($accountKey . ":" . $accountKey)
                    )
                    ));
    
    $response = file_get_contents($request, 0, $context);
    if ( $response === false )
{
   echo " No response from Bing";
}
    $jsonobj = json_decode($response);
    $news_html_string = " ";
    foreach($jsonobj->d->results as $value)
    {                          
      $news_html_string .= "<div class='well well-lg'>";
      $news_html_string .= "<a  style='font-weight: lighter;' target='_blank' href=";
      $news_html_string .= $value->Url;
      $news_html_string .=">";
      $news_html_string .= $value->Title;
      $news_html_string .= "</a><br><br><p style='font-weight: lighter;'>";
      $news_html_string .= $value->Description;
      $news_html_string .= "</p><br>";
      $news_html_string .= "<b>Publisher:";
      $news_html_string .= $value->Source;
      $news_html_string .= "<br><br>";
      $news_html_string .= "Date: ";
      $date = date_create($value->Date);
      $news_html_string .= date_format($date,"d M Y H:i:s");
      $news_html_string .= "</b></div><br><br>";
    }
    echo $news_html_string;
            
}

?>
