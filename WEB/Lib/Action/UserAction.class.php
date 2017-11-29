<?php
	class UserAction extends Action{


		//注册
		public function register(){

			    $data['phone']  =$_GET['phone_data'];
			     $data['nicename']  ='中国移动';
			     $data['password']  =md5($_GET['password_data']);
			    $re = M('user')->add($data);
			    echo $re;
		} 
	}
?> 