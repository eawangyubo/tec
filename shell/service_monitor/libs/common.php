<?php
/**
 * 業務関数CLASS
 *
 */
class Common
{
    /**
     * メールを送信する 関数
     *
     * @param string $mailfrom 送信元
     * @param string $mailto   送信先
     * @param string $subject  件名
     * @param string $content  本文
     * @param string $mailcc   CC
     * @param string $mailbcc  BCC
     * @return void
     */
    function sendMail($mailfrom, $mailto, $subject, $content, $mailcc = '', $mailbcc = '') {

        // 言語、文字コードを設定する
        mb_language("Ja");
        mb_internal_encoding("UTF-8");

        // メールヘッダーを設定する(FROM/CC/BCCは、メールヘッダーに設定する）
        
        // FROM
        $header = "From: $mailfrom";
        
        // CC
        if( $mailcc ){
        $header .= "\n";
        $header .= "CC: $mailcc";
        }

        //BCC
        if( $mailbcc ){
        $header .= "\n";
        $header .= "BCC: $mailbcc";
        }

        // Return-Pathを設定する(宛先不明などの場合にエラーメールを取得するアドレス）
        $returnpath = "-f $mailfrom";

        // メールを送信する
        $ret = mb_send_mail($mailto, $subject, $content, $header, $returnpath);
        if( !$ret ){
            echo( "メール送信エラー\n" );
            Base::log(basename(__FILE__)." メール送信エラー");
        }
    }
}
?>
