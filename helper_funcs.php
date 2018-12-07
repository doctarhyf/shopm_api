<?php




function echonl($d){
	echo $d . "<br/>";	
}

function enl($d){
	echonl($d);
}

function printq($sql,$data){
	
	echonl('-----------------------------------------------');
	$explode = explode('?', $sql);
		$new_str = '';
		
		for($i = 0; $i < count($explode); $i++){
			if($i < count($data)){
				$new_str .= $explode[$i] . "'" . $data[$i] . "'";	
			}
		}
		
	echonl($new_str);
	echonl('-----------------------------------------------');
}

?>