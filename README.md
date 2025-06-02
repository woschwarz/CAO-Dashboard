# CAO-Dashboard

Das Script entstand im Jahr 2003 um über den Browser Daten von CAO-Faktura abzufragen. Die letzte Funktionserweiterung fand 2008 statt. Die Weiterentwicklung habe ich schon vor sehr langer Zeit eingestellt. Der Programmierstil mag inzwischen etwas old school sein, aber es funktioniert. Und obwohl ich CAO-Faktura seit vielen Jahre nicht mehr verwende, bietet mir das Script weiterhin Zugriff auf alte Kunden- und Rechnungsdaten. 


## Dashboard für CAO v1.14 beta 

Das CAO Dashboard dient zum schnellen Zugriff auf Kundendaten, Artikel, Termine und Aufgaben. Zusätzlich hat man die nächsten Geburtstage der Kunden im Überblick.


### Voraussetzungen:

Lokaler Webserver (z.B. Apache/Nginx)  
PHP mit MySQLi Erweiterung  
MySQL/MariaDB Datenbank  
CAO-Faktura (http://www.cao-faktura.de) ohne das machts keinen Sinn  
etwas PHP Kenntnisse von Vorteil


## Installation:

Alle Dateien in einen Unterordner auf den Webserver kopieren. Bei einer Synology Diskstation könnte dies z.B. /web/cao sein.

Bearbeite die config.php mit einem Texteditor und passe folgende Einträge an den eigenen Datenbankserver an: 

```
$hostname = "localhost";  // Datenbankserver-Adresse z.B. 192.168.0.20  
$database = "cao";        // CAO-Datenbankname  
$username = "root";       // Benutzername  
$password = "password";   // Passwort  
```

Danach den Browser starten und die Adresse des Webservers eingeben (z.b. http://192.168.0.20/cao/index.php)

Stelle sicher das die Daten nur über das lokale Netzwerk und nicht über das Internet abgefragt werden können!


## Features:
- simples PHP Script
- Anzeige der Termine
- Anzeige der Geburtstage
- Anzeige der Notizen
- Anzeige der Aufgaben
- Favoriten können selbst festgelegt werden
- Suchfunktion für Kunden Adressen
- Suchfunktion nach BLZ oder Bankname
- Suchfunktion für Artikel
- Suchfunktion für Rechnungen

## Historie:
1.00
Erste Version

1.02
Fehlerbehebung:  
- Tabellennamen für Linux (Case Sensitive)

1.10
Neue Features:  
- Artikelsuche  
- Artikeldetails  
- Kundendetails  
- Routenplaner  

Fehlerbehebung:  
- richtige Sortierung bei Termin und Geburtstag

1.11
- Queries überarbeitet  
- Funktionalität mit register_globals=off implementiert  
- Funktion zur Routen-Suche überarbeitet  
- Funktionen in die Datei functions.php ausgelagert  

1.12
- Anpassung an CAO 1.2.6.x
- Historie bei Artikel und Adressen

1.13
- Anpassung an CAO 1.4.x.x (keine Kompatibilität zu 1.2.x.x)
- Rechnungen können ausgedruckt werden (HTML-Template kann selbst angepasst werden)
- Shopartikel werden gekennzeichnet

1.14
- Umstellung der MySQL-API auf MySQLi
- Umstellung auf Google Maps Routenplaner


## Mögliche Fehler

Sollte beim Aufruf der Seite ein derartige Fehlermeldung erscheinen:

`Warning: mysqli_fetch_array() expects parameter 1 to be mysqli_result, bool given in /www/cao/index.php on line 155`

Dann könnte es daran liegen, dass CAO bei Tabellennamen und Tabellenspalten GROSSBUCHSTABEN verwendet. Auf einem Windows System spielt dass keine Rolle. Sollte aber z.B. die Datenbank auf einen Linux Server umgezogen sein, dann könnte die Fehlermeldung daher kommen das hier strikt zwischen Groß- und Kleinschreibung unterschieden wird. Möglicherweise läßt sich das Problem dadurch beben, dass in dem Script die Tabellennamen in Kleinbuchstaben geändert werden, oder der Server-Parameter `lower_case_table_names` angepasst wird.

## Anmerkung:

Dieses Script dient dazu, um z.B. mal schnell die Telefonnummer eines Kunden herauszufinden ohne CAO starten zu müssen. Es können nur Daten abgefragt aber nicht geändert werden, es kann somit CAO-Faktura nicht ersetzen. Benutzung erfolgt auf eigene Gefahr.