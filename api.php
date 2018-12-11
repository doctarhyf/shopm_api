<?php

@session_start();

require_once('helper_funcs.php');
require_once("defs.php");
require_once("db.php");



if(isset($_REQUEST['act'])){
	
	$act = $_REQUEST['act'];
	
	
	
	if($act == "login" && isset($_REQUEST['username']) && isset($_REQUEST['password']) ){
		
	

		//$resp = '{result:\'false\'}';	
		
		$username = $_REQUEST["username"];
		$password = $_REQUEST["password"];
		
		
		
		$data = $db->login($username, $password);
		
		echo json_encode($data);
		
	}

	//shopm
	if($act == 'addItemToStock'){


		$item_name = $_REQUEST['item_name'];
		$item_desc = $_REQUEST['item_desc'];
		$item_price = $_REQUEST['item_price'];
		$item_unique_name = $db->getRandomHash(); 
		$item_stock_count = $_REQUEST['item_stock_count'];


		echo $db->addItemToStock($item_name, $item_desc, $item_price, $item_unique_name, $item_stock_count);

	}

	//shopm
	if($act == 'addSell'){

		$sell_item_id = $_REQUEST['sell_item_id'];

		echo $db->addSell($sell_item_id);
	}

	//shopm
	if($act == 'loadItem'){

		$item_unique_name = $_REQUEST['item_unique_name'];
		echo json_encode($db->loadItem($item_unique_name));

	}

	//shopm
	if($act == 'sellItem'){

		$item_id= $_REQUEST['item_id'];
		$item_qty= $_REQUEST['item_qty'];
		$exch_rate=$_REQUEST['exch_rate'];
		$rem_stock = $_REQUEST['rem_stock'];

		echo json_encode($db->sellItem($item_id, $item_qty, $exch_rate, $rem_stock));

	}

	if($act == 'updViewsCount'){

		
		$itemid = $_REQUEST['itemid'];

		$newCount = $db->updViewsCount($itemid);

		if($newCount){
			echo $newCount;
		}else{
			echo 'false';
		}
		

	}
	
	if($act == 'uploadPP'){
		
		$ppData = $_REQUEST['ppData'];
		
		$mobile = $_REQUEST['mobile'];

		$res = false; //$db->uploadPP($mobile, $ppData);

		$pp_file = md5($mobile);


		$ppDirs = ROOT_FOLDER_IMAGES . '/' . IMG_FOLDER_PP . '/' ;
		$this->createDirs($ppDirs);

		$fh = fopen($ppDirs  . $pp_file . '.jpg' , 'w');
		if(fwrite($fh, base64_decode($ppData))){
			fclose($fh);
			$res = true;
		}

		if($res === true){
			echo '{result:\'true\'}';
		}else{
			echo '{result:\'false\', message:\' ' . $res . '\'}';
		}



	}
	

	if($act == 'rmProd'){
		
		$myid = $_REQUEST['myid'];
		$unq = $_REQUEST['unq'];	
		
		$db->rmProd($myid, $unq);
		
		echo 'true';
		
		
		
	}

	

	if($act == 'loadCatCars'){
		
		echo $db->loadCat(CAT_CARS);
		
	}
	
	if($act == 'getAllCategories'){
		
		
		echo json_encode($db->getAllCategories());
		
	}
	
	if($act == 'postLooking4'){
		
		$uid = $_REQUEST['myid'];
		$title = $_REQUEST['title'];
		$desc = $_REQUEST['desc'];
		$rating = $_REQUEST['rating'];
		$updid = -1;

		if(isset($_REQUEST['updid'])) {
			$updid = $_REQUEST['updid'];
		}
		//echo 'updid = ' . $updid;
		if($db->postLooking4($uid, $title, $desc, $rating, $updid)){
			echo 'true';
		}else{
			echo 'false';
		}
		
	}

	if($act == 'dell4'){

		$iid = $_REQUEST['iid'];
		$uid = $_REQUEST['uid'];
		$res = $db->dell4($iid, $uid);
		if($res === true) {
			echo 'true';
		}else{
			echo $res;
		}

	}

	if($act == 'checkAllInquiries' && isset($_REQUEST['uid'])){



		$mine = isset($_REQUEST['mine']) ;
		$limit = -1;
		if(isset($_REQUEST['limit'])){
			$limit = $_REQUEST['limit'];
		}

		$uid = $_REQUEST['uid'];
		$data = $db->checkAllInquiries($uid, $limit, $mine);


		
			echo $data;
		

	}
	
	if($act == 'getAllItemTypes'){
		echo json_encode($db->getAllItemTypes());
	}
	
	if($act == 'loadCatElec'){
		
		echo $db->loadCat(CAT_ELEC);
		
	}
	
	if($act == 'loadCatOther'){
		
		echo $db->loadCat(CAT_OTHER);
		
	}
	
	if($act == 'test'){
		echo 'TEST OK';	
	}


	//GET FRESH NEW FOR NEW ITEM TO POST
	if($act == 'getUniqueId'){

		$date = date('Ymdhis');
		$itemUniqueName = rand(1000000000, 2000000000) . '_' . $date;  
		echo $itemUniqueName;

	}
	
	if($act == 'exposeItem'){
		
		
		//$date = date('Ymdhis');
		//$itemUniqueName = rand(1000000000, 2000000000) . '_' . $date;  
		//$un = $itemUniqueName;

		$un = $_REQUEST['un'];
		
		$itemName = $_REQUEST['itemName'];
		$itemPrice =  $_REQUEST['itemPrice'];
		$itemCur = $_REQUEST['itemCurrency'];
		$itemPriceToDiscuss = $_REQUEST['pdPriceToDiscuss'];
		$itemDesc = $_REQUEST['itemDescription'];
		$itemCat = $_REQUEST['itemCategory'];
		$itemType = $_REQUEST['pdType'];
		$ownerId = $_REQUEST['pdOwnerId'];
		$mainPic = 'no_pic';//$_REQUEST['mainPic'];
		$pic1 = 'no_pic';//$_REQUEST['pic1'];
		$pic2 = 'no_pic';//$_REQUEST['pic2'];
		$pic3 = 'no_pic';//$_REQUEST['pic3'];
		$itemQual = $_REQUEST['itemQuality'];
		//$un = $_REQUEST['un'];
		//$itemid = $_REQUEST['itemId'];
		
		
		
		$res = $db->exposeItem($un, $itemName, $itemPrice, $itemCur, $itemPriceToDiscuss, $itemDesc, $itemCat, $itemType, $ownerId, $mainPic, 
		$pic1 , $pic2, $pic3, $itemQual);

		
		
		if($res === true){
			
			echo '{result:\'true\',pdUniqueName:\'' . $un . '\'}';
			
		}else{
			
			echo '{result:\'false\', messgae:\'' . $res . '\'}';
		}
		
		//print_r($_REQUEST);
		//echo 'type : ' . $itemType;
		//echo 'cat : ' . $itemCat;
		
	
			
	}



	if($act == 'uploadProductImageFile' ){

		//$path = $_REQUEST['path'];


		//path of the folder which file is to be saved

//path of the folder which file is to be saved
		$fn = $_REQUEST['fn'];
		
 $file_path =  IMG_FOLDER_ROOT . '/' . IMG_FOLDER_PRODUCTS . '/' . $fn;

 if(move_uploaded_file($_FILES['uploaded_file']['tmp_name'], $file_path)) {
 echo "true";
 } else{
 echo print_r($_FILES['uploaded_file']);
 }

//

	}

	if($act == 'uploadImage' ){
		
		
		$sfn = $_REQUEST[KEY_REQ_SERVER_FILE_NAME];
		$relRootDir = $_REQUEST[KEY_REQ_REL_ROOT_DIR];
		$destination = $relRootDir . $sfn;

		$db->createDirs($relRootDir);

		print_r($_REQUEST);

		if(move_uploaded_file($_FILES[IMAGE_UPLOAD_FORM_NAME]['tmp_name'], $destination)){
			$res['res'] = 'true';
			$res['sfn'] = $sfn;
			echo json_encode($res);
		}else{
			$res = $_FILES[IMAGE_UPLOAD_FORM_NAME];
			$res['res'] = 'false';
			echo json_encode($res);
		}
	}

	if ($act == 'getItemTypeIdByNameAndCatName'){

		$typeName = $_REQUEST['typeName'];
		$catName = $_REQUEST['catName'];

		$res = $db->getItemTypeIdByNameAndCatName($typeName, $catName);

		if($res !== false){
			print $res;
		}else{
			echo 'false';
		}
	}


	if($act == 'loadItemsTypes'){


		//echo 'cool';

		$typeId = $_REQUEST['catId'];

		$res = $db->loadItemsTypes($typeId);

		//echo 'CATS ARRAY : ' . print_r($res);

		if($res){
			echo json_encode($res);
			//echo 'da res'
		}else{
			echo 'false';
		}

	}

	/*if($act == 'updItem'){

		
		//$itemUniqueName = rand(1000000000, 2000000000) . '_' . $date;  
		//$un = $itemUniqueName;
		
		$itemName = $_REQUEST['itemName'];
		$itemPrice =  $_REQUEST['itemPrice'];
		$itemCur = $_REQUEST['itemCurrency'];
		$itemPriceToDiscuss = $_REQUEST['pdPriceToDiscuss'];
		$itemDesc = $_REQUEST['itemDescription'];
		$itemCat = $_REQUEST['itemCategory'];
		$itemType = $_REQUEST['pdType'];
		$ownerId = $_REQUEST['pdOwnerId'];
		$mainPic = '';//$_REQUEST['mainPic'];
		$pic1 = '';//$_REQUEST['pic1'];
		$pic2 = '';//$_REQUEST['pic2'];
		$pic3 = '';//$_REQUEST['pic3'];
		$itemQual = $_REQUEST['itemQuality'];
		$itemId = $_REQUEST['itemId'];
		$un = $_REQUEST['un'];
		
		/////

		//$pic3 = $pic2 = $pic1 = $mainPic = 'no_pic';
		////
		
		//enl('OWNER ID : ' + $ownerId);
		

		$res = $db->updItem($itemId, $un, $itemName, $itemPrice, $itemCur, $itemPriceToDiscuss,
			$itemDesc, $itemCat, $itemType, $ownerId, 
			$mainPic, $pic1, $pic2, $pic3, $itemQual);

		
		
		if($res === true){
			
			echo '{result:\'true\',pdUniqueName:\'' . $un . '\'}';
			
		}else{
			
			echo '{result:\'false\', messgae:\'' . $res . '\'}';
		}
		
		
	}*/
	
	if($act == 'loadAllFeaturedProducts'){


		//print_r($_REQUEST);
        $uid = $_REQUEST['uid'];
		$res = $db->loadAllFeaturedProducts($uid);
		
		if($res !== false){
			
			echo json_encode($res);
		}else{
			echo 'false';
		}


	}
	
	if($act == 'productImageFileExists'){

		$fn = $_REQUEST["fn"];
		$fpath = IMG_FOLDER_ROOT . '/' . IMG_FOLDER_PRODUCTS . '/' . $fn;

		if(file_exists($fpath)){
			echo 'true';// : ' . $fpath;
		}else{
			echo 'false';// : ' . $fpath;
		}

	}

	//shopm
	if($act == 'loadAllItems'){
		
		//$uid = $_REQUEST['uid'];
		$res = $db->loadAllItems();
		
		echo json_encode($res);
		
	}


	if($act == 'adminLoadAllProducts'){

		$res = $db->adminLoadAllProducts();
		
		if($res){
			//$res['result'] = 'true';
			echo json_encode($res);
		}else{
			echo 'false';
		}


	}

	
	if($act == 'adminLoadAllLookingFor'){

		$res = $db->adminLoadAllLookingFor();
		
		if($res){
			//$res['result'] = 'true';
			echo json_encode($res);
		}else{
			echo 'false';
		}


	}

	
	if($act == 'adminLoadAllUsers'){

		$res = $db->adminLoadAllUsers();
		
		if($res){
			//$res['result'] = 'true';
			echo json_encode($res);
		}else{
			echo 'false';
		}


	}


	if($act == 'setProdStat'){

		$itemId = $_REQUEST['item_id'];
		$newStat = $_REQUEST['newStat'];

		$res = $db->setProdStat($itemId, $newStat);

		if($res === true){
			echo 'true';
		}else{
			echo 'false';
		}

	}

	


	if($act == 'loadAllCatsAndTypes'){

		$textOnly = false;

		if(isset($_REQUEST['textOnly'])){
			$textOnly = true;

		}

		$res = $db->loadAllCatsAndTypes($textOnly);
		$data = array();

		if($res){
			//echo json_encode($res);

			
			
			for($i = 0; $i < count($res); $i++){
				$cat = $res[$i];
	
	$cat['cat_types'] = $db->loadItemsTypes($cat['it_cat_id'], $textOnly);

				$data[] = $cat;
			}

			echo json_encode($data);

		}else{
			echo 'false';
		}
	}
	
	if($act == 'loadAllMyProducts'){
		
		$uid = $_REQUEST['uid'];
		
		$res = $db->loadAllMyProducts($uid);
		
		if($res == false){
			echo 'false';
		}else{
			echo json_encode($res);
		}
		
	}
	
	if($act == 'updSet'){//&setName=user_show_address&newVal=true)
	
	
		$setName = $_REQUEST['setName'];
		$newVal = $_REQUEST['newVal'];
		$uid = $_REQUEST['user_id'];
	
		//print_r($_REQUEST);
	
		if($db->updSet( $setName, $newVal,$uid)){
			echo 'true';	
		}else{
			echo 'false';	
		}
	
	
	}



	if($act == 'loadFeatItems'){
		
		$pds[] = ['pdImg' => 'xbox_one.jpg', 'pdName' => 'Xbox One', 'pdPrice' => '500', 'pdCur' => 'USD', 'pdCat' => 'consoles', 'pdDesc' => 'Desc', 'pdQual' => 'Brand New'];
		$pds[] = ['pdImg' => 'chevy.jpg', 'pdName' => 'Chevy Colorado', 'pdPrice' => '1500', 'pdCur' => 'USD', 'pdCat' => 'cars', 'pdDesc' => 'Desc', 'pdQual' => 'Brand New'];
		$pds[] = ['pdImg' => 'iphone.jpg', 'pdName' => 'iPhone 6s', 'pdPrice' => '1000', 'pdCur' => 'USD', 'pdCat' => 'consoles', 'pdDesc' => 'Desc', 'pdQual' => 'Brand New'];
		$pds[] = ['pdImg' => 'phantom_4.jpg', 'pdName' => 'DJI Phantom 4', 'pdPrice' => '800', 'pdCur' => 'USD', 'pdCat' => 'consoles', 'pdDesc' => 'Desc', 'pdQual' => 'Brand New'];
		$pds[] = ['pdImg' => 'ps4.jpg', 'pdName' => 'Sony Playstation 4', 'pdPrice' => '500', 'pdCur' => 'USD', 'pdCat' => 'consoles', 'pdDesc' => 'Desc', 'pdQual' => 'Brand New'];
		
		shuffle($pds);
		echo json_encode($pds);
		
		
	}
	
	if($act == 'delAcc' && isset($_REQUEST['user_mobile'])){
		
		
		
		$user_mobile = $_REQUEST['user_mobile'];
		if( $db->delAcc($user_mobile)){
			echo 'true';	
		}else{
			echo 'false';	
		}
			
	}

	if($act == 'loadSearchRes'){

		//sleep(1.5);

		$ids = $_REQUEST['ids'];

		$idsClean = str_replace('_', ',', $ids);

		$res = $db->loadSearchRes($idsClean);

		if($res){
			echo json_encode($res);
		}else{
			echo 'false';
		}

	}

	if($act == 'search'){

		$q = $_REQUEST['q'];
		$uid = $_REQUEST['uid'];

		$res = $db->search($uid, $q);

		if(is_array($res)){

			//echo json_encode($res);

			$numRes = count($res);

			if($numRes > 0){

				$ids = '';

				for($i = 0; $i < $numRes; $i++){

					$val = $res[$i][0];
					if($i < $numRes - 1){
						$val .= ',';
					}
					$ids .= $val;
				}

				//echo json_encode($ids);
				echo $ids;

			}else{
				echo 'false';
			}

		}else{
			echo 'false';
		}

	}


	if($act == 'publishItem'){


		$itid = $_REQUEST['itid'];
		$data = $db->publishItem($itid);

		echo json_encode($data);
	}

	
	if($act == 'clearWishlist'){

		$uid = $_REQUEST['uid'];

		if($db->clearWishlist($uid)){
			echo 'true';
		}else{
			echo 'false';
		}
	}
	
	if($act == 'logout'){
		

		$uid = $_REQUEST['uid'];

		//echo 'logout uid -> ' . $uid;
		if($db->logout($uid)){

			echo 'true';
		}else{
			echo 'false';
		}
		
	}
	
	if($act == 'signup'){
		
		
		$fullName = $_REQUEST['fullName'];
		$displayName = $_REQUEST['displayName'];
		$email = $_REQUEST['email'];
		$password = $_REQUEST['password'];
		$mobile = $_REQUEST['mobile'];
		$location = $_REQUEST['location'];
		
		$signupRes = $db->signup($fullName, $displayName, $email, $password, $mobile, $location);
		
		
		echo json_encode($signupRes);
		
			
	}
	
	
	
	if($act == 'loadChatContacts'){
		
		/*
		private int contactID;
    private String contactName;
    private String lastMsgDate;
    private String lastMsg;
		*/
		
		/*
		
		$contacts[] = ['contactID' => '1', 'contactName' => 'Vendor', 'lastMsgDate' => '2017-11-08', 'lastMsg' => 'Hey i have your item.'];
		$contacts[] = ['contactID' => '1', 'contactName' => 'Vendor', 'lastMsgDate' => '2017-11-08', 'lastMsg' => 'Hey i have your item.'];
		$contacts[] = ['contactID' => '1', 'contactName' => 'Vendor', 'lastMsgDate' => '2017-11-08', 'lastMsg' => 'Hey i have your item.'];
		$contacts[] = ['contactID' => '1', 'contactName' => 'Vendor', 'lastMsgDate' => '2017-11-08', 'lastMsg' => 'Hey i have your item.'];
		$contacts[] = ['contactID' => '1', 'contactName' => 'Vendor', 'lastMsgDate' => '2017-11-08', 'lastMsg' => 'Hey i have your item.'];
		$contacts[] = ['contactID' => '1', 'contactName' => 'Vendor', 'lastMsgDate' => '2017-11-08', 'lastMsg' => 'Hey i have your item.'];
		$contacts[] = ['contactID' => '1', 'contactName' => 'Vendor', 'lastMsgDate' => '2017-11-08', 'lastMsg' => 'Hey i have your item.'];
		
		
		
		echo json_encode($contacts);	*/
		
	}
	
	
	
	

	
	if($act == 'loadAccData' && isset($_REQUEST['uid'])){
		
		$uid = $_REQUEST['uid'];
		$data = array();
		
		$data[] = [
		'displayName' => 'Franvale Mutunda', 
		'accType' => 'Buyer', 
		'city' => 'Likasi', 
		'picName' => 'franvale.jpg',
		'fullName' => 'MUTUNDA KOJI FRANVALE',
		'email' => 'fmutundak@gmail.com',
		'mobile' => '0997475663',
		'company' => 'KOZI Engineering',
		'location' => 'Lubumbashi',
		'password' => 'mypass',
		'showMyEmail' => 'true',
		'showMyMobile' => 'true',
		'showMyAdd' => 'false'
		 ];
		
		echo json_encode($data);
		return json_encode($data); 	
		
	}

	if($act == 'loadWishList'){

		$res = $db->loadWishList($_REQUEST['uid']);

		if($res !== false){
			echo json_encode($res);
		}else{
			echo 'false';
		}

	}
	
	/*if($act == 'loadProds' && isset($_REQUEST['cat'])){
	
		$cat = $_REQUEST['cat'];
		
		$pds = array();
		
		if($cat == 'phones'){
			
		$pds[] = ['pdImg' => 'xbox_one.jpg', 'pdName' => 'Xbox One', 'pdPrice' => '500', 'pdCur' => 'USD', 'pdCat' => 'consoles', 'pdDesc' => 'Desc', 'pdQual' => 'Brand New'];
		$pds[] = ['pdImg' => 'chevy.jpg', 'pdName' => 'Chevy Colorado', 'pdPrice' => '25 000', 'pdCur' => 'USD', 'pdCat' => 'cars', 'pdDesc' => 'Desc', 'pdQual' => 'Brand New'];
		$pds[] = ['pdImg' => 'iphone.jpg', 'pdName' => 'iPhone 6s', 'pdPrice' => '1000', 'pdCur' => 'USD', 'pdCat' => 'consoles', 'pdDesc' => 'Desc', 'pdQual' => 'Brand New'];
		$pds[] = ['pdImg' => 'phantom_4.jpg', 'pdName' => 'DJI Phantom 4', 'pdPrice' => '800', 'pdCur' => 'USD', 'pdCat' => 'consoles', 'pdDesc' => 'Desc', 'pdQual' => 'Brand New'];
		$pds[] = ['pdImg' => 'ps4.jpg', 'pdName' => 'Sony Playstation 4', 'pdPrice' => '500', 'pdCur' => 'USD', 'pdCat' => 'consoles', 'pdDesc' => 'Desc', 'pdQual' => 'Brand New'];
		

		
			

		//echo json_encode($pds);
	
	}*/

	if($act == 'addItemToWishlist'){

		$uid = $_REQUEST['uid'];
		$wid = $_REQUEST['wid'];

		$res = $db->addItemToWishlist($uid, $wid);

		if($res === false){
			echo 'false';
		}else{
			echo 'true';
		}

	}

	//shopm
	if($act == 'delItem'){
		$item_id = $_REQUEST['item_id'];

		$res = $db->delItem($item_id);

		if($res === false){
			echo 'false';
		}else{
			echo 'true';
		}
	}

	//shopm
	if($act == 'updItem'){

		$item_id = $_REQUEST['item_id'];
		$item_name = $_REQUEST['item_name'];
		$item_price = $_REQUEST['item_price'];
		$item_stock_count = $_REQUEST['item_stock_count']; 
		$item_desc = $_REQUEST['item_desc'];

		echo json_encode($db->updItem($item_id, $item_name,$item_price,$item_stock_count, $item_desc));
	}


	if($act == 'loadItemsInType'){

		$typeId = $_REQUEST['typeId'];

		$res = $db->loadItemsInType($typeId);

		if($res !== false){
			echo json_encode($res);
		}else{
			echo 'false';
		}

	}
	
	
	if($act == 'loadMsgsInbox'){
		
		/*
		public static final String KEY_MESSAGE_TYPE = "msgType";
    public static final String KEY_MESSAGE_DATE = "msgDate";
    public static final String KEY_MESSAGE_FROM_ID = "msgFromID";
    public static final String KEY_MESSAGE_TO_ID = "msgToID";
    public static final String KEY_MESSAGE_CONTENT = "msgCont";
    public static final String KEY_MESSAGE_TITLE = "msgTitle";
	*/
		
		require_once('tmp/sample_msgs.php');
		
		
	}
	
}else{
	
	$msg = array();
	$msg['status'] = 'error';
	$msg['msg'] = 'Error connecting to API. Action not defined in request!';
	
	echo json_encode($msg);
}


?>