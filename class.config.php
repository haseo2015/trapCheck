<?php
class config {
    public static var $dbhost="localhost";
    public static var $EXTdbhost= "localhost";
// local connection
    if($_SERVER['HTTP_HOST']=='localhost'):
    public static var $dbuser= 'root';
    public static var $dbpass="admin";
    public static var $dbname="derat_database";
    public static var $EXTdbuser = "derat_admin";
    public static var $EXTdbpass = "123456";
    public static var $EXTdbname = "derat_central_admin";
// remote connection
    else:
    public static var $dbuser="fabiomon_derat";
    public static  var $dbpass="By7IWkC?gmvx";
    public static var $dbname="derat_database";
    public static var $EXTdbuser = "fabiomon_derat";
    public static var $EXTdbpass = "By7IWkC?gmvx";
    public static var $EXTdbname = "fabiomon_derat_central_admin";
    endif;
}