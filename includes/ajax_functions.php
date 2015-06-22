<?php 
// AJAX FUNCTIONS
require_once 'config.php';
include_once "../classes/class.connection.php";
include_once "library_essentials.php";
$conn = new mysql();
$conn->connect();
//echo 'sadkjahlsk';
//exit;
//
if (isset($_POST['_function'])) $function_called = $_POST['_function'];
if (isset($_POST['params'])) $params = $_POST['params'];

//$function_called = 'UpdateRecord';
//$params = array("table"=>"","id"=>"084");

switch($function_called){
	case 'SelectDati':			
		$dati = array();
		foreach($params as $paramName => $paramValue)
			$$paramName = $paramValue;											
		
		//---------------------------------
		SelectTabelle($tabella,$campi,$where,$order,$limit,$num_record,$dati);							
		$data = '<option value="">'.$testo_select.'</option>';
		foreach($dati as $campo){	
			$sel = ($selezionato == $campo[$params['campi'][0]])?'selected="selected"' : '';						
			$data .= '<option value="'.$campo[$params['campi'][0]].'" '.$sel.'>'.htmlentities($campo[$params['campi'][1]]).'</option>';			
		}
		echo $data;
	break;
	case 'random_code':
		$random_code = SessioneCasuale(12);
		echo $random_code;
	break;
	case 'UpdateRecord':
		$tabella = $params['table'];
		$params['campi']['password_text'] = $params['campi']['password'];
		$md5Pass = md5($params['campi']['password']);
		$params['campi']['password'] = $md5Pass;
		$dati = $params['campi'];		
		//
		
		$where_cond = $params['where'];
		//print_r($params['campi']);
		$esito = UpdateTabella($tabella, $dati,$where_cond);
		echo $esito;
	break;
	case 'returnValue':
		$dati = array();
		foreach($params as $paramName => $paramValue)
			$$paramName = $paramValue;
		SelectTabelle($table,$campi,$where_cond,$order_by_cond,$limit,$num_record,$dati);
		if ($counter)
			echo $num_record;
		else
			echo $dati;	
	break;
	case 'Publish':
		$tabella = $params['table'];		
		$dati = $params['campi'];		
		$where_cond = $params['where'];
		//print_r($params);
		//exit;
		sleep(2);
		$esito = UpdateTabella($tabella, $dati,$where_cond);
		echo $esito;
	break;
	case 'Delete':
		$tabella = $params['table'];						
		$where_cond = $params['where'];
		//print_r($dati);
		sleep(2);
		$esito = DeleteTabella($tabella,$where_cond);
		echo $esito;	
	break;
	case 'DeleteFile':						
		$tabella = $params['table'];		
		$dati = $params['campi'];		
		$where_cond = $params['where'];
		$directory = $params['dir'];			
		$file2delete = '../'.$directory.$dati['allegati'];	
	
		
		if(file_exists($file2delete)){
			if ($dati['allegati'] != "placeholder.jpg")
			//unlink($file2delete);
			echo 1;	
		} else {
			echo -1;	
		}
		
	break;
	
}





//echo "inizializzato";
