<?php
/*
  $Id: index.php,v 1.14 2008/04/08

  index file for cao_dashboard 
  http://www.imc-media.com

  Copyright (c) 2003 Wolfgang Schwarz
*/

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('functions.php');

if(file_exists("config.php")) 
	{
    include_once("config.php");
	}
else 
	{
    die("Mir fehlt die config.php. So kann ich nichts ausfuehren... :-((");
	}

$connect = mysqli_connect($hostname,
                          $username,
                          $password, $database);

mysqli_set_charset($connect,"utf8mb4");
//mysql_select_db($database, $connect) or die("Unable to select database.");


$MouseOver = "bgcolor=\"".BGCOLOR."\" onMouseOver=\"this.bgColor='".OVCOLOR."';\"onMouseOut=\"this.bgColor='".BGCOLOR."';\"";
$NoteDel = "bgcolor=\"".BGCOLOR."\" onMouseOver=\"this.bgColor='".OVCOLOR."';\"onMouseOut=\"this.bgColor='".BGCOLOR."';\" style=\"text-decoration: line-through;\"";


// -------------------------------------------------------------------------
// RECHNUNG DRUCKEN
// -------------------------------------------------------------------------
if (isset($_GET['rec_id'])) 
	{
	$sql_query = "SELECT * FROM JOURNAL LEFT JOIN JOURNALPOS ON JOURNAL.REC_ID = JOURNALPOS.JOURNAL_ID WHERE JOURNAL.REC_ID = ".$_GET['rec_id'];

	$sql_result = mysqli_query($connect, $sql_query);
	$sql_count = mysqli_num_rows($sql_result);
	
	$template = file_get_contents("rechnung.tpl.html");
	
	$i = 0;
	
	while($row = mysqli_fetch_array($sql_result)) 
		{
		$x_quelle[$i] = $row["QUELLE"];  
		$x_rdatum[$i] = date_mysql2german($row["RDATUM"]);
		$x_erst_name[$i] = $row["ERST_NAME"];  
		$x_kun_num[$i] = $row["KUN_NUM"];  
		$x_kun_name1[$i] = $row["KUN_NAME1"];  
		$x_kun_name2[$i] = $row["KUN_NAME2"];   
		$x_kun_name3[$i] = $row["KUN_NAME3"];
		$x_kun_strasse[$i] = $row["KUN_STRASSE"];
		$x_kun_land[$i] = $row["KUN_LAND"];
		$x_kun_plz[$i] = $row["KUN_PLZ"];   
		$x_kun_ort[$i] = $row["KUN_ORT"];
		$x_projekt[$i] = $row["PROJEKT"];
		$x_orgnum[$i] = $row["ORGNUM"];
		$x_best_name[$i] = $row["BEST_NAME"];
		$x_usr1[$i] = $row["USR1"];
		$x_usr2[$i] = $row["USR2"];
		$x_kopftext[$i] = $row["KOPFTEXT"];
		$x_fusstext[$i] = $row["FUSSTEXT"];
		$x_vrenum[$i] = $row["VRENUM"]; 
		$x_position[$i] = $row["POSITION"];   
		$x_menge[$i] = number_format($row["MENGE"],1); 
		$x_me_einheit[$i] = $row["ME_EINHEIT"];   
		$x_artnum[$i] = $row["ARTNUM"];                    
		$x_bezeichnung[$i] = $row["BEZEICHNUNG"];
		$x_epreis[$i] = number_format($row["EPREIS"],2);
		$x_gpreis[$i] = $row["GPREIS"];   
		$x_nsumme[$i] = $row["NSUMME"]; 
		$x_bsumme[$i] = $row["BSUMME"];            
		$i++;   
		}
	
	$x_mwst_summe = number_format($x_bsumme[0] - $x_nsumme[0],2);
	$x_quelle = $journal_quelle[$x_quelle[0]];
	
	$template = str_replace("#QUELLE#",$x_quelle,$template);
	$template = str_replace("#RDATUM#",$x_rdatum[0],$template);
	$template = str_replace("#ERST_NAME#",$x_erst_name[0],$template);
	$template = str_replace("#KUN_NUM#",$x_kun_num[0],$template);
	$template = str_replace("#KUN_NAME1#",$x_kun_name1[0],$template);
	$template = str_replace("#KUN_NAME2#",$x_kun_name2[0],$template);
	$template = str_replace("#KUN_NAME3#",$x_kun_name3[0],$template);
	$template = str_replace("#KUN_STRASSE#",$x_kun_strasse[0],$template);
	$template = str_replace("#KUN_LAND#",$x_kun_land[0],$template);
	$template = str_replace("#KUN_PLZ#",$x_kun_plz[0],$template);
	$template = str_replace("#KUN_ORT#",$x_kun_ort[0],$template);
	$template = str_replace("#PROJEKT#",$x_projekt[0],$template);
	$template = str_replace("#ORGNUM#",$x_orgnum[0],$template);
	$template = str_replace("#BEST_NAME#",$x_best_name[0],$template);
	$template = str_replace("#USR1#",$x_usr1[0],$template);
	$template = str_replace("#USR2#",$x_usr2[0],$template);
	$template = str_replace("#KOPFTEXT#",$x_kopftext[0],$template);
	$template = str_replace("#FUSSTEXT#",$x_fusstext[0],$template);
	$template = str_replace("#VRENUM#",$x_vrenum[0],$template);
	$template = str_replace("#NSUMME#",$x_nsumme[0],$template);
	$template = str_replace("#BSUMME#",$x_bsumme[0],$template);
	$template = str_replace("#MWST_SUMME#",$x_mwst_summe,$template);
	
	
	$y_positionen = "<table width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"0\">";
	for ($x=0; $x < $sql_count; $x++) {
	$y_positionen .= "<tr><td valign=\"top\" width=\"50\">$x_position[$x]</td><td valign=\"top\" width=\"70\">$x_menge[$x] $x_me_einheit[$x]</td><td valign=\"top\" width=\"80\">$x_artnum[$x] </td><td>$x_bezeichnung[$x]</td><td valign=\"top\" align=\"right\" width=\"100\">$x_epreis[$x]</td><td valign=\"top\" align=\"right\" width=\"100\">$x_gpreis[$x]</td></tr>";
	$y_positionen .= "<tr><td colspan=\"6\" style=\"border-width:1px;border-style:solid;border-color:#CCCCCC;\"></td></tr>";
	}
	$y_positionen .= "</table>";
	
	$template = str_replace("#POSITIONEN#",$y_positionen,$template);
	
	echo $template;
	
	die;
	}


