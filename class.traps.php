<?php


session_start();
error_reporting(E_ERROR);
class traps {
    protected  $dbhost;
    protected  $dbuser;
    protected  $dbpass;
    protected  $dbname;
    protected  $EXTdbhost;
    protected  $EXTdbuser;
    protected  $EXTdbpass;
    protected  $EXTdbname;
    protected $service; // identificativo del servizio
    var $token; // la stringa json da codificare
    protected $tk;
    protected $data;
    protected $conn;
    protected $db;
    protected $token_username;
    protected $token_password;
    protected $usedRemains;
    var $order;
    var $where_cond;
    var $filters;
    var $limit;


    function __construct(){
        //echo __CLASS__;
        include "class.utils.php";
        $this->dbhost="localhost";
        $this->EXTdbhost= "localhost";
        // echo "SERVER: " . $_SERVER['HTTP_HOST'];
        if ($_SERVER['HTTP_HOST'] == 'localhost'):
            $this->dbuser="root";
            $this->dbpass="admin";
            $this->dbname="derat_database";
            $this->EXTdbuser = "derat_admin";
            $this->EXTdbpass = "123456";
            $this->EXTdbname = "derat_central_admin";
        else:
            $this->dbuser="fabiomon_derat";
            $this->dbpass="By7IWkC?gmvx";
            $this->dbname="fabiomon_derat";
            $this->EXTdbuser = "fabiomon_derat";
            $this->EXTdbpass = "By7IWkC?gmvx";
            $this->EXTdbname = "fabiomon_derat_central_admin";
        endif;
        $this->data = array();
        $this->conn = "";
        $this->db = "";
        $this->service = "";
        $this->token_username = null;
        $this->token_password = null;
        $this->usedRemains = null;
        $this->filters = "";
        $this->order = "ID ASC";
        // check passaggio token
        if (!isset($_GET['token'])):
            traps::writeXML('<error><![CDATA[ko | token mancante]]></error>');
            return;
        endif;
        // setting token var
        $this->token = isset($_GET['token']) ? (array) json_decode(trim($_GET['token'])) : "";

        // check correct format of json string {"key":"value"}
        //echo count($this->token);
        if (count($this->token) < 1):
            traps::writeXML('<error><![CDATA[ko | formato JSON errato]]></error>');
            return;
        endif;

        $this->init();
    }

    function __destruct(){
        //session_unset();
        //session_destroy();
    }

    // inizializzazione sistema
    private function init() {
        //echo __FUNCTION__ . "<br>";
        $this->getTokenData();
        $this->getService();
    }
    //#######################################################################
    //################### CONNESSIONI AI DATABASE ###########################
    public function connect(){
        $this->conn = @mysql_connect($this->dbhost,$this->dbuser,$this->dbpass) or die ("parametri di connessione errati: " .mysql_error());
        $this->db = mysql_select_db($this->dbname,$this->conn) or die("Errore nella selezione del db: " . mysql_error());
    }

    public function disconnect(){
        mysql_close($this->conn);
    }

    public function getTokenData()
    {
         //echo "<hr>" . __FUNCTION__ . '<br>';
        $this->data = isset($this->token['data']) ? (array)$this->token['data'] : null;
        $this->service = $this->token['service'];
    }

    public function getService()
    {
        if ($this->checkAuth()) {
            $this->switchOperationsService($this->service);
        } else {
            traps::writeXML('<error><![CDATA[session expired]]></error>');
        }
    }
    private function switchOperationsService()
    {
        //echo __FUNCTION__;
        switch ($this->service) {
            case 'get_traps':
                $this->getTrapsData();
                break;
            case 'add_traps':
                $this->create_traps();
                break;
            case 'edit_traps':
            case 'edit_traps':
                $this->edit_traps();
                break;
            case 'delete_traps':
            case 'delete_traps':
                $this->delete_traps();
                break;

            default:
                traps::writeXML('<error><![CDATA[ko | Service error]]></error>');
                break;
        }
    }

