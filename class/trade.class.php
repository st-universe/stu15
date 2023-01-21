<?php
class trade
{
	function trade()
	{
		global $myDB,$user;
		$this->db = $myDB;
		$this->user = $user;
	}
	
	function getOfferList($mode,$sort,$se)
	{
		if (($mode <= 2 && $mode >= 1) && ($sort != 999)) return $this->db->query("SELECT a.id,a.user_id,UNIX_TIMESTAMP(a.date) as date_tsp from stu_trade_offers as a LEFT OUTER JOIN stu_trade_goods as b ON a.id=b.trade_offers_id WHERE b.goods_id=".$sort." AND b.status=".$mode." ORDER by a.date DESC LIMIT ".(($se-1)*50).",50");
		elseif ($sort == 999) return $this->db->query("SELECT DISTINCT a.id,a.user_id,UNIX_TIMESTAMP(a.date) as date_tsp from stu_trade_offers as a LEFT JOIN stu_trade_goods as b ON a.id=b.trade_offers_id LEFT JOIN  stu_goods as c ON c.id = b.goods_id WHERE c.hide=1 AND b.status=".$mode." ORDER by a.date DESC LIMIT ".(($se-1)*50).",50");
		return $this->db->query("SELECT id,user_id,UNIX_TIMESTAMP(date) as date_tsp from stu_trade_offers ORDER by date DESC LIMIT ".(($se-1)*50).",50");
	}
	
	function getOfferListByUser($userId) { return $this->db->query("SELECT id,UNIX_TIMESTAMP(date) as date_tsp from stu_trade_offers WHERE user_id='".$userId."' ORDER BY date DESC"); }
	
	function getTradeGivebyId($tradeId) { return $this->db->query("SELECT a.goods_id,a.count,b.name,b.secretimage FROM stu_trade_goods as a LEFT OUTER JOIN stu_goods as b ON a.goods_id=b.id WHERE a.trade_offers_id='".$tradeId."' AND a.status='1' ORDER BY b.sort ASC"); }
	
	function getTradeWantbyId($tradeId) { return $this->db->query("SELECT a.goods_id,a.count,b.name,b.secretimage FROM stu_trade_goods as a LEFT OUTER JOIN stu_goods as b ON a.goods_id=b.id WHERE a.trade_offers_id='".$tradeId."' AND a.status='2' ORDER BY b.sort ASC"); }
	
	function deloffer($offerId,$userId)
	{
		if ($this->db->query("DELETE FROM stu_trade_offers WHERE id='".$offerId."' AND user_id=".$userId,6) > 0)
		{
			$result = $this->db->query("SELECT * FROM stu_trade_goods WHERE trade_offers_id='".$offerId."' AND user_id=".$userId." AND status=1");
			for ($i=0;$i<mysql_num_rows($result);$i++)
			{
				$data = mysql_fetch_assoc($result);
				$this->upperstoragebygoodid($data['count'],$data[goods_id],$userId);
			}
			$this->db->query("DELETE FROM stu_trade_goods WHERE trade_offers_id=".$offerId);
			$this->db->query("INSERT INTO stu_trade_logs (user_id,aktion,date) VALUES ('".$userId."','Angebot ".$offerId." gelöscht',NOW())");
			$return[msg] = "Angebot ".$offerId." gelöscht";
			return $return;
		}
	}

	function konfoffer($offerId,$userId)
	{
		if ($this->db->query("DELETE FROM stu_trade_offers WHERE id='".$offerId."' AND user_id=".$userId,6) > 0)
		{
			$result = $this->db->query("SELECT * FROM stu_trade_goods WHERE trade_offers_id='".$offerId."' AND user_id=".$userId." AND status=1");
			for ($i=0;$i<mysql_num_rows($result);$i++)
			{
				$data = mysql_fetch_assoc($result);
				$this->upperstoragebygoodid($data['count'],$data[goods_id],5);
			}
			$this->db->query("DELETE FROM stu_trade_goods WHERE trade_offers_id=".$offerId);
			$this->db->query("INSERT INTO stu_trade_logs (user_id,aktion,date) VALUES ('".$userId."','Angebot ".$offerId." eingezogen',NOW())");
			$return[msg] = "Angebot ".$offerId." wurde eingezogen";
			return $return;
		}
	}
	function getkontobyUser() { return $this->db->query("SELECT a.goods_id,a.count,b.name,b.secretimage FROM stu_trade_goods as a LEFT JOIN stu_goods as b ON a.goods_id=b.id WHERE a.status=0 AND a.user_id=".$this->user." ORDER BY sort"); }
	