// -------------------------------------------------------------------------
// PIM
// -------------------------------------------------------------------------
$sql_query_pim = "SELECT RECORDID, DESCRIPTION, NOTES, DATE_FORMAT( STARTTIME,  '%d.%m.%Y'  )  AS STARTDATE, DATE_FORMAT( STARTTIME,  '%H:%i'  ) AS STARTTIME, DATE_FORMAT( ENDTIME,  '%d.%m.%Y'  )  AS ENDDATE, DATE_FORMAT( ENDTIME,  '%H:%i'  )  AS ENDTIME FROM `PIM_TERMINE` WHERE DATE(STARTTIME) BETWEEN CURRENT_DATE AND CURRENT_DATE + INTERVAL 1 MONTH ORDER  BY  DATE_FORMAT( STARTTIME, '%Y.%m.%d %H:%i' ) LIMIT 10";

$sql_result = mysqli_query($connect, $sql_query_pim);

$pim_content = "";
while($row = mysqli_fetch_array($sql_result)) 
	{
    $pim_content .= "<tr $MouseOver><td class=\"table02\" valign=\"top\"><a href=".$_SERVER['PHP_SELF']."?show_pim_id=".$row["RECORDID"].">".$row["STARTDATE"]."</a></td><td class=\"table02\" valign=\"top\">&nbsp;".$row["STARTTIME"]."</a></td><td class=\"table02\" width=\"100%\">&nbsp;&nbsp;".$row["DESCRIPTION"]."</td></tr>";
	}

// -------------------------------------------------------------------------
// PIM TODO
// -------------------------------------------------------------------------
$sql_query_pimtodo = "SELECT RECORDID, COMPLETE, DESCRIPTION, DETAILS, DATE_FORMAT(DUEDATE,  '%d.%m.%Y')  AS DUEDATE FROM PIM_AUFGABEN WHERE COMPLETE =  'N'";

$sql_result = mysqli_query($connect, $sql_query_pimtodo);

$pimtodo_content = "";

while($row = mysqli_fetch_array($sql_result)) 
	{
    $pimtodo_content .= "<tr $MouseOver><td class=\"table02\" valign=\"top\"><a href=".$_SERVER['PHP_SELF']."?show_pimtodo_id=".$row["RECORDID"].">".$row["DUEDATE"]."</a></td><td class=\"table02\" width=\"100%\">".$row["DESCRIPTION"]."</td></tr>";
	}
$pimtodo_content .= "";

// -------------------------------------------------------------------------
// NOTE
// -------------------------------------------------------------------------
$sql_query_note = "SELECT LFD_NR, ERLEDIGT_FLAG, WV_FLAG, KURZTEXT, DATE_FORMAT( DATUM, '%d.%m.%Y' ) AS DATUM, DATE_FORMAT( WV_DATUM, '%d.%m.%Y' ) AS WV_DATUM FROM INFO ORDER BY DATUM LIMIT 20";

$sql_result1 = mysqli_query($connect, $sql_query_note);

$note_content = "";
while($row = mysqli_fetch_array($sql_result1))
	{
    /////�berpr�fe ob die Notitz erledigt ist ///////////////
    if($row["ERLEDIGT_FLAG"]=="Y") 
		{
        $note_content .= "<tr $NoteDel><td class=\"table02\" valign=\"top\">".$row["DATUM"]."</td><td class=\"table02\">".$row["WV_DATUM"]."</a></td><td class=\"table02\" width=\"100%\">".$row["KURZTEXT"]."</td></tr>";
    	}
    else
		{
        $note_content .= "<tr $MouseOver><td class=\"table02\" valign=\"top\">".$row["DATUM"]."</td><td class=\"table02\">".$row["WV_DATUM"]."</a></td><td class=\"table02\" width=\"100%\">".$row["KURZTEXT"]."</td></tr>";
    	}
	}

// -------------------------------------------------------------------------
// BIRTDAY
// -------------------------------------------------------------------------
$sql_query = "select REC_ID, EMAIL, NAME1, NAME2, NAME3, DATE_FORMAT(KUN_GEBDATUM, '%d.%m.%Y') as KUN_GEBDATUM 
             from ADRESSEN where DAYOFYEAR(KUN_GEBDATUM) between DAYOFYEAR(CURRENT_DATE) and (DAYOFYEAR(CURRENT_DATE)+30)
             and KUN_GEBDATUM != '1899-12-30' order by DATE_FORMAT(KUN_GEBDATUM, '%m.%d.%y') limit 9";

$sql_result = mysqli_query($connect, $sql_query);

$birthday_content = "";
while($row = mysqli_fetch_array($sql_result)) 
	{
    $birthday_content .= "<tr $MouseOver><td class=\"table02\" valign=\"top\">".$row["KUN_GEBDATUM"]."</td><td class=\"table02\"><a href=".$_SERVER['PHP_SELF']."?show_adr_detail=".$row["REC_ID"].">".$row["NAME1"]." ".$row["NAME2"]." ".$row["NAME3"]."</td><td class=\"table02\" valign=\"top\"><a href=\"mailto:".$row["EMAIL"]."?subject=".MAIL_SUBJECT_BIRTH."&body=".MAIL_TEXT_BIRTH."\">".$row["EMAIL"]."</a></td></tr>";
	}

