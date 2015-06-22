<?php 
// FILE CONFIG
require_once "vars.php";
//#######################
// settings
foreach($conf['settings'] as $chiave => $valore):
	define($chiave,$valore,true);
endforeach;

// settings
foreach($conf['status_partite'] as $chiave => $valore):
	define($chiave,$valore,true);
endforeach;

// immagini
foreach($conf['images'] as $chiave => $valore):
	define($chiave,$valore,true);
endforeach;

// connessione
foreach($conf['connection'] as $chiave => $valore):
	define($chiave,$valore,true);
endforeach;

// tabelle
foreach($conf['tabelle'] as $chiave_tabella => $nome_tabella):
	define($chiave_tabella,$nome_tabella,true);
endforeach;

// mail
foreach($conf['mail'] as $chiave => $valore):
	define($chiave,$valore,true);
endforeach;
