<?php

// LIBRERIA FUNZIONI ESSENZIALI
//
//############################################################################# 
// funzione per debuggare
function Trace($var,$file=__FILE__,$line=__LINE__,$function=__FUNCTION__,$class=__CLASS__){
	if (defined("DEBUG")) {
		$type = gettype($var);
		//echo $type;
		echo "<pre>";
		echo "Pagina:" . $file ."<br />";
		echo "Classe:" . $class . "<br/>";
		echo "Funzione:" . $function ."<br />";
		echo "Linea:" . $line ."<br />";
		
		
		switch($type){
			case "array":
				print_r($var);
			break;	
			case "boolean":
				var_dump($var);
			break;
			default:
				echo $var;
			break;	
		}
		echo "</pre><br />";	
	}
}

function redirect($toUrl=""){
	header("Location: ".$toUrl);
}

//#######################################################################################################
//##################################### FUNZIONI SULLE STRINGHE #########################################
//#######################################################################################################
function SessioneCasuale($chars=16){	
	$Stringa = "";
	for($I=0;$I<$chars;$I++){
		do{
			$N = Ceil(rand(48,122));
		}while(!((($N >= 48) && ($N <= 57)) || (($N >= 65) && ($N <= 90)) || (($N >= 97) && ($N <= 122))));
			
		
		$Stringa = $Stringa.Chr ($N);
	}
	return $Stringa;
}
//#######################################################
// Tagliare stringa a n caratteri senza troncare parole
//#######################################################
function TagliaStringa($stringa, $max_char, $continua=" [...]"){
	if(strlen($stringa)>$max_char){
		$stringa_tagliata=substr($stringa, 0,$max_char);
		$last_space=strrpos($stringa_tagliata," ");
		$stringa_ok=substr($stringa_tagliata, 0,$last_space);
		return $stringa_ok.$continua;
	}else{
		return $stringa;
	}
}
//##############################################################################
function clear_data($string,$entity_decode=NULL){
	$string = trim(stripslashes($string));
	if ($entity_decode) $string = html_entity_decode($string,ENT_QUOTES);
	return $string;
}
//#############################################################################
//cancellazione dati da un array
function unset_fields(&$array,$fields){
	foreach($fields as $field){
		unset($array[$field]);
	}
}
//############################################################################# 
//function ribaltaData
//preleva la data in formato europeo o americano con il separatore
// e la rigira nel formato desiderato 
function ribaltaData($strData,$limiter="-",$format="EA"){	
	//echo $strData;
	$strData_tmp = explode($limiter,$strData);
	//trace($strData_tmp);		
	$rev = array_reverse($strData_tmp);	
	$strData = implode("-",$rev);
	/*
	if ($format == "EA"){
		$dataGG = $strData_tmp[0];				
		$dataMM = $strData_tmp[1];
		$dataAAAA = $strData_tmp[2];
		$strData  = $dataAAAA."-".$dataMM."-".$dataGG;
	}else {		
		$dataGG = $strData_tmp[2];
		$dataMM = $strData_tmp[1];
		$dataAAAA = $strData_tmp[0];	
		$strData  = $dataGG."-".$dataMM."-".$dataAAAA;	
	}*/
	return $strData;
}
//##############################################################################
// function getLastDayOfMonth
function getLastDayOfMonth($month){		
	return date('t',strtotime($month));	
}
//##############################################################################
// function UpdateQueryString
// aggiorna il campo $campo della QUERY_STRING 
// se $valore non è passato elimina il campo dalla query_string
//
function UpdateQueryString($campo,$valore=NULL){
	$qs = $_SERVER['QUERY_STRING'];
	$var = explode('&', $qs);

	$var_array = array();
	foreach($var as $varOne) {
		$name_value = explode('=', $varOne);
		if($name_value[0] == $campo) {
			if($valore != NULL ) {
				$var_array[$name_value[0]] = $valore;
//echo "<hr>$valore<hr>";
			}
		} else {
			if(isset($name_value[1])){
				$var_array[$name_value[0]] = $name_value[1];
			}
		}
	}

	$qs = '';
	$delimiter = "";
	foreach($var_array as $key => $value) {
		$qs .= $delimiter.$key."=".$value;
		$delimiter = "&";
	}

	$_SERVER['QUERY_STRING'] = $qs;
//echo "<hr>$qs<hr>";
}
// #########################################################################
// function UpdateQueryStringArr
// aggiorna i campi $campi della QUERY_STRING 
// se $valori[$campo] non è passato elimina il campo dalla query_string
//
function UpdateQueryStringArr($campi,$valori=array()){
	foreach($campi as $campo){
		UpdateQueryString($campo,(isset($valori[$campo])?$valori[$campo]:''));
	}
}
//##############################################################################
function conformaNome(&$nome) {
	$nome = strtolower($nome);
	$nome = fileNameReplace($nome);
}
//##############################################################################
//
// fileNameReplace()
// prende in input:
// 	- $nomeFile
// 	- $cerca opzionale array chars da cercare
// 	- $sostituisci opzionale array char da sostituire
// se cerca e sostiutisci non vengono passati come parrametri 
// usa quelli di default
// restituisce il nome del file in cui i caratteri in $cerca sono sostituiti 
// con quelli in $sostituisci e gli eventuali char speciali sono eliminati 
//
function fileNameReplace( $nomeFile, $cerca = '', $sostituisci = ''){
	if ( !$cerca ){
		// simboli da rimpiazzare di default
		$cerca = array (" ","--","ú","ù","û","ü","á","à","ä","ã","å","è","é","ê","ë","í","ì","î","ï","ò","ó","ô","ö","õ","ß","æ","ç","ñ","ÿ","ý","&");
		$sostituisci = array ("-","-","u","u","u","u","a","a","a","a","a","e","e","e","e","i","i","i","i","o","o","o","o","o","ss","ae","c","n","y","y","-e-");
	}
	// sostituisce i chars da rimpiazzare
	$_nomeFile = str_replace($cerca,$sostituisci,$nomeFile);
	// echo $_nomeFile;
	// elimina i chars diversi da lettere e numeri rimasti
	// Remove all but letters and numbers from $string
	// $_nome = preg_replace("/[^a-zA-Z0-9\-\.]/", "", $_nomeFile);
	$_nome = $_nomeFile;
	return $_nome;
}
//####################################################################
// Function sanitize
// prepata la stringa per essere convertita in url friendly
function sanitize($string){
	$string = trim(strtolower(fileNameReplace($string)));	
	return $string;
}
function desanitize($string){
	$string = str_replace('-',' ',$string);	   
	return $string;
}

