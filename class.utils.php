<?php
class utils {

	public static function checkConnessione(){
		if (!$sock = @fsockopen('www.google.com', 80, $num, $error, 5))  
			return 'OFFLINE';  
		else  
			return 'ONLINE';  	
	}

public static function renderData($dati, $output){
	//echo __FUNCTION__;
	//var_dump($data);
	//echo $output;
	$data = '';
	$numero_record = count($dati);
	switch($output){
			default:
			case 'object':
				$data = (object) $dati;
			break;
			case 'array':
				$data = $dati;
			break;
			case 'json':
				$data = json_encode($dati);
			break;
			case 'xml':
				$data .= "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>
		<urlset
      xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\"
      xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
      xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\">\n\n";
			
				if ($numero_record > 1):
				foreach($dati as $riga):
				$data .= "<node>\n";
					foreach($riga as $key => $value):
						$data .= "\t<".$key.">".$value."</".$key.">\n";
					endforeach;
				$data .= "</node>\n";	
				endforeach;
				
			break;
				else:
					foreach($dati[0] as $key => $value):
					$data .= "\t<".$key.">".$value."</".$key.">\n";
				endforeach;
				$data .= "</utente>\n\r";
			break;
				endif;
				
		}
	return $data;
}


///////////////////////////////////////////////////////////////////////
//////////// VARIE ////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////
function anti_injection($input){
    $pulito=strip_tags(addslashes(trim($input)));
    $pulito=str_replace("'","\'",$pulito);
    $pulito=str_replace('"','\"',$pulito);
    $pulito=str_replace(';','\;',$pulito);
    $pulito=str_replace('--','\--',$pulito);
    $pulito=str_replace('+','\+',$pulito);
    $pulito=str_replace('(','\(',$pulito);
    $pulito=str_replace(')','\)',$pulito);
    $pulito=str_replace('=','\=',$pulito);
    $pulito=str_replace('>','\>',$pulito);
    $pulito=str_replace('<','\<',$pulito);
    return $pulito;
}


public static function trace($var,$file=__FILE__,$line=__LINE__,$function=__FUNCTION__,$class=__CLASS__){
	$type = gettype($var);
	echo "<pre>";
		echo "Pagina:" . $file ."<br />";
		echo "Classe:" . $class . "<br/>";
		echo "Funzione:" . $function ."<br />";
		echo "Linea:" . $line ."<br />";
		switch($type){
			case "array":
			case "object":
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

	public static function redirect($toUrl=""){
		header("Location: ".$toUrl);
	}

	public static function SessioneCasuale($chars=16){
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
	public static function TagliaStringa($stringa, $max_char, $continua=" [...]"){
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
	public static function clear_data($string,$entity_decode=NULL){
		$string = trim(stripslashes($string));
		if ($entity_decode) $string = html_entity_decode($string,ENT_QUOTES);
		return $string;
	}
//#############################################################################
//cancellazione dati da un array
	public static function unset_fields(&$array,$fields){
		foreach($fields as $field){
			unset($array[$field]);
		}
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
	public static function fileNameReplace( $nomeFile, $cerca = '', $sostituisci = ''){
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
	public static function sanitize($string){
		$string = trim(strtolower(fileNameReplace($string)));
		return $string;
	}
	public static function desanitize($string){
		$string = str_replace('-',' ',$string);
		return $string;
	}
//##############################################################################################
//##################################### CRUD FUNCTIONS  #######################################
//##############################################################################################
// SelectTabelle()
// Prende in input:
// - $tabelle coinvolte
// - $where_cond (opzionale) filtro di selezione
// - $order_by_cond (opzionale) condizione di ordinamento
// Ritorna il cursore alle risorse
//
	public static function SelectTabelle($tabelle, $campi, $where_cond="", $order_by_cond="",$limit="", &$num_record = '-1', &$dati="",$debugmode=false) {
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
	public static function InsertTabella($tabella, $dati, $sep ='"',$debugmode=false ) {

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

		$r_insert = mysql_query($q_insert) or die ("Errore nella query $q_insert, mysql error " . mysql_error());

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
	public static function UpdateTabella($tabella, $dati, $where_cond, $sep_valore="'",$debugmode=false) {
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
	public static function DeleteTabella($tabella, $where_cond,$debugmode=false) {

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






}