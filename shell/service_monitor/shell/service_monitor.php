<?php
/**
 * サービス監視用Scripts
 * ・action : action_sample1 監視動作１
 * ・action : action_sample2 監視動作２
 * ・顧客要望より随時に監視動作追加
 *
 * 利用方法 PROJECT_ENV={localdevelopment/development/production} /usr/bin/php {path_to_project}/shell/service_monitor.php {action}
 * 例： PROJECT_ENV=localdevelopment /usr/bin/php /var/www/html/service_monitor/shell/service_monitor.php action_sample1
 */

//初期化
require_once(dirname(__DIR__).'/config/base.php');

/**
 * サービス監視 中核部分
 *
 */
class serviceMonitor extends Base
{
    /**
     * MAIN(入口)
     *
     */
    public function main() {
        $this->log(basename(__FILE__)." serviceMonitor start ");

        //パラメタある場合
        if($this->argc == 2) {
            
            //指定されるパラメタより監視動作を起動する
            switch ($this->argv[1]) {

                //監視動作１
                case 'action_sample1':
                    $this->log(basename(__FILE__)." actionSample1 start ");
                    $this->action_action_sample1();
                    $this->log(basename(__FILE__)." actionSample1 end ");
                    break;

                //監視動作２
                case 'action_sample2':
                    $this->log(basename(__FILE__)." actionSample1 start ");
                    $this->action_action_sample2();
                    $this->log(basename(__FILE__)." actionSample1 end ");
                    break;

                //ディフォルト動作：実行中止
                default:
                    echo "you need input a correct param to run this script. ( ex: action_sample1 | action_sample2 )\n";
            }

        //パラメタない場合
        } else {
            echo "you need input a param to run this script. ( ex: action_sample1 | action_sample2 )\n";
        }
        $this->log(basename(__FILE__)." serviceMonitor end ");
    }

    /**
     * 監視動作１
     *
     * @param  int      $param1 パラメタ１
     * @param  int      $param2 パラメタ２
     * @param  int      $param3 パラメタ３
     * @return void
     */
    private function action_action_sample1($param1 = null, $param2 = null, $param3 = null) {
        
        //データを抽出する
        $sql = "SELECT * FROM tables WHERE id in (1, 2, 3)";
        $result = $this->Mysql->query($sql)->result_array();

        //結果ある場合
        if(!empty($result)) {
            $this->pr($result);
        //結果なし場合
        } else {
            $this->log(basename(__FILE__)." data not found.");
        }
    }

    /**
     * 監視動作２
     *
     * @param  int      $param4 パラメタ４
     * @param  int      $param5 パラメタ５
     * @param  int      $param6 パラメタ６
     * @return void
     */
    private function action_action_sample2($param4 = null, $param5 = null, $param6 = null) {
        
        //データを抽出する
        $sql = "SELECT * FROM tables WHERE id in (4, 5, 6)";
        $result = $this->Mysql->query($sql)->result_array();

        //結果ある場合
        if(!empty($result)) {
            $this->pr($result);
        //結果なし場合
        } else {
            $this->log(basename(__FILE__)." data not found.");
        }
    }
}

$serviceMonitor = new serviceMonitor();
$serviceMonitor->main();
?>
