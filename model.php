<?php
// **************************
// コンボボックスの内容の編集
// **************************
function build_combobox( $mysqli ) {

    global $syozoku_option;

    $syozoku_option = "";
    $query = <<<QUERY
select * from コード名称マスタ where 区分 = 2
QUERY;
    
    $result = $mysqli->query( $query );
    while ( $row = $result->fetch_array( MYSQLI_BOTH ) ) {
        // 初期画面では $_POST["syozoku"] の内容は空
        if ( $_POST["syozoku"] == $row["コード"] ) {
            $syozoku_option .= 
                "<option selected value='{$row["コード"]}'>{$row["名称"]}</option>";
        }
        else {
            $syozoku_option .= 
                "<option value='{$row[0]}'>{$row["名称"]}</option>";
        }
    }

}

// **************************
// データ内容の編集
// **************************
function edit_after_check( $mysqli, $row ) {

    // 会話画面番号
    global $gno;

    if ( $row ) {
        $_POST["sname"] = $row["氏名"];
        $_POST["fname"] = $row["フリガナ"];
        $_POST["syozoku"] = $row["所属"];
        $_POST["seibetsu"] = $row["性別"];
        $_POST["kyuyo"] = $row["給与"];
        $_POST["teate"] = $row["手当"];
        $_POST["kanri"] = $row["管理者"];
        $_POST["kanri_name"] = $row["管理者名"];
        $_POST["birth"] = $row["生年月日"];

        // 社員データによる所属選択
        build_combobox( $mysqli );
    }
    else {
        $_POST["sname"] = "";
        $_POST["fname"] = "";
        $_POST["syozoku"] = "";
        $_POST["seibetsu"] = "";
        $_POST["kyuyo"] = "";
        $_POST["teate"] = "";
        $_POST["kanri"] = "";
        $_POST["birth"] = "";

        $_POST["message"] = "新規登録です";
    }

    // 次の画面の会話番号( 確認がクリックされた )
    $gno = 2;

}

// **************************
// 更新処理後の編集
// **************************
function edit_after_check_entry( $mysqli, $row ) {

    // 会話画面番号
    global $gno,$focus;

    if ( !$row ) {
        // 次の画面の会話番号( 入力エラー )
        $gno = 2;
        $_POST["message"] = "<span style='color:red;font-weight:bold'>管理者が存在しません</span>";
        $_POST["kanri_name"] = "";
        // エラー時のフォーカス
        $focus = "kanri";
    }
    else {
        // 正常な更新処理
        $row = check($mysqli);
        // 社員コードが存在する
        if ( $row ) {
            update( $mysqli );
        }
        // 社員コードが存在しない
        else{
            insert( $mysqli );
        }

        // 初期画面用の編集
        $_POST["scode"] = "";
        $_POST["sname"] = "";
        $_POST["fname"] = "";
        $_POST["syozoku"] = "";
        $_POST["seibetsu"] = "";
        $_POST["kyuyo"] = "";
        $_POST["teate"] = "";
        $_POST["kanri"] = "";
        $_POST["kanri_name"] = "";
        $_POST["birth"] = "";

        // 次の画面の会話番号( 更新がクリックされた )
        $gno = 1;
    }
}

// **************************
// 会話による画面編集
// **************************
function protect_control( $gno ) {

    global $readonly_1,$readonly_2,$disabled_1,$disabled_2;
    global $disabled_type_text,$disabled_type;

    if ( $gno == 1 ) {

        $readonly_1 = "";
        $readonly_2 = $disabled_type_text;
        $disabled_1 = "";
        $disabled_2 = $disabled_type;
    
    }
    if ( $gno == 2 ) {
    
        $readonly_1 = $disabled_type_text;
        $readonly_2 = "";
        $disabled_1 = $disabled_type;
        $disabled_2 = "";
    
    }
    
}

// **************************
// 入力チェック
// **************************
function check_entry( $mysqli ) {

    if( $_POST["kanri"] == "" ) {
        return true;
    }

    $query =<<<QUERY
select
    社員コード,
    氏名
from
    社員マスタ
where
    社員コード = '{$_POST["kanri"]}'
QUERY;

    $result = $mysqli->query( $query );
    $row = $result->fetch_array( MYSQLI_BOTH );

    return $row;
}

// **************************
// 社員コードで存在チェック
// **************************
function check( $mysqli ) {

    $query =<<<QUERY
select
    A.社員コード,
    A.氏名,
    A.フリガナ,
    A.所属,
    A.性別,
    A.給与,
    A.手当,
    A.管理者,
    B.氏名 as 管理者名,
    DATE_FORMAT(A.生年月日, '%Y-%m-%d') as 生年月日
from
    社員マスタ A
    left outer join
        社員マスタ B
    on A.管理者 = B.社員コード
where
    A.社員コード = '{$_POST["scode"]}'
QUERY;

    $result = $mysqli->query( $query );
    $row = $result->fetch_array( MYSQLI_BOTH );

    return $row;
}

// **************************
// 更新処理
// **************************
function insert( $mysqli ) {

    if ( $_POST["teate"] == ""  ) {
        $_POST["teate"] = "NULL";
    }

    if ( $_POST["kanri"] == ""  ) {
        $_POST["kanri"] = "NULL";
    }
    else {
        $_POST["kanri"] = "'{$_POST["kanri"]}'";
    }


    $query = <<<QUERY

insert into 社員マスタ 
    (社員コード
    ,氏名
    ,フリガナ
    ,所属
    ,性別
    ,給与
    ,手当
    ,管理者
    ,生年月日
    )
    values('{$_POST["scode"]}'
    ,'{$_POST["sname"]}'
    ,'{$_POST["fname"]}'
    ,'{$_POST["syozoku"]}'
    ,{$_POST["seibetsu"]}
    ,{$_POST["kyuyo"]}
    ,{$_POST["teate"]}
    ,{$_POST["kanri"]}
    ,'{$_POST["birth"]}'
    )

QUERY;

    $mysqli->query( $query );

}


function update( $mysqli ) {

    if ( $_POST["teate"] == ""  ) {
        $_POST["teate"] = "NULL";
    }

    if ( $_POST["kanri"] == ""  ) {
        $_POST["kanri"] = "NULL";
    }
    else {
        $_POST["kanri"] = "'{$_POST["kanri"]}'";
    }


    $query = <<<QUERY

update 社員マスタ set 

氏名 = '{$_POST["sname"]}',
フリガナ = '{$_POST["fname"]}',
所属 = '{$_POST["syozoku"]}',
性別 = {$_POST["seibetsu"]},
給与 = {$_POST["kyuyo"]},
手当 = {$_POST["teate"]},
管理者 = {$_POST["kanri"]},
生年月日 = '{$_POST["birth"]}'

where 社員コード = '{$_POST["scode"]}'

QUERY;

    $mysqli->query( $query );

}

// **************************
// デバッグ表示
// **************************
function debug_print() {

    print "<pre class=\"m-5\">";
    print_r( $_GET );
    print_r( $_POST );
    print_r( $_SESSION );
    print_r( $_FILES );
    print "</pre>";

}