    /* controllo autenticazione dopo il login */
    private function checkAuth()
    {
        //if (isset($_SESSION['customer']));
        //1return (isset($_SESSION['user']) AND $_SESSION['user']['auth'] === true) ? true : false;
        //if (isset($_SESSION['customer']['auth'] OR $_SESSION['user']['auth']);
        return true;
    }
//------------
    public function getWhat()
    {

        $dataArray = (array)$this->token['data'];
        $this->connect();
        $tabella = $dataArray['table'];
        $campi = '*';
        $where = $this->getFilters();
        $order = $this->getOrder();
        $limit = $this->getLimit();
        $records = 0;
        $dati = array();
        utils::SelectTabelle($tabella, $campi, $where, $order, $limit, $records, $dati, false);
        //utils::trace($dati);
        $stringData = "<items found=\"" . count($dati) . "\">\n";
        foreach ($dati as $record) {
            $stringData .= "    <item>\n";
            foreach ($record as $campo => $valore) {
                $stringData .= "     <" . $campo . "><![CDATA[" . utils::clear_data($valore) . "]]></" . $campo . ">\n";
            }
            $stringData .= "    </item>\n";
        }
        $stringData .= "</items>\n";
        traps::writeXML($stringData);
    }


    public function getFilters()
    {
        $filtersArray = (array)$this->token['filters'];
        $filter = "";
        //traps::trace($filtersArray);
        if (count($filtersArray) > 0):
            $this->filters = 'WHERE ';
            foreach ($filtersArray as $filterKey => $filterValue) {
                //echo $filterKey . "----". strrpos($filterValue,'%')."<br>";
                // controllo multivalore (IN STATEMENT)
                if ((strrpos($filterValue, '|') > 0)){
                        $filter .= $filterKey . ' IN ('.str_replace("|",",",$filterValue).')';
                } else
                // controllo per campi LIKE
                if (strrpos($filterValue, '%') > 0) {
                    $filter .= $filterKey . ' LIKE "' . $filterValue . '" AND ';
                    $filter = substr($filter, 0, -4);
                } else {
                    $filter .= $filterKey . '= "' . $filterValue . '" AND ';
                    $filter = substr($filter, 0, -4);
                }

            }
            //echo $this->filters . $filter;
            return $this->filters . $filter;
        else:
            $this->filters = '';
        endif;

    }

    public function getOrder()
    {
        $orderArray = (array)$this->token['order'];
        //utils::trace($orderArray);
        if (!empty($orderArray)) {
            $orderString = ' ORDER BY ';
            foreach ($orderArray as $campo => $valore)
                $orderString .= $campo . ' ' . $valore . ', ';
        }
        $orderString = substr($orderString, 0, -2);
        return $orderString;
    }

    public function getLimit()
    {
        $limitArray = (array)$this->token['limit'];
        //utils::trace($limitArray);
        if (!empty($limitArray)) {
            $limitString = 'LIMIT ' . $limitArray['start'] . ', ' . $limitArray['pager'];
        }

        return $limitString;
    }


