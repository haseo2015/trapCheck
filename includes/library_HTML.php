<?php 
// libreria di funzionalità varie per HTML
//----------------------------------------------
/* GeneraPulsanti
genera i pulsanti per l'admin sulla base dei permessi dati all'utente v,e,d,a, oppure *
parametri:
tipo => edit-record,  add-record, change-status
id => id del record
tabella => nome della tabella
params  => array con vari parametri
*/
function generaPulsanti($tipo,$id,$tabella,$params=""){
			$debug_string  = "$tipo - $id - $tabella";
			switch($tipo){
				case 'edit-record':
					// check user permissions
					if (in_array("v",$_SESSION['permessi']) or in_array("*",$_SESSION['permessi']))
					$html .= '
					<a class="btn btn-success" href="view-'.$tabella.'_'.$id.'.html" data-rel="tooltip" title="visualizza anteprima">
						<i class="icon-eye-open icon-white"></i>  
						'.$params['label']['view'].'                                            
					</a>';
					if (in_array("e",$_SESSION['permessi'])  or in_array("*",$_SESSION['permessi']))
					$html .= '
					<a class="btn btn-info" href="edit-'.$tabella.'_'.$id.'.html" data-rel="tooltip" title="modifica record">
						<i class="icon-edit icon-white"></i>  
						'.$params['label']['edit'].'                                            
					</a>';
					if (in_array("d",$_SESSION['permessi'])  or in_array("*",$_SESSION['permessi']))
					$html .= '
					<a class="btn btn-danger" href="delete-'.$tabella.'_'.$id.'.html" onclick="return confirm(\'Operazione non reversibile. Continuare?\')" data-rel="tooltip" title="cancella record">
						<i class="icon-trash icon-white"></i> 
						'.$params['label']['detele'].' 
					</a>';
					if ($tabella == MACCHINARI)
					$html .= '
					<a class="btn btn-primary" href="pdf-'.$tabella.'_'.$id.'.html" data-rel="tooltip" title="visualizza anteprima pdf">
						<i class="icon-book icon-white"></i>                                         
					</a>';
					
				break;
				case 'add-record':
				 if (in_array("a",$_SESSION['permessi']) or in_array("*",$_SESSION['permessi']))
					 $html = '
						 <a class="btn btn-success" href="add-'.$tabella.'.html" data-rel="tooltip" title="aggiungi record">
								<i class="icon-edit icon-white"></i>  
								'.$params['label']['add'].'                                       
							</a>';
				break;	
				case 'status-change':
					$tag = (in_array("e",$_SESSION['permessi']) or in_array("*",$_SESSION['permessi'])) ? 'a' : 'span';
					$jString = 'doCmd(\'publish\',this)';
					$statusChangeTo = ($params['attivo'] == 1) ? 0 : 1;
					$link = (in_array("e",$_SESSION['permessi']) or in_array("*",$_SESSION['permessi'])) ? 'onclick="javascript:'.$jString.'"' : '';			
					$btnstyle = ($params['attivo'] == 1) ? 'btn-success' : 'btn-danger';
					$label = ($params['attivo'] == 1) ? 'Online' : 'Offline';
					$labelicon = ($params['attivo'] == 1) ? 'icon-ok' : 'icon-remove';
					$tooltip = ($params['attivo'] == 1) ? 'Pubblicato' : 'Non pubblicato';
					$html = '<'.$tag.' id="attivo_'.$id.'" class="btn '.$btnstyle.'" '.$link.' data-table="'.$tabella.'" data-id="'.$id.'" data-status="'.$statusChangeTo.'"><i class="'.$labelicon.' icon-white"></i> '.$label.'</'.$tag.'>';
				break;
			}
			return $html;
}
//----------------------------------------------
/* Genera intestazioni tabella 
genera le intestazioni di una tabella sulla base di un array passato
*/
function generaIntestazioniTabella($dati){
	$html = '<tr>';
	foreach($dati as $campo)
		$html .= '<th>'.$campo.'</th>';
	$html .= '</tr>';	
	return $html;		
}