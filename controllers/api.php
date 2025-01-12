<?php

session_start();

require_once "../vendor/autoload.php";
use Sonata\GoogleAuthenticator\GoogleAuthenticator;

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->load(__DIR__ . '/../.env');
$db = $_ENV['db'] ?? '';
$agnt = $_ENV['agent'] ?? '';
$api_url = "";


$flag = $_GET['flag'];
//echo $flag;
//die("..");
$secret = "";

if($flag=='removeAppliedPromo')
{
  // echo "removeAppliedPromo";
  // die("..");
  
   $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
   $db = $con->selectDatabase('gms2024');
   $tbl = $db->selectCollection('users');
   $updateResult = $tbl->updateOne(
    ['_id' => new \MongoDB\BSON\ObjectID($_GET['id'])],
    [
        '$set' => [
            "promotionId" => null
           

        ]
    ]
   
);
header('Location: ../member-details.php ', true);
exit();
}
if ($flag == 'delPromo') {
    $pid = 0;
    if (isset($_GET['pid'])) {
        $pid = $_GET['pid'];
        $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
        $db = $con->selectDatabase('gms2024');
        $tbl = $db->selectCollection('promotions');
        $tbl->deleteOne(array('_id' => new MongoDB\BSON\ObjectId($pid)));
        header('Location: ../promote.php ', true);
        exit();
    }
}
if($flag=='manualDebit')
{
    $phn=$_POST['uphonee'];
   // die($phn);
   // $name = $_FILES['file']['name'];
   // $temp = $_FILES['file']['tmp_name'];
    $bal=$_POST['balancee'] - $_POST['withAmt'];
    $dt = new DateTime(date('Y-m-d'), new DateTimeZone('UTC'));
$ts = $dt->getTimestamp();
$today = new \MongoDB\BSON\UTCDateTime(time()*1000);
    
  // echo $bal;
  // die('');

//    if (move_uploaded_file($temp, "/var/www/html/ama-bundai/uploads/slips/" . $name)) {
//         echo "Your file was uploaded";
//     } else {
//         echo "Your file cound't upload";
//     }

  
   $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
   $db = $con->selectDatabase('gms2024');
   $tbl = $db->selectCollection('users');
   $updateResult = $tbl->updateOne(
    ['_id' => new \MongoDB\BSON\ObjectID($_GET['id'])],
    [
        '$set' => [
            "balance" => $bal
           

        ]
    ]
   
);
$con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
$db = $con->selectDatabase('gms2024');
$tbl = $db->selectCollection('transactions');
$document = array(
    "userid" => new MongoDB\BSON\ObjectId($_GET['id']),
    "platform" => 'luckyama',
    "userPhone" => $phn,
    "orderNo" => 'manual',
    "responseCode" => '0',
    "provider" => 'bigpayz',
    "status" => 'Manual Debit',
    "payAmount" => (int)$_POST['withAmt'],
    "type" => 'withdrawal',
    "date" => $today,
    "__v" => 0,
);
$tbl->insertOne($document);
header('Location: ../manage-members.php', true);
exit();
}
if($flag=='manualDeposit')
{
    $phn=$_POST['uphone'];
    $name = $_FILES['file']['name'];
    $temp = $_FILES['file']['tmp_name'];
    $bal=$_POST['balance'] + $_POST['amount'];
    $dt = new DateTime(date('Y-m-d'), new DateTimeZone('UTC'));
$ts = $dt->getTimestamp();
$today = new \MongoDB\BSON\UTCDateTime(time()*1000);
    
  // echo $bal;
  // die('');

   if (move_uploaded_file($temp, "/var/www/html/ama-bundai/uploads/slips/" . $name)) {
        echo "Your file was uploaded";
    } else {
        echo "Your file cound't upload";
    }

  
   $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
   $db = $con->selectDatabase('gms2024');
   $tbl = $db->selectCollection('users');
   $updateResult = $tbl->updateOne(
    ['_id' => new \MongoDB\BSON\ObjectID($_GET['id'])],
    [
        '$set' => [
            "balance" => $bal
           

        ]
    ]
   
);
                                                 




    $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
    $db = $con->selectDatabase('gms2024');
    $tbl = $db->selectCollection('transactions');
    $document = array(
        "userid" => new MongoDB\BSON\ObjectId($_GET['id']),
        "platform" => 'luckyama',
        "userPhone" => $phn,
        "orderNo" => 'manual',
        "responseCode" => '0',
        "provider" => 'bigpayz',
        "status" => 'Manual Credit',
        "payAmount" => (int)$_POST['amount'],
        "type" => 'deposit',
        "date" => $today,
        "__v" => 0,
    );
    $tbl->insertOne($document);
    header('Location: ../manage-members.php', true);
    exit();
}
if ($flag == 'createPromotion')//updPromotion
{
    $name = $_FILES['file']['name'];
    $temp = $_FILES['file']['tmp_name'];
//print_r($name);
//print_r($temp);
//die('...');
    if (move_uploaded_file($temp, "/var/www/html/ama-bundai/uploads/" . $name)) {
        echo "Your file was uploaded";
    } else {
        echo "Your file cound't upload";
    }

    //print "</pre>";
    $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
    $db = $con->selectDatabase('gms2024');
    $tbl = $db->selectCollection('promotions');
    $document = array(
        "photo" => $name,
        "title" => $_POST['title'],
        "details" => $_POST['details'],
        "promoCode" => $_POST['promoCode'],
        "status" => $_POST['status'],
        "expDate" => $_POST['expdt'],
      
        "percentBonus" => $_POST['percentBonus'],
        "depositAmnt" => $_POST['depositAmnt'],
        "bonusAmnt" => $_POST['bonusAmnt'],
        "turnover" => $_POST['turnover'],
        "highestPercent" => $_POST['highestPercent'],
        "permissions" => $_POST['permissions'],
        "agentname" => $agnt,
        "bonusCategory"=>$_POST['bonusCategory'],
        "games" => $_POST['games']

    );

    $tbl->insertOne($document);
    header('Location: ../promote.php', true);
    exit();
}