    public static function writeXML($string)
    {
        $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
        $xml .= $string;
        echo $xml;
    }

//########################################################################################
// CRUD TRAPS
    private function getTrapsData(){

        $campi = array('dr_traps.id,
                        dr_traps.trap_id,
                        dr_traps.trap_name,
                        dr_traps.customer_id,
                        dr_traps.address,
                        dr_traps.citta,
                        dr_traps.latitude,
                        dr_traps.longitude,
                        dr_traps.x,
                        dr_traps.y,
                        dr_traps.product_id,
                        dr_traps.covered_area_id,
                        dr_traps.notes,
                        dr_products.product_name,
                        dr_customers.customer_name,
                        dr_trap_type.type_name,
                        dr_trap_status.trap_state_name,
                        dr_trap_groups.trap_group_name');
        $tabella = 'dr_traps
			        LEFT JOIN dr_products ON dr_traps.product_id = dr_products.id
        			LEFT JOIN dr_customers ON dr_traps.customer_id = dr_customers.id
                    LEFT JOIN dr_trap_type ON dr_traps.trap_type = dr_trap_type.id
                    LEFT JOIN dr_trap_status ON dr_traps.trap_status = dr_trap_status.id
                    LEFT JOIN dr_trap_groups ON dr_traps.trap_group_id = dr_trap_groups.id';
        $where = $this->getFilters();
        $order = $this->getOrder();
        $limit = $this->getLimit();
        $records = 0;
        $dati = array();
        $this->connect();
        utils::SelectTabelle($tabella, $campi, $where, $order, $limit, $records, $dati, false);
        $this->disconnect();
        if (count($dati)>0){
            $stringData = "<traps found=\"" . count($dati) . "\">\n";
            foreach ($dati as $record) {
                $stringData .= "    <trap>\n";
                foreach ($record as $campo => $valore) {
                    $stringData .= "     <" . $campo . "><![CDATA[" . utils::clear_data($valore) . "]]></" . $campo . ">\n";
                }
                $stringData .= "    </trap>\n";
            }
            $stringData .= "</traps>\n";
            traps::writeXML($stringData);
        } else {
            traps::writeXML('<message><![CDATA[no results]]></message>');
        }
    }

// function create trap
// campi obligatori: "customer_id":"", "address":"", "trap_type":"", "trap_status":"", "product_id":"", "covered_area_id":"", "trap_group_id":""
    private function create_traps()
    {
        $this->connect();
        //utils::trace((array)$this->token['data']);
        $dati = (array)$this->token['data'];


        $_table = 'dr_traps';
        $tabella = $_table;

        //if (in_array("C", $_SESSION['user']['permissions']) or in_array("*", $_SESSION['user']['permissions']) or $_SESSION['customer']['auth'] === true) {

        foreach ($dati as $record){
            $_dati = (array) $record;
            //utils::trace($_dati);

            $result = utils::InsertTabella($tabella, $_dati);

            if ($result > 0) {
                traps::writeXML('<message><![CDATA[ok | trappola creata]]></message>');
            } else {
                traps::writeXML('<error><![CDATA[ko | Errore creazione trapspola]]></error>');
            }
        }


        //} else {
           // traps::writeXML('<error><![CDATA[ko | no privileges]]></error>');
        //}
        $this->disconnect();
    }

    private function edit_traps()
    {
        $this->connect();
        //utils::trace((array)$this->token['data']);
        $dati = (array)$this->token['data'];
        //if (!empty($dati['pwd'])) $dati['pwd'] = md5($dati['pwd']);
        //utils::trace($dati);
        $_table = 'dr_traps';
        $tabella = $_table;
        $campi = $dati;
        $where = $this->getFilters();
        //echo $tabella;
        //utils::trace($campi);
        //echo $where;
        if (in_array("E", $_SESSION['user']['permissions']) or in_array("*", $_SESSION['user']['permissions']) or $_SESSION['customer']['auth'] === true) {
            $result = utils::UpdateTabella($tabella, $campi, $where);
            //echo $result;
            if ($result > 0) {
                traps::writeXML('<message><![CDATA[ok | '.$result.' trappole modificate]]></message>');
                //echo "ok | user modificato";
            } else {
                traps::writeXML('<error><![CDATA[ko | '.$result.' trappole non modificate   ]]></error>');
                //echo "ko | user non modificato";
            }
        } else {
            traps::writeXML('<error><![CDATA[ko | no privileges]]></error>');
        }
        $this->disconnect();
        exit();
    }

    private function delete_traps()
    {
        $this->connect();
        $_table = 'dr_traps';
        $tabella = $_table;
        $where = $this->getFilters();
        //echo $where;
        // utils::trace($_SESSION['user']['permissions']);
        //if (in_array("D", $_SESSION['user']['permissions']) or in_array("*", $_SESSION['user']['permissions']) or $_SESSION['customer']['auth'] === true) {
            $result = utils::DeleteTabella($tabella, $where);
            //echo $result;
            if ($result > 0) {
                traps::writeXML('<message><![CDATA[ok | trappola/e cancellata/e]]></message>');
                //echo "ok | user cancelato";
            } else {
                traps::writeXML('<error><![CDATA[ko | trapola non cancellata]]></error>');
                //echo "ko | user non cancellato";
            }
        /*} else {
            traps::writeXML('<error><![CDATA[ko | no privileges]]></error>');
        }*/

        $this->disconnect();
        exit();
    }



}



$t = new traps();