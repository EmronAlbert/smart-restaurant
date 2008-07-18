<?php
/**
* My Handy Restaurant
*
* http://www.myhandyrestaurant.org
*
* My Handy Restaurant is a restaurant complete management tool.
* Visit {@link http://www.myhandyrestaurant.org} for more info.
* Copyright (C) 2003-2005 Fabio De Pascale
* 
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*
* @author		Fabio 'Kilyerd' De Pascale <public@fabiolinux.com>
* @package		MyHandyRestaurant
* @copyright		Copyright 2003-2005, Fabio De Pascale
*/

function stock_new_item_form(){
		echo "
		<form action=\"stock.php\" method=\"POST\">
		<input type=\"hidden\" name=\"command\" value=\"item_insert\">
		<table><tr>
		<td>".GLOBALMSG_STOCK_ITEM_NAME.":</td>
		<td><input type=\"text\" name=\"data[name]\" value=\"\"></td>
		</tr><tr>
		<td>".GLOBALMSG_STOCK_ITEM_INITIAL_QUANTITY.":</td>
		<td><input type=\"text\" size=\"4\" name=\"data[stock]\" value=\"0\"></td>
		</tr></table><input type=\"submit\" value=\"".GLOBALMSG_STOCK_ITEM_ADD."\">
		</form>
		<br>
		";


}

function stock_insert_item($data){
	$data['category']=0;
	$data['stock_is_on']=1;

	$table=$GLOBALS['table_prefix'].'dishes';
	$query="INSERT INTO $table (";
	for (reset ($data); list ($key, $value) = each ($data); ) {
		$query.="`".$key."`,";
	}
	// strips the last comma that has been put
	$query = substr ($query, 0, strlen($query)-1);
	$query.=") VALUES (";
	for (reset ($data); list ($key, $value) = each ($data); ) {
		$query.="'".$value."',";
	}
	// strips the last comma that has been put
	$query = substr ($query, 0, strlen($query)-1);	$query.=")";
	echo $query;
	
	$res = mysql_db_query ($_SESSION['common_db'],$query);
	$num_affected=mysql_affected_rows();

	if($num_affected!=1) return 1;

	return 0;
}