	function offercount() { return $this->db->query("SELECT COUNT(id) FROM stu_trade_offers WHERE user_id=".$this->user,1); }
	
	function newoffer()
	{
		$id = $this->db->query("INSERT INTO stu_trade_offers (user_id,date) VALUES ('".$this->user."',NOW())",5);
		$this->db->query("INSERT INTO stu_trade_logs (user_id,aktion,date) VALUES ('".$this->user."','Angebot ".$id." erstellt',NOW())");
		return $id;
	}
	
	function checkgood($goodId,$count,$userId)
	{
		if ($this->db->query("SELECT id FROM stu_trade_goods WHERE user_id='".$userId."' AND goods_id='".$goodId."' AND count>=".$count." AND trade_offers_id=0",1) == 0) return -1;
		if ($this->db->query("SELECT id FROM stu_goods WHERE id=".$goodId."",1) == 0) return -1;
	}
	
	function addtooffer($goodId,$count,$offerId,$status)
	{
		if ($status == 1)
		{
			$data = $this->db->query("SELECT id,count FROM stu_trade_goods WHERE user_id='".$this->user."' AND goods_id='".$goodId."' AND count>=".$count." AND trade_offers_id=0",4);
			if ($data['count'] > $count) $this->db->query("UPDATE stu_trade_goods SET count=count-".$count." WHERE id='".$data[id]."'");
			elseif ($data['count'] == $count) $this->db->query("DELETE FROM stu_trade_goods WHERE id='".$data[id]."'");
			else $error = 1;
			if ($error != 1) $this->db->query("INSERT INTO stu_trade_goods (trade_offers_id,goods_id,count,status,user_id) VALUES ('".$offerId."','".$goodId."','".$count."','".$status."','".$this->user."')");
		}
		else $this->db->query("INSERT INTO stu_trade_goods (trade_offers_id,goods_id,count,status,user_id) VALUES ('".$offerId."','".$goodId."','".$count."','".$status."','".$this->user."')");
		return 1;
	}

	function getgoodInfobyUser() { return $this->db->query("SELECT a.id,a.name,b.count,a.secretimage FROM stu_goods as a LEFT JOIN stu_trade_goods as b ON a.id=b.goods_id AND b.status=0 AND b.user_id=".$this->user." WHERE a.hide=0 ORDER BY a.sort"); }

	function getgoodmaxoffer($good) { return $this->db->query("SELECT maxoffer FROM stu_goods WHERE id=".$good."",4); }

	function getinvisiblegoodInfobyUser() { return $this->db->query("SELECT a.id,a.name,b.count,a.secretimage FROM stu_goods as a LEFT JOIN stu_trade_goods as b on a.id=b.goods_id WHERE b.user_id=".$this->user." AND b.status='0' AND a.hide =1 ORDER BY a.sort"); }

	function takeoffer($offerId)
	{
		$offerdata = $this->db->query("SELECT * FROM stu_trade_offers WHERE id='".$offerId."'",4);
		if ($offerdata == 0) return 0;
		$data = $this->db->query("SELECT * FROM stu_trade_goods WHERE trade_offers_id='".$offerId."' AND status=2",2);
		if ($data == 0) return 0;
		for ($i=0;$i<count($data);$i++)
		{
			if ($this->checkgood($data[$i][goods_id],$data[$i]['count'],$this->user) == -1)
			{
				$return[msg] = "Zum durchführen dieser Transaktion werden ".$data[$i]['count']." ".$this->db->query("SELECT name FROM stu_goods WHERE id=".$data[$i][goods_id],1)." benötigt";
				return $return;
			}
		}
		$data = $this->db->query("SELECT * FROM stu_trade_goods WHERE trade_offers_id='".$offerId."' AND status>0",2);
		for ($i=0;$i<count($data);$i++)
		{
			if ($data[$i][status] == 1)
			{
				$this->upperstoragebygoodid($data[$i]['count'],$data[$i][goods_id],$this->user);
				$maddp .= "<br> ".$data[$i]['count']." ".$this->db->query("SELECT name FROM stu_goods WHERE id=".$data[$i][goods_id],1);
			}
			else
			{
				$this->lowerstoragebygoodid($data[$i]['count'],$data[$i][goods_id],$this->user);
				$this->upperstoragebygoodid($data[$i]['count'],$data[$i][goods_id],$offerdata[user_id]);
				$maddm .= "<br> ".$data[$i]['count']." ".$this->db->query("SELECT name FROM stu_goods WHERE id=".$data[$i][goods_id],1);
			}
		}
		$this->db->query("DELETE FROM stu_trade_goods WHERE trade_offers_id='".$offerId."'");
		$this->db->query("DELETE FROM stu_trade_offers WHERE id='".$offerId."'");
		$this->db->query("INSERT INTO stu_trade_logs (user_id,aktion,date) VALUES ('".$userId."','Angebot ".$offerId." angenommen".$maddp."<br>gegen".$maddm."<br>getauscht',NOW())");
		$message = "Ich hab Dein Angebot (".$offerId.") angenommen".$maddp."<br>gegen.".$maddm."<br>getauscht";
		global $myComm;
		$myComm->sendpm($offerdata[user_id],$this->user,$message,3);
		$return[msg] = "Angebot ".$offerId." angenommen";
		return $return;
	}
	