//####################################################################
// Function generaListaOtions
// prepata una stringa options per una select
function generaListaOtions($params = array()){
	$tabella = $params['tabella'];
	$campi = $params['campi'];
	$where = $params['where'];	
	$order = $params['order'];
	$limit = $params['limit'];
	$selezionato = $params['selected'];
	$_selezionato = explode(',',$selezionato);
	//print_r($_selezionato);  
	$num_record = 0;
	$dati = array();
	SelectTabelle($tabella, $campi,$where,$order,$limit,$num_record,$dati);
	if (DEBUG == "SELECT") trace($dati);
	foreach($dati as $dato){
			//var_dump(in_array($dato[$campi[0]],$_selezionato));			
			$sel = (in_array($dato[$campi[0]],$_selezionato)) ? 'selected':'';
			$html .= '<option value="'.$dato[$campi[0]].'" '.$sel.'>'.$dato[$campi[1]].'</option>';
	}
	return $html;	
}


//##############################################################################################
//##################################### FUNZIONI SUI RECORD #######################################
//##############################################################################################
// SelectTabelle()
// Prende in input:
// - $tabelle coinvolte
// - $where_cond (opzionale) filtro di selezione
// - $order_by_cond (opzionale) condizione di ordinamento
// Ritorna il cursore alle risorse
//
function SelectTabelle($tabelle, $campi, $where_cond="", $order_by_cond="",$limit="", &$num_record = '-1', &$dati="",$debugmode=false) {
	$funzione = __FUNCTION__;
//echo "<pre>";
//var_dump(debug_backtrace());
//echo "</pre>";
//var_dump($campi);

	$elenco_dati = "";

	$elenco_dati = "";
	if( $campi == "" || $campi == '*' ) {
		$elenco_dati = "*";
	} else {
		$elenco_dati = implode(",",$campi);
	}	
	
	$q_select = "SELECT $elenco_dati \n";
	$q_select .=  "FROM $tabelle";
	$q_select .= " $where_cond ";
	$q_select .= " $order_by_cond \n";
	$q_select .= " $limit\n";
//stampe di controllo
if ($debugmode) utils::trace("<br />$funzione q:<br />". $q_select . "<br/><br />", __FILE__, __LINE__,__FUNCTION__);

	$r_select=  mysql_query($q_select) or die(_MSG_ERRORE_FATALE_1_ .":<br />Funzione:". __FUNCTION__ . "<br />Linea:" . __LINE__ ."<br />File:". __FILE__ ."<br />QUERY:<br />" .$q_select . "<br /><br />Errore:<br />". mysql_error());

	if( $num_record != -1 ) $num_record = mysql_num_rows($r_select);
	
	if (!is_array($dati)){	
		return $r_select;
	}else{
		while ($f_select = mysql_fetch_assoc($r_select)){
			$dati[] = $f_select;
		}
		mysql_free_result($r_select);
//print_r($dati);
	}
}
//###########################################################
function InsertTabella($tabella, $dati, $sep ='"',$debugmode=false ) {
	
// echo $controllo;
//
	$funzione = __FUNCTION__;

	/*
echo "<pre>";
 echo "$funzione ";
 print_r($dati);
 echo "</pre>";
*/

	$elenco_campi = $elenco_valori = '';
	foreach($dati as $campo => $valore) {
		$elenco_campi .= $campo . ', ';
		
		// $elenco_valori .= trim($sep.addslashes($valore) . $sep).',' ;
		$elenco_valori .= trim($sep . $valore . $sep).',' ;		
		
	}
 //echo "$elenco_campi". $elenco_campi;
//echo "$elenco_valori" . $elenco_valori;

	unset($valore);
	unset($campo);

	$elenco_campi = substr($elenco_campi, 0, strlen($elenco_campi) - 2);
	$elenco_valori = substr($elenco_valori, 0, strlen($elenco_valori) - 1);
//echo $elenco_valori;	
//exit;	
	$q_insert = "INSERT INTO " . $tabella;
	$q_insert .= "(" . $elenco_campi .")";
	$q_insert .= " VALUES ";
	$q_insert .= "(".trim($elenco_valori).")";
 
 	// STAMPA DI CONTROLLO
if ($debugmode) utils::trace("<br />$funzione q:<br />". $q_insert . "<br/><br />", __FILE__, __LINE__,__FUNCTION__);

	$r_insert = mysql_query($q_insert) or die ("Errore nella query $query, mysql error " . mysql_error());

//echo "<pre>";
//echo "errore: ". mysql_errno() . "<br/>";
//echo "constante_err: ". ER_DUP_ENTRY . "<br/>";
//echo "</pre>";
	//
	// se una chiave univoca esisteva già lascio 
	// al chiamante la gestione dell'errore
	if((mysql_errno() != 0) && (mysql_errno() != ER_DUP_ENTRY)) {
	       die(_MSG_ERRORE_FATALE_1_ .":<br />Funzione:". __FUNCTION__ . "<br />Linea:" . __LINE__ ."<br />File:". __FILE__ ."<br />QUERY:" .$q_insert . "<br />Errore: ". mysql_error());
	}

	return mysql_insert_id();
}