function form_stock_edit($invoice_id=0){


	if(!$invoice_id){
		echo ucfirst(phr('CHECK_NO_INVOICE_ERROR')).".<br>\n";
		return 1;
	}

	echo "<script src=\"../SpryTabbedPanels.js\" type=\"text/javascript\"></script>";
	echo "<link href=\"../SpryTabbedPanels.css\" rel=\"stylesheet\" type=\"text/css\" />";
	
	$table=$GLOBALS['table_prefix'].'stock_objects';
	$tablecat=$GLOBALS['table_prefix'].'categories';
	$tableing=$GLOBALS['table_prefix'].'ingreds';
	$query=" SELECT *, $tablecat.`name` as categoryname, $table.name as ingredname "; 
	$query.="FROM $table INNER JOIN $tableing ON $tableing.`id` = $table.`ref_id` ";
	$query.="INNER JOIN $tablecat ON $tableing.`category` = $tablecat.`id` ";
	$query.="WHERE $table.`stock_is_on`='1' AND $table.`name` NOT LIKE '' AND $table.`deleted` = 0 ";
	$query.="ORDER BY categoryname, ingredname";
	$res=mysql_db_query($_SESSION['common_db'],$query);

	if(!mysql_num_rows($res)) return 2;

	//i know it's stupid
	//but i had no other idea...
  	$categories = "";
	while($row = mysql_fetch_array($res)){
		if($row['categoryname'] != $categories) {
			$categories[$row['categoryname']] = $row['categoryname'];
		}
	}
	
	echo "<div id=\"TabbedPanels1\" class=\"TabbedPanels\">";
  	echo "<ul class=\"TabbedPanelsTabGroup\">";
	while(list($key, $val) = each($categories)) {
		echo "<li class=\"TabbedPanelsTab\" tabindex=\"0\">".$val."</li>";
	}
	echo "</ul>";
	echo "<div class=\"TabbedPanelsContentGroup\">";

	mysql_data_seek($res,0);
	$row=mysql_fetch_array($res);
	$categories = "";
	$iterator = 0;	
	do {
		$table=$GLOBALS['table_prefix'].'account_stock_log';
		$query="SELECT * FROM $table WHERE `name`='".$row['name']."' AND `invoice_id`='$invoice_id'";
		$res_local=mysql_db_query($_SESSION['common_db'],$query);
		
		if(mysql_num_rows($res_local)){
			$row_local=mysql_fetch_array($res_local);
			echo "<tr>\n";
			echo "<td>".$row_local['name']."</td>\n";
			echo "<td><input type=\"text\" name=\"data[quantity][edit][".$row_local['id']."]\" value=\"".$row_local['quantity']."\" maxlength=\"6\"></td>\n";
			echo "</tr>\n";
		} else {
			if ( $categories[$row['categoryname']] !=  $row['categoryname'] ) {
				$categories[$row['categoryname']] = $row['categoryname'];
				echo "<div class=\"TabbedPanelsContent\">";
				echo "<table>\n";
				echo "<tr><td><b>Artikulli</b></td><td><b>Sasia</b></td><td><b>Cmimi per njesi</b></td></tr>\n";

				do {
					++$iterator;
					echo "<tr>\n";
					echo "<td>".$row['ingredname']."</td>\n";
					echo "<td><input type=\"text\" name=\"data[quantity][new][".$row['ingredname']."]\" value=\"0\" maxlength=\"6\"></td>\n";
					echo "<td><input type=\"text\" name=\"data[value][new][".$row['ingredname']."]\" value=\"0\" maxlength=\"6\"></td>\n";
					echo "</tr>\n";
				} while ($categories[$row['categoryname']] == $row['categoryname'] && $row=mysql_fetch_array($res) ) ;
				mysql_data_seek($res,$iterator-2);
				$iterator=$iterator-2;
				echo "</table>\n";		
				echo "</div>";
			}
		}
	} while ($row=mysql_fetch_array($res));
	echo "</div>";
	echo "<script type=\"text/javascript\">";
	echo "var TabbedPanels1 = new Spry.Widget.TabbedPanels(\"TabbedPanels1\");";
	echo "</script>";
	return 0;
}


function form_stock_new(){
	//$table=$GLOBALS['table_prefix'].'dishes';
	$table=$GLOBALS['table_prefix'].'stock_objects';	
	//$query="SELECT * FROM $table WHERE `stock_is_on`='1' order by `name`";
	$query="SELECT * FROM $table WHERE `stock_is_on`='1' AND name NOT LIKE '' AND deleted = 0 order by `name`";	

	// CRYPTO
	$res=mysql_db_query($_SESSION['common_db'],$query);

	if(!mysql_num_rows($res)) return 2;

	echo "<table>\n";

	while ($row=mysql_fetch_array($res)) {
		echo "<tr>\n";
		echo "<td>".$row['name']."</td>\n";
		echo "<td><input type=\"text\" name=\"data[quantity][new][".$row['name']."]\" value=\"0\" maxlength=\"6\"></td>\n";
		echo "</tr>\n";
	}

	echo "</table>\n";
	return 0;
}


function movement_new(){
	echo "<form action=\"stock.php\" method=\"get\">\n";
	echo "<input type=\"hidden\" name=\"command\" value=\"edit\">\n";
	echo "<input type=\"hidden\" name=\"data[invoice_id]\" value=\"$inserted_id\">\n";
	$err=form_stock_edit($inserted_id);
	echo "<input type=\"submit\" value=\"".ucphr('SEND_TO_STOCK')."\">\n";
	echo "</form>\n";
}

