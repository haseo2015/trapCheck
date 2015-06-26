<?php
session_start();
error_reporting(E_ERROR);
class products {
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
            products::writeXML('<error><![CDATA[ko | token mancante]]></error>');
            return;
        endif;
        // setting token var
        $this->token = isset($_GET['token']) ? (array) json_decode(trim($_GET['token'])) : "";

        // check correct format of json string {"key":"value"}
        //echo count($this->token);
        if (count($this->token) < 1):
            products::writeXML('<error><![CDATA[ko | formato JSON errato]]></error>');
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
            products::writeXML('<error><![CDATA[session expired]]></error>');
        }
    }
    private function switchOperationsService()
    {
        //echo __FUNCTION__;
        switch ($this->service) {
            case 'get_products':
                $this->getProducts();
                break;
            case 'add_products':
                $this->create_products();
                break;
            case 'edit_products':
            case 'edit_products':
                $this->edit_products();
                break;
            case 'delete_products':
            case 'delete_products':
                $this->delete_products();
                break;
            case 'get_history_products':
                $this->getHistoryTraps();
                break;
            default:
                products::writeXML('<error><![CDATA[ko | Service error]]></error>');
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
        products::writeXML($stringData);
    }


    public function getFilters()
    {
        $filtersArray = (array)$this->token['filters'];
        $filter = "";
        //utils::trace($filtersArray);
        if (count($filtersArray) > 0):
            $this->filters = 'WHERE ';
            foreach ($filtersArray as $filterKey => $filterValue) {
                //echo $filterKey . "----". strrpos($filterValue,'%')."<br>";
                //echo $filterKey;
                switch ($filterKey){
                    case 'date_comp':
                    //echo "data comparsion";
                    $dataArray = (array) $filterValue;
                    $dataValue = $dataArray[key($dataArray)];
                    //utils::trace($dataArray);

                    foreach ($dataArray as $dataKey => $data_value){
                        $filter .= '('. $dataKey;
                        if ((strrpos($data_value, '|') > 0)){
                            //echo '2 date<br>';
                            $_date = explode("|",$data_value);
                            $filter .= ' BETWEEN "' . $_date[0] . '" AND "' . $_date[1] .'"';
                        } else {
                            //echo '1 data<br>';
                            $filter .= ' => "' . $data_value .'"';
                        }
                        $filter .= ') AND ';
                        //echo $filter;
                    }
                    $filter = substr($filter,0,-4);
                    //echo $filter;

                    break;
                    default:
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
                    break;
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


    public static function writeXML($string,$debug=false)
    {
        $xml = "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n";
        $xml .= $string;
        if ($debug): var_dump ($xml); else: echo $xml; endif;
    }

//########################################################################################
// CRUD TRAPS
    private function getProducts(){

        $campi = array('drp.id,
                      drp.product_name,
                      drp.brand,
                      drp.stock,
                      drmu.measure_name');
        $tabella = 'derat_database.dr_products drp Left Join
                    derat_database.dr_measure_unit drmu On drp.bait_measure_unit = drmu.id';
        $where = $this->getFilters();
        $order = $this->getOrder();
        $limit = $this->getLimit();
        $records = 0;
        $dati = array();
        $this->connect();
        utils::SelectTabelle($tabella, $campi, $where, $order, $limit, $records, $dati, false);
        $this->disconnect();
        if (count($dati)>0){
            $stringData = "<products found=\"" . count($dati) . "\">\n";
            foreach ($dati as $record) {
                $stringData .= "    <trap>\n";
                foreach ($record as $campo => $valore) {
                    $stringData .= "     <" . $campo . "><![CDATA[" . utils::clear_data($valore) . "]]></" . $campo . ">\n";
                }
                $stringData .= "    </trap>\n";
            }
            $stringData .= "</products>\n";
            products::writeXML($stringData);
        } else {
            products::writeXML('<message><![CDATA[no results]]></message>');
        }
    }

// function create trap
// campi obligatori: "customer_id":"", "address":"", "trap_type":"", "trap_status":"", "product_id":"", "covered_area_id":"", "trap_group_id":""
    private function create_products()
    {
        //utils::trace((array)$this->token['data']);
        $dati = (array)$this->token['data'];
        $_table = 'dr_products';
        $tabella = $_table;
        if (in_array("C", $_SESSION['user']['permissions']) or in_array("*", $_SESSION['user']['permissions']) or $_SESSION['customer']['auth'] === true) {
        foreach ($dati as $record){
            $_dati = (array) $record;
            //utils::trace($_dati);
            $this->connect();
            $result = utils::InsertTabella($tabella, $_dati);
            $this->disconnect();
            if ($result > 0) {
                products::writeXML('<message><![CDATA[ok | prodotto creato]]></message>');
            } else {
                products::writeXML('<error><![CDATA[ko | Errore creazione prodotto]]></error>');
            }
        }

        } else {
            products::writeXML('<error><![CDATA[ko | no privileges]]></error>');
        }

    }

    private function edit_products()
    {

        $dati = (array)$this->token['data'];
        $_table = 'dr_products';
        $tabella = $_table;
        $campi = $dati;
        $where = $this->getFilters();
        if (in_array("E", $_SESSION['user']['permissions']) or in_array("*", $_SESSION['user']['permissions']) or $_SESSION['customer']['auth'] === true) {
            $this->connect();
            $result = utils::UpdateTabella($tabella, $campi, $where);
            $this->disconnect();
            //echo $result;
            if ($result > 0) {
                products::writeXML('<message><![CDATA[ok | '.$result.' prodotti modificati]]></message>');
            } else {
                products::writeXML('<error><![CDATA[ko | '.$result.' prodotti non modificati]]></error>');
            }
        } else {
            products::writeXML('<error><![CDATA[ko | no privileges]]></error>');
        }

        exit();
    }

    private function delete_products()
    {
        $_table = 'dr_products';
        $tabella = $_table;
        $where = $this->getFilters();
        if (in_array("D", $_SESSION['user']['permissions']) or in_array("*", $_SESSION['user']['permissions']) or $_SESSION['customer']['auth'] === true) {
            $this->connect();
            $result = utils::DeleteTabella($tabella, $where);
            $this->disconnect();
            //echo $result;
            if ($result > 0) {
                products::writeXML('<message><![CDATA[ok | prodotto/i cancellato/i]]></message>');
                //echo "ok | user cancelato";
            } else {
                products::writeXML('<error><![CDATA[ko | prodotto/i non cancellato/i]]></error>');
                //echo "ko | user non cancellato";
            }
        } else {
            products::writeXML('<error><![CDATA[ko | no privileges]]></error>');
        }
        exit();
    }





// end class
}



$t = new products();