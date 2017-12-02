<?php
	class UserAction extends Action{


		//注册
		public function register(){

				$phone =$_GET['phone_data'];
				$password=md5($_GET['password_data']);

				//判断手机号码是否存在
				$arr=M('user')->where("`phone`='$phone'")->find();
				if($arr){
					echo -1;
					exit;
				} 

			    $data['phone']  =$phone;
			     $data['nicename']  ='中国移动';
			     $data['password']  =$password;
			     $data['time']=time();
			    $re = M('user')->add($data);
			    echo $re;
		} 

		//登录
		public function login(){
			$phone =$_GET['phone_data'];
				$password=md5($_GET['password_data']);

				//判断手机号码是否存在
				$user=M('user')->where("`phone`='$phone'")->find();
				if($user==''){
					echo -1; 
					exit; 
				}
				else{
					if($password==$user['password']){
						echo 1;
					}else{
						echo 0; 
					} 
				}

			}
		//获取用户信息
		public function get_user_info(){
				$phone =$_GET['phone_data']; 
			$arr=M('user')->where("`phone`='$phone'")->find();
			echo json_encode($arr);
		} 
	} 
?>   