function movement_insert($data){
	$table=$GLOBALS['table_prefix'].'account_stock_log';
	$query="INSERT INTO $table (";
	for (reset ($data); list ($key, $value) = each ($data); ) {
		$query.="`".$key."`,";
	}
	// strips the last comma that has been put
	$query = substr ($query, 0, strlen($query)-1);
	$query.=") VALUES (";
	for (reset ($data); list ($key, $value) = each ($data); ) {
		$query.="'".$value."',";
	}
	// strips the last comma that has been put
	$query = substr ($query, 0, strlen($query)-1);	$query.=")";
	$res = mysql_db_query ($_SESSION['common_db'],$query);
	$num_affected=mysql_affected_rows();

	if($num_affected!=1) return 1;

	set_stock($data['name'],$data['quantity'], $data['value']);

	return 0;
}

function movement_update($data,$id){
	if(!is_array($data)) return 0;

	$table=$GLOBALS['table_prefix'].'account_stock_log';
	$query="SELECT * FROM $table WHERE `id`='$id'";

	$res = mysql_db_query ($_SESSION['common_db'],$query);
	if($errno=mysql_errno()) {
		$msg="Error in ".__FUNCTION__." - ";
		$msg.='mysql: '.mysql_errno().' '.mysql_error()."\n";
		$msg.='query: '.$query."\n";
		echo nl2br($msg)."\n";
		error_msg(__FILE__,__LINE__,$msg);
		return $errno;
	}
	$row=mysql_fetch_array($res);
	mysql_free_result($res);

	$oldquantity=$row['quantity'];

	if(isset($data['date'])) {
		$data['timestamp']=$data['date']['year'];
		$data['timestamp'].=$data['date']['month'];
		$data['timestamp'].=$data['date']['day'];
		$data['timestamp'].=$data['date']['hour'];
		$data['timestamp'].=$data['date']['minute'];
		$data['timestamp'].=$data['date']['second'];

		$oldtimestamp=$row['timestamp'];
		if($oldquantity==$data['quantity'] && $oldtimestamp==$data['timestamp']) return 0;

		unset($data['date']);
	}

	if($oldquantity==$data['quantity']) return 0;
	$table=$GLOBALS['table_prefix'].'account_stock_log';
	$query="UPDATE $table SET ";
	if(is_array($data)){
		for (reset ($data); list ($key, $value) = each ($data); ) {
			$query.="`".$key."`='".$value."',";
		}
	}

	// strips the last comma that has been put
	$query = substr ($query, 0, strlen($query)-1);
	$query.=" WHERE `id`='$id'";

	$res2 = mysql_db_query ($_SESSION['common_db'],$query);
	$num_affected = mysql_affected_rows();


	if($errno=mysql_errno()) {
		$msg="Error in ".__FUNCTION__." - ";
		$msg.='mysql: '.mysql_errno().' '.mysql_error()."\n";
		$msg.='query: '.$query."\n";
		echo nl2br($msg)."\n";
		error_msg(__FILE__,__LINE__,$msg);
		return $errno;
	}

	$table=$GLOBALS['table_prefix'].'account_stock_log';
	$query="SELECT * FROM $table WHERE `id`='".$id."'";
	$res = mysql_db_query ($_SESSION['common_db'],$query);
	if($errno=mysql_errno()) {
		$msg="Error in ".__FUNCTION__." - ";
		$msg.='mysql: '.mysql_errno().' '.mysql_error()."\n";
		$msg.='query: '.$query."\n";
		echo nl2br($msg)."\n";
		error_msg(__FILE__,__LINE__,$msg);
		return $errno;
	}
	$row=mysql_fetch_array($res);

	$newstock=$row['name'];
	$newquantity=$data['quantity'];
	//new buing proce
	$newvalue = $data['value'];
	$diffquantity=$newquantity-$oldquantity;
	
	
	//sets the new stock quantity
	set_stock($newstock, $diffquantity);

	return 0;
}