// -------------------------------------------------------------------------
// SHOW_PIM
// -------------------------------------------------------------------------
$content = '';
if (!empty($_REQUEST['show_pim_id'])) 
	{
    $sql_query_show_pim = 'SELECT RECORDID, DESCRIPTION, NOTES, DATE_FORMAT( STARTTIME, \'%d.%m.%Y\' ) AS STARTDATE, DATE_FORMAT( STARTTIME, \'%H:%i\' ) AS STARTTIME, DATE_FORMAT( ENDTIME, \'%d.%m.%Y\' ) AS ENDDATE, DATE_FORMAT( ENDTIME, \'%H:%i\' ) AS ENDTIME ' .
                          'FROM PIM_TERMINE WHERE RECORDID = '. addslashes($_REQUEST['show_pim_id']);

    $sql_result = mysqli_query($connect, $sql_query_show_pim);

    $content = "<table><tr><td class=\"border02\">Start/Enddatum</td><td class=\"border02\">Start/Endzeit</td><td class=\"border02\">Betreff/Bemerkung</td></tr>";

    while($row = mysqli_fetch_array($sql_result)) 
		{
        $content .= "<tr $MouseOver><td>".$row["STARTDATE"]."</a></td><td>".$row["STARTTIME"]."</a></td><td>".$row["NOTES"]."</td></tr>";
        $content .= "<tr $MouseOver><td>".$row["ENDDATE"]."</a></td><td>".$row["ENDTIME"]."</a></td><td>".$row["DESCRIPTION"]."</td></tr>";
    	}
    $content .= "</table>";
	}

// -------------------------------------------------------------------------
// SHOW_PIM_TODO
// -------------------------------------------------------------------------
if (!empty($_REQUEST['show_pimtodo_id'])) 
	{
    $sql_query_show_pimtodo = 'SELECT DATE_FORMAT( DUEDATE,  \'%d.%m.%Y\' )  AS DUEDATE, DATE_FORMAT( CREATEDON,  \'%d.%m.%Y\' )  AS CREATEDON, DESCRIPTION, DETAILS ' .
                              'FROM PIM_AUFGABEN WHERE RECORDID = '. addslashes($_REQUEST['show_pimtodo_id']);

    $sql_result = mysqli_query($connect, $sql_query_show_pimtodo);
    $sql_count = mysqli_num_fields($sql_result);

    $content = "<table width=\"100%\"><tr>";

    for ($x=0; $x < $sql_count; $x++) 
		{
        $content .= "<td class=\"border02\">" . mysqli_field_name($sql_result, $x) ."</td>";
    	}
    $content .= "</tr><tr $MouseOver>";

    while($dat = mysqli_fetch_row($sql_result)) 
		{
        foreach ($dat as $feld) 
			{
        	$content .= "<td >$feld</td>";
        	}
        $content .= "</tr><tr bgcolor=\"#FFFFFF\" onMouseOver=\"this.bgColor='gold';\"onMouseOut=\"this.bgColor='#FFFFFF';\">";
    	}
    $content .= "</table>";
	}

// -------------------------------------------------------------------------
// SHOW_ART_SEARCH
// -------------------------------------------------------------------------
if (!empty($_REQUEST['show_art_search'])) 
	{
	$keywords = addslashes($_REQUEST['show_art_search']);

    $sql_query = 'select A.REC_ID, A.ARTNUM, H.HERSTELLER_NAME, A.MATCHCODE, A.KURZNAME, A.MENGE_AKT, A.EK_PREIS, A.VK5B, A.SHOP_ID, A.SHOP_VISIBLE
                 from ARTIKEL as A left join HERSTELLER as H on (A.HERSTELLER_ID = H.HERSTELLER_ID)
                 where KURZNAME like \'%'.$keywords.'%\' or MATCHCODE like \'%'.$keywords.'%\' or ARTNUM like \'%'.$keywords.'%\' 
				 or HERSTELLER_NAME like \'%'.$keywords.'%\' order by A.KURZNAME';

    $sql_result = mysqli_query($connect, $sql_query);
/*    $sql_count = mysql_num_fields($sql_result);

    $content = "<table width=\"100%\"><tr>";

    for ($x=1; $x < $sql_count; $x++) {
        $content .= "<td class=\"border02\">" . mysql_field_name($sql_result, $x) ."</td>";
    }
*/
	$content = "<table width=\"100%\"><tr class=\"border02\"><td>ART-NR.</td><td>HERSTELLER</td><td>MATCHCODE</td><td>ARTIKELBEZ.</td><td>MENGE</td><td>EK</td><td>VK</td><td>S</td>";

    $content .= "</tr><tr $MouseOver>";

    while($dat = mysqli_fetch_row($sql_result)) 
		{
/*        for ($x=1; $x < $sql_count; $x++) {
            $content .= "<td valign=\"top\"><a href=".$_SERVER['PHP_SELF']."?show_art_detail=".$dat[0].">$dat[$x]</td>";
        }
        $content .= "</tr><tr bgcolor=\"#FFFFFF\" onMouseOver=\"this.bgColor='gold';\"onMouseOut=\"this.bgColor='#FFFFFF';\">";
    }
    $content .= "</table>";
*/
	  	$shop = "";
		if ($dat[8] > 0 && $dat[9] == 1) $shop = "<img src=\"shop_true.gif\">";
		if ($dat[8] > 0 && $dat[9] == 0) $shop = "<img src=\"shop_false.gif\">";
	
		$content .= "<td valign=\"top\"><a href=".$_SERVER['PHP_SELF']."?show_art_detail=".$dat[0].">$dat[1]</a></td>";
		$content .= "<td valign=\"top\">$dat[2]</td>";
		$content .= "<td valign=\"top\">$dat[3]</td>";
		$content .= "<td valign=\"top\"><a href=".$_SERVER['PHP_SELF']."?show_art_detail=".$dat[0].">$dat[4]</a></td>";
		$content .= "<td valign=\"top\" align=\"right\">".number_format($dat[5],0)."</td>";
		$content .= "<td valign=\"top\" align=\"right\">".number_format($dat[6],2)."</td>";
		$content .= "<td valign=\"top\" align=\"right\">".number_format($dat[7],2)."</td>";
		//$content .= "<td valign=\"top\">$dat[8] $dat[9]</td>";
		$content .= "<td valign=\"top\">$shop</td>";
		$content .= "</tr><tr bgcolor=\"#FFFFFF\" onMouseOver=\"this.bgColor='gold';\"onMouseOut=\"this.bgColor='#FFFFFF';\">";
		} 
    $content .= "</tr></table>";

   
    $content .= "</table>";
	}