if ($flag == 'updPromotion')//
{
    if (isset($_GET['id'])) {


        $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
        $db = $con->selectDatabase('gms2024');
        $tbl = $db->selectCollection('promotions');

        $updateResult = $tbl->updateOne(
            ['_id' => new \MongoDB\BSON\ObjectID($_GET['id'])],
            [
                '$set' => [
                    "title" => $_POST['title'],
                    "details" => $_POST['details'],
                    "promoCode" => $_POST['promoCode'],
                    "status" => $_POST['status'],
                    "expDate" => $_POST['expdt'],
                     "percentBonus" => $_POST['percentBonus'],
                    "depositAmnt" => $_POST['depositAmnt'],
                    "bonusAmnt" => $_POST['bonusAmnt'],
                    "turnover" => $_POST['turnover'],
                    "highestPercent" => $_POST['highestPercent'],
                    "permissions" => $_POST['permissions'],
                    "agentname" => $agnt,
                    "bonusCategory"=>$_POST['bonusCategory'],
                    "games" => $_POST['games']

                ]
            ]
        );


        header('Location: ../promote.php', true);
        exit();
    }
}


if ($flag == 'createMem') {
    if (isset($_POST['name'])) {


        $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
        $db = $con->selectDatabase('gms2024');
        $tbl = $db->selectCollection('users');
        $document = array(
            "phone" => $_POST['phone'],

            "__v" => 0,
            "status" => $_POST['status']
        );

        $tbl->insertOne($document);
        header('Location: ../manage-members.php', true);
        exit();
    }
}

if ($flag == 'actDeact') {
   
    // print_r($_GET["uname"]);
    // print_r($_GET["stats"]);
   
    // die('..');
    $uname = $_GET["uname"];
    $status = $_GET["stats"];
    $newStatus = $status;
    if ($status == 'Active') {
        $newStatus = 'Active';
    }
    if ($status == 'Block') {
        $newStatus = 'Block';
    }
    if ($status == 'Blacklist') {
        $newStatus = 'Blacklist';
    }
    $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
    $db = $con->selectDatabase('gms2024');
    $tbl = $db->selectCollection('users');


    $updateResult = $tbl->updateOne(
        ['name' => $uname],
        [
            '$set' => [

                "status" => $newStatus,


            ]
        ]
    );

    // $tbl->insertOne($document);
     header('Location: ../manage-members.php?updated', true);
   // echo 'success';
    exit();


}
if ($flag == 'actDeactMem') {
   
    // print_r($_GET["uname"]);
    // print_r($_GET["stats"]);
   
    // die('..');
    $uname = $_GET["uname"];
    $status = $_GET["stats"];
    $newStatus = $status;
    if ($status == 'Active') {
        $newStatus = 'Block';
    }
    if ($status == 'Block') {
        $newStatus = 'Active';
    }
    if ($status == 'Blacklist') {
        $newStatus = 'Active';
    }
    $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
    $db = $con->selectDatabase('gms2024');
    $tbl = $db->selectCollection('users');


    $updateResult = $tbl->updateOne(
        ['name' => $uname],
        [
            '$set' => [

                "status" => $newStatus,


            ]
        ]
    );

    // $tbl->insertOne($document);
     header('Location: ../manage-members.php?updated', true);
   // echo 'success';
    exit();


}
if (isset($_SESSION['storedSecrect'])) {
    $secret = $_SESSION['storedSecrect'];
    // print_r($secret);
    //die('..');
}
if ($flag == 'qrscan') {
    $_SESSION["qrscanned"] = "true";
    $code=$_POST["2fa"];
    $secret = $_SESSION['storedSecrect'];
    print_r($code);
    $googleAuthenticator = new GoogleAuthenticator();
    if ($googleAuthenticator->checkCode($secret, $code)) {
    header('Location: ../dashboard.php ', true);
    }
    else
    {
        header('Location: ../qr-login.php?status=invalid2fa ', true);
       
    }
    exit();
}