function movement_delete($id){
	$table=$GLOBALS['table_prefix'].'account_stock_log';
	$query="SELECT * FROM $table WHERE `id`='$id'";
	$res = mysql_db_query ($_SESSION['common_db'],$query);
	$row=mysql_fetch_array($res);

	$oldstock=$row['name'];
	$oldquantity=$row['quantity'];
	$diffquantity=0-$oldquantity;

	set_stock($oldstock,$diffquantity);

	$table=$GLOBALS['table_prefix'].'account_stock_log';
	$query="DELETE FROM $table WHERE `id`='$id'";
	$res = mysql_db_query ($_SESSION['common_db'],$query);
	return 0;
}

function movement_invoice_delete($invoice_id){
	$table=$GLOBALS['table_prefix'].'account_stock_log';
	$query="SELECT * FROM $table WHERE `invoice_id`='$invoice_id'";
	$res = mysql_db_query ($_SESSION['common_db'],$query);
	while($row=mysql_fetch_array($res)){

		$oldstock=$row['name'];
		$oldquantity=$row['quantity'];
		$diffquantity=0-$oldquantity;

		set_stock($oldstock,$diffquantity);
	}
	$table=$GLOBALS['table_prefix'].'account_stock_log';
	$query="DELETE FROM $table WHERE `invoice_id`='$invoice_id'";
	$res = mysql_db_query ($_SESSION['common_db'],$query);
	return 0;
}

/**
 *  updates the sqock quantity
 *  change also the buing proce
 *
 * @param  $dishname
 * @param  $diffquantity
 * @param  $value
 * @author mizuko
 */
function set_stock($dishname,$diffquantity, $value){

	$dishname = str_replace ("'", "\'", $dishname);
	//$table=$GLOBALS['table_prefix'].'dishes';
	$table=$GLOBALS['table_prefix'].'stock_objects';
	$query="SELECT * FROM $table WHERE `name`='".$dishname."'";
	$res=mysql_db_query($_SESSION['common_db'],$query);

	if(!mysql_num_rows($res)) return 2;

	$row=mysql_fetch_array($res);
	mysql_free_result($res);

	//here we should check weather there is only one dish called dishname
	//(easy case) or if we should ask the user which dish to use

	if(!$row['stock_is_on']) return 1;
	$oldstock=$row['quantity'];
	$newstock=$oldstock+$diffquantity;
	$oldvalue = $row['value'];
	if( $oldvalue == 0 ) { 
		$newvalue = $value / $diffquantity;
	} else { 
		$newvalue =  ( $oldvalue + (  $value / $diffquantity ) ) / 2;
	}
	$table=$GLOBALS['table_prefix'].'stock_objects';
	$query="UPDATE $table SET `quantity`= '".$newstock."', value = '". $newvalue ."' WHERE `id`='".$row['id']."'";
	$res=mysql_db_query($_SESSION['common_db'],$query);
	return 0;
}

function set_stock_from_id($orderid,$newquantity){
	// this function should be called *before* the order update but after setting dishid in the order
	
	$diffquantity=stock_calculate_diffquantity($orderid,$newquantity);
	
	$table=$GLOBALS['table_prefix'].'orders';
	$query="SELECT * FROM $table WHERE `id`='".$orderid."'";
	$res=mysql_db_query($_SESSION['common_db'],$query);
	$row=mysql_fetch_array($res);

	$dishid=$row['dishid'];
	$table=$GLOBALS['table_prefix'].'dishes';
	$query="SELECT * FROM $table WHERE `id`='".$dishid."'";
	$res=mysql_db_query($_SESSION['common_db'],$query);
	$row=mysql_fetch_array($res);

	$err=set_stock($row['name'],$diffquantity);
	return 0;
}

