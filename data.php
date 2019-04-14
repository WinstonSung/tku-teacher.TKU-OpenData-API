<?php
//error_reporting(0);
ini_set('display_errors','1');
error_reporting(E_ALL);
/*************************************************
淡江電機教師歷程查詢系統
Copyright (C) 2017 - 2019 Fishcan
Author: Fishcan
E-mail: umts111321@gmail
time: 2019.04.14
*************************************************/

class getdata
{
    private $path;
    private $url;
    private $response;
    private $resultInfo;

    private function validation_url() //check file type
    {
        $this->url = preg_match('/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i', $this->path) ? true : false;
    }

    private function curl()
    {
        if (!$this->response = file_get_contents($this->path))
        {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->path);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Expect:'
            ));

            $this->response = curl_exec($ch);
            $this->resultInfo = curl_getinfo($ch);

            curl_close($ch);
        }
    }

    private function cfile() //get local file
    {
        if ($this->resultInfo = file_exists($this->path))
        {
            $file = fopen($this->path, 'r');
            if ($file != NULL)
            {
                while (!feof($file))
                {
                    $this->response .= fgets($file);
                }
                fclose($file);
            }
        }
    }

    public function get($encoding = 'auto')
    {
        $this->url ? $this->curl() : $this->cfile();
        $this->response = mb_convert_encoding($this->response, 'UTF-8', $encoding); //for firefox
    }

    public function res()
    {
        return $this->response;
    }

    public function __construct($path)
    {
        $this->path = $path;
        $this->validation_url();
    }
}

class tku_opendata_api
{
    public static function api($mode, $data)
    {
        $url = 'http://teacher.tku.edu.tw/api/';
        switch ($mode)
        {
            case 'FdTypInfo':
                $url .= 'GetFdTypInfo';
                $op = array(
                    'json'
                );
                $name = array(
                    'ty'
                );
            break;
            case 'UidList':
                $url .= 'OpUidList';
                $op = array(
                    'json',
                    'username', //your username
                    'password'  //your password
                );
                $name = array(
                    'ty',
                    'ut',
                    'vc',
                    'ot'
                );
            break;
            case 'ThrInfo':
                $url .= 'GetThrInfo';
                $op = array(
                    'json',
                    'username', //your username
                    'password'  //your password
                );
                $name = array(
                    'ty',
                    'ut',
                    'vc'
                );
            break;
            case 'TpFdList':
                $url .= 'GetTpFdList';
                $op = array(
                    'json',
                    'username', //your username
                    'password'  //your password
                );
                $name = array(
                    'ty',
                    'ut',
                    'vc',
                    'dt',
                    'yc',
                    'ti'
                );
            break;
            default:
                exit();            
        }

        $op = array_merge($op, $data);

        $res = $url . '?';
        $i = 0;
        foreach ($op as $c)
        {
            if ($c != '') $res .= $name[$i] . '=' . $c . '&';
            $i++;
        }
        $res = substr($res, 0, -1);
        return new getdata($res);
    }
}

class aas
{
    private $res;

    public function mode($mode, $op = array())
    {
        $ac = tku_opendata_api::api($mode, $op);
        $ac->get('UTF-16');
        $this->res = $ac->res();
    }

    public function res()
    {
        return $this->res;
    }
}

$data = array();
if (isset($_POST['mode'])) $mode = $_POST['mode'];
else exit();
if (isset($_POST['data'])) $data = $_POST['data'];

$test = new aas();
$test->mode($mode, $data);
echo $test->res();

?>
