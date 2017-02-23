<head>
    <title>ebay Shopping</title>
    <style type="text/css">

        .header {
            width: 256px;
            height: 128px;
            margin-left: 150px;
            margin-right: auto;
            display:block;
        }
        .header2 {
            float:left;
            display: inline;           
            width: 128px;
            height: 128px;
            vertical-align: middle;
            line-height: 128px;
        }
        hr { color: rgb(233,233,233);
        }
		fieldset td{border:0px;margin:0px;padding:0px;}
        h1 {border:0px;padding:0px;margin:0px}
        p {margin:0px;padding:0px;border:0px;}
        label.caption{font-weight: bold;}
        #keywords{width: 100%;}
        input.price_input{width: 50px;}
        p.buttons {text-align:right;}
    </style>
	<script type='text/javascript'>
	function checkform(what)
	{    
		var errorMessage="";
		var lower_price_int=0;
		var upper_price_int=0;
		var conditions=0;
		var buying_formats=0;
		//check keyword
		if (!(/\S+/.test(what.keywords.value)))
			errorMessage+="Please enter value for Key Words\n";

		//check price range
		//check lower
		
		if ((isNaN(what.price_down.value)) || (isNaN(what.price_up.value)))
		{
			errorMessage+="Please input valid number(s) in price";
		}
		else
		{
			if (what.price_down.value.length)
			{
				lower_price_int=parseFloat(what.price_down.value);
				if (lower_price_int<0)
					errorMessage+="Minimum price value cannot be negative\n";
			};
			//check higher
			if (what.price_up.value.length)
			{
				upper_price_int=parseFloat(what.price_up.value);
				if (upper_price_int<0)
					errorMessage+="Maximum price value cannot be negative\n";
			};
			//check range
			if ((what.price_down.value.length) && (what.price_up.value.length) && (lower_price_int>upper_price_int))
				errorMessage+="Invalid price range\n";
				
		};
				/*
		//eheck condition options
		if (what.New.checked)
			conditions+=1;
		if (what.Used.checked)
			conditions+=1;
		if (what.Very_Good.checked)
			conditions+=1;
		if (what.Good.checked)
			conditions+=1;
		if (what.Acceptable.checked)
			conditions+=1;
		if (!conditions)
			errorMessage+="Choose at least one Condition Option\n";
		*/
		
        /*
        //eheck buying formats
		if (what.Buy_It_Now.checked)
			buying_formats+=1;
		if (what.Auction.checked)
			buying_formats+=1;
		if (what.Classified_Ads.checked)
			buying_formats+=1;
		if (!buying_formats)
			errorMessage+="Choose at least one Buying Format\n";
		*/
		//Maximum handling days
		if (what.Max_days.value.length)
		{
			if (!(/^\s*-?\d+\s*$/.test(what.Max_days.value)))
			{	errorMessage+="Maximum handling days must be a integer\n";}
			else 
			{
				if (parseInt(what.Max_days.value)<1)
					errorMessage+="Maximum handling days is at least 1 day\n";
			}	
		};	
		//Final validation
		if (errorMessage.length)
			window.alert(errorMessage);
		else what.submit();
	}
	</script>
	
	<script type='text/javascript'>
	function clearform(what)
	{
		what.keywords.value="";
		what.price_down.value="";
		what.price_up.value="";
		what.New.checked=false;
		what.Used.checked=false;
		what.Very_Good.checked=false;
		what.Good.checked=false;
		what.Acceptable.checked=false;
		what.Buy_It_Now.checked=false;
		what.Auction.checked=false;
		what.Classified_Ads.checked=false;
		what.Return_accepted.checked=false;
		what.Free_Shipping.checked=false;
		what.Expedited_shipping_available.checked=false;
		what.Max_days.value="";
		what.Sort_by.value="Best_Match";
		what.Results_Per_Page.value="5";
		results.innerHTML="";
	}
	</script>
</head>


<body>
<div>
<!--
<?php
include_once("inc.php");
if(isset($_POST["submit"])): 
?>
<h3>Thanks for submitting!</h3>
<pre>
<?php print_array($_POST); ?>
</pre>    
<?php endif; ?>
-->