	function takeofferaction($userId)
	{

		if ($this->checkgood(24,25,$this->user) == -1)
		{
				$return[msg] = "Zum durchführen dieser Transaktion werden 25 Latinum benötigt";
				return $return;
		}
		$good = rand(1,190);
		if (($good >=   1) && ($good <=  10)) $goodId = 319;
		if (($good >=  11) && ($good <=  20)) $goodId = 320;
		if (($good >=  21) && ($good <=  30)) $goodId = 321;
		if (($good >=  31) && ($good <=  40)) $goodId = 322;
		if (($good >=  41) && ($good <=  50)) $goodId = 323;
		if (($good >=  51) && ($good <=  60)) $goodId = 324;
		if (($good >=  61) && ($good <=  70)) $goodId = 325;
		if (($good >=  71) && ($good <=  80)) $goodId = 327;
		if (($good >=  81) && ($good <=  90)) $goodId = 328;
		if (($good >=  91) && ($good <= 100)) $goodId = 329;
		if (($good >= 101) && ($good <= 110)) $goodId = 330;
		if (($good >= 111) && ($good <= 120)) $goodId = 331;
		if (($good >= 121) && ($good <= 130)) $goodId = 332;
		if (($good >= 131) && ($good <= 140)) $goodId = 333;
		if (($good >= 141) && ($good <= 150)) $goodId = 334;
		if (($good >= 151) && ($good <= 160)) $goodId = 335;
		if (($good >= 161) && ($good <= 170)) $goodId = 336;
		if (($good >= 171) && ($good <= 180)) $goodId = 337;
		if (($good >= 181) && ($good <= 183)) $goodId = 326;
		if (($good >= 184) && ($good <= 186)) $goodId = 338;
		if (($good >= 187) && ($good <= 189)) $goodId = 339;
		if ($good == 190) $goodId = 340;

		$name = $this->db->query("SELECT name FROM stu_goods WHERE id=".$goodId,1);

		$this->lowerstoragebygoodid(25,24,$this->user);
		$this->upperstoragebygoodid(1,$goodId,$this->user);

		$return[msg] = "Actionfigur gekauft. Das Paket enthielt: 1x ".$name.".";
		return $return;
	}

	function payout($goodId,$count,$shipId)
	{
		$hp = $this->db->query("SELECT name FROM stu_ships WHERE id='".$shipId."' AND ships_rumps_id=2",1);
		if ($hp == "") return 0;
		$res = $this->db->query("SELECT count FROM stu_trade_goods WHERE status=0 AND user_id=".$this->user." AND goods_id='".$goodId."'",1);
		if ($res == 0) return 0;
		if ($count > $res) $count = $res;
		global $myShip;
		$myShip->upperstoragebygoodid($count,$goodId,$shipId,2);
		$this->lowerstoragebygoodid($count,$goodId,$this->user);
		$name = $this->db->query("SELECT name FROM stu_goods WHERE id=".$goodId,1);
		$this->db->query("INSERT INTO stu_trade_logs (user_id,aktion,date) VALUES ('".$this>user."','".$count." ".$name." ausgezahlt (".$hp.")',NOW())");
		return $count." ".$name." ausgezahlt<br>";
	}
	
