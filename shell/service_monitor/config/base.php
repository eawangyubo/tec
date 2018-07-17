<?php

// タイムゾーン設定
ini_set('date.timezone', "Asia/Tokyo");

// メモリー設定
ini_set('memory_limit', '512M');

// 環境設定
switch (getenv('SUBMITMAIL_ENV')) {
    case 'localdevelopment':
        require_once(dirname(__DIR__) . '/config/localdevelopment.php');
        break;
    case 'development':
        require_once(dirname(__DIR__) . '/config/development.php');
        break;
    case 'production':
        require_once(dirname(__DIR__) . '/config/production.php');
        break;
    default:
        require_once(dirname(__DIR__) . '/config/localdevelopment.php');
}

// DB関数（From https://github.com/tschoffelen/db.php.git THX! ）
require_once(dirname(__DIR__).'/libs/database.php');

// 業務関数
require_once(dirname(__DIR__).'/libs/common.php');

/**
 * 初期化処理
 *
 */
class Base
{
    public $argc;
    public $argv;
    public $Common;
    public $Mysql;

    function __construct() {
        $this->argc = $_SERVER['argc'];
        $this->argv = $_SERVER['argv'];
        $this->init_common();
        $this->init_db();
    }

    /**
     * DB CLASS 初期化
     *
     */
    protected function init_db() {
        $this->Mysql = new Database(DATABASE_DATABASENAME, DATABASE_USERNAME, DATABASE_PASSWORD, DATABASE_HOST, DATABASE_PORT);
    }

    /**
     * 業務関数 CLASS 初期化
     *
     */
    protected function init_common() {
        $this->Common = new Common();
    }

    /**
     * ログ関数 共通処理
     *
     */
    function log($msg, $file_name = 'log') {
        if(DEBUG) { echo($msg . "\n"); }
        $log_path = dirname(__DIR__) . '/logs/';
        if(!file_exists($log_path)) {
            mkdir($log_path, 0777);
        }
        $file_name = $log_path . $file_name . '_' . date('Ymd') . '.log';
        $text = date("Y-m-d H:i:s") . ' ' . $msg . PHP_EOL;
        file_put_contents($file_name, $text, FILE_APPEND);
    }

    /**
     * Debug関数 共通処理
     *
     */
    function pr($str){
        echo "\r\n";
        print_r($str);
        echo "\r\n";
    }
}
?>
