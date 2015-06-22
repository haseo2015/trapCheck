<?php
include_once "class.extractor.php";
class router extends extractor {

    function __construct(){
        parent::__construct();
        parent::getTokenData();
        echo parent::$token['service'];
        $this->switchService($this->service);

    }

    private function switchService(){
        //echo "<hr>" . __FUNCTION__ . '<br>';
        //echo $this->service;

        switch($this->service){
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
                parent::doCentralLogin();
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
                $this->dolLogout('sistem');
                break;
            case 'get_user_data':
                $this->extractUserData();
                break;
            case 'get_mobile_user_data':
                $this->extractMobileUserData();
                break;
            default:
                extractor::writeXML('<errore><![CDATA[Service error]]></errore>');
                break;
        }
    }
}