// -------------------------------------------------------------------------
// SHOW_ART_DETAIL
// -------------------------------------------------------------------------
if (!empty($_REQUEST['show_art_detail'])) 
	{
    $info = (empty($_REQUEST['info']) ? '' : $_REQUEST['info']);
    
    switch ($info) {
    case 'shop':
        $sql_query_show_art_detail = ' SHOP_ARTIKEL_ID, SHOP_KURZTEXT, SHOP_LANGTEXT, SHOP_DATENBLATT, SHOP_PREIS_LISTE, SHOP_IMAGE, SHOP_IMAGE_MED, SHOP_IMAGE_LARGE ';
        break;
    case 'userfeld':
        $sql_query_show_art_detail = ' USERFELD_01, USERFELD_02, USERFELD_03, USERFELD_04, USERFELD_05, USERFELD_06, USERFELD_07, USERFELD_08, USERFELD_09, USERFELD_10 ';
        break;
    case 'preise':
        $sql_query_show_art_detail = ' EK_PREIS, VK1, VK1B, VK2, VK2B, VK3, VK3B, VK4, VK4B, VK5, VK5B, AUFW_KTO, ERLOES_KTO, ERSTELLT, GEAEND ';
        break;
    default:
        $sql_query_show_art_detail = ' MATCHCODE, HERSTELLER_NAME, ARTNUM, HERST_ARTNUM, ERSATZ_ARTNUM, BARCODE, KURZNAME, LANGNAME, INFO, EK_PREIS, VK5B, MENGE_MIN, MENGE_AKT, MENGE_BVOR, MENGE_START ';
        break;
    }
     
    $show_art_detail = intval(addslashes($_REQUEST['show_art_detail']));
    // $sql_query_show_adr_detail = "SELECT * FROM ADRESSEN WHERE REC_ID = $show_adr_detail";
    $sql_query_show_art_detail = 'SELECT '. $sql_query_show_art_detail .
                                 'FROM ARTIKEL AS A ' .
                                 'LEFT JOIN HERSTELLER AS H ON (A.HERSTELLER_ID = H.HERSTELLER_ID) ' .
                                 'LEFT JOIN WARENGRUPPEN AS W ON (A.WARENGRUPPE = W.ID) ' .
                                 'WHERE REC_ID = '. $show_art_detail;

    $sql_result = mysqli_query($connect, $sql_query_show_art_detail);
    $sql_count = mysqli_num_fields($sql_result);

    $content = "<table width=\"100%\"><tr class=\"border03\">";
    $content .= "<td width=\"16%\" align=\"center\"><a href=\"javascript:history.go(-1);\"><font color=\"FFFFFF\"><<<</font></a></td>";
    $content .= "<td width=\"17%\" align=\"center\"><a href=\"$_SERVER[PHP_SELF]?show_art_detail=$show_art_detail\"><font color=\"#FFFFFF\">Allgemein</font></a></td>";
    $content .= "<td width=\"17%\" align=\"center\"><a href=\"$_SERVER[PHP_SELF]?show_art_detail=$show_art_detail&info=preise\"><font color=\"#FFFFFF\">Preise / Details</font></a></td>";
    $content .= "<td width=\"16%\" align=\"center\"><a href=\"$_SERVER[PHP_SELF]?show_art_detail=$show_art_detail&info=shop\"><font color=\"#FFFFFF\">Shop</font></a></td>";
    $content .= "<td width=\"17%\" align=\"center\"><a href=\"$_SERVER[PHP_SELF]?show_art_detail=$show_art_detail&info=userfeld\"><font color=\"#FFFFFF\">Benutzerfelder</font></a></td>";
    $content .= "<td width=\"17%\" align=\"center\"><a href=\"$_SERVER[PHP_SELF]?show_art_detail=$show_art_detail&info=historie\"><font color=\"#FFFFFF\">Historie</font></a></td>";
    $content .= "</td></tr></table><p></p>";

    $content .= "<table width=\"100%\">";

    while($dat = mysqli_fetch_array($sql_result)) {

        switch ($info) {
        case 'shop':
            $content .= "<tr><td class=\"border02\" width=\"16%\">Artikel-Nr.</td><td width=\"34%\" $MouseOver> $dat[SHOP_ARTIKEL_ID]</td><td width=\"16%\"></td><td width=\"34%\"></td></tr>";
            $content .= "<tr><td class=\"border02\">Kurztext</td><td colspan=\"3\" $MouseOver> $dat[SHOP_KURZTEXT]</td></tr>";
            $content .= "<tr><td class=\"border02\">Langtext</td><td colspan=\"3\" $MouseOver> $dat[SHOP_LANGTEXT]</td></tr>";
            $content .= "<tr><td class=\"border02\">Herstellerlink</td><td $MouseOver> <a href=\"http://$dat[SHOP_DATENBLATT]\" target=\"_blank\">$dat[SHOP_DATENBLATT]</td><td></td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">VK</td><td $MouseOver> $dat[SHOP_PREIS_LISTE]</td><td></td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">Artikelbild</td><td $MouseOver> <a href=\"".IMAGE_URL."$dat[SHOP_IMAGE]\" target=\"_blank\">$dat[SHOP_IMAGE]</td><td></td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">Artikelbild (2)</td><td $MouseOver> $dat[SHOP_IMAGE_MED]</td><td></td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">Artikelbild (3)</td><td $MouseOver> $dat[SHOP_IMAGE_LARGE]</td><td></td><td></td></tr>";
            break;

        case 'userfeld':
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 01</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_01]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 02</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_02]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 03</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_03]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 04</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_04]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 05</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_05]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 06</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_06]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 07</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_07]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 08</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_08]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 09</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_09]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 10</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_10]</td></tr>";
            break;

        case 'preise':
            $content .= "<tr><td class=\"border02\" width=\"16%\">EK</td><td width=\"34%\" $MouseOver> $dat[EK_PREIS]</td><td width=\"16%\"></td><td width=\"34%\"></td></tr>";
            $content .= "<tr><td class=\"border02\">VK 1 (Netto)</td><td $MouseOver> $dat[VK1]</td><td class=\"border02\">VK 1 (Brutto)</td><td $MouseOver> $dat[VK1B]</td></tr>";
            $content .= "<tr><td class=\"border02\">VK 2 (Netto)</td><td $MouseOver> $dat[VK2]</td><td class=\"border02\">VK 2 (Brutto)</td><td $MouseOver> $dat[VK2B]</td></tr>";
            $content .= "<tr><td class=\"border02\">VK 3 (Netto)</td><td $MouseOver> $dat[VK3]</td><td class=\"border02\">VK 3 (Brutto)</td><td $MouseOver> $dat[VK3B]</td></tr>";
            $content .= "<tr><td class=\"border02\">VK 4 (Netto)</td><td $MouseOver> $dat[VK4]</td><td class=\"border02\">VK 4 (Brutto)</td><td $MouseOver> $dat[VK4B]</td></tr>";
            $content .= "<tr><td class=\"border02\">VK 5 (Netto)</td><td $MouseOver> $dat[VK5]</td><td class=\"border02\">VK 5 (Brutto)</td><td $MouseOver> $dat[VK5B]</td></tr>";
            $content .= "<tr><td></td><td></td><td></td><td>";
            $content .= "<tr><td class=\"border02\">Aufwands Konto</td><td $MouseOver> $dat[AUFW_KTO]</td><td></td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">Erl�skonto</td><td $MouseOver> $dat[ERLOES_KTO]</td><td></td><td></td></tr>";
            $content .= "<tr><td></td><td></td><td></td><td>";
            $content .= "<tr><td class=\"border02\">Erstellt</td><td $MouseOver> ".date_mysql2german($dat['ERSTELLT'])."</td><td></td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">Ge�ndert</td><td $MouseOver> ".date_mysql2german($dat['GEAEND'])."</td><td></td><td></td></tr>";
            break;
            
        case 'historie':
        $content .= art_historie($show_art_detail);
	      break;  

        default:
            $content .= "<tr><td class=\"border02\" width=\"16%\">Matchcode</td><td width=\"34%\" $MouseOver> $dat[MATCHCODE]</td><td class=\"border02\" width=\"16%\">Hersteller</td><td width=\"34%\" $MouseOver> $dat[HERSTELLER_NAME]</td></tr>";
            $content .= "<tr><td class=\"border02\">Artikel-Nr.</td><td $MouseOver> $dat[ARTNUM]</td><td class=\"border02\">Hersteller-Nr.</td><td $MouseOver> $dat[HERST_ARTNUM]</td></tr>";
            $content .= "<tr><td class=\"border02\">Ersatz-Nr.</td><td $MouseOver> $dat[ERSATZ_ARTNUM]</td><td class=\"border02\">Barcode</td><td $MouseOver> $dat[BARCODE]</td></tr>";
            $content .= "<tr><td class=\"border02\">Kurztext</td><td colspan=\"3\" $MouseOver> $dat[KURZNAME]</td></tr>";
            $content .= "<tr><td class=\"border02\">Langtext</td><td colspan=\"3\" $MouseOver> ".nl2br($dat['LANGNAME'])."</td></tr>";
            $content .= "<tr><td class=\"border02\">Info</td><td colspan=\"3\" $MouseOver> ".nl2br($dat['INFO'])."</td></tr>";
            $content .= "<tr><td class=\"border02\">EK</td><td $MouseOver> $dat[EK_PREIS]</td><td class=\"border02\">VK (Brutto)</td><td $MouseOver> $dat[VK5B]</td></tr>";
            $content .= "<tr><td class=\"border02\">Mind. Bestand</td><td $MouseOver> ".number_format($dat['MENGE_MIN'],0)."</td><td class=\"border02\">Akt. Bestand</td><td $MouseOver> ".number_format($dat['MENGE_AKT'],0)."</td></tr>";
            $content .= "<tr><td class=\"border02\">Bestellvorschlag</td><td $MouseOver> ".number_format($dat['MENGE_BVOR'],0)."</td><td class=\"border02\">Bestellt</td><td $MouseOver> ".number_format($dat['MENGE_START'],0)."</td></tr>";
            break;
        }
        $content .= "</table>";
    }
}