function stock_calculate_diffquantity($orderid,$newquantity){
	$table=$GLOBALS['table_prefix'].'orders';
	$query="SELECT * FROM $table WHERE `id`='".$orderid."'";

	$res=mysql_db_query($_SESSION['common_db'],$query);
	$row=mysql_fetch_array($res);
	mysql_free_result($res);

	$diffquantity=$row['quantity']-$newquantity;
	return $diffquantity;
}

function invoice_stock_show($invoice_id){
	$table=$GLOBALS['table_prefix'].'account_stock_log';
	$query="SELECT * FROM $table WHERE `invoice_id`='$invoice_id' order by `name`";
	$res=mysql_db_query($_SESSION['common_db'],$query);

	if(!mysql_num_rows($res)){
		echo ucphr('NO_STOCK_MOVEMENT_ASSOCIATED').".";
		return 1;
	}
	echo "<table bgcolor=\"".color(-1)."\">\n";
	echo "<tr bgcolor=\"".color(-1)."\">
	<td>".ucfirst(phr('ID'))."</td>
	<td>".ucfirst(phr('NAME'))."</td>
	<td>".ucfirst(phr('QUANTITY'))."</td>
	</tr>\n";

	$i=0;

	while($row=mysql_fetch_array($res)){
		echo "<tr bgcolor=\"".color($i)."\">
		<td>".$row['id']."</td>
		<td>".$row['name']."</td>
		<td>".$row['quantity']."</td>
		</tr>\n";
		$i++;
	}
	echo "</table>\n";

}


function stock_table(){
	echo "<table bgcolor=\"".color(-1)."\">\n";
		echo "<tr bgcolor=\"".color(-1)."\">
		<td>".ucfirst(phr('ID'))."</td>
		<td>".ucfirst(phr('NAME'))."</td>
		<td>".ucfirst(phr('QUANTITY'))."</td>
		</tr>\n";

	$i=0;
	$table=$GLOBALS['table_prefix'].'dishes';
	$query="SELECT * FROM $table WHERE `stock_is_on`='1' order by `name`";
	$res=mysql_db_query($_SESSION['common_db'],$query);
	while($row=mysql_fetch_array($res)){
		echo "<tr bgcolor=\"".color($i)."\">
		<td>".$row['id']."</td>
		<td>".$row['name']."</td>
		<td>".$row['stock']."</td>
		</tr>\n";
		$i++;
	}
	echo "</table>\n";
}

function movement_form($id){

	if($id) {
		$editing=1;
		$table=$GLOBALS['table_prefix'].'account_stock_log';
		$query="SELECT * FROM $table WHERE `id`='$id'";
		$res=mysql_db_query($_SESSION['common_db'],$query);
		$row=mysql_fetch_array($res);
		$year=substr($row['timestamp'],0,4);
		$month=substr($row['timestamp'],4,2);
		$day=substr($row['timestamp'],6,2);
		$hour=substr($row['timestamp'],8,2);
		$minute=substr($row['timestamp'],10,2);
		$second=substr($row['timestamp'],12,2);
	} else {
		$id=0;
		$editing=0;
		$day=date("j",time());
		$month=date("n",time());
		$year=date("Y",time());
		$hour=date("H",time());
		$minute=date("i",time());
		$second=date("s",time());
	}

?>
<h4>Stock movements</h4><br>
<table>
	<tr>
	<td><?php echo ucfirst(phr('DATE')); ?></td>
	<td>
		<input type="text" size="2" name="data[date][day]" value="<?php echo $day; ?>"> /
		<input type="text" size="2" name="data[date][month]" value="<?php echo $month; ?>"> /
		<input type="text" size="4" name="data[date][year]" value="<?php echo $year; ?>">
	</td>
	</tr>
	<tr>
	<td><?php echo ucfirst(phr('TIME')); ?></td>
	<td>
		<input type="text" size="2" name="data[date][hour]" value="<?php echo $hour; ?>"> :
		<input type="text" size="2" name="data[date][minute]" value="<?php echo $minute; ?>"> :
		<input type="text" size="2" name="data[date][second]" value="<?php echo $second; ?>">
	</td>
	</tr>
	<tr>
		<td><?php echo ucfirst(phr('ITEM')); ?></td>
		<td><?php echo $row['name']; ?></td>
	</tr>
	<tr>
		<td><?php echo ucfirst(phr('QUANTITY')); ?></td>
		<td><input type="text" size="6" name="data[quantity]" value="<?php echo $row['quantity']; ?>"></td>
	</tr>
	<tr>
		<td><?php echo ucfirst(phr('ASSOCIATED_INVOICE')); ?></td>
<?php
	if($row['invoice_id']) {
		$invoice_descr=get_db_data(__FILE__,__LINE__,$_SESSION['common_db'],
			'account_mgmt_main','description',$row['invoice_id']);
?>
			<td><a href="db.php?command=show&id=<?php echo $row['invoice_id']; ?>"><?php echo $row['invoice_id']; ?> - <?php echo $invoice_descr; ?></td>
<?php
	} else {
?>
			<td> <?php echo ucfirst(phr('NONE')); ?> </td>
<?php
	}
?>
	</tr>
</table>
<?php

}

