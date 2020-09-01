<?php
    session_start();
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <title>提款</title>
</head>
<body>
<?php
if(@$_SESSION["ID"] == NULL){
    echo "<h2 class='text-danger text-center'>違法操作，請重新登入</h2>";
    exit();
}
$userID = $_SESSION["ID"];

?>
    <div class = "text-center">
        <h3>請輸入提款金額</h3>
    </div>
    <form class="container" method = "post">
    <div class="form-group row">
        <label for="text" class="col-2 col-form-label text-right" >金額</label> 
        <div class="col-8">
        <input id="money" name="text" type="text" class="form-control" pattern = "\d{1,6}">
        </div>
    </div> 
    <div class="form-group row">
        <div class="offset-5 col-6 center">
        <button name="checkmon" type="submit" class="btn btn-primary ">確認</button>
        </div>
    </div>
    </form>
    <?php
        if(isset($_POST["checkmon"])){
            require("linksql.php");
            $sql = "select money
            FROM userdata 
            where ID = '$userID';";
            $revalue = mysqli_query($link, $sql);
            $row = mysqli_fetch_assoc($revalue);
            $takemoney = "0";
            if($_POST["text"] != ""){$takemoney = $_POST["text"];}
            $count = (int)$row["money"] - (int)$_POST["text"];
            if((int)$count < 0){ 
                echo "<h2 class='text-danger text-center'>餘額不足，操作失敗</h2>";
                exit();
            }
            $nowTime = date("Y年m月d日 H:i:s");

            $sql = "update userdata set
            money = $count
            where ID = '$userID';";
            $revalue = mysqli_query($link, $sql);

            $sql = "INSERT INTO `userList`(`ID`, `date`, `actionList`) VALUES
            ('$userID', '$nowTime', '提款 $takemoney 元，餘額 $count 元');";
            $revalue = mysqli_query($link, $sql);

            mysqli_close($link);

            $_SESSION["actionList"] = "提款 $takemoney 元，餘額 $count 元";
            $_SESSION["date"] = $nowTime;

            header("Location: final.php");
            exit();
        }
    ?>
</body>
</html>