// -------------------------------------------------------------------------
// SHOW_ADR_SEARCH
// -------------------------------------------------------------------------
if (!empty($_REQUEST['show_adr_search'])) 
	{
	$keywords = addslashes($_REQUEST['show_adr_search']);
	
    $sql_query = 'select REC_ID, KUNNUM1, NAME1, NAME2, NAME3, STRASSE, PLZ, ORT, TELE1, FAX from ADRESSEN 
                 where NAME1 like \'%'.$keywords.'%\' or NAME2 like \'%'.$keywords.'%\'
                 or NAME3 like \'%'.$keywords.'%\' or PLZ like \'%'.$keywords.'%\' or ORT like \'%'.$keywords.'%\' 
                 or TELE1 like \'%'.$keywords.'%\' ORDER BY NAME1';

    $sql_result = mysqli_query($connect, $sql_query);
/*    $sql_count = mysql_num_fields($sql_result);

    $content = "<table width=\"100%\"><tr>";

    for ($x=1; $x < $sql_count; $x++) {
        $content .= "<td class=\"border02\">" . mysql_field_name($sql_result, $x) ."</td>";
    }
*/
	$content = "<table width=\"100%\"><tr class=\"border02\"><td nowrap>KD-NR.</td><td>NAME</td><td>STRASSE</td><td>PLZ, ORT</td><td>TELEFON</td><td>FAX</td>";
	
    $content .= "</tr><tr $MouseOver>";

    while($dat = mysqli_fetch_array($sql_result)) {
/*        for ($x=1; $x < $sql_count; $x++) {
            $content .= "<td valign=\"top\"><a href=".$_SERVER['PHP_SELF']."?show_adr_detail=".$dat[0].">$dat[$x]</td>";
        }
        $content .= "</tr><tr bgcolor=\"#FFFFFF\" onMouseOver=\"this.bgColor='gold';\"onMouseOut=\"this.bgColor='#FFFFFF';\">";
    }
    $content .= "</table>";
*/	
      $content .= "<td valign=\"top\"><a href=".$_SERVER['PHP_SELF']."?show_adr_detail=".$dat['REC_ID'].">$dat[KUNNUM1]</a></td>";
      $content .= "<td valign=\"top\"><a href=".$_SERVER['PHP_SELF']."?show_adr_detail=".$dat['REC_ID'].">$dat[NAME1] $dat[NAME2] $dat[NAME3]</td>";
      $content .= "<td valign=\"top\">$dat[STRASSE]</td>";
      $content .= "<td valign=\"top\" nowrap>$dat[PLZ] $dat[ORT]</td>";
      $content .= "<td valign=\"top\" nowrap>$dat[TELE1]</td>";
      $content .= "<td valign=\"top\" nowrap>$dat[FAX]</td>";
      $content .= "</tr><tr bgcolor=\"#FFFFFF\" onMouseOver=\"this.bgColor='gold';\"onMouseOut=\"this.bgColor='#FFFFFF';\">";
    } 
    $content .= "</tr></table>";

    $content .= "</table>";
	}

