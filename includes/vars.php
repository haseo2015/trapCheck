<?php 
// variabili
error_reporting(E_ERROR);

$conf['settings']['lctime']  = 'it-IT';
$conf['settings']['debug'] = false;
$conf['settings']['title'] = 'Gambling';
$conf['settings']['charset'] = 'utf-8';
$conf['settings']['prefisso_tabelle'] = 'g_';
$conf['settings']['site_redirect'] = '#';
$conf['settings']['attivo'] = 1;
$conf['settings']['date_ext'] = date('Y-m-d H:i:s');
$conf['settings']['date_short']  = date('Y-m-d');
$conf['settings']['time_expiring'] = 2; // questo valore viene moltiplicato per 60 e paragonato con il time

$conf['settings']['sezione'] =  isset($_GET['page']) ? $_GET['page'] : '';
$conf['settings']['pagina'] =  $sezione. ".php";
$conf['settings']['area'] = isset($_GET['area']) ? $_GET['area'] : '';
$conf['settings']['command'] =  isset($_GET['cmd']) ? $_GET['cmd'] :'';

$conf['status_partite']['pending'] = 0;
$conf['status_partite']['attiva'] = 1;
$conf['status_partite']['completata'] = 2;


// immagini
$conf['images']['thumb_dimension_small'] = 100;

// mail
$conf['mail']['mail_abilitata'] = false;
$conf['mail']['is_mail_smtp'] = false;
$conf['mail']['smtp_debug'] = 1; // 1 = errori e messaggi | 2 = solo messaggi
$conf['mail']['smtp_auth'] = true;
$conf['mail']['admin_email'] =  'fabio.monti@gmail.com';
$conf['mail']['admin_fullname'] = 'Admin'; 
$conf['mail']['prefix_subject'] = '';
$conf['mail']['email_from'] = '';
$conf['mail']['smtp_user'] = '';
$conf['mail']['smtp_password'] = '';
$conf['mail']['smtp_host'] = '';
$conf['mail']['smtp_port'] = 25;
$conf['mail']['smtp_secure'] = '';
$conf['mail']['mail_charset']  = 'utf-8';
$conf['mail']['form_subject']  = '';

// tabelle
$conf['tabelle']['utenti'] = $conf['settings']['prefisso_tabelle'].'utenti';
$conf['tabelle']['partite'] = $conf['settings']['prefisso_tabelle'].'partite';
$conf['tabelle']['livelli'] = $conf['settings']['prefisso_tabelle'].'livelli';
$conf['tabelle']['crediti'] = $conf['settings']['prefisso_tabelle'].'crediti_utente';
$conf['tabelle']['partite_utente'] = $conf['settings']['prefisso_tabelle'].'partite_utente';
$conf['tabelle']['livelli_partita'] = $conf['settings']['prefisso_tabelle'].'livelli_partita';
$conf['tabelle']['utenti_partita'] = $conf['settings']['prefisso_tabelle'].'utenti_partita';

//variabili globali
$GLOBALS['array_mesi'] = array("01"=>"Gennaio","02"=>"Febbraio","03"=>"Marzo","04"=>"Aprile","05"=>"Maggio","06"=>"Giugno","07"=>"Luglio","08"=>"Agosto","09"=>"Settembre","10"=>"Ottobre","11"=>"Novembre","12"=>"Dicembre");
$GLOBALS['range-records'] = array(5,10,20,50,100,1000);
$GLOBALS['giorni'] = array("Lu"=>"Luned&igrave;","Ma"=>"Marted&igrave;","Me"=>"Mercoled&igrave;","Gi"=>"Gioved&igrave;","Ve"=>"Venerd&igrave;","Sa"=>"Sabato","Do"=>"Domenica");

 // connessione
if ($_SERVER['SERVER_NAME'] == 'localhost'){
	//configurazione locale
	$conf['connection']['DBHost']  = 'localhost';
	$conf['connection']['DBUser'] = 'mobile_usr14';
	$conf['connection']['DBPassword'] = 'M0b1l3';
	$conf['connection']['DBName'] = 'gambling';
}else{
	//configurazione remota
	$conf['connection']['DBHost']  = 'localhost';
	$conf['connection']['DBUser'] = 'fabiomon_gambler';
	$conf['connection']['DBPassword'] = '@M=KSTf@$tX{';
	$conf['connection']['DBName'] = 'fabiomon_gambling';
}