//################################################################################################
// UpdateTabella()
// Prende in input:
// - $tabella da aggiornare
// - $dati (campo => valore)
// - $where_cond (opzionale) condizione di aggiornamento
// Ritorna il numero di righe aggiornate
//
function UpdateTabella($tabella, $dati, $where_cond, $sep_valore="'",$debugmode=false) {
	$funzione = __FUNCTION__;
 //echo "<pre>";
 //echo "$funzione($tabella, $dati, $where_cond) . <br\>";
 //var_dump(debug_backtrace() );
 //echo "</pre>";
	$elenco_dati = "";

	$sep = '';
	foreach($dati as $campo => $valore) {
		// $elenco_dati .= $sep.$campo . " = $sep_valore".addslashes($valore)."$sep_valore ";
		$elenco_dati .= $sep.$campo . " = $sep_valore".$valore."$sep_valore ";
		$sep = ',';
	}
	unset($valore);
	unset($campo);
//echo $elenco_dati;

	$q_update = "UPDATE $tabella SET ";
	$q_update .=  $elenco_dati ;
	$q_update .= (strstr($where_cond,"WHERE ")?" ":" WHERE ").$where_cond ;

//stampe di controllo
if ($debugmode) utils::trace("<br />$funzione q:<br />". $q_update . "<br/><br />", __FILE__, __LINE__,__FUNCTION__);;
//exit;
	$r_update=  mysql_query($q_update) or die(_MSG_ERRORE_FATALE_1_ .":<br />Funzione:". __FUNCTION__ . "<br />Linea:" . __LINE__ ."<br />File:". __FILE__ ."<br />QUERY:" .$q_update . "<br />Errore: ". mysql_error());

	$righe = mysql_affected_rows();

//stampe di con trollo
// echo "righe >>".$righe."<< <br/>";
// VERIFICARE CHE SE NON CAMBIANO I DATI RIGHE VALGA CMQ 1
	return $righe;
}

