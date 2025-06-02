<?php
/*
  $Id: config.php,v 1.14 2008/04/08

  config file for cao_dashboard 
  http://www.imc-media.com

  Copyright (c) 2003 Wolfgang Schwarz
*/

// define our database connection
$hostname = "localhost";
$database = "cao";
$username = "root";
$password = "password";

// define languages
define('HEAD_TEXT', 'CAO-INTRANET Dashboard');
define('HEAD_PIM', 'Termine');
define('HEAD_PIM_TODO', 'Aufgaben');
define('HEAD_NOTE', 'Notizen');
define('HEAD_SEARCH', 'Suchfunktionen');
define('HEAD_SEARCH_RES', 'Suchergebnis');
define('HEAD_BIRTHDAY', 'Geburtstage');
define('HEAD_FAVORITE', 'Favoriten');
define('MAIL_SUBJECT_BIRTH', 'Alles Gute zum Geburtstag');
define('MAIL_TEXT_BIRTH', '');

// define colours
define('BGCOLOR', '#FFFFFF');
define('OVCOLOR', '#FFCC00');

// define your Webshop Image-URL
define('IMAGE_URL', 'http://www.planetcomputer.de/shop/images/');

$journal_quelle = array("0","Angebot","Lieferschein","Rechnung","Einkauf","EK-Bestellung","6","7","8","9","10","Angebot (*)","Lieferschein (*)","Rechnung (*)","Einkauf (*)","EK-Bestellung (*)");

$journal_stadium = array(20 => "Vorkasse offen",
                         22 => "-> offen <-",
                         23 => "-> offen <-",
                         24 => "Nachnahme offen",
                         28 => "Paypal",
                         32 => "1x gemahnt",
                         72 => "Teilzahlung",
                         82 => "&Uuml;berweisung m. Skonto",
                         90 => "Vorkasse",
                         91 => "BAR erhalten",
                         92 => "&Uuml;berweisung BANK",
                         95 => "Scheck erhalten",
                         127 => "wurde storniert");


/* ROUTE URL
   Parameter %s 
       0: StartStrasse, 1: StartPLZ, 2: StartOrt
       3: ZielStrasse,  4: ZielPLZ,  5: ZielOrt
 */
//define('ROUTE_URL', 'http://www.viamichelin.com/viamichelin/deu/dyn/controller/ItiWGPerformPage?intItineraryType=1&strStartAddress=%s&strStartCP=%s&strStartCity=%s&strStartCityCountry=000000240&strDestAddress=%s&strDestCP=%s&strDestCity=%s&strDestCityCountry=000000240&intItineraryType=1');
define('ROUTE_URL', 'https://www.google.com/maps/dir/%s+%s+%s/%s+%s+%s');

// define your favorite
$box_content = "<a href=\"https://www.cao-faktura.de\" target=\"_blank\">CAO-Faktura</a><br>
                <a href=\"https://www.planetcomputer.de\" target=\"_blank\">PLANET COMPUTER</a><br>";


// german date
$day_ger = array("Sonntag", "Montag", "Dienstag", "Mittwoch", "Donnerstag", "Freitag", "Samstag");
$month_ger = array("Januar", "Februar", "M&auml;rz", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember");
?>