if ($flag == 'login') {
   // echo "api page - database connection error";

    $uname = $_POST['uname'];
    // die($uname);

    $password = $_POST['pass'];
    
    
    //  if ($g->checkCode($secret, $code)) {
    $_SESSION["auth"] = $secret;

    $mongo = new MongoDB\Driver\Manager("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
    $filter = ['userid' => $uname, 'pwd' => $password];
    $options = [];
    $query = new MongoDB\Driver\Query($filter, $options);
    $rows = $mongo->executeQuery($db . '.agents', $query);
    $agentArr = $rows->toArray();
    //echo count($agentArr);
    //die('');
    if (count($agentArr) > 0) {
        $_SESSION["uid"] = $uname;
        $_SESSION["prefix"] = $agentArr[0]->prefix;

        if (property_exists($agentArr[0], 'type')) {
            $_SESSION["utype"] = $agentArr[0]->type;
             $_SESSION["access"]=$agentArr[0]->permissions;
            $_SESSION["platform"] = $agentArr[0]->platform;
        } else {
            $_SESSION["utype"] = $agentArr[0]->agentid;
        }
        if ($agentArr[0]->parent == 'master') {
            $_SESSION['agent'] = 'master';//master agent login
        } else {
            $_SESSION['agent'] = $uname;//agent and other users
        }
       // if (!isset($_SESSION["qrscanned"])) {
            
            header('Location: ../qr-login.php ', true);
            exit();
      //  } else {
          //  header('Location: ../dashboard.php ', true);
          //  exit();
     //   }
    } else {
        $filter = ['phone' => $uname, 'rpwd' => $password];
        $options = [];
        $query = new MongoDB\Driver\Query($filter, $options);
        $rows = $mongo->executeQuery($db . '.users', $query);
        $agentArr = $rows->toArray();
        //echo count($agentArr);
        // die("");

        $_SESSION["uid"] = $agentArr[0]->name;


        $_SESSION["utype"] = "PLAYER";

        if ($agentArr[0]->parent == 'master') {
            $_SESSION['agent'] = 'master';//master agent login
        } else {
            $_SESSION['agent'] = $uname;//agent and other users
        }
      //  if (!isset($_SESSION["qrscanned"])) {
            header('Location: ../qr-login.php ', true);
            exit();
      //  } else {
           // header('Location: ../dashboard.php ', true);
          //  exit();
      //  }






        //header('Location: ../index.php?sts=invalid_credentials', true);
        //exit();

    }
    //die($agentArr);


    // } 
    // else {

    // header('Location: ../index.php?sts=invalid2facode', true);
    // exit();

    // }


}
if ($flag == "updAgent") {

    if (isset($_GET['id'])) {
        //  print_r($_SESSION['agent']);
        // die("");
        $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
        $db = $con->selectDatabase('gms2024');
        $tbl = $db->selectCollection('agents');
        $document = array(
            "agentname" => $_POST['agentname'],
            "userid" => $_POST['userid'],
            "pwd" => $_POST['password'],
            "percentage" => $_POST['percentage'],
            "__v" => 0,
            "platform" => $_POST['platform'],
            "parent" => $_SESSION['agent'],
            "url" => $_POST['url'],
            "prefix" => "SBGT"
        );


        $updateResult = $tbl->updateOne(
            ['agentid' => $_GET['id']],
            [
                '$set' => [
                    "agentname" => $_POST['agentname'],
                    "userid" => $_POST['userid'],
                    "pwd" => $_POST['password'],
                    "percentage" => $_POST['percentage'],


                    "platform" => $_POST['platform'],
                    "url" => $_POST['url'],
                ]
            ]
        );

        // $tbl->insertOne($document);
        header('Location: ../manage-agent.php', true);
        exit();

    }

}
if ($flag == "newAgent") {

    if (isset($_SESSION['agent'])) {
        //  print_r($_SESSION['agent']);
        // die("");
        $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
        $db = $con->selectDatabase('gms2024');
        $tbl = $db->selectCollection('agents');
        $document = array(
            "agentname" => $_POST['agentname'],
            "userid" => $_POST['userid'],
            "pwd" => $_POST['password'],
            "percentage" => $_POST['percentage'],
            "type" => "SBGT",
            "agentid" => $_POST['agentid'],
            "__v" => 0,
            "platform" => $_POST['platform'],
            "parent" => $_POST['parent'],
            "url" => $_POST['url'],
            "prefix" => "SBGT"
        );

        $tbl->insertOne($document);
        header('Location: ../manage-subagent.php', true);
        exit();

    }

}

if ($flag == "newEmp") {

    if (isset($_POST['uname'])) {
        $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
        $db = $con->selectDatabase('gms2024');
        $tbl = $db->selectCollection('agents');
        $document = array(
            "agentname" => $_POST['name'],
            "phone" => $_POST['phone'],
            "userid" => $_POST['uname'],
            "pwd" => $_POST['pass'],
            "shift" => $_POST['shift'],
            "permissions" => $_POST['permissions'],
            "type" => "EMPLOYEE",
            "agentid" => "luckyama-" . $_POST['uname'],
            "__v" => 1,
            "platform" => "luckyama",
            "manualLimit" => $_POST['manualLimit'],
            "url" => "",
            "prefix" => "EMPLOYEE"
        );

        $tbl->insertOne($document);
        header('Location: ../manage-employees.php', true);
        exit();

    }

}

if ($flag == "updEmp") {
    if (isset($_GET['id'])) {

        //print_r($_POST['permissions']);
        //die('');
        // print_r($_GET['id']);
        // die("");
        $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
        $db = $con->selectDatabase('gms2024');
        $tbl = $db->selectCollection('agents');


        $updateResult = $tbl->updateOne(
            ['userid' => $_GET['id']],
            [
                '$set' => [

                    "agentname" => $_POST['name'],
                    "phone" => $_POST['phone'],
                    "userid" => $_POST['uname'],
                    "pwd" => $_POST['pass'],
                    "shift" => $_POST['shift'],
                    "permissions" => $_POST['permissions'],
                    "manualLimit" => $_POST['manualLimit'],
                    "agentid" => "luckyama-" . $_POST['uname'],
                    "__v" => 1,

                    "url" => "",

                ]
            ]
        );

        // $tbl->insertOne($document);
        header('Location: ../manage-employees.php', true);
        exit();

    }
}

if ($flag == "settings") {
    //if (isset($_POST['uid'])) {

    print_r($_POST['percentage']);
    // die('');
    //  print_r($_SESSION['agent']);
    // die("");
    $con = new MongoDB\Client("mongodb+srv://nicheelee24:IEX63RWtZZ5CSSuD@serverlessinstance0.uesvbfh.mongodb.net/?retryWrites=true&w=majority&appName=ServerlessInstance0&serverSelectionTryOnce=false&serverSelectionTimeoutMS=30");
    $db = $con->selectDatabase('gms2024');
    $tbl = $db->selectCollection('settings');

    $document = array(
        "username" => 'user1',
        "percentage" => $_POST['percentage'],
        "agentid" => 'agent1',

    );

    $tbl->insertOne($document);

    //   $updateResult = $tbl->updateOne(
//      [ 'userid' => $_POST['uid']],
//      [ '$set' =>[ 

    //        "agentname" => $_POST['name'],
//             "phone" => $_POST['phone'],
//             "userid" => $_POST['uname'],
//             "pwd" => $_POST['pass'],
//             "shift" => $_POST['shift'],
//             "permissions" => $_POST['permissions'],

    //             "agentid" => "Testla-" . $_POST['uname'],
//             "__v" => 1,

    //             "url" => "",

    //               ]
//               ]
//   );

    // $tbl->insertOne($document);
    header('Location: ../manage-employees.php', true);
    exit();

    //  }
}


function httpPost($url, $params, $redirect_url)
{
    $postData = http_build_query($params);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    // curl_setopt($ch, CURLOPT_POST, $postData);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    $output = curl_exec($ch);
     print_r($output);
    $rslt = json_decode($output, true);
     print_r($rslt['result']['token']);
     die("..");
    curl_close($ch);
    if ($rslt['status']) {
        $_SESSION["auth"] = $rslt['result']['token'];
        header('Location: ../' . $redirect_url, true);
        exit();
    } else {
        header('Location: ../index.php?sts=invalid_credentials', true);
        exit();
    }


}




?>