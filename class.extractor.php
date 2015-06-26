<?php
session_start();
error_reporting(E_ERROR);
   class extractor
   {
       protected $dbhost;
       protected $dbuser;
       protected $dbpass;
       protected $dbname;
       protected $EXTdbhost;
       protected $EXTdbuser;
       protected $EXTdbpass;
       protected $EXTdbname;
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


       function __construct()
       {
           //echo __CLASS__;
           include "class.utils.php";
           $this->dbhost = "localhost";
           $this->EXTdbhost = "localhost";
           // echo "SERVER: " . $_SERVER['HTTP_HOST'];
           if ($_SERVER['HTTP_HOST'] == 'localhost'):
               $this->dbuser = "root";
               $this->dbpass = "admin";
               $this->dbname = "derat_database";
               $this->EXTdbuser = "derat_admin";
               $this->EXTdbpass = "123456";
               $this->EXTdbname = "derat_central_admin";
           else:
               $this->dbuser = "fabiomon_derat";
               $this->dbpass = "By7IWkC?gmvx";
               $this->dbname = "fabiomon_derat";
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
               extractor::writeXML('<error><![CDATA[ko | token mancante]]></error>');
               return;
           endif;
           // setting token var
           $this->token = isset($_GET['token']) ? (array)json_decode(trim($_GET['token'])) : "";

           // check correct format of json string {"key":"value"}
           //echo count($this->token);
           if (count($this->token) < 1):
               extractor::writeXML('<error><![CDATA[ko | formato JSON errato]]></error>');
               return;
           endif;

           $this->init();
       }

       function __destruct()
       {
           //session_unset();
           //session_destroy();
       }

       // inizializzazione sistema
       private function init()
       {
           //echo __FUNCTION__ . "<br>";
           $this->getTokenData();
           $this->getService();
       }

//#######################################################################
//################### CONNESSIONI AI DATABASE ###########################
    public function EXTconnect()
    {
        //echo __FUNCTION__;
        //echo $this->dbhost."-".$this->dbuser."-".$this->dbpass."-".$this->EXTdbname;
        $this->EXTconn = @mysql_connect($this->EXTdbhost, $this->EXTdbuser, $this->EXTdbpass) or die ("parametri di connessione errati: " . mysql_error());
        $this->EXTdb = mysql_select_db($this->EXTdbname, $this->EXTconn) or die("Errore nella selezione del db: " . mysql_error());
    }

    public function EXTdisconnect()
    {
        mysql_close($this->EXTconn);
    }

    public function connect()
    {
        $this->updateLocalDBVars();
        $this->conn = @mysql_connect($this->dbhost, $this->dbuser, $this->dbpass) or die ("parametri di connessione errati: " . mysql_error());
        $this->db = mysql_select_db($this->dbname, $this->conn) or die("Errore nella selezione del db: " . mysql_error());
    }

    public function disconnect()
    {
        mysql_close($this->conn);
    }

    public function __set($varName, $varValue)
    {
        $this->$varName = $varValue;
    }


    private function updateLocalDBVars()
    {
        $this->EXTconnect();
        $q_select = 'SELECT db_vars FROM dr_customers WHERE cod_cli="' . $this->token['username'] . '"';
    }
//#######################################################################
//################### SMISTAMENTO DEI SERVIZI ###########################
    private function switchPrimaryService()
    {
        switch ($this->service) {
            case 'newInstallation':
            case 'install':
                $this->updateStatusLicences('+1');
                break;
            case 'uninstall':
                $this->updateStatusLicences('-1');
                break;
            case 'setDBVars':
                $this->setDBVars();
                break;
            case 'centralLogin':
                $this->doCentralLogin();
                break;
            case 'appLogin':
                $this->doLogin('app');
                break;
            case 'mobileLogin':
                $this->doLogin('mobile');
                break;
            case 'centralLogout':
                $this->doLogout('central');
                break;
            case 'sistemLogout':
                $this->doLogout('sistem');
                break;
            case 'retriveData':
                $this->getWhat();
                break;
            default:
                extractor::writeXML('<error><![CDATA[ko | Service error]]></error>');
                break;
        }
    }

    private function switchOperationsService()
    {
        switch ($this->service) {
            case 'get_app_user_data':
                $this->extractUserData();
                break;
            case 'get_mobile_user_data':
                $this->extractUserData('mobile');
                break;
            case 'create_app_user':
                $this->create_user();
                break;
            case 'create_mobile_user':
                $this->create_user('mobile');
                break;
            case 'edit_app_user':
                $this->edit_user();
                break;
            case 'edit_mobile_user':
                $this->edit_user('mobile');
                break;
            case 'delete_app_user':
                $this->delete_user();
                break;
            case 'delete_mobile_user':
                $this->delete_user('mobile');
                break;
            default:
                extractor::writeXML('<error><![CDATA[ko | Service error]]></error>');
                break;
        }
    }
//#######################################################################
//################### SERVIZI DI LOGIN ##################################
    // login for owners (da fare su db1)
    private function doCentralLogin()
    {
        //echo "<hr>" . __FUNCTION__ . '<br>';
        $this->EXTconnect();
        $this->token_username = $this->token['username'];
        $this->token_password = $this->token['password'];
        //$this->token_password = $this->token['password'];
        $tabella = '`dr_customers`';
        $campi = '*';
        $where = 'WHERE cod_cli = "' . $this->token_username . '" AND
            password = "' . $this->token_password . '"';
        $order = '';
        $limit = '';
        $num_record = 0;
        $riga = array();
        utils::SelectTabelle($tabella, $campi, $where, $order, $limit, $num_record, $riga, false);
        //utils::trace($riga);
        if (!empty($riga[0]['id'])) {
            // check expiration date
            $date = new DateTime();
            $date2 = new DateTime($riga['expiration_date']);
            $expirationTS = $date2->getTimestamp();
            //$check = $this->checkExpirationDate($expirationTS);
            $todayTS = $date->getTimestamp();
            // check expiration of licence
            if ($expirationTS < $todayTS):
                extractor::writeXML('<error><![CDATA[ko | Licenza scaduta]]></error>');

            endif;
            $_SESSION['customer']['user_id'] = $riga[0]['id'];
            $_SESSION['customer']['login_date'] = $date->getTimestamp();
            $_SESSION['customer']['customerName'] = $riga[0]['customer_name'];
            $_SESSION['customer']['login'] = $riga[0]['cod_cli'];
            $_SESSION['customer']['auth'] = true;
            $_SESSION['customer']['IP'] = $_SERVER['REMOTE_ADDR'];

            //$this->updateStatusLicences('+1');
            //extractor::trace($_SESSION);
            extractor::writeXML('<result_login><![CDATA[ok]]></result_login>');
            exit();
        } else {
            extractor::writeXML('<result_login><![CDATA[ko | Errore di autenticazione]]></result_login>');
            exit();
        }
        $this->EXTdisconnect();
    }

    private function checkExpirationDate($EXPdate)
    {
        $date = new DateTime();
        $todayTS = $date->getTimestamp();
        // check expiration of licence
        if ($EXPdate < $todayTS):
            extractor::writeXML('<error><![CDATA[Licenza scaduta2]]></error>');
        endif;
        return;
    }


    // login for users
    private function doLogin($type = 'app')
    {
        //echo "<hr>" . __FUNCTION__ . '<br>';
        $this->connect();
        $this->token_username = $this->token['username'];
        $this->token_password = $this->token['password'];
        $_table = 'dr_' . (($type == 'app') ? 'app_' : 'mobile_') . 'users';
        $tabella = $_table;
        $tabella .= ' LEFT JOIN dr_roles ON ' . $_table . '.user_type = dr_roles.id ';
        $tabella .= ($type == 'mobile') ? ' LEFT JOIN dr_trap_groups ON dr_mobile_users.id_trap_group = dr_trap_groups.id' : '';
        $gruppo = ($type == 'mobile') ? 'dr_trap_groups.trap_group_name' : 'dr_roles.id';
        $campi = array($_table . '.*', 'dr_roles.role_name', 'dr_roles.role_permission', $gruppo);
        $where = ' WHERE
            username = "' . $this->token_username . '"
            AND
            pwd = "' . $this->token_password . '"';
        $order = '';
        $limit = '';
        $num_record = 0;
        $riga = array();
        utils::SelectTabelle($tabella, $campi, $where, $order, $limit, $num_record, $riga, false);

        if (!empty($riga[0]['id'])):
            $date = new DateTime();
            $_SESSION['user']['user_id'] = $riga[0]['id'];
            $_SESSION['user']['login_date'] = $date->getTimestamp();
            $_SESSION['user']['username'] = $riga[0]['username'];
            $_SESSION['user']['userRole'] = $riga[0]['user_type'];
            $_SESSION['user']['userRoleName'] = $riga[0]['role_name'];
            $_SESSION['user']['permissions'] = explode("|", $riga[0]['role_permission']);
            $_SESSION['user']['auth'] = true;
            $_SESSION['user']['IP'] = $_SERVER['REMOTE_ADDR'];
            if ($type == 'mobile') $_SESSION['user']['group'] = $riga[0]['trap_group_name'];
            extractor::writeXML('<result_login><![CDATA[ok]]></result_login>');
            //utils::trace($_SESSION);
            return;
        else:
            extractor::writeXML('<result_login><![CDATA[ko | Errore di autenticazione]]></result_login>');
            exit();
        endif;
        $this->disconnect();
    }

    function doLogout($fromWhere = 'central')
    {
        session_unset();
        session_destroy();
        extractor::writeXML('<result_logout><![CDATA[logout ok]]></result_logout>');
        exit();
    }

//#######################################################################
//################### FUNZIONI ESTRAZIONE DATI ##########################
// estraggo i dati dall'array del token
    public function getTokenData()
    {
        // echo "<hr>" . __FUNCTION__ . '<br>';
        $this->data = isset($this->token['data']) ? (array)$this->token['data'] : null;
        $this->service = $this->token['service'];
    }

    public function getService()
    {
        switch ($this->service) {
            case 'newInstallation':
            case 'install':
            case 'uninstall':
            case 'setDBVars':
            case 'centralLogin':
            case 'appLogin':
            case 'mobileLogin':
            case 'centralLogout':
            case 'sistemLogout':
            case
                'retriveData':
                    $this->switchPrimaryService($this->service);
                    break;
            default:

                if ($this->checkAuth()) {
                    $this->switchOperationsService($this->service);
                } else {
                    extractor::writeXML('<error><![CDATA[session expired]]></error>');
                }

                break;
        }
    }


    private function setDBVars()
    {
        //extractor::trace($this->data);
        $localData = json_encode($this->data);
        $q_update = "UPDATE dr_customers SET db_vars='" . trim($localData) . "' WHERE cod_cli='" . $this->token['username'] . "'";

        $this->EXTconnect();
        mysql_query($q_update) or die("Errore nella query" . $q . mysql_error());
        $this->EXTdisconnect();
    }

    /* aggiornamento dopo installazione/disinstallazione del numero di licenze cliente */
    private function updateStatusLicences($operator)
    {
        $this->EXTconnect();
        if ($operator == '+1') {
            $tabella = '`dr_customers`';
            $campi = array('used', 'installations');
            $where = 'WHERE
                        cod_cli = "' . $this->token['username'] . '" AND
                        `password` = "' . $this->token['password'] . '"';
            $order = '';
            $limit = 'LIMIT 1';
            $record = 0;
            $row = array();
            utils::SelectTabelle($tabella, $campi, $where, $order, $limit, $record, $row);

            //echo $row['used'];
            if ($row[0]['used'] + 1 > $row[0]['installations']) {
                extractor::writeXML('<errore><![CDATA[Licenze disponibili esaurite]]></errore>');
                exit();
            }
            // update the installation
            $q = 'update dr_customers
              SET used = used ' . $operator . '
              where cod_cli = "' . $this->token['username'] . '"';
            //extractor::trace($q);
            $res = mysql_query($q) or die("Errore nella query" . $q . mysql_error());
            extractor::writeXML('<message><![CDATA[installed]]></message>');
            exit();
        } else {
            $q = 'update dr_customers
              SET used = used ' . $operator . '
                where cod_cli = "' . $this->token['username'] . '"';
            //extractor::trace($q);
            $res = mysql_query($q) or die("Errore nella query" . $q . mysql_error());
            extractor::writeXML('<message><![CDATA[uninstalled]]></message>');
            exit();
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


//##################################################
//################ SERVIZI APPLICAZIONE DESKTOP
    private function extractUserData($type = 'app')
    {
        $this->connect();
        $_table = 'dr_' . (($type == 'app') ? 'app_' : 'mobile_') . 'users';
        $tabella = ($type == 'app') ? $_table : $_table . ' LEFT JOIN dr_trap_groups ON ' . $_table . '.id_trap_group = dr_trap_groups.id LEFT JOIN dr_customers ON dr_customers.id = dr_mobile_users.customer_id';
        $campi = ($type == 'mobile') ? array($_table . '.*', 'dr_trap_groups.trap_group_name', 'dr_customers.customer_name') : '*';
        $where = $this->getFilters();
        $order = $this->getOrder();
        $limit = $this->getLimit();
        $records = 0;
        $dati = array();

        utils::SelectTabelle($tabella, $campi, $where, $order, $limit, $records, $dati, false);
        //utils::trace($dati);
        if (count($dati) > 0):
            $stringUsers = "<users found=\"" . count($dati) . "\">\n";
            foreach ($dati as $userData):
                $stringUsers .= "    <user>\n";
                foreach ($userData as $key => $value) {
                    $stringUsers .= "       <" . $key . "><![CDATA[" . utils::clear_data($value) . "]]></" . $key . ">\n";;
                }
                $stringUsers .= "    </user>\n";
            endforeach;
            $stringUsers .= "</users>\n";
            extractor::writeXML($stringUsers);
        else:
            extractor::writeXML('<message><![CDATA[no users]]></message>');
        endif;
        $this->disconnect();
        return $dati;
    }

    // function create user app/mobile
    private function create_user($type = 'app')
    {
        $this->connect();
        //utils::trace((array)$this->token['data']);
        $dati = (array)$this->token['data'];
        //$dati['pwd'] = md5($dati['pwd']);
        //utils::trace($dati);
        $_table = 'dr_' . (($type == 'app') ? 'app_' : 'mobile_') . 'users';
        $tabella = $_table;

        if (in_array("C", $_SESSION['user']['permissions']) or in_array("*", $_SESSION['user']['permissions']) or $_SESSION['customer']['auth'] === true) {
            $result = utils::InsertTabella($tabella, $dati);
            if ($result > 0) {
                extractor::writeXML('<message><![CDATA[ok | user created]]></message>');
            } else {
                extractor::writeXML('<error><![CDATA[ko | Errore creazione utente]]></error>');
            }
        } else {
            extractor::writeXML('<error><![CDATA[ko | no privileges]]></error>');
        }
        $this->disconnect();
    }

    private function edit_user($type = 'app')
    {
        $this->connect();
        //utils::trace((array)$this->token['data']);
        $dati = (array)$this->token['data'];
        //if (!empty($dati['pwd'])) $dati['pwd'] = md5($dati['pwd']);
        //utils::trace($dati);
        $_table = 'dr_' . (($type == 'app') ? 'app_' : 'mobile_') . 'users';
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
                extractor::writeXML('<message><![CDATA[ok | user modificato]]></message>');
                //echo "ok | user modificato";
            } else {
                extractor::writeXML('<error><![CDATA[ko | user non modificato]]></error>');
                //echo "ko | user non modificato";
            }
        } else {
            extractor::writeXML('<error><![CDATA[ko | no privileges]]></error>');
        }
        $this->disconnect();
        exit();
    }

    private function delete_user($type = 'app')
    {
        $this->connect();
        $_table = 'dr_' . (($type == 'app') ? 'app_' : 'mobile_') . 'users';
        $tabella = $_table;
        $where = $this->getFilters();


        // utils::trace($_SESSION['user']['permissions']);
        if (in_array("D", $_SESSION['user']['permissions']) or in_array("*", $_SESSION['user']['permissions']) or $_SESSION['customer']['auth'] === true) {
            $result = utils::DeleteTabella($tabella, $where);
            //echo $result;
            if ($result > 0) {
                extractor::writeXML('<message><![CDATA[ok | '.$result.' utenti cancellati]]></message>');
                //echo "ok | user cancelato";
            } else {
                extractor::writeXML('<error><![CDATA[ko | '.$result.' utenti non cancellati]]></error>');
                //echo "ko | user non cancellato";
            }
        } else {
            extractor::writeXML('<error><![CDATA[ko | no privileges]]></error>');
        }

        $this->disconnect();
        exit();
    }

    public function getWhat()
    {

        $dataArray = (array)$this->token['data'];
        //utils::trace($dataArray);
//exit();
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
        extractor::writeXML($stringData);
    }

//------------
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

}
   
   
   $e = new extractor();
?>