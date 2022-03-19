<!DOCTYPE html>
<html>

<head>
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta charset="utf-8">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<link rel="stylesheet" href="syain.css?_=<?= time() ?>">
<script>
// ******************************
// jQuery onload イベント
// ******************************
$(function(){

    // 第一画面の初期フォーカス
    $("#scode").focus();

    // 氏名～生年月日
    $(".data").on("keypress", function(e){
        if ( e.keyCode == 13 ) {
            var target = $(this).parents(".body").nextAll(".body").eq(0);
            if ( target.length == 0 ) {
                $("#sname")
                    .focus()
                    .select();
            }
            else {
                target.find(".data")
                    .focus()
                    .select();
            }
            return false;
        }
    });

    // 第二画面の初期フォーカス
    if ( <?= $gno ?> == 2 ) {
        $("#<?= $focus ?>")
            .focus()
            .select();
    }

    // ファンクション用
    $(window).on("keydown", function(e){

        var key_code = e.which ? e.which : e.keyCode;

        // 第一画面の F3 で IFRAME を開く
        // 114 は F3 : gno が 2 は第二画面
        if ( key_code == 114 && <?= $gno ?> == 1 ) {
            ref2();
            return false;
        }

        // 第二画面の F3 で デフォルトの動作をキャンセル
        if ( key_code == 114 && <?= $gno ?> == 2 ) {
            ref1();
            return false;
        }

        // ESC で IFRAME を閉じる
        if ( key_code == 27 ) {
            $("iframe").css({"display": "none"});
        }

    });

});

// ******************************
// IFRAME 表示
// ******************************
function ref1() {

    $("#iframe1").eq(0).css({
        "display": "block"
    });

}
function ref2() {

$("#iframe2").eq(0).css({
    "display": "block"
});

}

// ******************************
// 確認ボタンの時の送信チェック
// ******************************
function check(){

    // 社員コード
    var scode = $("#scode").val();
    // 前ゼロ４桁に変換
    scode = ( "0000" + scode ).slice(-4);
    // セットし直し
    $("#scode").val( scode );

    var scode = $("#scode").val();
    if ( scode.length != 4 ) {
        alert("社員コードを4桁入力してください");
        return false;
    }

    if ( <?= $gno ?> == 2 && !confirm("更新してもよろしいですか?") ) {
        return false;
    }

    if ( <?= $gno ?> == 2 ) {
        // 管理者コード
        var kcode = $("#kanri").val();
        // 前ゼロ４桁に変換
        kcode = ( "0000" + kcode ).slice(-4);
        // セットし直し
        $("#kanri").val( kcode );
    }

    return true;
}
</script>
</head>

<body>
<h3 class="alert alert-primary">社員マスタメンテ</h3>
<div id="content">

    <form method="post"
        onsubmit="return check()">
    <div>
        <div class="entry left">社員コード</div>
        <div class="entry right">
            <input class="form-control w100"
                required
                <?= $readonly_1 ?>
                maxlength="4"
                pattern="[0-9]+"
                placeholder="9999"
                type="text"
                name="scode"
                id="scode"
                value="<?= $_POST["scode"] ?>">
        </div>
        <input <?= $disabled_1 ?> type="submit" name="btn" id="btn" class="btn btn-primary ms-4" value="確認">
        <input class="ms-3 btn btn-primary" <?= $disabled_1 ?> type="button" id="btn3" value="参照" onclick="ref2()">
        <a class="ms-4 btn btn-success" href="print/mysql-query.php" target="print">社員一覧</a>
    </div>

    <div class="body">
        <div class="entry left">氏名
        </div>
        <div class="entry right">
            <input class="form-control data"
                required
                size="50"
                maxlength="50"
                pattern="[ 　一-\u9FA5|ァ-ンヴー|ぁ-んー]+"
                <?= $readonly_2 ?>
                type="text"
                name="sname"
                id="sname"
                value="<?= $_POST["sname"] ?>">
        </div>
    </div>
    <div class="body">
        <div class="entry left">フリガナ
        </div>
        <div class="entry right">
            <input class="form-control data"
                size="50"
                maxlength="50"
                pattern="[ 　ァ-ンヴー]+"
                <?= $readonly_2 ?>
                type="text"
                name="fname"
                id="fname"
                value="<?= $_POST["fname"] ?>">
        </div>
    </div>
    <div class="body">
        <div class="entry left">所属
        </div>
        <div class="entry right">
            <select  class="form-select data w200"
                id="syozoku"
                name="syozoku"
                <?= $disabled_2 ?>>
                <?= $syozoku_option ?>
            </select>
        </div>
    </div>
    <div class="body">
        <div class="entry left">性別
        </div>
        <div class="entry right">
            <select class="form-select data w100"
                id="seibetsu"
                name="seibetsu"
                <?= $disabled_2 ?>>
                <option value="0" <?= $_POST['seibetsu'] == "0" ? "selected" : "" ?>>男</option>
                <option value="1" <?= $_POST['seibetsu'] == "1" ? "selected" : "" ?>>女</option>
            </select>
        </div>
    </div>
    <div class="body">
        <div class="entry left">給与
        </div>
        <div class="entry right">
            <input class="form-control data w100"
                required
                pattern="[0-9]+"
                maxlength="6"
                <?= $readonly_2 ?>
                type="text"
                name="kyuyo"
                id="kyuyo"
                value="<?= $_POST["kyuyo"] ?>">
        </div>
    </div>
    <div class="body">
        <div class="entry left">手当
        </div>
        <div class="entry right">
            <input class="form-control data w100"
                pattern="[0-9]+"
                maxlength="5"
                <?= $readonly_2 ?>
                type="text"
                name="teate"
                id="teate"
                value="<?= $_POST["teate"] ?>">
        </div>
    </div>
    <div class="body">
        <div class="entry left">管理者
        </div>
        <div class="entry right">
            <div class="input-group">
                <input class="form-control data w100"
                    pattern="[0-9]+"
                    maxlength="4"
                    <?= $readonly_2 ?>
                    type="text"
                    name="kanri"
                    id="kanri"
                    value="<?= $_POST["kanri"] ?>">
                <input class="w200 ms-1"
                    <?= $disabled_type_text ?>
                    type="text"
                    name="kanri_name"
                    id="kanri_name"
                    value="<?= $_POST["kanri_name"] ?>">
            </div>
        </div>

        <input class="ms-3 btn btn-primary" <?= $disabled_2 ?> type="button" id="btn2" value="参照" onclick="ref1()">
    </div>
    <div class="body">
        <div class="entry left">生年月日
        </div>
        <div class="entry right">
            <input class="form-control data w200"
                required
                <?= $readonly_2 ?>
                type="date"
                name="birth" 
                id="birth" 
                value="<?= $_POST["birth"] ?>">
        </div>
    </div>

    <div class="mt-4">
        <input <?= $disabled_2 ?> type="submit" name="btn" id="btn" class="btn btn-primary" value="更新">

        <input type="button"
            class="ms-3 btn btn-primary"
            onclick='location.href="<?= $_SERVER["PHP_SELF"] ?>"'
            <?= $disabled_2 ?>
            value="キャンセル">

        <span class="ms-5"><?= $_POST["message"] ?></span>
    </div>

    </form>

</div>

<iframe width="800" height="400" src="req/mysql-query.php" id="iframe1"></iframe>
<iframe width="800" height="400" src="req2/mysql-query.php" id="iframe2"></iframe>

</body>
</html>