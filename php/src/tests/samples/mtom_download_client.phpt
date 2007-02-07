--TEST--
Test for mtom download client sample
--FILE--
<?php

$reqPayloadString = <<<XML
	<download>
	</download>
XML;

try {

    $client = new WSClient(
        array("to"=>"http://localhost/mtom_download_service.php",
               "useMTOM"=>TRUE,
		   "responseXOP"=>TRUE));
    $reqMessage = new WSMessage($reqPayloadString);					
    $resMessage = $client->request($reqMessage);
    
    printf("Response = %s \n", $resMessage->str);

    $cid2stringMap = $resMessage->attachments;
    $cid2contentMap = $resMessage->cid2contentType;
    $imageName;
    if($cid2stringMap && $cid2contentMap){
	    foreach($cid2stringMap as $i=>$value){
	        $f = $cid2stringMap[$i];
      	  $contentType = $cid2contentMap[$i];
	        if(strcmp($contentType,"image/jpeg") ==0){
      	      $imageName = $i."."."jpg";
	            file_put_contents("/tmp/".$imageName, $f);
      	  }
	    }
	}else{
		printf("attachments were not found ");
	}
    
} catch (Exception $e) {

	if ($e instanceof WSFault) {
		printf("Soap Fault: %s\n", $e->code);
	} else {
		printf("Message = %s\n",$e->getMessage());
	}
}
?>
--EXPECT--