	function gettradelog() { return $this->db->query("SELECT *,UNIX_TIMESTAMP(date) as date_tsp FROM stu_trade_logs ORDER BY date DESC",2); }
	
	function getferggoods() { return $this->db->query("SELECT * FROM stu_goods WHERE id=3 OR id=5 OR id=6 OR id=8 OR id=9 OR id=10 OR id=20",2); }

	function getfergtechs() { return $this->db->query("SELECT * FROM stu_goods WHERE id=45 OR id=46 OR id=48 OR id=221",2); }
	
	function getkpricebygoodId($goodId)
	{
		if ($goodId != 3 && $goodId != 5 && $goodId != 6 && $goodId != 8 && $goodId != 9 && $goodId != 10 && $goodId != 20) return 0;
		$wf = $this->db->query("SELECT wfaktor FROM stu_goods WHERE id=".$goodId,1);
		$sum = $this->db->query("SELECT SUM(count) FROM stu_trade_goods WHERE status=0 AND user_id=14",1);
		$good = $this->db->query("SELECT count FROM stu_trade_goods WHERE status=0 AND user_id=14 AND goods_id=".$goodId,1);
		return round(@(($good/$sum)*600*$wf));
	}

	function gettechpricebygoodId($goodId)
	{
		if ($goodId != 45 && $goodId != 46 && $goodId != 48 && $goodId != 221) return 0;
		$good = $this->db->query("SELECT count FROM stu_trade_goods WHERE status=0 AND user_id=14 AND goods_id=".$goodId,1);
		if ($goodId == 45)
		{
			$price[goods_id] = 230;
			if ($good < 5)
			{
				$price[count] = (5-$good)*10 + 20;
			} else $price[count] = 20;
		}
		elseif ($goodId == 46)
		{
			$price[goods_id] = 233;
			if ($good < 5)
			{
				$price[count] = (5-$good)*5 + 10;
			} else $price[count] = 10;
		}
		elseif ($goodId == 221)
		{
			$price[goods_id] = 231;
			if ($good < 5)
			{
				$price[count] = (5-$good)*10 + 20;
			} else $price[count] = 20;
		}
		elseif ($goodId == 48)
		{
			$price[goods_id] = 232;
			if ($good < 5)
			{
				$price[count] = (5-$good)*5 + 10;
			} else $price[count] = 10;
		}
		return $price;
	}
	function getvkpricebygoodId($goodId)
	{
		if ($goodId != 3 && $goodId != 5 && $goodId != 6 && $goodId != 8 && $goodId != 9 && $goodId != 10 && $goodId != 20) return 0;
		$wf = $this->db->query("SELECT wfaktor FROM stu_goods WHERE id=".$goodId,1);
		$sum = $this->db->query("SELECT SUM(count) FROM stu_trade_goods WHERE status=0 AND user_id=14",1);
		$good = $this->db->query("SELECT count FROM stu_trade_goods WHERE status=0 AND user_id=14 AND goods_id=".$goodId,1);
		return round(@(($good/$sum)*600*$wf)*0.75);
	}
	
	function getfergcountbygoodid($goodId) { return $this->db->query("SELECT count FROM stu_trade_goods WHERE user_id=14 AND status=0 AND goods_id=".$goodId,1); }
	
	function buylatinum($goodId,$shipId)
	{
		global $myShip;
		if ($myShip->cshow == 0) return 0;
		if ($goodId != 3 && $goodId != 5 && $goodId != 6 && $goodId != 8 && $goodId != 9 && $goodId != 10 && $goodId != 20) return 0;
		if ($this->db->query("SELECT id FROM stu_ships WHERE id=".$myShip->cdock." AND (ships_rumps_id=87 OR ships_rumps_id=100 AND user_id=14)",1) == 0)
		{
			$return[msg] = "Das Schiff muss an einem Ferengiposten angedockt sein";
			return $return;
		}
		$price = $this->getkpricebygoodId($goodId);
		if ($price == 0) return 0;
		$stor = $myShip->getcountbygoodid($goodId,$shipId);
		$name = $this->db->query("SELECT name FROM stu_goods WHERE id=".$goodId,1);
		if ($stor < $price)
		{
			$return[msg] = "Zum Kauf werden ".$price." ".$name." benötigt - Vorhanden sind nur ".$stor;
			return $return;
		}
		$myShip->lowerstoragebygoodid($price,$goodId,$shipId);
		$myShip->upperstoragebygoodid(1,24,$shipId,$this->user);
		$this->upperstoragebygoodid($price,$goodId,14);
		$return[msg] = "Es wurde 1 Latinum für ".$price." ".$name." gekauft";
		return $return;
	}
	
