<?php 

    $API_KEY = "ChuyangZ-productS-PRD-516de56dc-16fe0077";

    if (isset($_POST['keyword'])){
        $keyword = $_POST['keyword'];
        $keyword = urlencode($keyword);
        $distance = $_POST['distance'];
        $location = $_POST['location'];
        $free = $_POST['free'];
        $pickup = $_POST['pickup'];
        $new_cond = $_POST['new_cond'];
        $used = $_POST['used'];
        $unspecified = $_POST['unspecified'];
        $hidden = $_POST['hidden'];
        $type = $_POST['type'];

        switch($type){
            case 'art':
                $categoryId = 550;
                break;
            case 'baby':
                $categoryId = 2984;
                break;
            case 'book':
                $categoryId = 267;
                break;
            case 'cloth':
                $categoryId = 11450;
                break;
            case 'computer':
                $categoryId = 58058;
                break;
            case 'health':
                $categoryId = 26395;
                break;
            case 'music':
                $categoryId = 11233;
                break;
            case 'game':
                $categoryId = 1249;
                break;
            default:
                $categoryId = 0;
        }

        $url = "http://svcs.ebay.com/services/search/FindingService/v1?OPERATION-NAME=findItemsAdvanced&SERVICE-VERSION=1.0.0&SECURITY-APPNAME=" . $API_KEY .
        "&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD&paginationInput.entriesPerPage=20&keywords=" .$keyword;
        $n = -1;
        $index = -1;
        if ($categoryId != 0)   $url .= "&categoryId=" . $categoryId;
        if ($location != ""){
            $n += 1;
            $url .= "&buyerPostalCode=" . $location;
            $url .= "&itemFilter(" . $n. ").name=MaxDistance&itemFilter(" . $n. ").value=" .$distance;
        }    
        
        if ($free == "On"){
            $n += 1;
            $url .= "&itemFilter(" . $n. ").name=FreeShippingOnly&itemFilter(". $n. ").value=true";
        } if ($pickup == "On"){
            $n += 1;
            $url .= "&itemFilter(" .$n. ").name=LocalPickupOnly&itemFilter(". $n. ").value=true";
        } if ($new_cond == "On" || $used == "On" || $unspecified == "On"){
            $n += 1;
            $url .= "&itemFilter(" .$n. ").name=Condition";
            if ($new_cond == "On"){
                $index += 1;
                $url .= "&itemFilter(" .$n. ").value(" .$index. ")=New" ;
            } if ($used == "On"){
                $index += 1;
                $url .= "&itemFilter(" .$n. ").value(" .$index. ")=Used" ;
            } if ($unspecified == "On"){
                $index += 1;
                $url .= "&itemFilter(" .$n. ").value(" .$index. ")=Unspecified" ;
            }
              
        } 
        $n += 1;
        $url .= "&itemFilter(" .$n. ").name=HideDuplicateItems&itemFilter(" . $n. ").value=true";

        echo $url;
        $url_data = json_decode(file_get_contents($url), true);
        exit(json_encode($url_data));

    } else if (isset($_POST['item_id'])){
        $item_id = $_POST['item_id'];
        if (isset($_POST['name'])){
            $name = $_POST['name'];
            if ($name == "detail"){
                $url_item_detail = "http://open.api.ebay.com/shopping?callname=GetSingleItem&responseencoding=JSON&appid=". $API_KEY.
                "&siteid=0&version=967&ItemID=".$item_id."&IncludeSelector=Description,Details,ItemSpecifics";
                $url_data_item_detail = json_decode(file_get_contents($url_item_detail), true);   
                exit(json_encode($url_data_item_detail));
            } else if ($name == "similar"){
                $url_item_similar = "http://svcs.ebay.com/MerchandisingService?OPERATION-NAME=getSimilarItems&SERVICE-NAME=MerchandisingService&SERVICE-VERSION=1.1.0&CONSUMER-ID="
                .$API_KEY. "&RESPONSE-DATA-FORMAT=JSON&REST-PAYLOAD&itemId=".$item_id."&maxResults=8";
                $url_item_similar_detail = json_decode(file_get_contents($url_item_similar), true);
                exit(json_encode($url_item_similar_detail));
            }   
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<style>

    body{
        font: Libre Baskerville;
    }

    #box{
        margin: auto;
        width: 45%;
        border: 2px solid #D0D0D0;
        background-color:#F8F8F8;
        margin-top: 15px;
    }

    .distance{
        visibility: hidden;
        width: 15%;

    }

    #location{
        display: inline;
        list-style-type: None;
        margin: 0;
        padding: 0;
        position: absolute;
    }

    #buttons{
        text-align: center;
        margin-top: 40px;
        margin-bottom: 20px;
        margin-left: 10px;
    }


    #img_table{
        margin-top: 5px;
    }

    #des_table{
        margin: 0 auto;
        text-align: center;
        display: none;
        width: 80%;
    }

    iframe{
        margin: 0 auto;
    }

    table{
        border: solid grey;
    }

    a:hover{
        color:  #D0D0D0; 
        text-decoration: none; 
        font-weight: none;
    }


    a{
        color: black;
        text-decoration: none;
    }
    a:hover{
        color: #D0D0D0;
    }
    