function movement_table(){
	require("./mgmt_start.php");

?>
	<div align="center"><h3><?php echo ucphr('STOCK_MOVEMENTS'); ?></h3>
<?php

	echo "<table bgcolor=\"".color(-1)."\">\n";
		echo "<tr bgcolor=\"".color(-1)."\">
		<td>".ucfirst(phr('ID'))."</td>
		<td>".ucfirst(phr('DATE'))."</td>
		<td>".ucfirst(phr('TIME'))."</td>
		<td>".ucfirst(phr('ITEM'))."</td>
		<td>".ucfirst(phr('QUANTITY'))."</td>
		<td>".ucfirst(phr('NIVOICE'))."</td>
		</tr>\n";

	$i=0;
	$table=$GLOBALS['table_prefix'].'account_stock_log';
	$query="SELECT * FROM $table";
	$query.=" WHERE `timestamp`>=$timestamp_start AND `timestamp`<=$timestamp_end";
	$query.=" order by `id`";

	$res=mysql_db_query($_SESSION['common_db'],$query);
	if(!mysql_num_rows($res)) return 1;

	while($row=mysql_fetch_array($res)){
		$date['year']=substr($row['timestamp'],0,4);
		$date['month']=substr($row['timestamp'],4,2);
		$date['day']=substr($row['timestamp'],6,2);
		$date['hour']=substr($row['timestamp'],8,2);
		$date['minute']=substr($row['timestamp'],10,2);
		$date['second']=substr($row['timestamp'],12,2);


		echo "<tr bgcolor=\"".color($i)."\">
		<td><a href=\"movement.php?command=edit&id=".$row['id']."\">".$row['id']."</a></td>
		<td><a href=\"movement.php?command=edit&id=".$row['id']."\">".$date['day']."/".$date['month']."/".$date['year']."</a></td>
		<td><a href=\"movement.php?command=edit&id=".$row['id']."\">".$date['hour'].":".$date['minute'].":".$date['second']."</a></td>
		<td>".$row['name']."</td>
		<td>".$row['quantity']."</td>\n";
		if($row['invoice_id']) {
			$table=$GLOBALS['table_prefix'].'account_mgmt_main';
			$res_local=mysql_db_query($_SESSION['common_db'],"SELECT * FROM $table WHERE `id`='".$row['invoice_id']."'");
			if(mysql_num_rows($res_local)) {
				$row_local=mysql_fetch_array($res_local);
				echo "<td><a href=\"db.php?command=show&id=".$row['invoice_id']."\">".$row['invoice_id']." - ".$row_local['description']."</td>\n";

			} else
				echo "<td> - </td>\n";
		} else
			echo "<td> - </td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "</table></div>\n";
}

?>