// -------------------------------------------------------------------------
// SHOW_ADR_DETAIL
// -------------------------------------------------------------------------
if (!empty($_REQUEST['show_adr_detail'])) 
	{
    $show_adr_detail = intval($_REQUEST['show_adr_detail']);
    // $sql_query_show_adr_detail = "SELECT * FROM ADRESSEN WHERE REC_ID = $show_adr_detail";
    $sql_query = 'SELECT * FROM ADRESSEN, REGISTRY ' .
                                 "WHERE REC_ID = $show_adr_detail AND MAINKEY LIKE  '%ADDR_HIR%' AND VAL_INT = KUNDENGRUPPE";
    
    $sql_result = mysqli_query($connect, $sql_query);
    $sql_count = mysqli_num_fields($sql_result);

    while($dat = mysqli_fetch_array($sql_result)) 
		{
        $Route =  route_berechnen($dat['STRASSE'], $dat['PLZ'], $dat['ORT']);

        $content = "<table width=\"100%\"><tr class=\"border03\">";

        $content .= "<td width=\"16%\" align=\"center\"><a href=\"javascript:history.go(-1);\"><font color=\"FFFFFF\"><<<</font></a></td>";
        $content .= "<td width=\"17%\" align=\"center\"><a href=\"$_SERVER[PHP_SELF]?show_adr_detail=$show_adr_detail\"><font color=\"#FFFFFF\">Allgemein</font></a></td>";
        $content .= "<td width=\"17%\" align=\"center\"><a href=\"$_SERVER[PHP_SELF]?show_adr_detail=$show_adr_detail&info=detail\"><font color=\"#FFFFFF\">Details</font></a></td>";
        $content .= "<td width=\"16%\" align=\"center\"><a href=\"$Route\" target=\"_blank\"><font color=\"FFFFFF\">Route</font></a></td>";
        $content .= "<td width=\"17%\" align=\"center\"><a href=\"$_SERVER[PHP_SELF]?show_adr_detail=$show_adr_detail&info=userfeld\"><font color=\"#FFFFFF\">Benutzerfelder</font></a></td>";
        $content .= "<td width=\"17%\" align=\"center\"><a href=\"$_SERVER[PHP_SELF]?show_adr_detail=$show_adr_detail&info=historie\"><font color=\"#FFFFFF\">Historie</font></a></td>";
        $content .= "</td></tr></table><p></p>";

        $content .= "<table width=\"100%\">";

        $info = (empty($_REQUEST['info']) ? '' : $_REQUEST['info']);
        switch ($info) {
        case 'detail':
            $content .= "<tr><td class=\"border02\" width=\"16%\">Konto-Nr.</td><td width=\"34%\" $MouseOver> $dat[KTO]</td><td class=\"border02\" width=\"16%\">IBAN</td><td width=\"34%\" $MouseOver> $dat[IBAN]</td></tr>";
            $content .= "<tr><td class=\"border02\">BLZ</td><td $MouseOver> $dat[BLZ]</td><td class=\"border02\">SWIFT</td><td $MouseOver> $dat[SWIFT]</td></tr>";
            $content .= "<tr><td class=\"border02\">Konto-Inhaber</td><td $MouseOver> $dat[KTO_INHABER]</td><td></td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">Kreditlimit</td><td $MouseOver> $dat[KUN_KRDLIMIT]</td><td></td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">UmsatzSt.-ID</td><td $MouseOver> $dat[UST_NUM]</td><td></td><td></td></tr>";
            $content .= "<tr><td></td><td></td><td></td><td>";
            $content .= "<tr><td class=\"border02\">Kunde seit</td><td $MouseOver> ".date_mysql2german($dat['KUN_SEIT'])."</td><td></td><td></td></tr>";
            $content .= "<tr><td></td><td></td><td></td><td>";
            $content .= "<tr><td class=\"border02\">Erstellt</td><td $MouseOver> ".date_mysql2german($dat['ERSTELLT'])."</td><td></td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">Ge�ndert</td><td $MouseOver> ".date_mysql2german($dat['GEAEND'])."</td><td></td><td></td></tr>";
            break;

        case 'userfeld':
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 01</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_01]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 02</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_02]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 03</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_03]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 04</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_04]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 05</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_05]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 06</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_06]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 07</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_07]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 08</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_08]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 09</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_09]</td></tr>";
            $content .= "<tr><td class=\"border02\" width=\"16%\">Benutzerfeld 10</td><td colspan=\"3\" $MouseOver> $dat[USERFELD_10]</td></tr>";
            break;
            
        case 'historie':
        $content .= adr_historie($show_adr_detail);
	    break;    

        default:
			if(! preg_match('~^https?\:\/\/~', $dat['INTERNET'])) { $dat['INTERNET'] = "http://" . $dat['INTERNET']; }
            $content .= "<tr><td class=\"border02\" width=\"16%\">Name 1</td><td width=\"34%\" $MouseOver> $dat[NAME1]</td><td class=\"border02\" width=\"16%\">Kundennummer</td><td width=\"34%\" $MouseOver> $dat[KUNNUM1]</td></tr>";
            $content .= "<tr><td class=\"border02\">Name 2</td><td $MouseOver> $dat[NAME2]</td><td class=\"border02\">Kd.-Nr. bei Lieferant</td><td $MouseOver> $dat[KUNNUM2]</td></tr>";
            $content .= "<tr><td class=\"border02\">Name 3</td><td $MouseOver> $dat[NAME3]</td><td class=\"border02\">Kundengruppe</td><td $MouseOver> $dat[NAME]</td></tr>";
            $content .= "<tr><td class=\"border02\">Strasse</td><td $MouseOver> $dat[STRASSE]</td><td class=\"border02\">Selektion</td><td $MouseOver> $dat[GRUPPE]</td></tr>";
            $content .= "<tr><td class=\"border02\">PLZ / Ort</td><td $MouseOver> $dat[PLZ] $dat[ORT]</td><td class=\"border02\">Geburtstag</td><td $MouseOver> ".date_mysql2german($dat['KUN_GEBDATUM'])."</td></tr>";
            $content .= "<tr><td class=\"border02\">Telefon</td><td $MouseOver> $dat[TELE1]</td><td class=\"border02\">Telefon 2 </td><td $MouseOver> $dat[TELE2]</td></tr>";
            $content .= "<tr><td class=\"border02\">Telefax</td><td $MouseOver> $dat[FAX]</td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">Mobil</td><td $MouseOver> $dat[FUNK]</td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">eMail</td><td $MouseOver> <a href=\"mailto:$dat[EMAIL]\">$dat[EMAIL]</a></td><td class=\"border02\">eMail 2</td><td $MouseOver> <a href=\"mailto:$dat[EMAIL2]\">$dat[EMAIL2]</a></td></tr>";
            $content .= "<tr><td class=\"border02\">Internet</td><td $MouseOver> <a href=\"$dat[INTERNET]\" target=\"_blank\">$dat[INTERNET]</a></td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">Diverses</td><td $MouseOver> $dat[DIVERSES]</td><td></td></tr>";
            $content .= "<tr><td class=\"border02\">Info</td><td $MouseOver> ".nl2br($dat['INFO'])."</td><td></td></tr>";
            break;
        }
        $content .= "</table>";
    }
	}