</style>


<body onload="init()">
    <div id="box">
        <form method="GET" id="form" action="">
            <center><h1 style="font-family: Libre Baskerville;"><i>Product Search</i></h1></center>
            <hr>
            <p>
                <b><label style="margin-left: 10px" for="keyword">Keyword</label></b>
                <input type="text" id="keyword" name="keyword" value="">
            </p>

            <p>
                <b><label style="margin-left: 10px" for="category">Category</label></b>
                <select name="type" id="type">
                    <option value="all">All Categories</option>
                    <option value="art">Art</option>
                    <option value="baby">Baby</option>
                    <option value="book">Books</option>
                    <option value="cloth">Clothing, Shoes & Accessories</option>
                    <option value="computer">Computers/Tablets & Networking</option>
                    <option value="health">Health & Beauty</option>
                    <option value="music">Music</option>
                    <option value="game">Video Games & Consoles</option>

                </select>
            </p>

            <p>
                <b><label style="margin-left: 10px" for="condition">Condition</label></b>
                <input type="checkbox" name="new" id="new"> New
                <input type="checkbox" name="used" id="used"> Used
                <input type="checkbox" name="unspecified" id="unspecified"> Unspecified
            </p>

            <p>
                <b><label style="margin-left: 10px" for="shipping">Shipping Options</label></b>
                <input type="checkbox" name="pickup" id="pickup"> Local Pickup
                <input type="checkbox" name="free" id="free"> Free Shipping
            </p>
            <b><input style="margin-left: 10px" type="checkbox" name="search" id="search" onclick=enable_search()> Enable Nearby Search </b>
            <input type="text" name="distance" style="width:10%" id="distance"' disabled="disabled" value="10"> <b id="word" style="opacity: 0.5"> miles from </b>
            <ul id="location">
                <li>
                    <input type="radio" id="location_here" disabled="disabled" value="" name="here" checked="checked" onclick = "check_here()">
                    <label for="here">Here</label>
                </li>
                <li>
                    <input type="radio" id="location_zip" name="zipcode" disabled="disabled" value="" onclick="check_location()">
                    <input type="text", id="zip" name="zipcode" style="width: 60%" disabled="disabled" value="" placeholder="zip code">
                </li>
            </ul>
            <div id="buttons">
                <input type="button" name="search" id="search_button" value="search" onclick="start_search()">
                <input type="button", value="clear" onclick="clear_form()">
            </div>
        </form>
    </div>

    <div id="result" style="margin-top:10px;"></div>
    <!-- <div id="result_table"></div> -->
    <div style='margin-top: 20px padding-bottom: 10px' id="img_table"></div>
    <div style='padding-bottom: 10px' id="img_table1"></div>
    <div style=' padding-bottom: 10px' id="des_table"></div>

    <div style='padding-bottom: 10px' id="img_table2"></div>
    <div style='padding-bottom: 10px' id="img_table3"></div>

    <div style='padding-bottom: 10px' id="img_table4"></div>

</body>
</html>

