<?php
class trade {

	function trade() {
	
		global $myDB;
		$this->dblink = $myDB->dblink;
	
	}
	
	function getOfferList() {
		
		$query = "SELECT id,user_id,UNIX_TIMESTAMP(date) as date_tsp from stu_trade_offers";
		$result = mysql_query($query, $this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	
	}
	
	function getOfferListByUser($userId) {
		
		$query = "SELECT id,UNIX_TIMESTAMP(date) as date_tsp from stu_trade_offers WHERE user_id='".$userId."'";
		$result = mysql_query($query, $this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	
	}
	
	function getTradeGivebyId($tradeId) {
	
		$query = "SELECT goods_id,count FROM stu_trade_goods WHERE trade_offers_id='".$tradeId."' AND status='1'";
		$result = mysql_query($query, $this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
			$query = "SELECT name from stu_goods WHERE id=".$data[$i][goods_id]."";
			$data[$i][good] = mysql_fetch_array(mysql_query($query,$this->dblink));
		}
		return $data;
	
	}
	
	function getTradeWantbyId($tradeId) {
	
		$query = "SELECT goods_id,count FROM stu_trade_goods WHERE trade_offers_id='".$tradeId."' AND status='2'";
		$result = mysql_query($query, $this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
			$query = "SELECT name from stu_goods WHERE id=".$data[$i][goods_id]."";
			$data[$i][good] = mysql_fetch_array(mysql_query($query,$this->dblink));
		}
		return $data;
	
	}
	
	function deloffer($offerId,$userId) {
	
		mysql_query("DELETE FROM stu_trade_offers WHERE id='".$offerId."' AND user_id='".$userId."'",$this->dblink);
		if (mysql_affected_rows()>0) {
			$result = mysql_query("SELECT * FROM stu_trade_goods WHERE trade_offers_id='".$offerId."' AND user_id='".$userId."' AND status=1",$this->dblink);
			for ($i=0;$i<mysql_num_rows($result);$i++) {
				$data[$i] = mysql_fetch_array($result);
				$result2 = mysql_query("SELECT * FROM stu_trade_goods WHERE goods_id='".$data[$i][goods_id]."' AND status=0 AND user_id='".$userId."'",$this->dblink);
				if (mysql_num_rows($result2) > 0) {
					mysql_query("UPDATE stu_trade_goods SET count=count+".$data[$i]['count']." WHERE goods_id='".$data[$i][goods_id]."' AND status=0 AND user_id='".$userId."'",$this->dblink);
					mysql_query("DELETE FROM stu_trade_goods WHERE trade_offers_id='".$offerId."' AND user_id='".$userId."'",$this->dblink);
				} else {
					mysql_query("UPDATE stu_trade_goods SET trade_offers_id=0,status=0 WHERE trade_offers_id='".$offerId."' AND user_id='".$userId."' AND status=1",$this->dblink);
					mysql_query("DELETE FROM stu_trade_goods WHERE status=2 AND trade_offers_id='".$offerId."'",$this->dblink);
				}
			}
		}
	}
	
	function getkontobyUser($userId) {
	
		$query = "SELECT goods_id,count FROM stu_trade_goods WHERE status='0' AND user_id='".$userId."'";
		$result = mysql_query($query, $this->dblink);
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
			$data[$i][good] = mysql_fetch_array(mysql_query("SELECT name,id FROM stu_goods WHERE id='".$data[$i][goods_id]."'",$this->dblink));
		}
		return $data;
	
	}
	
	function newoffer($userId) {
	
		mysql_query("INSERT INTO stu_trade_offers (user_id,date) VALUES ('".$userId."',NOW())",$this->dblink);
		return mysql_insert_id();
	
	}
	
	function checkgood($goodId,$count,$userId,$offerId) {
	
		$result = mysql_query("SELECT id FROM stu_trade_goods WHERE user_id='".$userId."' AND goods_id='".$goodId."' AND count>=".$count." AND trade_offers_id=0",$this->dblink);
		if (mysql_num_rows($result) == 0) return -1;
	
	}
	
	function addtooffer($goodId,$count,$userId,$offerId,$status) {
	
		if ($status == 1) {
			$result = mysql_query("SELECT id,count FROM stu_trade_goods WHERE user_id='".$userId."' AND goods_id='".$goodId."' AND count>=".$count." AND trade_offers_id=0",$this->dblink);
			$data = mysql_fetch_array($result);
			if ($data['count'] > $count) mysql_query("UPDATE stu_trade_goods SET count=count-".$count." WHERE id='".$data[id]."'",$this->dblink);
			elseif ($data['count'] == $count) mysql_query("DELETE FROM stu_trade_goods WHERE id='".$data[id]."'",$this->dblink);
			else $error = 1;
			if ($error != 1) mysql_query("INSERT INTO stu_trade_goods (trade_offers_id,goods_id,count,status,user_id) VALUES ('".$offerId."','".$goodId."','".$count."','".$status."','".$userId."')",$this->dblink);
		} else mysql_query("INSERT INTO stu_trade_goods (trade_offers_id,goods_id,count,status,user_id) VALUES ('".$offerId."','".$goodId."','".$count."','".$status."','".$userId."')",$this->dblink);
		return 1;
	}
	
	function getgoodInfobyUser($userId) {
	
		$query = "SELECT id,name FROM stu_goods";
		$result = mysql_query($query, $this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
			$data[$i][user] = mysql_fetch_array(mysql_query("SELECT count FROM stu_trade_goods WHERE status='0' AND user_id='".$userId."' ANd goods_id='".$data[$i][id]."'",$this->dblink));
		}
		return $data;
	
	}
	
	function takeoffer($offerId,$userId) {
	
		$result = mysql_query("SELECT * FROM stu_trade_goods WHERE trade_offers_id='".$offerId."' AND status=2",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			$data[$i] = mysql_fetch_array($result);
			$return = $this->checkgood($data[$i][goods_id],$data[$i]['count'],$userId,$offerId);
			if ($return == -1) return -1;
		}
		if ($return != -1) {
			for ($i=0;$i<count($data);$i++) {
				$result = mysql_query("SELECT count FROM stu_trade_goods WHERE status=0 AND user_id='".$userId."' AND goods_id='".$data[$i][goods_id]."' AND count>'".$data[$i]['count']."'",$this->dblink);
				if (mysql_num_rows($result) > 0) mysql_query("UPDATE stu_trade_goods SET count=count-".$data[$i]['count']." WHERE user_id='".$userId."' AND status=0 AND goods_id='".$data[$i][goods_id]."'",$this->dblink);
				else mysql_query("DELETE FROM stu_trade_goods WHERE user_id='".$userId."' AND status=0 AND goods_id='".$data[$i][goods_id]."'",$this->dblink);
				$result = mysql_query("SELECT count FROM stu_trade_goods WHERE status=0 AND user_id='".$data[$i][user_id]."' AND goods_id='".$data[$i][goods_id]."'",$this->dblink);
				if (mysql_num_rows($result) > 0) mysql_query("UPDATE stu_trade_goods SET count=count+".$data[$i]['count']." WHERE user_id='".$data[$i][user_id]."' AND status=0 AND goods_id='".$data[$i][goods_id]."'",$this->dblink);
				else mysql_query("INSERT INTO stu_trade_goods (goods_id,count,user_id) VALUES ('".$data[$i][goods_id]."','".$data[$i]['count']."','".$data[$i][user_id]."')",$this->dblink);
			}
		}
		mysql_query("DELETE FROM stu_trade_goods WHERE trade_offers_id='".$offerId."' AND status=2");
		$result = mysql_query("SELECT * FROM stu_trade_goods WHERE trade_offers_id='".$offerId."' AND status=1",$this->dblink);
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) {
			if (!$recipient) $recipient = $data[$i][user_id];
			$data[$i] = mysql_fetch_array($result);
			$result2 = mysql_query("SELECT count FROM stu_trade_goods WHERE status=0 AND user_id='".$userId."' AND goods_id='".$data[$i][goods_id]."'",$this->dblink);
			if (mysql_num_rows($result2) > 0) mysql_query("UPDATE stu_trade_goods SET count=count+".$data[$i]['count']." WHERE user_id='".$userId."' AND status=0 AND goods_id='".$data[$i][goods_id]."'",$this->dblink);
			else mysql_query("UPDATE stu_trade_goods SET user_id='".$userId."',trade_offers_id='0',status='0' WHERE id='".$data[$i][id]."'",$this->dblink);
		}
		mysql_query("DELETE FROM stu_trade_goods WHERE trade_offers_id='".$offerId."' AND status=1");
		mysql_query("DELETE FROM stu_trade_offers WHERE id='".$offerId."'",$this->dblink);
		include_once("inc/dummymsg.inc.php");
		$message = str_replace("dummyoffer",$offerId,$dummymsg[1]);
		global $myComm;
		$myComm->sendpm($recipient,$user,$message);
		return 1;
	}
	
	function payout($goodId,$count,$shipId,$userId) {
	
		$result = mysql_query("SELECT user_id FROM stu_ships WHERE id='".$shipId."' AND ships_classes_id=2",$this->dblink);
		if (mysql_num_rows($result) == 0) return -1;
		else {
			$data = mysql_fetch_array($result);
			mysql_query("INSERT INTO stu_ships_storage (ships_id,user_id,goods_id,count) VALUES ('".$shipId."','".$data[user_id]."','".$goodId."','".$count."')",$this->dblink);
			mysql_query("UPDATE stu_trade_goods SET count=count-".$count." WHERE goods_id='".$goodId."' AND user_id='".$userId."' AND status=0 AND count>'".$count."'",$this->dblink);
			if (mysql_affected_rows() < 1) mysql_query("DELETE FROM stu_trade_goods WHERE goods_id='".$goodId."' AND user_id='".$userId."' AND status=0");
		}
	
	}
}
?>