// DeleteTabella()
// Prende in input:
// - $tabella da aggiornare
// - $where_cond (opzionale) filtro da applicare ai dati
// Ritorna il numero di righe cancellate
//
function DeleteTabella($tabella, $where_cond,$debugmode=false) {

	$funzione = "DeleteTabella";
//echo "Sono in $funzione <br/>";
	$q_delete = "DELETE FROM $tabella \n";
	$q_delete .= " $where_cond ";
//stampe di controllo
if($debugmode) utils::trace("<br />$funzione q:<br />". $q_delete . "<br/><br />", __FILE__, __LINE__,__FUNCTION__);;

	$r_delete=  mysql_query($q_delete) or die(_MSG_ERRORE_FATALE_1_ . $funzione . _MSG_ERRORE_FATALE_2_ . mysql_error());
	$righe = mysql_affected_rows();
	return $righe;

}

//##########################################
function generaArrayCampiTabelle($array){
	$array_globale = array();
foreach($array as $tabella){
		${'array_'.$tabella} = array();
		$query = "select * from $tabella";
		$result = mysql_query($query) or die (mysql_error());
		//echo mysql_num_fields($result)."<br />";
		for ($i=0;$i<mysql_num_fields($result);$i++){
			array_push(${'array_'.$tabella}, mysql_field_name($result,$i));
		}
								
		//echo "<pre>";
		$array_globale[$tabella] = ${'array_'.$tabella};
		//echo "</pre>";
	}	
	return $array_globale;
}