// -------------------------------------------------------------------------
// SHOW_BLZ_SEARCH
// -------------------------------------------------------------------------
if (!empty($_REQUEST['show_blz_search'])) 
	{
	$keywords = addslashes($_REQUEST['show_blz_search']);

    $sql_query = 'select BLZ, BANK_NAME from BLZ where BLZ like \'%'.$keywords.'%\' or 
				 BANK_NAME like \'%'.$keywords.'%\' order by BLZ';

    $sql_result = mysqli_query($connect, $sql_query);
    $sql_count = mysqli_num_fields($sql_result);

    $content = "<table width=\"100%\"><tr>";

    // Überprüfen Headerzeile?
    for ($x=0; $x < $sql_count; $x++) {
        //$content .= "<td class=\"border02\">" . mysqli_field_name($sql_result, $x) ."</td>";
        $content .= "<td class=\"border02\">" . $x ."</td>";
    }
    $content .= "</tr><tr $MouseOver>";
    
    while($dat = mysqli_fetch_row($sql_result)) {
        foreach ($dat as $feld) {
            $content .= "<td >$feld</td>";
        }
        $content .= "</tr><tr $MouseOver>";
    }
    $content .= "</table>";
}

// -------------------------------------------------------------------------
// SHOW_JOUR_SEARCH
// -------------------------------------------------------------------------
if (!empty($_REQUEST['show_jour_search'])) 
	{
	$keywords = addslashes($_REQUEST['show_jour_search']);

    $sql_query = 'select REC_ID, ADDR_ID, VRENUM, QUELLE, KUN_NAME1, KUN_NAME2, KUN_NAME3, ORGNUM, PROJEKT, BSUMME, DATE_FORMAT(RDATUM,\'%d.%m.%Y\') as REDATUM, MA_ID 
				 from JOURNAL where VRENUM like \'%'.$keywords.'%\' or KUN_NUM like \'%'.$keywords.'%\'
				 or KUN_NAME1 like \'%'.$keywords.'%\' or KUN_NAME2 like \'%'.$keywords.'%\' or KUN_NAME3 like \'%'.$keywords.'%\' or PROJEKT like \'%'.$keywords.'%\' or ORGNUM like \'%'.$keywords.'%\'';      
    
    $sql_result = mysqli_query($connect, $sql_query);

/*    $sql_count = mysql_num_fields($sql_result);

    $content = "<table width=\"100%\"><tr>";


    for ($x=2; $x < $sql_count; $x++) {
        $content .= "<td class=\"border02\">" . mysql_field_name($sql_result, $x) ."</td>";
    }
*/

	$content = "<table width=\"100%\"><tr class=\"border02\"><td>RE-NR.</td><td>RE-TYP</td><td>NAME</td><td>REFERENZNR.</td><td>PROJEKT</td><td>BETRAG</td><td>DATUM</td><td>MITARBEITER-ID</td>";
	
    $content .= "</tr><tr $MouseOver>";
    
    while($dat = mysqli_fetch_row($sql_result))
    	{
    	$quelle = $dat[3];

    	$content .= "<td valign=\"top\"><a href=\"javascript:popupWindow('index.php?rec_id=$dat[0]')\">$dat[2]</a></td>";
    	$content .= "<td valign=\"top\">$journal_quelle[$quelle]</td>";
    	$content .= "<td valign=\"top\"><a href=\"".$_SERVER['PHP_SELF']."?show_adr_detail=$dat[1]\">$dat[4] $dat[5] $dat[6]</td>";
    	$content .= "<td valign=\"top\" nowrap>$dat[7]</td>";
		$content .= "<td valign=\"top\" align=\"right\">$dat[8]</td>";
    	$content .= "<td valign=\"top\" align=\"right\">$dat[9]</td>";
    	$content .= "<td valign=\"top\" align=\"right\">$dat[10]</td>";
    	$content .= "<td valign=\"top\" align=\"center\">$dat[11]</td>";
    	$content .= "</tr><tr bgcolor=\"#FFFFFF\" onMouseOver=\"this.bgColor='gold';\"onMouseOut=\"this.bgColor='#FFFFFF';\">";
    	} 
    $content .= "</tr></table>";

	$content .= "</table>";
	}

