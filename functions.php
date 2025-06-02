<?php
/*
  $Id: functions.php,v 1.14 2008/04/08

  functions for cao_dashboard 
  http://www.imc-media.com

  Copyright (c) 2003 Wolfgang Schwarz
*/

// date_mysql2german wandelt ein MySQL Datum (ISO-Date) in ein traditionelles deutsches Datum.
function date_mysql2german($datum) {
    list($jahr, $monat, $tag) = explode("-", $datum);

    return sprintf("%02d.%02d.%04d", $tag, $monat, $jahr);
}

// date_german2mysql wandelt ein traditionelles deutsches Datum nach MySQL (ISO-Date).
function date_german2mysql($datum) {
    list($tag, $monat, $jahr) = explode(".", $datum);

    return sprintf("%04d-%02d-%02d", $jahr, $monat, $tag);
}

// timestamp_mysql2german wandelt ein MySQL-Timestamp in ein traditionelles deutsches Datum um.
function timestamp_mysql2german($t) {
    return sprintf("%02d.%02d.%04d",
                    substr($t, 8, 2),
                    substr($t, 6, 2),
                    substr($t, 0, 4));
}

// Routenberechnung
function route_berechnen($End_Str, $End_PLZ, $End_Ort) {
 
    global $connect;
 
    $Start_Str = "";
    $Start_PLZ = "";
    $Start_Ort = "";

    // Strasse, PLZ und Ort von erster Firma falls nicht definiert
    if (empty($Start_Ort)) 
		{
        $query = 'SELECT STRASSE, PLZ, ORT FROM FIRMA LIMIT 0,1';

        $rs = mysqli_query($connect, $query);
        list($Start_Str, $Start_PLZ, $Start_ORT) = mysqli_fetch_row($rs);
        mysqli_free_result($rs);
    	}

    $Route = sprintf(ROUTE_URL, $Start_Str, $Start_PLZ, $Start_Ort, $End_Str, $End_PLZ, $End_Ort);
    return $Route;
}

// Zeigt Verkaufs-Historie zum ausgewählten Kunden an
function adr_historie($ADDR_ID){

global $journal_quelle, $journal_stadium, $connect; 

$sql_query_show_adr_historie = "select REC_ID,QUELLE,VRENUM,VLSNUM, DATE_FORMAT(RDATUM,'%d.%m.%Y') as RDATUM,LDATUM,
KUN_NAME1,KUN_NAME2,KUN_NAME3,KUN_ANREDE, ADDR_ID,KFZ_ID,KM_STAND,NSUMME,MSUMME,BSUMME, STADIUM,PROJEKT,ORGNUM,WAEHRUNG,
MWST_0,MWST_1,MWST_2,MWST_3, MA_ID from JOURNAL where ADDR_ID = $ADDR_ID";

$sql_result = mysqli_query($connect, $sql_query_show_adr_historie);
$sql_count = mysqli_num_fields($sql_result);

$content1 = "<table width=\"100%\"><tr class=\"border02\"><td>QUELLE</td><td>DATUM</td><td>BELEG</td>
            <td>PROJEKT</td><td>STADIUM</td><td>BETRAG</td><td>BEZAHLT</td><td>M</td>";
            

while($dat = mysqli_fetch_array($sql_result))
	{
	$quelle = $dat['QUELLE'];
	$stadium = $dat['STADIUM'];

	$content1 .= "</tr><tr bgcolor=\"#FFFFFF\" onMouseOver=\"this.bgColor='gold';\"onMouseOut=\"this.bgColor='#FFFFFF';\">";
	$content1 .= "<td valign=\"top\">$journal_quelle[$quelle]</td>";
	$content1 .= "<td valign=\"top\">$dat[RDATUM]</td>";
	$content1 .= "<td valign=\"top\"><a href=\"javascript:popupWindow('index.php?rec_id=$dat[REC_ID]')\">$dat[VRENUM]</a></td>";
	$content1 .= "<td valign=\"top\">$dat[PROJEKT]</td>";
	$content1 .= "<td valign=\"top\">$journal_stadium[$stadium]</td>";
	$content1 .= "<td valign=\"top\" align=\"right\">".number_format($dat['BSUMME'],2)."</td>"; 
	//$content1 .= "<td valign=\"top\" align=\"right\">".number_format($dat['IST_BETRAG'],2)."</td>"; 
	//$content1 .= "<td valign=\"top\" align=\"center\">$dat[MAHNSTUFE]</td>";
}
$content1 .= "</tr></table>";

return $content1;
}

// Zeigt Verkauf-Historie zum ausgewählten Artikel an
function art_historie($ARTIKEL_ID){

global $journal_quelle, $connect;

$sql_query = "select JOURNAL_ID, JOURNALPOS.QUELLE, ARTIKEL_ID, JOURNALPOS.VRENUM, BEZEICHNUNG, MENGE, EPREIS,
             RABATT, JOURNAL.REC_ID, DATE_FORMAT(JOURNAL.RDATUM, '%d.%m.%Y') as RDATUM, JOURNAL.ADDR_ID, JOURNAL.KUN_NUM,
             JOURNAL.KUN_NAME1, JOURNAL.KUN_NAME2, JOURNAL.KUN_NAME3, JOURNAL.WAEHRUNG, LDATUM, JOURNAL.VLSNUM 
             from JOURNALPOS, JOURNAL where ARTIKEL_ID = $ARTIKEL_ID and JOURNALPOS.JOURNAL_ID = JOURNAL.REC_ID";

$sql_result = mysqli_query($connect, $sql_query);
$sql_count = mysqli_num_fields($sql_result);

$content1 = "<table width=\"100%\"><tr class=\"border02\"><td>QUELLE</td><td>DATUM</td><td>BELEG</td>
            <td>KD-NR.</td><td>KUNDE</td><td>MENGE</td><td>EPREIS</td>";

            
while($dat = mysqli_fetch_array($sql_result))
	{
	$quelle = $dat['QUELLE'];

	$content1 .= "</tr><tr bgcolor=\"#FFFFFF\" onMouseOver=\"this.bgColor='gold';\"onMouseOut=\"this.bgColor='#FFFFFF';\">";
	$content1 .= "<td valign=\"top\">$journal_quelle[$quelle]</td>"; 
	$content1 .= "<td valign=\"top\">$dat[RDATUM]</td>"; 
	$content1 .= "<td valign=\"top\"><a href=\"javascript:popupWindow('index.php?rec_id=$dat[REC_ID]')\">$dat[VRENUM]</a></td>"; 
	$content1 .= "<td valign=\"top\"><a href=".$_SERVER['PHP_SELF']."?show_adr_detail=".$dat['ADDR_ID'].">$dat[KUN_NUM]</td>";
	$content1 .= "<td valign=\"top\"><a href=".$_SERVER['PHP_SELF']."?show_adr_detail=".$dat['ADDR_ID'].">$dat[KUN_NAME1] $dat[KUN_NAME2] $dat[KUN_NAME3]</td>";
	$content1 .= "<td valign=\"top\" align=\"right\">".number_format($dat['MENGE'],1)."</td>"; 
	$content1 .= "<td valign=\"top\" align=\"right\">".number_format($dat['EPREIS'],2)."</td>"; 
	}

$content1 .= "</tr></table>";

return $content1;
}
?>