//############################################################################# 
// funzione getValue()
//Restituisce un singolo valore dati campi e condizioni 
function getValue($_tabella,$_campo,$where_cond, &$messaggio){
	$funzione = __FUNCTION__ . "<br />";
	$q_generica = "SELECT $_campo FROM $_tabella $where_cond";
//if (DEBUG == "SELECT") 
//trace($q_generica,__FILE__,__LINE__);
	$i = 0;
	$result = mysql_query($q_generica) or die(_MSG_ERRORE_FATALE_1_ . $funzione . $q_generica . '<br />Error Message: ' . mysql_error() . "<br />
Errore numero:" . mysql_errno());
	$num_row = mysql_num_rows($result);	
	if ($num_row > 1) {
		$messaggio = "Ci sono $num_row valori per $_campo con queste condizioni $where_cond";
		return "";
	}
	$dato = mysql_fetch_array($result);
	//utils::trace($dato[$_campo]);
	return $dato[$_campo];
}

//######################################################################
// GetNextId()
// prende in input il nome di una tabella ($table) 
// restituisce il valore successivo del campo autoincrement
//
function GetNextId($table) {
	$r = mysql_query("SHOW TABLE STATUS LIKE '$table' ");	
	$row = mysql_fetch_array($r);
	$Auto_increment = $row['Auto_increment'];
	mysql_free_result($r);

	return($Auto_increment);
}		

// ###########################################################################
// function CreaId(()
// Prende in input:
//  - $dati (array dati record db)
//  - $chiave (array composizione della chiave)
// compone l'id con i campi chiave separati da -
// restituisce:
//  - $id (chiave composita separata da -)
//
function CreaId($dati,$chiave){
//echo "<pre>";
//Trace($dati,__FILE__,__LINE__);
//echo "</pre>";
	$id = $dati[$chiave[0]];
	for($i=1; $i<count($chiave);$i++) $id .= '-'. $dati[$chiave[$i]];
	return $id;
}

//#######################################################################################################
//##################################### FUNZIONI SUI FILES ##############################################
//#######################################################################################################

// function isImgToUpload()
// prende in input il nome di un campo di tipo file da uploadare e  restituisce:
// 	False se non è una img
// 	True se è un immagine 
// 	se passato carica un messaggio di errore
//
function isImgToUpload($image_file_to_upload, &$error = NULL) {
	// estenzioni consentite per le immagini
	$image_extensions_allowed = array('jpg', 'jpeg', 'png', 'gif');

	$filename = $_FILES[$image_file_to_upload]['tmp_name'];
	$file_info  = getimagesize($filename);
	if(empty($file_info))   { // No Image?
		$error = "Formato file non corretto.";
		return FALSE;
	} 
	$filename = $_FILES[$image_file_to_upload]['name'];
	$ext = findExts($filename);
	if(!in_array($ext, $image_extensions_allowed)) {
		$exts = implode(', ',$image_extensions_allowed);
		$error .= "Estensioni ammesse: ".$exts;
		return false;
	}
	return true;
}


//#######################################################################################################
// DeleteFile()
// Rimuove il file $dir/$file
//
function DeleteFile($dir, $file){
	$function = "DeleteFile";
	$cur_dir = getcwd();
	if(chdir( $dir )) {
		if( @unlink($file) ) 
			return (3);
		else
			return (1);
		chdir( $cur_dir );
	}
	return 2;
}
//##############################################################################
function removeFile($file, $dir) {
	$funzione = "removeFile";
	if(!$file) return 0;
//echo "$funzione: ($dir$file)<hr>";
	
	if (file_exists("$dir$file")) if(!@unlink("$dir$file")) return -1;

	return 0;
}


//#############################################################################################
function cleanFileList(){
	foreach($_FILES as $dati_file => $dati){
		if($dati['error'] == 4 ) {//file vuoto
				unset($_FILES[$dati_file]);
//echo "UNSET: $dati_file ";
//print_r($_FILES[$i]);
		}
		$i++;
	}
}

//##############################################################################
//
// UploadFile()
// prende in input:
// 	- $file (id del campo POST tipo file da uploadare)
// 	- $nomefilecalcolato (output per il nome generato)
// 	- $isImg( se il file da uploadare deve essere un'immagine)
// 	- $random (se il nome del file target deve essere prefissato da un numero random)
// 	- $tipi_ammessi (i tipi ammessi per il file da uploadare)
// ritorna i seguenti valori
//	-1 nessun file selezionato
//	0 OK
//	1 tipo non corretto
//	2 upload fallito

function UploadFile( $file,$target_dir, $random = false ) {

	$funzione = "UploadFile";
//echo $funzione ."<hr>";
	$_ret_val = 0;

	$tmp = $_FILES[$file]['tmp_name'];
	$name = $_FILES[$file]['name'];
	$tipo = $_FILES[$file]['type'];
	$ext = findExts($name);

	//	echo "<pre>$tmp $name $tipo $ext</pre>";
	// verifica se esiste la target dir altrimenti tenta di 0755crearla
	// controllo esistenza cartella
	if (!file_exists($target_dir)) {
		echo ("Path $target_dir upload errato<br />");
		return;
	}
	
	// esegue upload
	//creo un nome per il file di destinazione
	//se deve essere modificato il nome
	$_random = ($random) ? rand(1000000,9999999).'.': '';
	$name = $_random . $ext;
	if (DEBUG == "UPLOAD") echo "filename:" . $target_dir . $name.'<br />';
	//echo "$tmp,$target_dir . $name<br />";
	if(move_uploaded_file($tmp,$target_dir . $name)){
		//echo "caricato..";	
	} else {
		echo "file $name non caricato";
		return;	
	}
	if (DEBUG == "UPLOAD") echo 'uploading...';
	
	if (file_exists($target_dir. $name)){
		// upload riuscito
		$_ret_val =  $name ;
		//$nome_file_calcolato = $name;
	} else {
		// upload fallito
		$_ret_val =  2 ;
	}
//echo "<pre>";
//echo "<hr>$nome_file_calcolato<hr>";
if (DEBUG == "UPLOAD") echo $_ret_val . "<br />";
//echo "</pre>";
	return ( $_ret_val );
}
//##############################################################################
function findExts ($filename) { 
	$filename = strtolower($filename) ; 
	$exts = split("[/\\.]", $filename) ; 
	$n = count($exts)-1; 
	$exts = $exts[$n]; 
	return $exts; 
} 
//#######################################################################################################
//##################################### FUNZIONI MISCELLANEE ############################################
//#######################################################################################################
// function warning_box
// crea il box di avvertimento
// type = warning, info, success
// message = messaggio 
function warning_box($message='<strong>Well done!</strong> You successfully read this important alert message.',$type="success"){
	$html = '<div class="alert alert-'.$type.'">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	'.$message.'
	</div>';
	return $html;	
}
//##############################################################################
// function show_warning_box
// mostra il box di avvertimento
function show_warning_box($warning_box=NULL){
	$_SESSION['warning'] = $warning_box;
	echo $_SESSION['warning'];
	unset($_SESSION['warning']);
}
//##############################################################################
// function check_session
// controlla la sessione, se non loggato ti butta fuori
function check_session(){
  if (!$_SESSION['auth']) header("Location: logout.php");
}

// function check_session_expire
// controlla la sessione, se scade ti butta fuori
function check_session_expire(){
 if(time() > $_SESSION['expire']){
 		header("Location: logout.php");
	} 
}

//##########################################################################
// INVIO DELLA MAIL CON PHP MAILER
function sendPHPMAILER($dati,$charset='iso-8859-1'){
	//echo __FUNCTION__;
	//echo __DIR__;
	//var_dump(file_exists('classes/class.phpmailer.php')); 
	//exit;
	
	
	if (file_exists('classes/class.phpmailer.php'))
		require_once('classes/class.phpmailer.php');
	else 	
		echo "CLASSE PHPMAILER NON TROVATA";
	
	$subject = $dati['subject'];
	$to = $dati['to'];
	$toname = $dati['toname'];
	$body = $dati['mail_body'];
	
	$dati['cc'] = 'danielacribioli@globelife.com';
	$dati['ccname'] = 'Daniela Cribioli';
				
	$from = MAIL_FROM;	
	$mail = new PHPMailer(true); 		
	//
	try {	
	 $mail->SMTPDebug  = 1;		//1 = errori e messaggi , 2 = solo messaggi									 
	 if (is_mail_smtp){ 	  					
	  $mail->SMTPAuth   = true;                  
	  $mail->SMTPSecure = smtp_secure;           
	  $mail->Host       = smtp_host;      		 
	  $mail->Port       = smtp_port;            
	  $mail->Username   = smtp_user;  			 
	  $mail->Password   = smtp_password;              
	 }	 
	 //
	  $mail->IsHTML(true);
	  $mail->AddReplyTo($from, $name);
	  $mail->AddAddress($to, $toname);
	 // if (!empty($dati['cc'])) $mail->AddCC($dati['cc'], $dati['ccname']);
	  $mail->SetFrom($from, $from);
	  $mail->Subject = $subject;
	  $mail->Body = $body;
	  $mail->CharSet = $charset;	  
	  $esitoInvio = $mail->Send();
	  //var_dump($esitoInvio);
	  
	  echo (empty($dati['success-message'])) ? "<h1>Il suo messaggio &egrave; stato inviato correttamente</h1>\n" : $dati['success-message'];	   
	  //if (isset($dati['sendOK'])) header("location: " . $dati['sendOK']);
	} catch (phpmailerException $e) {
	  echo $e->errorMessage(); // messaggi di errore di PHPMailer	  
	   if (isset($dati['sendKO'])) header("location: " . $dati['sendKO']);
	} catch (Exception $e) {
	  echo $e->getMessage(); //Messaggi di errore di qualsiasi altra cosa!
	 	if (isset($dati['sendKO'])) header("location: " . $dati['sendKO']);
	}
	// FINE IF ESITO
}
//----------------------------------------
function detectSubDomain($ref){
	if (empty($ref)) $ref = $myUrl = $_SERVER['HTTP_REFERER'];	
	// estraggo il sottodominio
	$tmpUrl = str_replace('http://','',$ref);
	$url_parts = explode(".",$tmpUrl);
	$comitato = $url_parts[0];
	return $comitato;
}

//generaFileExcellOnFly 
// genera file excell al volo da un'array di dati
function generaFileExcellOnFly($dati,$categoria=''){
	
	$filename="prova.xls";	
	
	
	header ("Content-Type: application/vnd.ms-excel");
    header ("Content-Disposition: inline; filename=$filename");
	echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
		<html lang=it>
		<head>
		<title>Titolo</title>
		<style>
		</style>
		</head>
		<body>		
		<table width="500">';		
		foreach($dati[0] as $campo => $valore){
			echo '<tr>';
			echo '<th valign="top" align="left" width="300"><strong>'.str_replace("_"," ",$campo).'</strong></th>';
			echo '<td valign="top" align="left" width="500">';
			
			switch($campo){
				default:
					echo clear_data($valore);	
				break;	
			}
			if ($campo == 'allegati'){
				$tmp_allegati = explode("#",$valore);
				foreach($tmp_allegati as $k => $v){
					echo $v ."<br>";	
				}
			} else {
				
			}
			echo '</td>';
			echo '</tr>';
		}						
		echo '</table>
		</body></html>';	
}

//generaFileExcellOnFly 
// genera file excell al volo da un'array di dati
function generaFileExcell($tabella,$campi="*",$where_cond="",$order_by="",$limit="",$num_record=-1){
	//trace($dati);
	$dati = array();
	SelectTabelle($tabella,$campi,$where_cond,$order_by,$limit,$num_record,$dati);	
	generaFileExcellOnFly($dati,$tabella);	
		
}

 
include_once 'library_HTML.php';


?>