<?php
if(isset($_POST["submit"]))
{	
	$URL_POST = "http://svcs.eBay.com/services/search/FindingService/v1?";
	$URL_POST = $URL_POST . "siteid=0";
	$URL_POST = $URL_POST . "&SECURITY-APPNAME=Universi-bbf8-4cbb-8af7-99a31013a89a";
	$URL_POST = $URL_POST . "&OPERATION-NAME=findItemsAdvanced";
	$URL_POST = $URL_POST . "&SERVICE-VERSION=1.0.0";
	$URL_POST = $URL_POST . "&RESPONSE-DATA-FORMAT=XML";
	$URL_POST = $URL_POST . "&keywords=" . urlencode($_POST['keywords']);
	$URL_POST = $URL_POST . "&sortOrder=";
	if ($_POST['Sort_by']=="Best_Match")
		$URL_POST = $URL_POST . "BestMatch";
	if ($_POST['Sort_by']=="Price_highest_first")
		$URL_POST = $URL_POST . "CurrentPriceHighest";	
	if ($_POST['Sort_by']=="Price_Shipping_highest_first")
		$URL_POST = $URL_POST . "PricePlusShippingHighest";	
	if ($_POST['Sort_by']=="Price_Shipping_lowest_first")
		$URL_POST = $URL_POST . "PricePlusShippingLowest";
	$URL_POST = $URL_POST . "&paginationInput.entriesPerPage=" . $_POST['Results_Per_Page'];
	$filter_index = 0;
	if (preg_match("/\S+/",$_POST['price_down']))
	{
		$URL_POST = $URL_POST . "&itemFilter[$filter_index].name=MinPrice&itemFilter[$filter_index].value=" . floatval($_POST['price_down']);
		$filter_index+=1;
	};
	if (preg_match("/\S+/",$_POST['price_up']))
	{
		$URL_POST = $URL_POST . "&itemFilter[$filter_index].name=MaxPrice&itemFilter[$filter_index].value=" . floatval($_POST['price_up']);
		$filter_index+=1;
	}
    //condition
    $condition_index = 0;

    if (isset($_POST['New']))
    {
        if ($condition_index == 0)
        {    
            $URL_POST = $URL_POST . "&itemFilter[$filter_index].name=Condition";
        };
        $URL_POST = $URL_POST . "&itemFilter[$filter_index].value[" . $condition_index. "]=1000";
        $condition_index +=1;
    };
    if (isset($_POST['Used']))
    {
        if ($condition_index == 0)
        {    
            $URL_POST = $URL_POST . "&itemFilter[$filter_index].name=Condition";
        };$URL_POST = $URL_POST . "&itemFilter[$filter_index].value[" . $condition_index. "]=3000";
        $condition_index +=1;
    };
    if (isset($_POST['Very_Good']))
    {
        if ($condition_index == 0)
        {    
            $URL_POST = $URL_POST . "&itemFilter[$filter_index].name=Condition";
        };        
        $URL_POST = $URL_POST . "&itemFilter[$filter_index].value[" . $condition_index. "]=4000";
        $condition_index +=1;
    }; 
    if (isset($_POST['Good']))
    {
        if ($condition_index == 0)
        {    
            $URL_POST = $URL_POST . "&itemFilter[$filter_index].name=Condition";
        };        
        $URL_POST = $URL_POST . "&itemFilter[$filter_index].value[" . $condition_index. "]=5000";
        $condition_index +=1;
    };
    if (isset($_POST['Acceptable']))
    {
        if ($condition_index == 0)
        {    
            $URL_POST = $URL_POST . "&itemFilter[$filter_index].name=Condition";
        };        
        $URL_POST = $URL_POST . "&itemFilter[$filter_index].value[" . $condition_index. "]=6000";
        $condition_index +=1;
    };
	if ($condition_index>0)
        $filter_index+=1;
    
    //Buting formats
    $condition_index = 0;

    if (isset($_POST['Buy_It_Now']))
    {
        if ($condition_index == 0)
        {
            $URL_POST = $URL_POST . "&itemFilter[$filter_index].name=ListingType";
        };        
        $URL_POST = $URL_POST . "&itemFilter[$filter_index].value[" . $condition_index. "]=FixedPrice";
        $condition_index +=1;
    };
    if (isset($_POST['Auction']))
    {
        if ($condition_index == 0)
        {
            $URL_POST = $URL_POST . "&itemFilter[$filter_index].name=ListingType";
        };         
        $URL_POST = $URL_POST . "&itemFilter[$filter_index].value[" . $condition_index. "]=Auction";
        $condition_index +=1;
    };
    if (isset($_POST['Classified_Ads']))
    {
        $URL_POST = $URL_POST . "&itemFilter[$filter_index].value[" . $condition_index. "]=Classified";
        $condition_index +=1;
    }; 
    if ($condition_index>0)
        $filter_index+=1;
    
    //seller
	if (isset($_POST['Return_accepted']))
    {
        if ($condition_index == 0)
        {
            $URL_POST = $URL_POST . "&itemFilter[$filter_index].name=ListingType";
        };         
        $URL_POST = $URL_POST . "&itemFilter[$filter_index].name=ReturnsAcceptedOnly&itemFilter[$filter_index].value=" . 'true';
	    $filter_index+=1;
    };
    
    //shipping
	if (isset($_POST['Free_Shipping']))
    {    
        $URL_POST = $URL_POST . "&itemFilter[$filter_index].name=FreeShippingOnly&itemFilter[$filter_index].value=" . 'true';
	    $filter_index+=1;
    };
	
    if (isset($_POST['Expedited_shipping_available']))
	{	$URL_POST = $URL_POST . "&itemFilter[$filter_index].name=ExpeditedShippingType&itemFilter[$filter_index].value=Expedited";
		$filter_index+=1;
	};
	
	if (strlen($_POST['Max_days'])>0)
		$URL_POST = $URL_POST . "&itemFilter[" . $filter_index . "].name=MaxHandlingTime&itemFilter[" . $filter_index . "].value=" . intval($_POST['Max_days']);
    $URL_POST .= "&outputSelector(0)=SellerInfo";
    $URL_POST .= "&outputSelector(1)=PictureURLSuperSize";
    $URL_POST .= "&outputSelector(2)=StoreInfo";
    $URL_POST .="&paginationInput.pageNumber=1";
    


	#echo $URL_POST;
	
    $xmlDoc = new DOMDocument();
	$xmlDoc->load($URL_POST) or die ("Error loading XML");
	$x=simplexml_import_dom($xmlDoc);
	#print $xmlDoc->saveXML();
	$testing='';

	

	if (($x->paginationOutput->totalEntries)==0)
	{	
		$json_text='{"ack":"No results found"}';
        #$html_text="<h1 style='margin-left: 150px;'>No results found</h1></body>";
	}
	else{
		if ((floatval($x->paginationOutput->entriesPerPage))>(floatval($x->paginationOutput->totalEntries))) 
			$item_number = floatval($x->paginationOutput->totalEntries);
		else
			$item_number = floatval($x->paginationOutput->entriesPerPage);
        $json_text='{ "ack": "Success",';
		#$html_text="<table border='2'>";
		#$html_text.="<tr><th colspan='2'><b>" . $x->paginationOutput->totalEntries . " Result(s) for " . $_POST['keywords'] . "</b></th></tr>";
        $json_text.='"resultCount":	"' . $x->paginationOutput->totalEntries . '",';
        $json_text.='"pageNumber": "' . $x->paginationOutput->pageNumber . '",';
        $json_text.='"itemCount": "' . $x->paginationOutput->entriesPerPage . '",';
        
		for ($i=0; $i<$item_number; $i++)
		{	$json_text.='"item' . $i . '":{';
            $json_text.='"basicInfo": {';
            $json_text.='"title": "' . $x->searchResult->item[$i]->title . '",';
            $json_text.='"viewItemURL": "' . $x->searchResult->item[$i]->viewItemURL . '",';
            $json_text.='"galleryURL": "' . $x->searchResult->item[$i]->galleryURL . '",';
            $json_text.='"pictureURLSuperSize": "' . $x->searchResult->item[$i]->pictureURLSuperSize . '",';
            $json_text.='"convertedCurrentPrice": "' . $x->searchResult->item[$i]->sellingStatus->convertedCurrentPrice . '",';
            $json_text.='"shippingServiceCost": "' . $x->searchResult->item[$i]->shippingInfo->shippingServiceCost . '",';
            $json_text.='"conditionDisplayName": "' . $x->searchResult->item[$i]->condition->conditionDisplayName . '",';
            $json_text.='"listingType": "' . $x->searchResult->item[$i]->listingInfo->listingType . '",';
            $json_text.='"location": "' . $x->searchResult->item[$i]->location . '",';
            $json_text.='"categoryName": "' . $x->searchResult->item[$i]->primaryCategory->categoryName . '",';
            $json_text.='"topRatedListing": "' . $x->searchResult->item[$i]->topRatedListing . '"';
         
            $json_text.='},"sellerInfo": {';
         
            $json_text.='"sellerUserName": "' . $x->searchResult->item[$i]->sellerInfo->sellerUserName . '",';
            $json_text.='"feedbackScore": "' . $x->searchResult->item[$i]->sellerInfo->feedbackScore . '",';
            $json_text.='"positiveFeedbackPercent": "' . $x->searchResult->item[$i]->sellerInfo->positiveFeedbackPercent . '",';
            $json_text.='"feedbackRatingStar": "' . $x->searchResult->item[$i]->sellerInfo->feedbackRatingStar . '",';
            $json_text.='"topRatedSeller": "' . $x->searchResult->item[$i]->sellerInfo->topRatedSeller . '",';
            $json_text.='"sellerStoreName": "' . $x->searchResult->item[$i]->storeInfo->storeName . '",';
            $json_text.='"sellerStoreURL": "' . $x->searchResult->item[$i]->storeInfo->storeURL . '"';
         
            $json_text.='},"shippingInfo": {';
         
            $json_text.='"shippingType": "' . $x->searchResult->item[$i]->shippingInfo->shippingType . '",';
            
            $location_count = count($x->searchResult->item[$i]->shippingInfo->shipToLocations);
            
            if ($location_count<=1)
            {
                $json_text.='"shipToLocations": "' . $x->searchResult->item[$i]->shippingInfo->shipToLocations . '",';  
            }
            else
            {
                $json_text.='"shipToLocations": "' ;
                for ($j=0; $j<$location_count-1; $j++)
                {
                    $json_text.= $x->searchResult->item[$i]->shippingInfo->shipToLocations[$j] . ',';
                };
                $json_text.= $x->searchResult->item[$i]->shippingInfo->shipToLocations[$location_count-1] . '",';
            };
            $json_text.='"oneDayShippingAvailable": "' . $x->searchResult->item[$i]->shippingInfo->oneDayShippingAvailable . '",';
            $json_text.='"returnsAccepted": "' . $x->searchResult->item[$i]->returnsAccepted . '",';
            $json_text.='"handlingTime": "' . $x->searchResult->item[$i]->shippingInfo->handlingTime . '"';
            $json_text.='} }';
            if ($i<$item_number-1)
                $json_text.=',';
        };
        $json_text.='}';
            
         
         
         
            #$html_text.="<tr><td><img src='" . $x->searchResult->item[$i]->galleryURL . "' alt='Item image not found'></img></td>";
			#$html_text.="<td float='center' vertical-align='center' valign='middle'><a href='" . $x->searchResult->item[$i]->viewItemURL . "'>" . $x->searchResult->item[$i]->title . "</a><br>";
			#$html_text.="<br>";
/*			if ($x->searchResult->item[$i]->condition->conditionDisplayName!=null)
                $html_text.="<b>Condition: </b>" . $x->searchResult->item[$i]->condition->conditionDisplayName;
            else
                $html_text.="<b>Condition: </b>";
			if ($x->searchResult->item[$i]->topRatedListing=="true")
				$html_text.="<img style='vertical-align:middle;' src='" . "http://cs-server.usc.edu:45678/hw/hw6/itemTopRated.jpg". "' width=50px height=50px ></img>";
			$html_text.="<br><br>";
			if (($x->searchResult->item[$i]->listingInfo->listingType=="FixedPrice") || ($x->searchResult->item[$i]->listingInfo->listingType=="StoreInventory"))
				$html_text.="<b>Buy It Now</b>";
			if ($x->searchResult->item[$i]->listingInfo->listingType=="Auction")
				$html_text.="<b>Auction</b>";
			if ($x->searchResult->item[$i]->listingInfo->listingType=="Classified")
				$html_text.="<b>Classified Ad</b>";
			$html_text.="<br><br>";
			if ($x->searchResult->item[$i]->returnsAccepted=="true")
				$html_text.="<p>Seller Accepts return</p>";
			#else
			#	$html_text.="<p>Seller Rejects return</p>";
			$html_text.="<p>";
			if (($x->searchResult->item[$i]->shippingInfo->shippingServiceCost)=="0.0")
				$html_text.="FREE Shipping";
			else
				$html_text.="Shipping Not FREE";
			#$html_text.="";
			if ($x->searchResult->item[$i]->shippingInfo->expeditedShipping=="true")
				$html_text.=" -- Expedited Shipping Available";
			#else
			#	$html_text.="Expedited Shipping NOT Available";
			$html_text.=" -- ";
			$html_text.="Handled for shipping in " . $x->searchResult->item[$i]->shippingInfo->handlingTime . " day(s)";
			$html_text.="<br><br>";
			$html_text.="<b>Price: $" . $x->searchResult->item[$i]->sellingStatus->convertedCurrentPrice;
			if (($x->searchResult->item[$i]->shippingInfo->shippingServiceCost)!=0.0)
				$html_text.=" (+ $" . $x->searchResult->item[$i]->shippingInfo->shippingServiceCost . " for shipping)";
			$html_text.="</b><i> From " . $x->searchResult->item[$i]->location;
			$html_text.="</td></tr>";
		}

$html_text.="</table>";
*/
}
	#echo $html_text;
}
?>