?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="ISO-8859-1">
<title><?php echo HEAD_TEXT; ?></title>
<link href="stylesheet.css" rel="stylesheet" type="text/css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','scrollbars=yes,resizable=yes,width=750,height=800')
} 
//--></script>
</head>
<body link="#000000" vlink="#000000" alink="#000000" leftmargin="0" topmargin="0">
<table width="100%" border="0" cellspacing="0" cellpadding="1">
  <tr>
    <td class="border01"><?php echo HEAD_TEXT ?></td>
    <td align="right" class="border01"><?php $today = time(); 
        echo $day_ger[Date('w',$today)] .', '. 
             date('j') .'. '. $month_ger[date('n',$today)-1] .' '.date('Y',$today); ?></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="4">
  <tr>
    <td width="30%" height="100%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td colspan="3" class="border02"><?php echo HEAD_PIM ?></td>
        </tr>
        <?php echo $pim_content; ?>
      </table>
      <br> </td>
    <td rowspan="2" class="table02" height="100%" valign="top"> 
    <IMG border="0" height="100%" src="dot.gif" width="1"></td>
    <td align="center" valign="top" class="font01" width="30%"> <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td class="border02"><?php echo HEAD_SEARCH ?></td>
        </tr>
      </table>
      <table width="200" border="0" cellspacing="5">
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr><form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <td>Kunden:          </td>
          <td><input type="text" name="show_adr_search" 
                value="<?php if (!empty($_REQUEST['show_adr_search'])) echo $_REQUEST['show_adr_search']; ?>"></td>
          <td><input type="submit" name="Submit" value="Suchen"></td>
        </form></tr>
        <tr><form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <td>Bank:          </td>
          <td><input type="text" name="show_blz_search" 
                value="<?php if (!empty($_REQUEST['show_blz_search'])) echo $_REQUEST['show_blz_search']; ?>"></td>
          <td><input type="submit" name="Submit2" value="Suchen"></td>
        </form></tr>
        <tr>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <tr><form name="form3" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <td>Artikel:          </td>
          <td><input type="text" name="show_art_search" 
                value="<?php if (!empty($_REQUEST['show_art_search'])) echo $_REQUEST['show_art_search']; ?>"></td>
          <td><input type="submit" name="Submit3" value="Suchen"></td>
        </form></tr>
        <tr><form name="form4" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <td>Rechnung:          </td>
          <td><input type="text" name="show_jour_search" 
                value="<?php if (!empty($_REQUEST['show_jour_search'])) echo $_REQUEST['show_jour_search']; ?>"></td>
          <td><input type="submit" name="Submit4" value="Suchen"></td>
        </form></tr>
      </table>
      </td>
    <td align="center"class="table02" height="100%" valign="top">
          <IMG border="0" height="100%" src="dot.gif" width="1">
     </td>
    <td align="right" valign="top" width="40%">
        <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr><td colspan="3" class="border02"><?php echo HEAD_BIRTHDAY ?></td></tr>
        <?php echo $birthday_content; ?>
        </table>
    </td>
  </tr>

  <tr>
  
  
    <td width="30%" valign="top" class="table02"> 
    <IMG border="0" height="1" src="dot.gif" width="100%"> 
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td class="border02"><?php echo HEAD_FAVORITE ?></td>
        </tr>
      <tr><td><?php echo $box_content; ?></td></tr></table>
      <IMG border="0" height="1" src="dot.gif" width="100%">
      <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td colspan="3" class="border02"><?php echo HEAD_PIM_TODO ?></td>
        </tr>
        
        <?php echo $pimtodo_content; ?></table><br>
        <IMG border="0" height="1" src="dot.gif" width="100%">
         <table width="100%" border="0" cellspacing="0" cellpadding="1">
        <tr>
          <td colspan="3" class="border02"><?php echo HEAD_NOTE ?></td>
        </tr>
        <?php echo $note_content; ?> </table></td>
        
    <td width="70%" colspan="3" valign="top" class="table02">
    <IMG border="0" height="1" src="dot.gif" width="100%">
    <table width="100%" border="0" cellspacing="2" cellpadding="1">
        <tr>
          <td class="border02"><?php echo HEAD_SEARCH_RES ?></td>
        </tr>
    </table><p></p>
      <?php echo $content; ?></td>
  </tr>
</table>
</body>
</html>
