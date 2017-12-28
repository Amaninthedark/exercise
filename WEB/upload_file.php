
<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $ret=array('strings'=>$_POST,'error'=>'0');

    $fs=array();

    foreach ($_FILES as $name=>$file ) {
        $img = $file['name'];
        $dian=strrchr($img,'.');
        $imgname="head_".time().mt_rand(100,999).$dian;

        $fn=$imgname;
        $ft=strrpos($fn,'.',0);
        $fm=substr($fn,0,$ft);
        $fe=substr($fn,$ft);
        $fp='./Public/img/'.$fn; //这里需要改成您的路径

        move_uploaded_file($file['tmp_name'],$fp);
        $fp='/Public/img/'.$fn; //这里是返回的数据
 
        $fs[$name]=array('name'=>$fn,'url'=>$fp,'type'=>$file['type'],'size'=>$file['size']);
    }

    $ret['files']=$fs;

    echo json_encode($ret);
}else{
    echo "{'error':'Unsupport GET request!'}";
}


?>