<script>
    function init(){
        document.getElementById("search").checked = false;
        document.getElementById("search_button").setAttribute("disabled", true);
        document.getElementById("location_here").setAttribute("disabled", true);
        document.getElementById("distance").setAttribute("disabled", true);
        document.getElementById("zip").setAttribute("disabled", true);

        var lat = "";
        var lon = "";

        try{
            url = "http://ip-api.com/json";
            xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET", url, false);
            xmlhttp.send();
            jsonObj = JSON.parse(xmlhttp.responseText);
            zip = jsonObj.zip;
        }catch(e){
            zip = "";
        }

        document.getElementById('location_here').value = zip;
        document.getElementById('search_button').removeAttribute("disabled");
    }

    function clear_form(){
        document.getElementById('form').reset();
        document.getElementById('zip').placeholder = 'zip code';
        document.getElementById('word').style.opacity = 0.5;
        document.getElementById('result').innerHTML = "";
        document.getElementById('img_table').innerHTML = "";
        document.getElementById('img_table1').innerHTML = "";
        document.getElementById('img_table2').innerHTML = "";
        document.getElementById('img_table3').innerHTML = "";
        document.getElementById('img_table4').innerHTML = "";
        document.getElementById('des_table').innerHTML = "";

        init();
    }

    function enable_search(){
        if (document.getElementById('search').checked){
            document.getElementById('distance').removeAttribute("disabled");
            document.getElementById('location_zip').removeAttribute("disabled");
            document.getElementById("location_here").removeAttribute("disabled");
            document.getElementById('word').style.opacity = 1;
        } else{
            
            document.getElementById('distance').setAttribute("disabled", true);
            document.getElementById('location_zip').setAttribute("disabled", true);
            document.getElementById("location_here").setAttribute("disabled", true);
            document.getElementById('word').style.opacity = 0.5;

        }
    }

    function check_here(){
        document.getElementById("location_here").setAttribute("checked", true);
        if (document.getElementById("location_here").checked){
            if (document.getElementById("location_zip").checked){
                document.getElementById("location_zip").checked = false;
                if (document.getElementById("zip").disabled){
                }else{
                    document.getElementById("zip").setAttribute("disabled", true);
                    document.getElementById('zip').placeholder = "zip code";

                }
            }
        }
    }

    function check_location(){
        if (document.getElementById("location_zip").checked){
            document.getElementById("zip").disabled = false;
            if (document.getElementById("location_here").checked){
                document.getElementById("location_here").checked = false;
            }
        }
    }

    function start_search(){

        document.getElementById('result').innerHTML = "";
        document.getElementById('img_table').innerHTML = "";
        document.getElementById('img_table1').innerHTML = "";
        document.getElementById('img_table2').innerHTML = "";
        document.getElementById('img_table3').innerHTML = "";
        document.getElementById('img_table4').innerHTML = "";
        document.getElementById('des_table').innerHTML = "";

        document.getElementById("keyword").setAttribute("required", "");
        document.getElementById("keyword").reportValidity();

        if (document.getElementById("location_zip").disabled == false){
            document.getElementById("zip").setAttribute("required", "");
            document.getElementById("zip").reportValidity();
            if (document.getElementById('zip').reportValidity() == false){
                return;
            }
        }

        var hp = new XMLHttpRequest();
        var url = "productSearch.php";
        var keyword = document.getElementById("keyword").value;
        var type = document.getElementById("type").options[document.getElementById("type").selectedIndex].value;
        var distance = document.getElementById("distance").value;
        var free = "";
        var pickup = "";
        var new_cond = "";
        var used = "";
        var unspecified = "";
        var hidden = "On";

        if (document.getElementById("free").checked)    free = "On";
        if (document.getElementById("pickup").checked)  pickup = "On";
        if (document.getElementById("new").checked)     new_cond = "On";
        if (document.getElementById("used").checked)    used = "On";
        if (document.getElementById("unspecified").checked)    unspecified = "On";
        
        var disance_pattern = /^\d+(\.\d+)?$/;
        if (disance_pattern.test(document.getElementById("distance").value)){
            distance_valid = 1;
        }else{
            distance_valid = 0;
        }

        var zip_pattern = /^\d{1,5}$/;
        if (zip_pattern.test(document.getElementById("distance").value)){
            zip_valid = 1;
        }else{
            zip_valid = 0;
        }

        if (zip_valid === 0){
            alert("Invalid zip code");
            return false;
        }
        if (distance_valid === 0){
            alert("Invalid number entered");
            return false;
        } else{
            if (document.getElementById("search").checked){
                if (document.getElementById('location_here').checked){
                    console.log("here!!");
                    var location = document.getElementById("location_here").value;
                } else{
                    var location = document.getElementById('zip').value;
                }    
            }else{
                var location = "";
            }


            if (keyword !== "" ){

                hp.open("POST", url, true);
                hp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");              
                hp.send(
                    "keyword=" + keyword +
                    "&location=" + location + 
                    "&distance=" + distance +
                    "&type=" + type +
                    "&free=" + free +
                    "&pickup=" + pickup +
                    "&new_cond=" + new_cond + 
                    "&used=" + used +
                    "&unspecified=" + unspecified + 
                    "&hidden=" + hidden +
                    "&type=" + type);

                hp.onreadystatechange = function() {
                    if (hp.readyState == 4 && hp.status == 200){ 
                        var dataObj = hp.responseText;
                        dataObj = dataObj.substring(dataObj.indexOf("{"));
                        makeTable(dataObj);
                    }
                }
            }
        }
    }

    function makeTable(dataObj){
        
        if (dataObj == null || dataObj == "")   return;
        var parse_data = JSON.parse(dataObj);
        console.log(parse_data);
        if (parse_data.findItemsAdvancedResponse[0].ack == "Failure"){
            document.getElementById('result').innerHTML = "<center style='margin-top: 15px; background-color:#D0D0D0'>" + parse_data.findItemsAdvancedResponse[0].errorMessage[0].error[0].message +  "</center>";
            return;
        }
        var data_result = parse_data.findItemsAdvancedResponse[0].searchResult[0];
        console.log(data_result);
        if (data_result['@count'] == 0){
            document.getElementById("result").innerHTML = "<center style='margin-top: 15px; background-color:#D0D0D0'> No Records has been found </center>";
            return;
        }
        var html_content = "<br />";
        html_content += "<center><table id='result_table' border='1' width='90%' cellspacing='0' bordercolor='grey' style='text-align:left;'>";
        html_content += "<tr><th><center>Index</center></th><th><center>Photo</center></th><th><center>Name</center></th><th><center>Price</center></th><th><center>Zip code</center></th><th><center>Condition </center></th><th> <center>Shipping Option </center></th></tr>";
        var index = 0;
        var item_id = 0;
        for (var i = 0; i < data_result['@count']; i ++){
            html_content += "<tr>";
            index = i + 1;
            html_content += "<td>" + index  + "</td>";
            if (data_result.item[i].galleryURL) html_content += "<td><img width='100px' height='90px' src='" + data_result.item[i].galleryURL + "'> </td>";
            else    html_content += "<td> Image Not Found </td>";

            item_id = data_result.item[i].itemId;
            html_content += "<td><a onclick='item_details(" + item_id +")' href='javascript:void(0);'>" + data_result.item[i].title + "</a></td>";
            html_content += "<td> $" + data_result.item[i].sellingStatus[0].currentPrice[0].__value__ + "</td>";
            if (data_result.item[i].postalCode) html_content += "<td> " + data_result.item[i].postalCode[0] + "</td>";
            else    html_content += "<td> N/A </td>";
            if (data_result.item[i].condition)  html_content += "<td> " + data_result.item[i].condition[0].conditionDisplayName + "</td>";
            else   html_content += "<td> N/A </td>";
            if (data_result.item[i].shippingInfo[0].shippingServiceCost){
                if (data_result.item[i].shippingInfo[0].shippingServiceCost[0].__value__ == 0)  html_content += "<td>Free Shipping</td>";
                else    html_content += "<td> $" + data_result.item[i].shippingInfo[0].shippingServiceCost[0].__value__ + "</td>";
            }else   html_content += "<td> N/A </td>";
            
            html_content += "</tr>";
        }

        html_content += "</table></center>";
        document.getElementById("result").innerHTML = html_content;
        



    
    }

    function item_details(item_id){
        hide_similar(item_id);
        var hp1 = new XMLHttpRequest();
        var url = "productSearch.php";
        console.log(item_id);
        hp1.open("POST", url, true);
        hp1.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        hp1.send("item_id=" + item_id + "&name=detail");
        hp1.onreadystatechange = function() {
            if (hp1.readyState == 4 && hp1.status == 200){ 
                var dataObj_item_detail = hp1.responseText;
                dataObj_item_detail = dataObj_item_detail.substring(dataObj_item_detail.indexOf("{"));
                makeDetailTable(dataObj_item_detail);
            }
        }
    }

    function makeDetailTable(dataObj){
        if (dataObj == "" || dataObj == null)   return;
        var parse_detail_data = JSON.parse(dataObj);
        console.log(parse_detail_data);
        var content_detail = "<br />";
        content_detail += "<center> <b> <H1> Item Details <H1> </b> </center>";
        var data_item_result = parse_detail_data.Item;
        content_detail += "<center><table id='detail_table' border='1' width='auto' cellspacing='0' bordercolor='grey' style='text-align:left; font-size: 18px'></center>";
        if (data_item_result.PictureURL)    content_detail += "<tr><th><b>Photo</b></th> <th><img width='150px' height='180px' src='" + data_item_result.PictureURL + "'></td></tr>";
        if (data_item_result.Title)     content_detail += "<tr><th><b>Title</b></th> <td>" + data_item_result.Title + "</td></tr>";
        if (data_item_result.Subtitle)     content_detail += "<tr><th><b>Subtitle</b></th> <td>" + data_item_result.Subtitle + "</td></tr>";
        if (data_item_result.CurrentPrice)     content_detail += "<tr><th><b>Price</b></th> <td>" + data_item_result.CurrentPrice.Value + " " + data_item_result.CurrentPrice.CurrencyID +"</td></tr>";
        if (data_item_result.Seller.UserID)     content_detail += "<tr><th><b>Seller</b></th> <td>" + data_item_result.Seller.UserID + "</td></tr>";
        if (data_item_result.ReturnPolicy) {
            if (data_item_result.ReturnPolicy.ReturnsWithin)
                content_detail += "<tr><th><b>Return Policy(US)</b></th> <td>" + data_item_result.ReturnPolicy.ReturnsAccepted + " within " + data_item_result.ReturnPolicy.ReturnsWithin + "</td></tr>";
            else    content_detail += "<tr><th><b>Return Policy(US)</b></th> <td>" + data_item_result.ReturnPolicy.ReturnsAccepted + "</td></tr>";
        }
            
        if (data_item_result.ItemSpecifics){
            if (data_item_result.ItemSpecifics.NameValueList){
                for (var i = 0; i < data_item_result.ItemSpecifics.NameValueList.length; i++){
                    content_detail += "<tr><th><b>" +  data_item_result.ItemSpecifics.NameValueList[i].Name + "</b></th> <td>" + data_item_result.ItemSpecifics.NameValueList[i].Value[0] + "</td></tr>";
                }
            }
        }

        document.getElementById("result").innerHTML = "";
        document.getElementById("result").innerHTML = content_detail;


        
        follow_up_desp(data_item_result);
    }

    function follow_up_desp(Obj){
        if (Obj.Description){
            var data_description = Obj.Description;
        }else{
            var data_description = "";
        }

        var item_id = Obj.ItemID;
        console.log("sim,", item_id);
        document.getElementById("img_table").innerHTML = "<center><font color=#D0D0D0> click to show seller message </font> </center>";
        document.getElementById("img_table1").innerHTML ="<center><img src='http://csci571.com/hw/hw6/images/arrow_down.png' width=30px onclick='show_desp(" + item_id + ")'></center>";        var des_data = "";
 
        if (data_description == ""){
            document.getElementById("des_table").innerHTML = "<b><center style='background-color:#D0D0D0; margin-top: 15px'> No Seller Message found </center> </b>";
        }else{
            var des_data = "<iframe style='margin-top: 10px' width='100%' id='iframe1' frameborder='0' srcdoc=''> Sorry, your brower doesn't support iframe.<iframe>";

        } 
        document.getElementById("des_table").innerHTML = des_data;
        document.getElementById('iframe1').srcdoc = data_description;
        
        document.getElementById("img_table2").innerHTML = "<center><font color=#D0D0D0> click to show similar message </font> </center>";
        document.getElementById("img_table3").innerHTML ="<center><img src='http://csci571.com/hw/hw6/images/arrow_down.png' width=30px onclick='getSimilar(" + item_id + ")'></center>";

    }

    function getSimilar(item_id){
        show_similar(item_id);
        var hp2 = new XMLHttpRequest();
        var url = "productSearch.php";
        console.log(item_id);
        hp2.open("POST", url, true);
        hp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        hp2.send("item_id=" + item_id + "&name=similar");
        hp2.onreadystatechange = function() {
            if (hp2.readyState == 4 && hp2.status == 200){ 
                var dataObj_item_similar = hp2.responseText;
                console.log("data_similar: ", dataObj_item_similar);
                dataObj_item_similar = dataObj_item_similar.substring(dataObj_item_similar.indexOf("{"));
                makeSimilarTable(dataObj_item_similar);
            }
        }
    }

    function makeSimilarTable(Obj){
        if (Obj == "" || Obj == null)   return;
        var parse_sim_data = JSON.parse(Obj);
        console.log(parse_sim_data);
        var content_sim = "<br />";
        var data_item_sim = parse_sim_data.getSimilarItemsResponse.itemRecommendations.item;
        if (data_item_sim.length == 0)  content_sim += "<center style='margin-top: 15px; background-color:#D0D0D0'> No Similar Item found </center>";
        else{
            content_sim += "<center><div style='width: 70%; overflow-x: scroll'><table id='sim_table' width='60%' cellspacing='0' style='text-align:left; font-size: 18px'></center>";
            content_sim += "<tr>";
            for (var i = 0; i < data_item_sim.length; i++){
                item_id = data_item_sim[i].itemId;
                content_sim += "<td width='400' style='padding-left: 15px; padding-right: 15px'>";
                content_sim += "<div><center><img src='" + data_item_sim[i].imageURL + "'></center></div>";
                content_sim += "<div style='font-size: 10px'><a style='color:black-decoration:none;' onclick='item_details(" + item_id +")' href='javascript:void(0);'>" + data_item_sim[i].title + "</div>";
                if (data_item_sim[i].currentPrice){
                    content_sim += "<div style='font-size: 10px'><b><center>" + data_item_sim[i].currentPrice.__value__ + " " + data_item_sim[i].currentPrice["@currencyId"] + "</center></b></div>";
                }else if (data_item_sim[i].buyItNowPrice){
                    content_sim += "<div style='font-size: 10px; padding-bottom: 15px'><b><center>" + data_item_sim[i].buyItNowPrice.__value__ + " " + data_item_sim[i].buyItNowPrice["@currencyId"] + "</center></b></div>";
                }else{
                    content_sim += "<div style='font-size: 10px padding-top: 15px padding-bottom: 15px'><b><center>N/A</center></div>"
                }
                content_sim += "</td>";
                
                content_sim += "<div></div></td>";
            }
            content_sim += "</tr>";

        }


        content_sim += "</table>";
        document.getElementById("img_table4").innerHTML = content_sim;

    }

    function show_desp(item_id){
        document.getElementById("img_table4").style.display = "none";
        document.getElementById('img_table2').innerHTML = "<center><font color=#D0D0D0> click to show similar message </font> </center>";
        document.getElementById("img_table3").innerHTML ="<center><img src='http://csci571.com/hw/hw6/images/arrow_down.png' width=30px onclick='getSimilar(" + item_id + ")'></center>";
        document.getElementById('img_table').innerHTML = "<center><font color=#D0D0D0> click to hide seller message </font> </center>";
        document.getElementById("img_table1").innerHTML ="<center><img src='http://csci571.com/hw/hw6/images/arrow_up.png' width=30px onclick='hide_desp(" + item_id + ")'></center>";
        document.getElementById("des_table").style.display = "block";
        var innerHeight = document.getElementById('iframe1').contentWindow.document.body.scrollHeight + 40 ;
        document.getElementById('iframe1').height = innerHeight + "px";
    }

    function hide_desp(item_id){
        document.getElementById("img_table").innerHTML = "<center><font color=#D0D0D0> click to show seller message </font> </center>";
        document.getElementById("img_table1").innerHTML ="<center><img src='http://csci571.com/hw/hw6/images/arrow_down.png' width=30px onclick='show_desp(" + item_id + ")'></center>";
        document.getElementById("des_table").style.display = "none";
    }

    function show_similar(item_id){
        document.getElementById("des_table").style.display = "none";
        document.getElementById("img_table").innerHTML = "<center><font color=#D0D0D0> click to show seller message </font> </center>";
        document.getElementById("img_table1").innerHTML ="<center><img src='http://csci571.com/hw/hw6/images/arrow_down.png' width=30px onclick='show_desp(" + item_id + ")'></center>";
        document.getElementById('img_table2').innerHTML = "<center><font color=#D0D0D0> click to hide similar message </font> </center>";
        document.getElementById("img_table3").innerHTML ="<center><img src='http://csci571.com/hw/hw6/images/arrow_up.png' width=30px onclick='hide_similar(" + item_id + ")'></center>";
        document.getElementById("img_table4").style.display = "block";
        // var innerHeight = document.getElementById('iframe1').contentWindow.document.body.scrollHeight + 20 ;
        // document.getElementById('iframe1').height = innerHeight + "px";
    }

    function hide_similar(item_id){
        console.log("called hide");
        document.getElementById('img_table2').innerHTML = "<center><font color=#D0D0D0> click to show similar message </font> </center>";
        document.getElementById("img_table3").innerHTML ="<center><img src='http://csci571.com/hw/hw6/images/arrow_down.png' width=30px onclick='getSimilar(" + item_id + ")'></center>";
        document.getElementById("img_table4").style.display = "none";
    }
</script>


