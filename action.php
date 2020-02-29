<?php
/*
 * PHP page to get all the action request...
 * @Author: Suraj Roy
 * @Date  :   30 August 2016
 * @Source: https://jsonworld.com
 * @Topic : CRUD with Angularjs, PHP & mysql.
 */        

include 'connect.php';
$db = new DATABASE();
$tblName = 'ep_item';


if(isset($_REQUEST['type']) && !empty($_REQUEST['type'])){ 
    $type = $_REQUEST['type'];
    
    switch($type){
        case "view":
            if ($_SESSION['log']=0) die();
            
            $records = $db->getRows($tblName);  
            if($records){
                $resp['records'] = $db->getRows($tblName);
                $resp['status'] = 'OK';
            }else{
                $resp['records'] = array();
                $resp['status'] = 'ERR';
            }
            echo json_encode($resp);
            break;

        case "add":
            if ($_SESSION['log']=0) die();
            
            if(!empty($_POST['data'])){
                $userData = array(
                    'item_code' => $_POST['data']['item_code'],
                    'item_name' => $_POST['data']['item_name'],
                    'item_price' => $_POST['data']['item_price']
                );                
                $item_code  = $_POST['data']['item_code']  ? $_POST['data']['item_code'] : '';
                $item_name = $_POST['data']['item_name'] ? $_POST['data']['item_name'] : '';
                $item_price = $_POST['data']['item_price'] ? $_POST['data']['item_price'] : '';
                
                $res = $db->add_item($item_code,$item_name,$item_price);
                if($res){
                    $d = array(
                        "item_id"=> $res,
                        "item_code" => $item_code,
                        "item_name" => $item_name,
                        "item_price" => $item_price
                    );
                    $resp['data'] = $d;
                    $resp['status'] = 'OK';
                    $resp['msg'] = 'item has been added successfully.';
                }else{
                    $resp['status'] = 'ERR';
                    $resp['msg'] = 'Some problem occurred, please try again.';
                }
            }else{
                $resp['status'] = 'ERR';
                $resp['msg'] = 'Some problem occurred, please try again.';
            }
            echo json_encode($resp);
            break;

        case "edit":
            if ($_SESSION['log']=0) die();
            if(!empty($_POST['data'])){
                $update = $db->update($_POST['data']['item_code'],$_POST['data']['item_name'],$_POST['data']['item_price'],$_POST['data']['item_id']);
                if($update){
                    $resp['status'] = 'OK';
                    $resp['msg'] = 'item data has been updated successfully.';
                }else{
                    $resp['status'] = 'ERR';
                    $resp['msg'] = 'Some problem occurred, please try again.';
                }
            }else{
                $resp['status'] = 'ERR';
                $resp['msg'] = 'Some problem occurred, please try again.';
            }
            echo json_encode($resp);
            break;

        case "delete":
            if ($_SESSION['log']=0) die();
            if(!empty($_POST['item_id'])){
                $id = $_POST['item_id'];

                $delete = $db->delete($id);
                if($delete){
                    $resp['status'] = 'OK';
                    $resp['msg'] = 'item data has been deleted successfully.';
                }else{
                    $resp['status'] = 'ERR';
                    $resp['msg'] = 'Something went wrong.';
                }
            }else{
                $resp['status'] = 'ERR';
                $resp['msg'] = 'Something went wrong.';
            }
            echo json_encode($resp);
            break;

        case "login":
            $data=$_REQUEST['data'];   
            if(!empty($data)){     
                   
                /*
                var_dump($data);
                die();
                $dat = json_decode($data);
                $sho = $dat->sho;
                $nam = $dat->nam;
                $pas = $dat->pas;
                */
                $sho = $data['sho'];
                $nam = $data['nam'];
                $pas = $data['pas'];

                
                $qry ="SELECT  s.shopname,u.username,u.usertype  FROM ep_user as u 
                LEFT JOIN ep_shop as s ON s.sid = u.sid  
                WHERE u.usercode='$nam' AND u.userpass='$pas' AND s.shopcode='$sho'";

                $res = $db->readQryRows($qry);
                //$res = json_encode($db->readQryRows($qry));
                //echo $res);
                //echo $res[0]->usertype;
                //die();
                //$res = $db->qryRows($qry);
                
                if($res[0]->usertype >0){
                    $resp['status'] = 'OK';
                    $resp['msg'] = $res[0];
                    $_SESSION['log']=$res[0]->usertype;

                }else{
                    $resp['status'] = 'NO';
                    $resp['msg'] = 'User Not Found.';
                }
            }else{
                $resp['status'] = 'ERR';
                $resp['msg'] = 'Some problem occurred, please try again.';
            }
            echo json_encode($resp);
            break;
        case "logout":
            $_SESSION['log']=0;
            break;
        default:
            echo '{"status":"INVALID"}';
    }
}
