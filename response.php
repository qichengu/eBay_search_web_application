<?php

//if (is_ajax()) {
if (isset($_GET["action"]) && !empty($_GET["action"])) { //Checks if action value exists
$action = $_GET["action"];
switch($action) { //Switch case for value of action
case "test": test_function(); break;
}
}
//}


//Function to check if the request is an AJAX request
function is_ajax() {
return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}


function test_function(){
    
	$URL_GET = "http://svcs.eBay.com/services/search/FindingService/v1?";
	$URL_GET = $URL_GET . "siteid=0";
	$URL_GET = $URL_GET . "&SECURITY-APPNAME=Universi-bbf8-4cbb-8af7-99a31013a89a";
	$URL_GET = $URL_GET . "&OPERATION-NAME=findItemsAdvanced";
	$URL_GET = $URL_GET . "&SERVICE-VERSION=1.0.0";
	$URL_GET = $URL_GET . "&RESPONSE-DATA-FORMAT=XML";
	$URL_GET = $URL_GET . "&keywords=" . urlencode($_GET['keywords']);
	$URL_GET = $URL_GET . "&sortOrder=";
	if ($_GET['Sort_by']=="Best_Match")
		$URL_GET = $URL_GET . "BestMatch";
	if ($_GET['Sort_by']=="Price_highest_first")
		$URL_GET = $URL_GET . "CurrentPriceHighest";	
	if ($_GET['Sort_by']=="Price_Shipping_highest_first")
		$URL_GET = $URL_GET . "PricePlusShippingHighest";	
	if ($_GET['Sort_by']=="Price_Shipping_lowest_first")
		$URL_GET = $URL_GET . "PricePlusShippingLowest";
	$URL_GET = $URL_GET . "&paginationInput.entriesPerPage=" . $_GET['Results_Per_Page'];
	$filter_index = 0;
	if (preg_match("/\S+/",$_GET['price_down']))
	{
		$URL_GET = $URL_GET . "&itemFilter[$filter_index].name=MinPrice&itemFilter[$filter_index].value=" . floatval($_GET['price_down']);
		$filter_index+=1;
	};
	if (preg_match("/\S+/",$_GET['price_up']))
	{
		$URL_GET = $URL_GET . "&itemFilter[$filter_index].name=MaxPrice&itemFilter[$filter_index].value=" . floatval($_GET['price_up']);
		$filter_index+=1;
	}
    //condition
    $condition_index = 0;

    if (isset($_GET['New']))
    {
        if ($condition_index == 0)
        {    
            $URL_GET = $URL_GET . "&itemFilter[$filter_index].name=Condition";
        };
        $URL_GET = $URL_GET . "&itemFilter[$filter_index].value[" . $condition_index. "]=1000";
        $condition_index +=1;
    };
    if (isset($_GET['Used']))
    {
        if ($condition_index == 0)
        {    
            $URL_GET = $URL_GET . "&itemFilter[$filter_index].name=Condition";
        };$URL_GET = $URL_GET . "&itemFilter[$filter_index].value[" . $condition_index. "]=3000";
        $condition_index +=1;
    };
    if (isset($_GET['Very_Good']))
    {
        if ($condition_index == 0)
        {    
            $URL_GET = $URL_GET . "&itemFilter[$filter_index].name=Condition";
        };        
        $URL_GET = $URL_GET . "&itemFilter[$filter_index].value[" . $condition_index. "]=4000";
        $condition_index +=1;
    }; 
    if (isset($_GET['Good']))
    {
        if ($condition_index == 0)
        {    
            $URL_GET = $URL_GET . "&itemFilter[$filter_index].name=Condition";
        };        
        $URL_GET = $URL_GET . "&itemFilter[$filter_index].value[" . $condition_index. "]=5000";
        $condition_index +=1;
    };
    if (isset($_GET['Acceptable']))
    {
        if ($condition_index == 0)
        {    
            $URL_GET = $URL_GET . "&itemFilter[$filter_index].name=Condition";
        };        
        $URL_GET = $URL_GET . "&itemFilter[$filter_index].value[" . $condition_index. "]=6000";
        $condition_index +=1;
    };
	if ($condition_index>0)
        $filter_index+=1;
    
    //Buting formats
    $condition_index = 0;

    if (isset($_GET['Buy_It_Now']))
    {
        if ($condition_index == 0)
        {
            $URL_GET = $URL_GET . "&itemFilter[$filter_index].name=ListingType";
        };        
        $URL_GET = $URL_GET . "&itemFilter[$filter_index].value[" . $condition_index. "]=FixedPrice";
        $condition_index +=1;
    };
    if (isset($_GET['Auction']))
    {
        if ($condition_index == 0)
        {
            $URL_GET = $URL_GET . "&itemFilter[$filter_index].name=ListingType";
        };         
        $URL_GET = $URL_GET . "&itemFilter[$filter_index].value[" . $condition_index. "]=Auction";
        $condition_index +=1;
    };
    if (isset($_GET['Classified_Ads']))
    {
        if ($condition_index == 0)
        {
            $URL_GET = $URL_GET . "&itemFilter[$filter_index].name=ListingType";
        };         
        $URL_GET = $URL_GET . "&itemFilter[$filter_index].value[" . $condition_index. "]=Classified";
        $condition_index +=1;
    }; 
    if ($condition_index>0)
        $filter_index+=1;
    
    //seller
	if (isset($_GET['Return_accepted']))
    {
        if ($condition_index == 0)
        {
            $URL_GET = $URL_GET . "&itemFilter[$filter_index].name=ListingType";
        };         
        $URL_GET = $URL_GET . "&itemFilter[$filter_index].name=ReturnsAcceptedOnly&itemFilter[$filter_index].value=" . 'true';
	    $filter_index+=1;
    };
    
    //shipping
	if (isset($_GET['Free_shipping']))
    {    
        $URL_GET = $URL_GET . "&itemFilter[$filter_index].name=FreeShippingOnly&itemFilter[$filter_index].value=" . 'true';
	    $filter_index+=1;
    };
	
    if (isset($_GET['Expedited_shipping_available']))
	{	$URL_GET = $URL_GET . "&itemFilter[$filter_index].name=ExpeditedShippingType&itemFilter[$filter_index].value=Expedited";
		$filter_index+=1;
	};
	
	if (strlen($_GET['Max_days'])>0)
		$URL_GET = $URL_GET . "&itemFilter[" . $filter_index . "].name=MaxHandlingTime&itemFilter[" . $filter_index . "].value=" . intval($_GET['Max_days']);
    $URL_GET .= "&outputSelector(0)=SellerInfo";
    $URL_GET .= "&outputSelector(1)=PictureURLSuperSize";
    $URL_GET .= "&outputSelector(2)=StoreInfo";
    $URL_GET .="&paginationInput.pageNumber=";
    $URL_GET .= $_GET['pagenumber'];
    
    
    
    
    
    
    
	
	
    $xmlDoc = new DOMDocument();
	$xmlDoc->load($URL_GET) or die ("Error loading XML");
	$x=simplexml_import_dom($xmlDoc);

   
    $json_object=new stdClass();
    if (($x->paginationOutput->totalEntries)==0)
	{	
		$json_object->ack="No results found";
        
	}
    //(floatval($x->paginationOutput->totalEntries))
    //((floatval($x->paginationOutput->totalEntries))-($x->paginationOutput->pageNumber-1)*$x->paginationOutput->entriesPerPage)
    
	else{
        
		if ((floatval($x->paginationOutput->entriesPerPage))>((floatval($x->paginationOutput->totalEntries))-($x->paginationOutput->pageNumber-1)*$x->paginationOutput->entriesPerPage)) 
			$item_number = ((floatval($x->paginationOutput->totalEntries))-($x->paginationOutput->pageNumber-1)*$x->paginationOutput->entriesPerPage);
		else
			$item_number = floatval($x->paginationOutput->entriesPerPage);
        
        $json_object->ack="Success";
		$json_object->resultCount=(string)($x->paginationOutput->totalEntries);
        $json_object->pageNumber=(string)($x->paginationOutput->pageNumber);
        $json_object->itemCount=(string)($x->paginationOutput->entriesPerPage);
       
        
		for ($i=0; $i<$item_number; $i++)
		{	$item_index="item" . $i;
            $per_item=new stdClass();
            $json_object->$item_index=$per_item;
            $basic=new stdClass();
            $per_item->basicInfo=$basic;
            $basic->title=(string)($x->searchResult->item[$i]->title);
            $basic->viewItemURL=(string)($x->searchResult->item[$i]->viewItemURL);
            $basic->galleryURL=(string)($x->searchResult->item[$i]->galleryURL);
            $basic->pictureURLSuperSize=(string)($x->searchResult->item[$i]->pictureURLSuperSize);
            $basic->convertedCurrentPrice=(string)($x->searchResult->item[$i]->sellingStatus->convertedCurrentPrice);
            $basic->shippingServiceCost=(string)($x->searchResult->item[$i]->shippingInfo->shippingServiceCost);
            $basic->conditionDisplayName=(string)($x->searchResult->item[$i]->condition->conditionDisplayName);
            $basic->listingType=(string)($x->searchResult->item[$i]->listingInfo->listingType);
            $basic->location=(string)($x->searchResult->item[$i]->location);
            $basic->categoryName=(string)($x->searchResult->item[$i]->primaryCategory->categoryName);
            $basic->topRatedListing=(string)($x->searchResult->item[$i]->topRatedListing);
         
            $seller=new stdClass();
            $per_item->sellerInfo=$seller;
            $seller->sellerUserName=(string)($x->searchResult->item[$i]->sellerInfo->sellerUserName);
            $seller->feedbackScore=(string)($x->searchResult->item[$i]->sellerInfo->feedbackScore);
            $seller->positiveFeedbackPercent=(string)($x->searchResult->item[$i]->sellerInfo->positiveFeedbackPercent);
            $seller->feedbackRatingStar=(string)($x->searchResult->item[$i]->sellerInfo->feedbackRatingStar);
            $seller->topRatedSeller=(string)($x->searchResult->item[$i]->sellerInfo->topRatedSeller);
            $seller->sellerStoreName=(string)($x->searchResult->item[$i]->storeInfo->storeName);
            $seller->sellerStoreURL=(string)($x->searchResult->item[$i]->storeInfo->storeURL);
         
            $shipping=new stdClass();
            $per_item->shippingInfo=$shipping;
            $shipping->shippingType=(string)($x->searchResult->item[$i]->shippingInfo->shippingType);
            
            $location_count = count($x->searchResult->item[$i]->shippingInfo->shipToLocations);
            
            if ($location_count<=1)
            {
                $shipping->shipToLocations=(string)($x->searchResult->item[$i]->shippingInfo->shipToLocations);  
            }
            else
            {
                $temp_locations='';
                for ($j=0; $j<$location_count-1; $j++)
                {
                    $temp_locations.=(string)( $x->searchResult->item[$i]->shippingInfo->shipToLocations[$j]) . ',';
                };
                $temp_locations.=(string)( $x->searchResult->item[$i]->shippingInfo->shipToLocations[$location_count-1]);
                $shipping->shipToLocations=$temp_locations;
            };
            //expeditedShipping
            $shipping->expeditedShipping=(string)($x->searchResult->item[$i]->shippingInfo->expeditedShipping);
         //make up
         
            $shipping->oneDayShippingAvailable=(string)($x->searchResult->item[$i]->shippingInfo->oneDayShippingAvailable);
            $shipping->returnsAccepted=(string)($x->searchResult->item[$i]->returnsAccepted);
            $shipping->handlingTime=(string)($x->searchResult->item[$i]->shippingInfo->handlingTime);
     
        };
            
         
    };

    echo json_encode($json_object);
    #echo $URL_GET;
}


?> 