	function selllatinum($goodId,$shipId)
	{
		global $myShip;
		if ($myShip->cshow == 0) return 0;
		if ($goodId != 3 && $goodId != 5 && $goodId != 6 && $goodId != 8 && $goodId != 9 && $goodId != 10 && $goodId != 20) return 0;
		if ($this->db->query("SELECT id FROM stu_ships WHERE id=".$myShip->cdock." AND (ships_rumps_id=87 OR ships_rumps_id=100 AND user_id=14)",1) == 0)
		{
			$return[msg] = "Das Schiff muss an einem Ferengiposten angedockt sein";
			return $return;
		}
		$price = $this->getvkpricebygoodId($goodId);
		if ($price == 0) return 0;
		$count = $this->getfergcountbygoodid($goodId);
		if ($count < $price)
		{
			$return[msg] = "Diese Ware ist nicht verfügbar";
			return $return;
		}
		if ($myShip->getcountbygoodid(24,$shipId) == 0)
		{
			$return[msg] = "Es ist kein Latinum auf dem Schiff";
			return $return;
		}
		$stor = $this->db->query("SELECT SUM(count) FROM stu_ships_storage WHERE ships_id=".$shipId,1);
		if ($stor+$price > $myShip->cclass[storage])
		{
			$return[msg] = "Es ist nicht genügend Lagerraum auf dem Schiff vorhanden";
			return $return;
		}
		$myShip->lowerstoragebygoodid(1,24,$shipId);
		$myShip->upperstoragebygoodid($price,$goodId,$shipId,$this->user);
		$this->lowerstoragebygoodid($price,$goodId,5);
		$return[msg] = "Es wurden ".$price." ".$this->db->query("SELECT name FROM stu_goods WHERE id=".$goodId,1)." für ein 1 Latinum gekauft";
		return $return;
	}