</div>
    <div class="header">
        <div class="header2">
            <img src="http://cs-server.usc.edu:45678/hw/hw6/ebay.jpg" height=128px width=128px alt="ebay logo not found" />
        </div>
        <div class="header2">
            <h1>Shopping</h1>
        </div>
    </div>

    <div class="main">
    <form method="POST" action="">
        <fieldset>
			<table style="border:0px;">
                <tr>
					<td><label class="caption">Key Words*:</label></td>
					<td><input type="text" style="width:100%" name="keywords" value="<?php echo isset($_POST['keywords']) ? $_POST['keywords'] : '' ?>"></td>
				</tr>
				<tr><td><hr></td><td><hr></td></tr>
                <tr>
					<td><label class="caption">Price Range:</label></td>
					<td><label>from $</label>
						<input class="price_input" type="text" value="<?php echo isset($_POST['price_down']) ? $_POST['price_down'] : '' ?>" name="price_down">
						<label>to $</label>
						<input class="price_input" type="text" value="<?php echo isset($_POST['price_up']) ? $_POST['price_up'] : '' ?>" name="price_up">
						
					</td>
				</tr>
				<tr><td><hr></td><td><hr></td></tr>
                <tr>
					<td><label class="caption">Condition:</label></td>
					<td><input type="checkbox" value="New" name="New" <?php if(isset($_POST['New'])) echo "checked='checked'"; ?> >New&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="checkbox" value="Used" name="Used" <?php if(isset($_POST['Used'])) echo "checked='checked'"; ?> >Used&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="checkbox" value="Very Good" name="Very_Good" <?php if(isset($_POST['Very_Good'])) echo "checked='checked'"; ?> >Very Good&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="checkbox" value="Good" name="Good" <?php if(isset($_POST['Good'])) echo "checked='checked'"; ?> >Good&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="checkbox" value="Acceptable" name="Acceptable" <?php if(isset($_POST['Acceptable'])) echo "checked='checked'"; ?> >Acceptable
						
					</td>
				</tr>
				<tr><td><hr></td><td><hr></td></tr>
                <tr>
					<td><label class="caption">Buying formats:</label></td>
					<td><input type="checkbox" value="Buy It Now" name="Buy_It_Now" <?php if(isset($_POST['Buy_It_Now'])) echo "checked='checked'"; ?> >Buy It Now&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="checkbox" value="Auction" name="Auction" <?php if(isset($_POST['Auction'])) echo "checked='checked'"; ?> >Auction&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="checkbox" value="Classified Ads" name="Classified_Ads" <?php if(isset($_POST['Classified_Ads'])) echo "checked='checked'"; ?> >Classified Ads
						
					</td>
				</tr>
				<tr><td><hr></td><td><hr></td></tr>
                <tr>
					<td><label class="caption">Seller:</label></td>
					<td><input type="checkbox" value="Return_accepted" name="Return_accepted"  <?php if(isset($_POST['Return_accepted'])) echo "checked='checked'"; ?> >Return accepted
					
				</tr>
				<tr><td><hr></td><td><hr></td></tr>
				<tr>
					<td><label class="caption">Shipping:</label><br><br><br></td>
					<td><input type="checkbox" value="Free_Shipping" name="Free_Shipping" <?php if(isset($_POST['Free_Shipping'])) echo "checked='checked'"; ?> >Free Shipping<br>
                        <input type="checkbox" value="Expedited_shipping_available" name="Expedited_shipping_available" <?php if(isset($_POST['Expedited_shipping_available'])) echo "checked='checked'"; ?> >Expedited shipping available<br>
						<label>Max handling time (days):&nbsp;</label><input class="price_input" type="text" value="<?php echo isset($_POST['Max_days']) ? $_POST['Max_days'] : '' ?>" name="Max_days">
						
					</td>
				</tr>
				<tr><td><hr></td><td><hr></td></tr>
				<tr>
                    <td><label class="caption">Sort by:</label></td>
					<td><select name="Sort_by">
							<option value="Best_Match" <?php echo (isset($_POST['Sort_by'])) ? (($_POST['Sort_by']=="Best_Match") ? "selected" : "") : "selected";?> >Best&nbsp;Match</option>
							<option value="Price_highest_first" <?php echo (isset($_POST['Sort_by']) && ($_POST['Sort_by']=="Price_highest_first")) ? "selected" :"";?> >Price:&nbsp;highest&nbsp;first</option>
							<option value="Price_Shipping_highest_first" <?php echo (isset($_POST['Sort_by']) && ($_POST['Sort_by']=="Price_Shipping_highest_first")) ? "selected" : "";?> >Price&nbsp;+&nbsp;Shipping:&nbsp;highest&nbsp;first</option>
							<option value="Price_Shipping_lowest_first" <?php echo (isset($_POST['Sort_by']) && ($_POST['Sort_by']=="Price_Shipping_lowest_first")) ? "selected" : "";?> >Price&nbsp;+&nbsp;Shipping:&nbsp;lowest&nbsp;first</option>
						</select>	
					</td>
				</tr>
				<tr><td><hr></td><td><hr></td></tr>
				<tr>
					<td><label class="caption">Results Per Page:</label></td>
					<td><select name="Results_Per_Page">
							<option value="5"  <?php echo (isset($_POST['Results_Per_Page'])) ? (($_POST['Results_Per_Page']=="5") ? "selected" : "") : "selected";?> >5</option>
							<option value="10" <?php echo (isset($_POST['Results_Per_Page']) && ($_POST['Results_Per_Page']=="10")) ? "selected" :"";?> >10</option>
							<option value="15" <?php echo (isset($_POST['Results_Per_Page']) && ($_POST['Results_Per_Page']=="15")) ? "selected" :"";?> >15</option>
							<option value="20" <?php echo (isset($_POST['Results_Per_Page']) && ($_POST['Results_Per_Page']=="20")) ? "selected" : "";?> >20</option>
						</select>
						
					</td>
				</tr>
				<tr><td><hr></td><td><hr></td></tr>
				<tr><td colspan="2" style="text-align:right;">
						<input type="reset" name="clear" value="clear" onclick="clearform(this.form); return false;">
						<input type="submit" name="submit" value="search" onclick="checkform(this.form); return false;">
					</td>
				</tr>
			</table>	
        </fieldset>
    </form>

</div>
<div id="results">
<?php
if(isset($_POST["submit"]))
{
    #echo $testing;
    echo $URL_POST;
    echo '<br />';
    echo $json_text;
}
?>
</div>
</body>