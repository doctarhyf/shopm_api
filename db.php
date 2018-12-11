<?php
@session_start();

//echo date('F d Y H:i:s.');

$home = 'http://localhost/shopm/';


require_once('helper_funcs.php');

try {



class DB{
	

	//new file
	//network creds
        
        /*
	 private $dbuser = "id3203451_doctarhyf";
	 private $dbpass = "Aliceliwena1966";
	 private $dbname = "id3203451_sosachat";
        
	 	*/
	
	
	//local creds
	
	private $dbuser = "root";
	private $dbpass = "";
	private $dbname = "shopm";
	
	
	private $pdo;
	private $dbhost = DB_HOST;
	
	
	
	
	
	
	function DB(){
		
		
		
		$dsn = "mysql:host=" . $this->dbhost . ";dbname=" . $this->dbname;
		$this->pdo = new PDO($dsn,$this->dbuser,$this->dbpass);
		 

	}
	
	
	function getAllCategories(){
		
		
		
		return $this->getAllDataFromTable(TABLE_NAME_ITEMS_CATEGORY, ' ORDER BY `it_cat_priority` ASC');
		
	}
	
	function getAllItemTypes(){
		return $this->getAllDataFromTable(TABLE_NAME_ITEMS_TYPES, ' ORDER BY `items_types_priority` ASC');
	} 
	
	function getAllDataFromTable($tableName, $order = '', $fetchMethod = PDO::FETCH_ASSOC){
		
		$sql = 'SELECT * FROM ' . $tableName . $order;
		$pdo = $this->pdo;
		$pds = $pdo->prepare($sql);
		$cats = $pds->execute(array());
		
		//echonl($sql);
		
		return $pds->fetchAll($fetchMethod);
		
	}

	function updViewsCount($itemid){

		$pdo = $this->pdo;
		$sql = 'UPDATE items set pdViews = pdViews + 1 where item_id = ' . $itemid;
		$rows = $pdo->exec($sql);

		

		if($rows == 1){

			$sql = 'SELECT pdViews FROM items WHERE item_id = ' . $itemid;
			$pds = $pdo->prepare($sql);
			$pds->execute(array());
			$res = $pds->fetchAll();

			return $res[0][0];

			
		}else{
			return false;
		}

		

		

	}

	function clearWishlist($uid){

		$pdo = $this->pdo;
		$sql = 'DELETE FROM wishlist WHERE wl_uid = ' . $uid;
		//echo $sql;
		$pds = $pdo->prepare($sql);

		return $pds->execute(array());

	}
	
	function logout($uid){
		
		
		
		$pdo = $this->pdo;
		$sql = 'UPDATE users SET  
		user_date_last_logout=\''. $this->GCD() .'\' ';
		$sql .= ' , user_logged_in = 0 ';
		$sql .= ' WHERE user_id = ' . $uid ;

		$pds = $pdo->prepare($sql);
		

		//echo $sql;
		//$_SESSION = array();
		session_unset();
		session_destroy();

		return $pds->execute(array());
		
		
	}

	function search($uid, $q){

		$pdo = $this->pdo;
		$data = array();
		/*$sql = 'SELECT
p.pdUniqueName,
p.pdUniqueName as pdImg,
p.pdDateAdded,
p.pdCat,
p.pdViews,
p.pdDesc,
p.item_id,
p.pdOwnerId,
p.pdName,
p.pdPrice,
p.pdCur,
p.pdQual,
u.user_display_name,
u.user_id,
u.user_mobile,
u.user_email from
(items p join users u on
p.pdOwnerId = u.user_id  ) where p.pdOwnerId != ' . $uid . ' and p.pdName LIKE \'%' . $q . '%\' order by p.pdDateAdded desc';*/

		$sql = 'SELECT item_id FROM items WHERE pdOwnerId != ' . $uid . ' and pdName LIKE \'%' . $q . '%\' order by pdDateAdded desc';

//echo $sql;
$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		//echo $sql;

		return $pds->fetchAll();	
		

	}

	//shopm
	/*function addItemToStock($item_name,$item_price,$item_stock_count,$item_desc){

		$pdo = $this->pdo;
		$data = array();//$item_id, $item_qty, $exch_rate);

		//$sell_invoice_uid = time();

		$sql = "INSERT INTO 
		`sells` (`sell_id`, `sell_invoice_uid`, `sell_item_id`, `sell_qty`, `sell_exch_rate`,`sell_remaining_stock`, `sell_date`) 
		VALUES (NULL, '$sell_invoice_uid', '$item_id', '$item_qty', '$exch_rate', '$rem_stock', CURRENT_TIMESTAMP) ";

		//echo $sql;

		$pds = $pdo->prepare($sql);
		if( $pds->execute($data)){


			$lid = $pdo->lastInsertId();

			$sql = "UPDATE `items` SET `item_stock_count` = '$rem_stock' WHERE `items`.`item_id` = " . $item_id;
			$pds = $pdo->prepare($sql);
			$pds->execute($data);



			return $this->loadItemByProp("item_id", $item_id);//$lid;
		}else{
			return false;
		}



	}*/

	//shopm
	function sellItem($item_id, $item_qty, $exch_rate, $rem_stock){

		$pdo = $this->pdo;
		$data = array();//$item_id, $item_qty, $exch_rate);

		$sell_invoice_uid = time();

		$sql = "INSERT INTO 
		`sells` (`sell_id`, `sell_invoice_uid`, `sell_item_id`, `sell_qty`, `sell_exch_rate`,`sell_remaining_stock`, `sell_date`) 
		VALUES (NULL, '$sell_invoice_uid', '$item_id', '$item_qty', '$exch_rate', '$rem_stock', CURRENT_TIMESTAMP) ";

		//echo $sql;

		$pds = $pdo->prepare($sql);
		if( $pds->execute($data)){


			$lid = $pdo->lastInsertId();

			$sql = "UPDATE `items` SET `item_stock_count` = '$rem_stock' WHERE `items`.`item_id` = " . $item_id;
			$pds = $pdo->prepare($sql);
			$pds->execute($data);



			return $this->loadItemByProp("item_id", $item_id);//$lid;
		}else{
			return false;
		}



	}

	//shopm
	function loadItemByProp($kPropName, $propVal){

		$pdo = $this->pdo;
		$data = array();
		$sql = "SELECT * FROM items WHERE $kPropName='$propVal' order by item_added_date desc ";
 
		//echo $sql;

		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);	

		

		return $res[0];

	}

	//shopm
	function loadItem($item_unique_name){

		$pdo = $this->pdo;
		$data = array();
		$sql = "SELECT * FROM items WHERE item_unique_name='$item_unique_name' order by item_added_date desc";
 
		//echo $sql;

		$pds = $pdo->prepare($sql);
				$pds->execute($data);
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);	

		

		return $res;


	}

	//shopm
	function getRandomHash(){
		$key = SECRET_KEY;
		$time = time();
		$hash = hash_hmac('sha256', $time, $key);

		return $hash;
	}

	//shopm other
	function addSell($sell_item_id){

		$pdo = $this->pdo;
		$sell_invoid_uid = time();

		$sql = "INSERT INTO 
		`sells` (`sell_id`, `sell_invoid_uid`, `sell_item_id`, `sell_date`)
		 VALUES ('NULL', '$sell_invoid_uid', ?, CURRENT_TIMESTAMP) ";

		 $data = array( $sell_item_id);


		 $pds = $pdo->prepare($sql);
if( $pds->execute($data)){
	return $pdo->lastInsertId();
}else{
	return false;
}



	}

	//shopm
	function addItemToStock($item_name, $item_desc, $item_price, $item_unique_name, $item_stock_count){
		$pdo = $this->pdo;
		$data = array($item_name, $item_desc, $item_price, $item_unique_name, $item_stock_count);

		//$sql = 'SELECT COUNT(*) FROM wishlist WHERE wl_uid = ' . $uid . ' AND wl_item_id = ' . $wid  ;

		//echo $sql;

		//$pds = $pdo->prepare($sql);
		//$pds->execute($data);
		//$res = $pds->fetchAll();

		//$cnt = $res[0][0];


		$sql = "INSERT INTO `items` (`item_id`, `item_name`, `item_desc`, `item_price`, `item_unique_name`, `item_stock_count`, `item_last_stock_upd`, `item_added_date`) 
		VALUES 
		('NULL', ?,?,?,?,?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP) ";

		/*
		('NULL', '$item_name', '$item_desc', 
		'$item_price', 
		'$item_unique_name', 
		'$item_stock_count', 
		'CURRENT_TIMESTAMP', 
		'CURRENT_TIMESTAMP') ";*/


		//echo $sql;

$pds = $pdo->prepare($sql);
if( $pds->execute($data)){
	return $pdo->lastInsertId();
}else{
	return false;
}

		/*
		if($cnt == 0){

			$sql = 'INSERT INTO `wishlist` (`wl_id`, `wl_uid`, `wl_item_id`) VALUES (NULL, '. $uid .', '. $wid .');';



			$pds = $pdo->prepare($sql);
			if( $pds->execute($data)){
				return true;
			}else{
				return false;
			}

		}else{
			return true;
		}*/
		
	}

	function removeItemFromWishlist($uid, $wid){
		$pdo = $this->pdo;
		$data = array();

		$sql = 'DELETE FROM `wishlist` WHERE `wishlist`.`wl_uid` = ' . $uid . ' AND wl_item_id = ' . $wid;

		$pds = $pdo->prepare($sql);
		//echo $sql;
		
		$pds->execute($data);

		if($pds->rowCount() === 1){
			return true;
		
		}else{
		return false;
		}
		
	}



	function loadWishList($uid){

		/*
SELECT p.pdName, u.user_display_name
from items p, wishlist b, users u
WHERE p.item_id = b.wl_item_id and u.user_id = b.wl_uid

		*/

		$pdo = $this->pdo;
		$data = array();
		$sql = 'SELECT 
p.pdUniqueName,
p.pdUniqueName as pdImg,
p.pdDateAdded,
p.pdDateSold,
p.*,
u.*,
p.pdCat,
p.pdViews,
p.pdDesc,
p.pdType,
p.item_id,
p.pdOwnerId,
p.pdName,
p.pdPrice,
p.pdCur,
p.pdQual,
u.user_display_name,
u.user_id,
u.user_mobile,
u.user_email 
from items p, wishlist b, users u  
where p.item_id = b.wl_item_id 
and p.pdStat = \'' . PROD_STAT_PUBLISHED . '\' 
and u.user_id = b.wl_uid 
and u.user_id = ' . $uid . '
order by p.pdDateAdded desc';

		

//echo $sql;


		/*$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);

		

		if(is_array($res) && (count($res) > 0)){
				foreach ($res as &$item) {
					# code...
					$typeID = $item['pdType'];
					$catID = $item['pdCat'];
					$item['pdType'] = $this->getItemTypeByID($typeID);
					$item['pdCat'] = $this->getItemCatByID($catID);
				}
			

			return $res;
		}else{
			return false;
		}*/

		//echo $sql;

		$pds = $pdo->prepare($sql);
				$pds->execute($data);
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);	

		$items = $this->GITCP($res);

		return $items;

	}

	function publishItem($itid){

		$pdo = $this->pdo;
		$data = array();
		$sql = 'UPDATE `items` SET `pdStat` = ' . PROD_STAT_WAITING . ' WHERE `items`.`item_id` =  ' . $itid;

		//echo $sql;

		$pds = $pdo->prepare($sql);
		
		if($pds->execute($data)){
			$res['code'] = RESULT_CODE_SUCCESS;
			$res['data'] = $pdo->lastInsertId();
		}else{
			$res['code'] = RESULT_CODE_FAILURE;
			$res['data']  = '
			error! q : ' . mysql_real_escape_string($sql);
		}

		return $res;


	}

	function loadSearchRes($ids){

		$pdo = $this->pdo;
		$data = array();
		$sql = 'SELECT
p.pdUniqueName,
p.pdUniqueName as pdImg,
p.pdDateAdded,
p.pdDateSold,
p.pdCat,
p.pdViews,
p.pdDesc,
p.item_id,
p.pdOwnerId,
p.pdName,
p.pdPrice,
p.pdCur,
p.pdQual,
u.user_display_name,
u.user_id,
u.user_mobile,
u.user_email from
(items p join users u on
p.pdOwnerId = u.user_id  ) where p.item_id IN(' . $ids . ') order by p.pdDateAdded desc';

		

//echo $sql;
$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		//echo $sql;

		return $pds->fetchAll();	

	}
	
	function rmProd($myid, $unq){
		
		$pdo = $this->pdo;
		$data = array($myid, $unq);
		
		
		/*
		$sql = 'SELECT COUNT(*) FROM items WHERE pdOwnerId = ? AND `items`.`pdUniqueName` = ? ';
			$pds = $pdo->prepare($sql);
			$pds->execute($data);
			$res = $pds->fetchAll();
			$cnt = $res[0][0];
			
			if($cnt == 0){
				return false;	
			}*/
		
		
		
		$sql ='DELETE FROM items WHERE pdOwnerId = ? AND `items`.`pdUniqueName` = ?  ' ;
		$pds = $pdo->prepare($sql);
		
		
		//printq($sql, $data);
		
		//if(
		$pds->execute($data);//){
			
			/*$sql = 'SELECT COUNT(*) FROM items WHERE pdOwnerId = ? AND `items`.`pdUniqueName` = ? ';
			$pds = $pdo->prepare($sql);
			$pds->execute($data);
			$res = $pds->fetchAll();
			$cnt = $res[0][0];
			
			if($cnt == 0){*/
			
			
			
			
			@unlink(DIR_PRODUCTS_PIX . $unq . '_main.jpg');
			@unlink(DIR_PRODUCTS_PIX . $unq . '_pic1.jpg');
			@unlink(DIR_PRODUCTS_PIX . $unq . '_pic2.jpg');
			@unlink(DIR_PRODUCTS_PIX . $unq . '_pic3.jpg');
			
			//echo 'path -> ' . DIR_PRODUCTS_PIX . $unq . '_pic3.jpg';
			
			/*return true;
			
			}else{
				return false;	
			}
		}else{
			return false;	
		}*/
		
		return true;
		
	}
	
	function postLooking4($myid, $title, $desc, $rating, $updid){
		
		
		$pdo = $this->pdo;
		//$title = $pdo->quote($title);
		//$desc = $pdo->quote($desc);
		$date = date('Y-m-d H:i:s');
		$data = array($myid, $title, $desc, $date, $rating);
		$sql = "INSERT INTO `items_inquiry` (`inq_id`, user_id, `inq_title`, `inq_desc`, `inq_date`, `inq_rating`) VALUES (NULL, ?, ?, ?, ?,?) ; " ;
		

		//$res = false;
		
		if($updid != -1){

			$data = array($title, $desc, $rating, $date);
			$sql = "UPDATE `items_inquiry` SET `inq_title` = ?, ";
			$sql .= "inq_desc =  ?, ";
			$sql .= "inq_rating = ?, " ;
			$sql .= "inq_date = ? ";
			$sql .= " WHERE `items_inquiry`.`inq_id` = $updid ";

		}
		
		//echo $sql;
		$pds = $pdo->prepare($sql);
		if($pds->execute($data)){
			return true;
		}else{
			return false;
		}
		
	}

	function dell4($iid,$uid){

		$pdo = $this->pdo;
		
		
		$data = array($iid, $uid);
		$sql ='DELETE FROM items_inquiry WHERE inq_id = ? ';
		$sql .= ' AND user_id = ? ' ;
		$pds = $pdo->prepare($sql);
		return $pds->execute($data);
	}


	
	function CTBL($tableName, $arr_kv, $arr_conds = NULL){
		return $this->countTableEntriesWithKeyVals($tableName, $arr_kv, $arr_conds);
	}

	function countTableEntriesWithKeyVals($tableName, $arr_kv, $arr_conds = NULL){



		$pdo = $this->pdo;
		$sql = 'SELECT COUNT(*) FROM ';
		$sql .= $tableName;
		$sql .= ' WHERE ';

		$i = 0;
		$c = count($arr_kv);

		if($arr_conds == NULL){
			for($i = 0; $i < $c; $i++){
				$arr_conds[$i] = '=';
			}
		}


		foreach ($arr_kv as $k => $v) {
			
			$sql .= $k . ' ' . $arr_conds[$i] . ' ' . $v;

			if($i < $c -1 ){
				$sql .= ' AND ';
			}

			$i ++;

		}

		//echo $sql;

		$pds = $pdo->prepare($sql);

		$pds->execute(array());
		$res = $pds->fetchAll(PDO::FETCH_BOTH);

		return $res[0][0];

		
	}

	function checkAllInquiries($uid, $limit, $mine = false ){



		

		

		
		
		$data['inq_active'] = 1;

		$pdo = $this->pdo;
		$sql = '
		SELECT
		i.*,
		u.*
		FROM
		(items_inquiry i JOIN users u ON 
		i.user_id = u.user_id) ';

		if($mine){
			$sql .= ' WHERE u.user_id = ' . $uid;
			
			//$data['user_id'] = $uid;

		
		}else{
			$sql .= ' WHERE u.user_id != ' . $uid;

			//$data['user_id'] = $uid;
		}

		$sql .= ' ORDER BY i.inq_date DESC ';
		
		if($limit != -1){
			
			$sql .= ' LIMIT ' . $limit;
		}

		//$cnt = $this->CTBL('items_inquiry', $data);

		//echo $sql;

		/* 'SELECT 
		i.*,
		u.* 
		FROM
		(items_inquiry i join users u on
		i.user_id = u.user_id  )

		WHERE i.inq_active = 1 ';
		if($mine === true){
			$sql .= ' AND i.user_id = ' . $uid;
		}else{
			$sql .= ' AND i.user_id != ' . $uid;
		}
		
		$sql .= ' ORDER BY i.inq_date DESC ';
		if($limit != -1){
			$sql .= ' LIMIT ' . $limit;
		} */
		//echo $sql;
		
		$pds = $pdo->prepare($sql);

		$pds->execute(array());
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);



		$cnt = count($res);
		//print_r($res);
		
		if($cnt > 0){
			$ret = array(false, json_encode($res));
			$l4s = array();

			


				foreach ($res as $k => $l4) {
					# code...
					$root = $_SERVER['SERVER_NAME'] . '/' . ROOT_FOLDER_SOSACHAT . '/';
					
					$local_path_pp = ROOT_FOLDER_IMAGES . '/' . FOLDER_NAME_PROFILE_PICTURES . '/' . $l4['user_mobile_hash'] . '.jpg';

					$path_pp = $root . $local_path_pp;

					$l4[KEY_RES_PATH_PROFILE_PICTURE] = $path_pp ;
					$l4[KEY_MTIME_PP] = $this->getFilemtime($local_path_pp);

					$l4s[] = $l4;
				}


				$js = json_encode($l4s);

				//echo 'js : ' . $js;

				return $js;

			


			
		}else {
			return ERR_EMPTY;
		}
	}
	
	function loadCat($cat){
		
		
		$pdo = $this->pdo;
		
		$data = array($cat);
		$sql ='SELECT * FROM items_cats WHERE items_cat_name = ? ' ;
		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		//printq($sql, array('y'));
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);
		
		//print_r( $res);
		
		return json_encode($res);	
		
	}
	
	function getIDFromTableByName($tableName, $val, $rowId, $rowName){

		$pdo = $this->pdo;
		$data = array();
		
		$sql = 'SELECT ' . $rowId . ' FROM ' . $tableName . '  where '. $rowName .' = \'' . $val . '\'';



		//echo $sql;

		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		$res = $pds->fetchAll();

		
		

		if(is_array($res)){
			return $res[0][0];
		}else{
			return 0;
		}

		//return -1;
	}



		function loadItemsTypes($typeId, $textOnly = false){

			

			$pdo = $this->pdo;
		$data = array();
		
		$sql ='SELECT * FROM `items_types` WHERE items_types_cat = ' . $typeId;
		if($textOnly == true){

			$sql = 'SELECT items_types_name, items_types_priority, items_types_id FROM items_types WHERE items_types_cat = ' . $typeId;

			//echo $sql . '<br/>\n';
		}

		$sql .= ' ORDER BY items_types_name ASC'; 


		//echo $sql;

		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);

		
		

		if(is_array($res)){

			//if()
			return $res;
		}else{
			return false;
		}


		}

	function updItem($itemId, $un, $itemName, $itemPrice, $itemCur, $itemPriceToDiscuss,
			$itemDesc, $itemCat, $itemType, $ownerId, 
			$mainPic, $pic1, $pic2, $pic3, $itemQual){

		$pdo = $this->pdo;


		if($itemPriceToDiscuss == '1'){
			$itemPrice = '-1';
		}
		
		$itemType = $this->getItemTypeIdByNameAndCatName($itemType, $itemCat);


		$itemCat = $this->getIDFromTableByName('it_cats', $itemCat, 'it_cat_id', 'it_cat_name');
		$itemQual = $this->getIDFromTableByName('it_quals', $itemQual, 'it_qual_prior', 'it_qual_name');


		$data = array($itemName,$itemPrice, $itemCur, $itemPriceToDiscuss,
			$itemDesc, $itemCat, $itemType, $itemQual, $ownerId, $itemId);

		$sql = " UPDATE items SET pdName=?, pdPrice=?, pdCur=?, pdPriceToDiscuss=?, pdDesc=?, pdCat=?, pdType=?, pdQual=? where (pdOwnerId=? AND item_id=?) ";


		//printq($sql,$data);

		$pds = $pdo->prepare($sql);


		return $pds->execute($data);
		/*if($pds->execute($data)){

			$fh = fopen('img/' . IMG_FOLDER_PRODUCTS . '/' . $un . '_main.jpg' , 'w');
		fwrite($fh, base64_decode($mainPic));
		fclose($fh);
		
		if($pic1 != KEY_ITEM_NO_PIC){
			$fh = fopen('img/' . IMG_FOLDER_PRODUCTS . '/' . $un . '_pic1.jpg' , 'w');
			fwrite($fh, base64_decode($pic1));
			fclose($fh);
		}
		
		if($pic2 != KEY_ITEM_NO_PIC){
			$fh = fopen('img/' . IMG_FOLDER_PRODUCTS . '/' . $un . '_pic2.jpg' , 'w');
			fwrite($fh, base64_decode($pic2));
			fclose($fh);
		}
		
		if($pic3 != KEY_ITEM_NO_PIC){
			$fh = fopen('img/' . IMG_FOLDER_PRODUCTS . '/' . $un . '_pic3.jpg' , 'w');
			fwrite($fh, base64_decode($pic3));
			fclose($fh);
		}

			return true;
		}else{
			return false;
		}*/

	}

	function loadItemsInType($typeId){

		$pdo = $this->pdo;

		

/*
		$sql = 'SELECT *, pdUniqueName as pdImg FROM items WHERE pdType = ' . $typeId;*/

		$sql = 'SELECT
p.pdUniqueName,
p.pdUniqueName as pdImg,
p.pdDateAdded,
p.pdDateSold,
p.pdCat,
p.pdViews,
p.pdDesc,
p.item_id,
p.pdOwnerId,
p.pdName,
p.pdPrice,
p.pdCur,
p.pdQual,
u.user_display_name,
u.user_id,
u.user_mobile,
u.user_email from
(items p join users u on
p.pdOwnerId = u.user_id  ) WHERE pdType = ' . $typeId . ' order by p.pdDateAdded desc';

		//echo $sql;

		$data = array();

		$pds = $pdo->prepare($sql);
		$pds->execute($data);

		//echo $sql;

		$res = $pds->fetchAll(PDO::FETCH_ASSOC);
		if(is_array($res) && count($res) > 0){
			return $res;
		}else{
			return false;
		}

	}
	
	function getItemTypeIdByNameAndCatName($typeName, $catName){

		$pdo = $this->pdo;

		$catId = $this->getIDFromTableByName('it_cats', $catName, 'it_cat_id', 'it_cat_name');

		$sql = 'SELECT items_types_id FROM items_types WHERE 
		items_types_name = \'' . $typeName . '\' AND items_types_cat = ' . $catId;

		//echo $sql;

		$data = array();

		$pds = $pdo->prepare($sql);
		$pds->execute($data);

		//echo $sql;

		$res = $pds->fetchAll();
		if(is_array($res) && count($res) > 0){
			return $res[0][0];
		}else{
			return false;
		}
		

	}

	function exposeItem($itemUniqueName,$itemName, $itemPrice, $itemCur, $priceToDiscuss, $itemDesc,
	 $itemCat, $itemType, $userId, $picMain, $pic1, $pic2, $pic3, $itemQual ){
		
		
		$un = $itemUniqueName;
		$pdo = $this->pdo;
		
		$dateAdded = date('Y-m-d H:i:s');
		$dateSold = '0';

		if($priceToDiscuss == '1'){
			$itemPrice = '-1';
		}
		

		$itemType = $this->getItemTypeIdByNameAndCatName($itemType, $itemCat);

		$itemCat = $this->getIDFromTableByName('it_cats', $itemCat, 'it_cat_id', 'it_cat_name');
		$itemQual = $this->getIDFromTableByName('it_quals', $itemQual, 'it_qual_prior', 'it_qual_name');



		$data = array($itemUniqueName,$itemName, $itemPrice, $itemCur, $priceToDiscuss, $itemDesc,
	 	$itemCat, $itemType, $userId,  $dateSold, $itemQual);
		
		
		
		$sql = 'INSERT INTO `items` 
		(`item_id`,
		`pdUniqueName`,
		`pdName`, 
		`pdPrice`, 
		`pdCur`, 
		`pdPriceToDiscuss`, 
		`pdDesc`, 
		`pdCat`, 
		`pdType`,
		`pdOwnerId`,
		`pdDateAdded`, 
		`pdDateSold`,
		`pdQual`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL, ?, ?)';
		
		
		
		$pds = $pdo->prepare($sql);
		

		
		//OLD WRITE PIC
		/*
			$fh = fopen('img/' . IMG_FOLDER_PRODUCTS . '/' . $un . '_main.jpg' , 'w');
		$mp = fwrite($fh, base64_decode($picMain));

		fclose($fh);

		$p1 = $p2 = $p3 = false;
		
		if($pic1 != KEY_ITEM_NO_PIC){
			$fh = fopen('img/' . IMG_FOLDER_PRODUCTS . '/' . $un . '_pic1.jpg' , 'w');
			$p1 = fwrite($fh, base64_decode($pic1));
			fclose($fh);
			//$pic1 = true;
		}else{
			$p1 = true;
		}
		
		if($pic2 != KEY_ITEM_NO_PIC){
			$fh = fopen('img/' . IMG_FOLDER_PRODUCTS . '/' . $un . '_pic2.jpg' , 'w');
			$p2 = fwrite($fh, base64_decode($pic2));
			fclose($fh);
			//$pic2 = true;
		}else{
			$p2 = true;
		}
		
		if($pic3 != KEY_ITEM_NO_PIC){
			$fh = fopen('img/' . IMG_FOLDER_PRODUCTS . '/' . $un . '_pic3.jpg' , 'w');
			$p3 = fwrite($fh, base64_decode($pic3));
			fclose($fh);
			//$pic3 = true;
		}else{
			$p3 = true;
		}
			
		if( $mp && $p1 && $p2 && $p3){
			return $pds->execute($data);
		}else{
			return false;
		}

		return $pds->execute($data);*/
		if($pds->execute($data)){
			return true;
		}else{
			return false;
		}
		
	}
	
	
	/*
	function loadAllProducts(){
		
		
		$pdo = $this->pdo;
		$data = array();
		$sql = 'SELECT *, pdUniqueName as pdImg FROM `items` ORDER BY `items`.`pdDateAdded` DESC';
		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		return $pds->fetchAll(PDO::FETCH_ASSOC);
	}*/
	
	//shopm
	function loadAllItems(){
		
		
		$pdo = $this->pdo;
		$data = array();
		/*$sql = 'SELECT 
		p.pdUniqueName,
		p.pdUniqueName as pdImg,
		p.*,
		u.* from
		(items p join users u on
		p.pdOwnerId = u.user_id  ) 
		where p.pdStat = \'' . PROD_STAT_PUBLISHED . '\' 
		order by p.pdDateAdded desc';*/
		//echo $sql;


		$sql = "SELECT * FROM items";

		$pds = $pdo->prepare($sql);
				$pds->execute($data);
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);	

		

		if(count($res) == 0){
			$data[KEY_RESULT_CODE] = RESULT_CODE_EMPTY_LIST;
			$data[KEY_RESULT_DATA] = null;
		}else{

			$data[KEY_RESULT_CODE] = RESULT_CODE_SUCCESS;
			$data[KEY_RESULT_DATA] = $res;
		}

		
		return $data;



		
		
	}

	function GITCP(&$items, $androidBundleFormat = true){
		return $this->genImageTypeCatAndPaths($items);
	}

	/*function genImageTypeCatAndPaths(&$items, $androidBundleFormat = true){

		$new_items = false;

		if(is_array($items) && (count($items) > 0)){

				//$img[] = array();

				
				

				foreach ($items as &$item) {
					# code...
					$typeID = $item['pdType'];
					$catID = $item['pdCat'];
					$item['pdType'] = $this->getItemTypeByID($typeID);
					$item['pdCat'] = $this->getItemCatByID($catID);

					$path_pp = ROOT_FOLDER_IMAGES . '/' . FOLDER_NAME_PROFILE_PICTURES . '/' . $item['user_mobile_hash'] . '.jpg';
					$item['img_mtime_loggedin_user'] = $this->getFilemtime($path_pp, false);

					$postFix = '_main';
					$imgInfo = $this->getImageFileInfo($item, $postFix, '.jpg');

					if(!$androidBundleFormat){
						$item['img'][$postFix] = $imgInfo;
					}else{
						$item['img_path' . $postFix] = $imgInfo['path'];
						$item['img_mtime' . $postFix] = $imgInfo['mtime'];
					}
//////////////////////////////////////////////////////
					$postFix = '_pic1';
					$imgInfo = $this->getImageFileInfo($item, $postFix, '.jpg');

					if(!$androidBundleFormat){
						$item['img'][$postFix] = $imgInfo;
					}else{
						$item['img_path' . $postFix] = $imgInfo['path'];
						$item['img_mtime' . $postFix] = $imgInfo['mtime'];
					}
//////////////////////////////////////////////////////

					$postFix = '_pic2';
					$imgInfo = $this->getImageFileInfo($item, $postFix, '.jpg');
					if(!$androidBundleFormat){
						$item['img'][$postFix] = $imgInfo;
					}else{
						$item['img_path' . $postFix] = $imgInfo['path'];
						$item['img_mtime' . $postFix] = $imgInfo['mtime'];
					}
/////////////////////////////////////////
					$postFix = '_pic3';
					$imgInfo = $this->getImageFileInfo($item, $postFix, '.jpg');
					if(!$androidBundleFormat){
						$item['img'][$postFix] = $imgInfo;
					}else{
						$item['img_path' . $postFix] = $imgInfo['path'];
						$item['img_mtime' . $postFix] = $imgInfo['mtime'];
					}
				}
			

			$new_items = $items;
		}

		return $new_items;

	}*/



	function getImageFileInfo($item, $postFix, $fileExt = '.jpg', $formatDate = false){
		$path_img_main = ROOT_FOLDER_IMAGES . '/' . FOLDER_NAME_PRODUCTS . '/' . $item['pdUniqueName'] . $postFix . $fileExt;
					$img['path'] = $_SERVER['SERVER_NAME'] . '/' . ROOT_FOLDER_SOSACHAT . '/' . $path_img_main;

					if(!file_exists($path_img_main)) $img['path'] = false;


					$img['mtime'] = $this->getFilemtime($path_img_main, $formatDate);

					return $img;
	}

	function getFilemtime($path, $formatDate = false){
		$ret = false;
		if(file_exists($path)){
		$ret = filemtime($path) ;
	}

		if($formatDate && file_exists($path)){
			$ret = date('m-d-Y H:i:s', $ret);
		}

		return $ret;
	}


	function adminLoadAllProducts(){
		
		
		$pdo = $this->pdo;
		$data = array();
		$sql = 'SELECT 

		p.*,
    	u.* from
    	(items p join users u on
    	p.pdOwnerId = u.user_id  ) 
    		
    	order by p.pdDateAdded desc';
		//echo $sql;
		$pds = $pdo->prepare($sql);
				$pds->execute($data);
		
		return $pds->fetchAll(PDO::FETCH_ASSOC);	
		
	}

	
	function adminLoadAllLookingFor(){
		
		
		$pdo = $this->pdo;
		$data = array();
		$sql = 'SELECT 
		u.*,
		i.* FROM 
		(items_inquiry i join users u ON 
		i.user_id = u.user_id )
		ORDER BY inq_date DESC  ';
    	


		$pds = $pdo->prepare($sql);
				$pds->execute($data);
		
		return $pds->fetchAll(PDO::FETCH_ASSOC);	
		
	}


	function adminLoadAllUsers(){

		$pdo = $this->pdo;
		$data = array();
		$sql = 'SELECT * FROM users ORDER by users.user_added_on desc';
		//echo $sql;
		$pds = $pdo->prepare($sql);
				$pds->execute($data);
		
		return $pds->fetchAll(PDO::FETCH_ASSOC);	

	}

	function setProdStat($itemId, $newStat){

		$pdo = $this->pdo;
		$data = array();

		$sql = 'UPDATE `items` SET 
		`pdStat` = \'' . $newStat . '\' 
		WHERE `items`.`item_id` = ' . $itemId . ';';

		//echo $sql;
		$pds = $pdo->prepare($sql);
		$pds->execute($data);

		$c = $pds->rowCount();

		if($c == 1){
			return true;
		}else{
			return false;
		}

	}
	

	function loadAllCatsAndTypes($textOnly){

		$pdo = $this->pdo;
		$data = array();
		
		/*
        $sql = 'SELECT DISTINCT
cat.it_cat_id,
cat.it_cat_name,
cat.it_cat_pic,
cat.it_cat_priority,
tp.items_types_id,
tp.items_types_name,
tp.items_types_pic,
tp.items_types_cat,
tp.items_types_priority from
(it_cats cat join items_types tp on
tp.items_types_cat = cat.it_cat_id )' ; // where*/

        $sql = 'SELECT * FROM it_cats ';

        if($textOnly === true){
        	$sql = 'SELECT it_cat_id, it_cat_name FROM it_cats';
        }

        $sql .= ' ORDER BY it_cat_priority DESC ';

		$pds = $pdo->prepare($sql);
		$pds->execute($data);

		
		
		
		
		$types = $pds->fetchAll(PDO::FETCH_ASSOC);

		/*if(is_array($types) && count($types) > 0){
			foreach ($types as $k => $v) {
				
				$path_img = 'http://' . $_SERVER['SERVER_NAME'] . '/' . ROOT_FOLDER_SOSACHAT . '/' .ROOT_FOLDER_IMAGES . '/' .  FOLDER_NAME_CATS . '/' . $v['items_types_pic'];

				$types[$k]['path_img'] = $path_img;
			}
		}*/

		return $types;

	}

	function loadAllFeaturedProducts($uid){
		
		
		$pdo = $this->pdo;
		$data = array();
		//$sql = 'SELECT *, pdUniqueName as pdImg FROM `items` WHERE pdFeatured = 1 ORDER BY `items`.`pdDateAdded` DESC';

        //$sql = 'SELECT *, pdUniqueName as pdImg FROM `items` ORDER BY `items`.`pdDateAdded` DESC LIMIT 0,5';

        /*
1692199707_20180531072500_main.jpg
        */

        $sql = 'SELECT
p.pdUniqueName,
p.pdUniqueName as pdImg,
p.*,
u.* from
(items p join users u on
p.pdOwnerId = u.user_id  ) 
where p.pdOwnerId != ' . $uid ;  
$sql .= ' AND p.pdStat = \'' . PROD_STAT_PUBLISHED . '\' ';
$sql .= ' order by p.pdDateAdded desc LIMIT 0,5 ';

        //echo $sql;
		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);

		return $this->GITCP($res);

		
	}

	function getItemCatByID($catID){

		$cat = 'NO_CAT';

$pdo = $this->pdo;
		$data = array();
        $sql = 'SELECT it_cat_name FROM it_cats WHERE it_cat_id = ' . $catID;


		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);

		if(is_array($res) && count($res) > 0){
			$cat = $res[0]['it_cat_name'];
		}

		//echo $cat;
		return $cat;
	}

	function getItemTypeByID($typeID){
		$type = 'NO_TYPE';

$pdo = $this->pdo;
		$data = array();
        $sql = 'SELECT items_types_name FROM items_types WHERE items_types_id = ' . ($typeID);


//echo $sql;
		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);

		if(is_array($res) && count($res) > 0){
			$type = $res[0]['items_types_name'];
		}

		//echo $cat;
		return $type;
	}
	
	function loadAllMyProducts($uid){
		
		
		$pdo = $this->pdo;
		$data = array($uid);
		/*$sql = 'SELECT *, pdUniqueName as pdImg  FROM `items` WHERE ' . KEY_PRODUCT_OWNER_ID . ' = ? ORDER BY `items`.`pdDateAdded` DESC';*/

$sql = 'SELECT
p.pdUniqueName,
p.pdUniqueName as pdImg,
p.*,
u.*  from
(items p join users u on p.pdOwnerId = u.user_id  ) 
where ' . KEY_PRODUCT_OWNER_ID . ' = ' . $uid ;

$sql .= ' order by p.pdDateAdded desc ';

		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		//printq($sql, $data);
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);

		$items = $this->GITCP($res);

		return $items;
	}

	function createDirs($path){
		mkdir($path,0755,true);
	}

	function uploadPP($mobile, $ppData){



		$pp_file = md5($mobile);


		$ppDirs = ROOT_FOLDER_IMAGES . '/' . IMG_FOLDER_PP . '/' ;
		$this->createDirs($ppDirs);

		$fh = fopen($ppDirs  . $pp_file . '.jpg' , 'w');
		if(fwrite($fh, base64_decode($ppData))){
			fclose($fh);
			return true;
		}else{
			return false;
		}


		

	}
	
	function updSet($setName, $newVal, $uid){
		
		$pdo = $this->pdo;
		
		$data = array($newVal, $uid);
		
		$sql = 'UPDATE users SET ' . $setName . ' = ? WHERE `users`.`user_id` = ?';
		$pds = $pdo->prepare($sql);
		
		//printq($sql, $data);
		
		//print_r( $pdo->e);
		$pds->execute($data);
		
		if($pds->rowCount() == 1){
			return true;	
		}else{
			return false;	
		}
			
	}

