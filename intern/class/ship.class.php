<?php
class ship {

	function ship() {
	
		global $myDB;
		$t->qry = $myDB->query;
	
	}
	
	function getclassbyid($classId)
	{
		$result = $t->qry("SELECT * FROM stu_ships_rumps WHERE id=".$classId);
		if (mysql_num_rows($result) == 0) return 0;
		return mysql_fetch_array($result);
	}
	
	function getdatabyid($shipId)
	{
		$result = $t->qry("SELECT * FROM stu_ships WHERE id=".$shipId);
		if (@mysql_num_rows($result) == 0) return 0;
		$data = mysql_fetch_array($result);
		if ($data[cloak] == 1) $ecloak = 3;
		if ($data[replikator] == 1) $erepl = ceil($data[crew]/5);
		$class = $this->getclassbyid($data[ships_rumps_id]);
		$module = $this->getmodulebyid($data[huellmodlvl]);
		$data[maxhuell] = $class[huellmod]*$module[huell];
		$module = $this->getmodulebyid($data[schildmodlvl]);
		$data[maxshields] = $class[schildmod]*$module[shields];
		$module = $this->getmodulebyid($data[epsmodlvl]);
		$data[maxeps] = $class[epsmod]*$module[eps];
		if ($data[waffenmodlvl] > 0)
		{
			$waff = $this->getmodulebyid($data[waffenmodlvl]);
			$plu = $class[waffenmod_max]-$waff[lvl];
		}
		else $plu = 1 + ($class[waffenmod_max]-$class[waffenmod_min]);
		if (($data[reaktormodlvl] != 0) && ($data[warpcore] > 0))
		{
			$module = $this->getmodulebyid($data[reaktormodlvl]);
			$data[maxreaktor] = $module[reaktor]+$class[fusion]+$plu;
		} else $data[maxreaktor] = $class[fusion]+$plu;
		$data[realreaktor] = $data[maxreaktor]-$data[kss]-$data[lss]-$data[schilde_aktiv]-$ecloak-$erepl;
		$module = $this->getmodulebyid($data[computermodlvl]);
		$data[maxtreffer] = $module[phaser_chance];
		$data[maxausweichen] = $module[torp_evade];
		$module = $this->getmodulebyid($data[antriebmodlvl]);
		$data[maxtreffer] = $data[maxtreffer] + $module[phaser_chance];
		$data[maxausweichen] = $data[maxausweichen] + $module[torp_evade];
		if ($data[waffenmodlvl] != 0)
		{
			$module = $this->getmodulebyid($data[waffenmodlvl]);
			$data[maxphaser] = round($module[phaser] * (1+(($class[waffenmod]-1)/3)));
			$data[maxtreffer] = $data[maxtreffer] + $module[phaser_chance];
		}
		$module = $this->getmodulebyid($data[sensormodlvl]);
		$data[maxlss] = $module[lss_range]+($class[sensormod]-1);
		$data[maxausweichen] = $data[maxausweichen] + $class[torp_evade];
		$data[damaged] = 0;
		if ($data[huelle] <= ($data[maxhuell] * 0.4)) $data[damaged] = 1;
		return $data;
	}

	function getmissionships() {
	
		$result = $t->qry("SELECT a.*,b.goods_id FROM stu_ships as a left outer join stu_ships_storage as b on a.id = b.ships_id WHERE b.goods_id >= 300 AND a.user_id > 100 ORDER BY b.goods_id ASC");
		if (mysql_num_rows($result) == 0) return 0;
		for ($i=0;$i<mysql_num_rows($result);$i++) $data[$i] = mysql_fetch_array($result);
		return $data;
	
	}
}
?>