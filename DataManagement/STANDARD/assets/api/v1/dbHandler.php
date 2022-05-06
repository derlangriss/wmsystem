<?php

class DbHandler
{

    private $conn;

    public function __construct()
    {
        require_once 'dbConnect.php';
        // opening db connection
        $db         = new dbConnect();
        $this->conn = $db->connect();
    }
    /**
     * Fetching single record
     */
    public function getOneRecord($query)
    {
        $r             = $this->conn->query($query . ' LIMIT 1') or die($this->conn->errorInfo . __LINE__);
        return $result = $r->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Creating new record
     */
    public function insertIntoTable($obj, $column_names, $table_name)
    {

        $c       = (array) $obj;
        $keys    = array_keys($c);
        $columns = '';
        $values  = '';
        foreach ($column_names as $desired_key) {
            // Check the obj received. If blank insert blank into the array.
            if (!in_array($desired_key, $keys)) {
                $$desired_key = '';
            } else {
                $$desired_key = $c[$desired_key];
            }
            $columns = $columns . $desired_key . ',';
            $values  = $values . "'" . $$desired_key . "',";
        }

       

        $query = "INSERT INTO " . $table_name . "(" . trim($columns, ',') . ") VALUES(" . trim($values, ',') . ") RETURNING idlogin_user";

        $r = $this->conn->query($query) or die($this->conn->errorInfo . __LINE__);
        foreach ($r as $row) {
            if ($r) {
                $new_row_id = $row['idlogin_user'];
                return $new_row_id;
            } else {
                return null;
            }
        }

    }
    public function getSession()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        $sess = array();
        if (isset($_SESSION['idlogin_user'])) {
            $sess["idlogin_user"]         = $_SESSION['idlogin_user'];
            $sess["firstname"]   = $_SESSION['firstname'];
            $sess["lastname"]    = $_SESSION['lastname'];
            $sess["email"]       = $_SESSION['email'];
            $sess["avatar"]     = $_SESSION['avatar'];
            $sess['user_status']   = $_SESSION['user_status'];
            $sess['iduser_status'] = $_SESSION['iduser_status'];
            $sess['idsection'] = $_SESSION['idsection'];

        } else {
            $sess["idlogin_user"]       = '';
            $sess["firstname"] = '';
            $sess["lastname"]  = '';
            $sess["email"]     = '';
            $sess["avatar"]   = '';
            $sess['user_status'] = '';
            $sess['iduser_status'] = '';
            $sess['idsection'] = '';
        }
        return $sess;
    }
    public function destroySession()
    {
        if (!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION['idlogin_user'])) {
            unset($_SESSION['idlogin_user']);
            unset($_SESSION['firstname']);
            unset($_SESSION['lastname']);
            unset($_SESSION['email']);
            unset($_SESSION['avartar']);
            unset($_SESSION['user_status']);
            unset($_SESSION['iduser_status']);
            unset($_SESSION['idsection']);

            $info = 'info';
            if (isset($_COOKIE[$info])) {
                setcookie($info, '', time() - $cookie_time);
            }
            $msg = "Logged Out Successfully...";
        } else {
            $msg = "Not logged in...";
        }
        return $msg;
    }

}