/*
	function userExists($emailOrMobile){

	$username = $emailOrMobile;
	$data = array($username, $username);//, $username, $password);

		$sql = 'SELECT COUNT(*) FROM users WHERE user_email = ? 
		OR user_mobile = ? ';
		//echo $sql;
		$pds = $pdo->prepare($sql);
		$pds->execute($data);

		$count = $pds->fetchAll();
		$count = $count[0][0];

		if($count == 1){
			return true;
		}else{
			return false;
		}

}*/
	
	function login($username, $password){
		
		$pdo = $this->pdo;

		$data = array($username, $username);

		
		

		if(!$this->userExists($username, $username) ){

			$data[KEY_RESULT_CODE] = RESULT_CODE_USER_DONT_EXIST;
			$data[KEY_RESULT_DATA] = $username;
			return $data;

		}else{
		
		$data = array($username, $username, $password);
		$sql ='SELECT * FROM users WHERE ( user_email= ? OR user_mobile=? ) AND user_password=?';
		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		
		$res = $pds->fetchAll(PDO::FETCH_ASSOC);
		
		//printq($sql, $data);

		if(count($res) == 1){
			$this->updateFingerPrint($res[0]);
			$_SESSION['user'] = $res[0];
			$data[KEY_RESULT_CODE] = RESULT_CODE_USER_CONNECTION_SUCCESS;
			$data[KEY_RESULT_DATA] = $res[0];

			//echo '<br/>---------<br/>';
			//print_r($res[0]);
			//echo '<br/>---------<br/>';

			return $data;
		}else{
			
			$data[KEY_RESULT_CODE] = RESULT_CODE_USER_CONNECTION_FAILURE_PASSWORD_ERROR;
			$data[KEY_RESULT_DATA] = $username;

			return $data;
		}
	
	}
		
	}
	
	function getCurDateTimeYmdhis(){
		
		return date('Y-m-d H:i:s');	
	}
	
	function GCD(){
		return $this->getCurDateTimeYmdhis();	
	}
	
	function updateFingerPrint($user){
		//print_r($user);
		$ip = $_SERVER['SERVER_ADDR'];
		$pdo = $this->pdo;
		$sql = 'UPDATE users SET 
		user_last_ip = "' . $ip . '", 
		user_date_last_login="'. $this->GCD() .'",  
		user_logged_in = 1 
		WHERE user_id = ' . $user['user_id'] ;

		//echo $sql;

		$pdo->exec($sql);
			
	}
	
	function userExists($email, $mobile){
		
		$pdo = $this->pdo;
		$sql = 'SELECT COUNT(*) FROM users WHERE user_email = ? OR user_mobile = ?';
		$data = array($email, $mobile);
		
		$pds = $pdo->prepare($sql);
		$pds->execute($data);
		$res = $pds->fetchAll();
		$cnt = $res[0][0];
		
		
		
		if($cnt == 0 ){
			return false;	
		}else{
			return true;	
		}
		
			
	}
	
	function signup($fullName, $displayName, $email, $password, $mobile, $location){
		
		
		$pdo = $this->pdo;

		$user['user_full_name'] = $fullName;
		$user['user_display_name'] = $displayName;
		$user['user_email'] = $email;
		$user['user_password'] = $password;
		$user['user_mobile'] = $mobile;
		$user['user_location'] = $location;
		
		
		$exists = $this->userExists($email, $mobile);
		
		if($exists){
			$data[KEY_RESULT_CODE] = RESULT_CODE_SIGNUP_FAILURE_USER_EXISTS;
			$data[KEY_RESULT_DATA] = json_encode($user);	
			return $data;
		}else{
			
			$date = date('Y-m-d H:i:s');
			$pic = NO_PIC;
			
			$sql = 'INSERT INTO users
			(`user_id`, `user_display_name`, `user_full_name`, `user_email`, 
			`user_mobile`, `user_mobile_hash`, `user_password`, `user_location`, `user_added_on`, 
			`user_pic`) VALUES 
			(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?);';
			
			$mobileHash = md5($mobile);

			$data = array($displayName, $fullName, $email,  $mobile, $mobileHash,   $password, $location, $date, $pic);
		
			$pds = $pdo->prepare($sql);
			$success = $pds->execute($data);
			
			//printq($sql, $data);

			if($success){
				$user = $this->login($mobile, $password);	
				$user['id'] = $pdo->lastInsertId();

				$data[KEY_RESULT_CODE] = RESULT_CODE_SIGNUP_SUCCESS;
				$data[KEY_RESULT_DATA] = $user;

				return $data;
			}else{

				$data[KEY_RESULT_CODE] = RESULT_CODE_SIGNUP_FAILURE;
				$data[KEY_RESULT_DATA] = $success;
				return $data;	
			}
			
			 
			
		}
		
		
		
		
	}
	
	function delAcc($mobile){
		
		$pdo = $this->pdo;
		$sql = 'DELETE FROM users WHERE `users`.`user_mobile` = ?';	
		$data = array($mobile);
			
		$pds = $pdo->prepare($sql);
		if($pds->execute($data)){
			return true;	
		}else{
			return false;	
		}
		
	}
}

$db = new DB();

} catch(PDOException $e) {
		
      die ('Erreur connecting to databse : ' . $e);
}

?>