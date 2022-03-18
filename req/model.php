<?php

// 基本 SQL
$_POST["query"] = <<<QUERY
select 社員コード,氏名,フリガナ from 社員マスタ
QUERY;

// 入力値で条件作成
if ( $_POST["simei"] != "" ) {

    $_POST["query"] .= " where 氏名 like '%{$_POST["simei"]}%' ";

}

// SQL の実行
if ( $_POST["query"] != "" ) {


    // SQL の実行
    $result = $mysqli->query( $_POST["query"] );
    if ( $result === FALSE ){
        //エラー
        $error = $mysqli->error;
    }
    else {
        if( $result === TRUE ) {

        }
        else {

            // 列の情報一覧を取得
            $field = $result->fetch_fields( );

            $title = "";

            $title .= "<tr>";
            for( $i = 0; $i < count( $field ); $i++ ) {

                $title .= "<td>{$field[$i]->name}</td>";

            }
            $title .= "</tr>";

            // 行データ変数の初期化
            $data_body = "";

            // MYSQLI_BOTH
            while ( $row = $result->fetch_array( MYSQLI_BOTH ) ) {

                $data_body .= "<tr>";
                for( $i = 0; $i < count( $field ); $i++ ) {

                    if ( $i == 1 ) {
                        $data_body .= "<td><a href='#' onclick='setData(\"{$row[0]}\",\"{$row[1]}\")'>{$row[$i]}</a></td>";
                    }
                    else {
                        $data_body .= "<td>{$row[$i]}</td>";
                    }

                }
                $data_body .= "</tr>";

            }
            print "</pre>";
        }
    }

}

// ***************************
// 接続解除
// ***************************
$mysqli->close();

?>