	function buytech($goodId,$shipId)
	{
		global $myShip,$myComm;
		if ($myShip->cshow == 0) return 0;
		if ($goodId != 45 && $goodId != 46 && $goodId != 47 && $goodId != 48 && $goodId != 221) return 0;
		if ($this->db->query("SELECT id FROM stu_ships WHERE id=".$myShip->cdock." AND (ships_rumps_id=87 OR ships_rumps_id=100 AND user_id=14)",1) == 0)
		{
			$return[msg] = "Das Schiff muss an einem Ferengiposten angedockt sein";
			return $return;
		}
		if ($this->getfergcountbygoodid($goodId) < 1)
		{
			$return[msg] = "Diese Daten sind derzeit nicht verfügbar";
			return $return;
		}
		$price = $this->gettechpricebygoodId($goodId);
		if ($price[count] == 0) return 0;
		$stor = $myShip->getcountbygoodid($price[goods_id],$shipId);
		$name = $this->db->query("SELECT name FROM stu_goods WHERE id=".$goodId,1);
		$pname = $this->db->query("SELECT name FROM stu_goods WHERE id=".$price[goods_id],1);
		if ($stor < $price[count])
		{
			$return[msg] = "Zum Kauf werden ".$price[count]." ".$pname." benötigt - Vorhanden sind nur ".$stor;
			return $return;
		}
		$myShip->lowerstoragebygoodid($price[count],$price[goods_id],$shipId);
		$myShip->upperstoragebygoodid(1,$goodId,$shipId,$this->user);
		$this->upperstoragebygoodid($price[count],$price[goods_id],14);
		$this->lowerstoragebygoodid(1,$goodId,14);
		$return[msg] = "Es wurden ".$name." für ".$price[count]." ".$pname." gekauft";
		$myComm->sendpm(14,$this->user,$return[msg],3);
		return $return;
	}
	function getinformants($shipId2,$infoId,$shipId)
	{
		global $myShip;
		if ($myShip->cshow == 0) return 0;
		if ($this->db->query("SELECT id FROM stu_ships WHERE id=".$myShip->cdock." AND (ships_rumps_id=87 OR ships_rumps_id=100) AND user_id=14",1) == 0)
		{
			$return[msg] = "Das Schiff muss an einem Ferengiposten angedockt sein";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_informants_user WHERE user_id=".$this->user,3) >= 3)
		{
			$return[msg] = "Es befinden sich zur Zeit keine Informanten in der Bar";
			return $return;
		}
		$data = $this->db->query("SELECT id,type,pic,price FROM stu_informants WHERE posten=".$shipId2." AND infoId='".$infoId."' AND user_id=".$this->user,2);
		return $data;
	}
	
	function addInformants($shipId,$infoId)
	{
		for ($i=0;$i<3;$i++)
		{
			$rasse = $this->db->query("SELECT rasse FROM stu_informants_data ORDER BY RAND() LIMIT 1",1);
			$beruf = $this->db->query("SELECT beruf FROM stu_informants_data ORDER BY RAND() LIMIT 1",1);
			$type = $this->db->query("SELECT id FROM stu_informants_data WHERE rasse='".$rasse."'",1);
			if ($infoId == 4) $this->db->query("INSERT INTO stu_informants (type,pic,price,posten,infoId,user_id,map_sectors_id) VALUES ('".($rasse." ".$beruf)."','".$type."','".rand(3,7)."','".$shipId."','".$infoId."','".$this->user."','".$this->db->query("SELECT id FROM stu_map_sectors WHERE hide=0 ORDER BY RAND() LIMIT 1",1)."')");
			else $this->db->query("INSERT INTO stu_informants (type,pic,price,posten,infoId,user_id) VALUES ('".($rasse." ".$beruf)."','".$type."','".rand(3,7)."','".$shipId."','".$infoId."','".$this->user."')");
		}
	}
	
	function delinformants($userId) { $this->db->query("DELETE FROM stu_informants WHERE user_id=".$userId); }
	
	function informant($postenId,$shipId,$infoId,$informantId,$shipId2)
	{
		global $myShip,$myComm;
		if ($myShip->cshow == 0) return 0;
		if ($this->db->query("SELECT id FROM stu_ships WHERE id=".$myShip->cdock." AND (ships_rumps_id=87 OR ships_rumps_id=100 AND user_id=14)",1) == 0)
		{
			$return[msg] = "Das Schiff muss an einem Ferengiposten angedockt sein";
			return $return;
		}
		if ($this->db->query("SELECT id FROM stu_informants_user WHERE user_id=".$this->user,3) >= 3) return 0;
		$infor = $this->db->query("SELECT id,type,price,map_sectors_id FROM stu_informants WHERE posten=".$postenId." AND infoId=".$infoId." AND id=".$informantId." AND user_id=".$this->user,4);
		if ($infor == 0)
		{
			$return[msg] = "Der ausgewählte Informant ist nicht verfügbar";
			return $return;
		}
		if ($this->db->query("SELECT count FROM stu_ships_storage WHERE goods_id=24 AND ships_id=".$shipId." AND count>=".$infor[price],1) == 0)
		{
			$return[msg] = "Der Informant verlangt ".$infor[price]." Latinum";
			return $return;
		}
		if ($infoId == 1)
		{
			if ($infor[price] < 3) $shipdata = $this->db->query("SELECT name,coords_x,coords_y FROM stu_ships WHERE id=".$shipId2." AND cloak=0 ANd user_id>100 AND deact=1",4);
			else $shipdata = $this->db->query("SELECT name,coords_x,coords_y,crew,energie,user_id FROM stu_ships WHERE id=".$shipId2." AND cloak=0 ANd user_id>100 AND deact=1",4);
			if ($shipdata == 0)
			{
				$return[msg] = "Dieses Schiff konnte nicht gefunden werden";
				return $return;
			}
			if ($infor[price] > 3) $msg = "Der ".$infor[type]." berichtet, dass sich die ".$shipdata[name]." in Sektor ".$shipdata[coords_x]."/".$shipdata[coords_y]." befindet. Das Schiff hat ".$shipdata[energie]." Energie und ".$shipdata[crew]." Crewmitglieder an Board.";
			else $msg = "Der ".$infor[type]." glaubt, dass sich das Schiff in Sektor ".$shipdata[coords_x]."/".$shipdata[coords_y]." befindet";
			$myComm->sendpm($this->user,3,$msg);
		}
		if ($infoId == 2)
		{
			if ($infor[price] < 3) $shipdata = $this->db->query("SELECT name,coords_x,coords_y FROM stu_colonies WHERE id=".$shipId2." AND user_id>100",4);
			else $shipdata = $this->db->query("SELECT name,coords_x,coords_y,bev_used,bev_free,schild_freq1,schild_freq2,energie,user_id FROM stu_colonies WHERE id=".$shipId2." AND user_id>100",4);
			if ($shipdata == 0)
			{
				$return[msg] = "Diese Kolonie existiert nicht";
				return $return;
			}
			if ($infor[price] > 5) $msg = "Der ".$infor[type]." kennt die Kolonie. Die Kolonie ".$shipdata[name]." befindet sich in Sektor ".$shipdata[coords_x]."/".$shipdata[coords_y].". Auf der Kolonie leben ".($shipdata[bev_used]+$shipdata[bev_free])." Einwohner und es ist ".$shipdata[energie]." Energie vorhanden.";
			else $msg = "Der ".$infor[type]." hat gehört, dass sich die Kolonie in Sektor ".$shipdata[coords_x]."/".$shipdata[coords_y]." befindet";
			$myComm->sendpm($this->user,3,$msg);
		}
		if ($infoId == 3)
		{
			$lastaction = $this->db->query("SELECT UNIX_TIMESTAMP(lastaction) as lastaction FROM stu_user WHERE id=".$shipId2." AND id>100",1);
			if ($lastaction == 0)
			{
				$return[msg] = "Dieser Siedler existiert nicht";
				return $return;
			}
			if ($infor[price] < 3)
			{
				if (time()-$lastaction < 604800) $msg = "Der ".$infor[type]." berichtet, dass er den Siedler erst vor Kurzem gesehen wurde";
				if (time()-$lastaction > 604800) $msg = "Der ".$infor[type]." berichtet, dass er den Siedler schon lang nicht mehr hier gesehen hat";
				if (time()-$lastaction > 1209600) $msg = "Der ".$infor[type]." kann sich an den Siedler gar nicht mehr erinnern";
			}
			else $msg = "Den Siedler hat der ".$infor[type]." zuletzt am ".date("d.m.",$lastaction).(date("Y",$lastaction)+375)." gesehen";
			$myComm->sendpm($this->user,3,$msg);
		}
		if ($infoId == 4)
		{
			if ($this->db->query("SELECT id FROM stu_map_sectors_user WHERE user_id=".$this->user." AND map_sectors_id=".$infor[map_sectors_id],1) != 0)
			{
				$return[msg] = "Du besitzt diesen Kartenausschnitt bereits";
				return $return;
			}
			$msg = "Der ".$infor[type]." hat Dir den Kartenausschnitt geschickt. Er ist jetzt in der Sternenkarte abrufbar";
			$myComm->sendpm($this->user,3,$msg);
			$this->db->query("INSERT INTO stu_map_sectors_user (user_id,map_sectors_id) VALUES ('".$this->user."','".$infor[map_sectors_id]."')");
		}
		$myShip->lowerstoragebygoodid($infor[price],24,$shipId);
		$this->db->query("DELETE FROM stu_informants WHERE user_id=".$this->user);
		$this->db->query("INSERT INTO stu_informants_user (user_id) VALUES ('".$this->user."')");
		$return[msg] = "Die Information wird Dir in Kürze per PM zugesandt";
		return $return;
	}
	
	function gettradegoods() { return $this->db->query("SELECT id,name FROM stu_goods WHERE hide=0 ORDER BY sort",2); }
	
	function lowerstoragebygoodid($count,$goodId,$userId)
	{
		$aff = $this->db->query("UPDATE stu_trade_goods SET count=count-".$count." WHERE count>".$count." AND user_id=".$userId." AND goods_id=".$goodId." AND status=0",6);
		if ($aff == 0) $this->db->query("DELETE FROM stu_trade_goods WHERE goods_id=".$goodId." AND status=0 AND user_id=".$userId);
		return 1;
	}
	
	function upperstoragebygoodid($count,$goodId,$userId)
	{
		$aff = $this->db->query("UPDATE stu_trade_goods SET count=count+".$count.",date=NOW() WHERE goods_id=".$goodId." AND status=0 AND user_id=".$userId,6);
		if ($aff == 0) $this->db->query("INSERT INTO stu_trade_goods (user_id,goods_id,count,date) VALUES ('".$userId."','".$goodId."','".$count."',NOW())");
		return 1;
	}
}
?>