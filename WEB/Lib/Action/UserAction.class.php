<?php

ini_set("display_errors", "on");

require_once dirname(__DIR__) . '/api_sdk/vendor/autoload.php';
require_once 'AipFace.php';
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;

const APP_ID = '10599908';
			const API_KEY = 'IPm1XFE6vQEAShWPI0p4q2Xp';
			const SECRET_KEY = 'VEyvTxKoFpRWyGGtieZq3Et0UoY1gk77';

			
// 加载区域结点配置
Config::load();

	class UserAction extends Action{

		public function Aip(){
		$client = new AipFace(APP_ID, API_KEY, SECRET_KEY);
				// 调用人脸检测

				$image1=(string)($_GET['$image1_data']);
			$image1=(string)($_GET['$image2_data']);
				$image2='http://192.168.43.209/exercise/WEB/Public/img/head_1514485688397.jpg';
				$images = array(
			    file_get_contents($image1),
			    file_get_contents($image2), 
			);  
 
				$array =$client->match($images);
			 
				echo json_encode($array) ;  
		}

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
				if($user == ''){
					echo -1; 
					exit; 
				}
				else{
					if($password == $user['password']){
						echo 1;
					} 
					else 
					{
						echo -1;
					}
				}

}
		//获取用户信息
		public function get_user_info(){
				$phone =$_GET['phone_data']; 
			$arr=M('user')->where("`phone`='$phone'")->find();
			echo json_encode($arr);
		}



	static $acsClient = null;

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient() {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        // TODO 此处需要替换成开发者自己的AK (https://ak-console.aliyun.com/)
        $accessKeyId = "LTAIoShvAINn3ie1"; // AccessKeyId

        $accessKeySecret = "ppKkuNVaXYjrxBUGRsug2UtSlWbiin"; // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";


        if(static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 发送短信
     * @return stdClass
     */
    public static function sendSms() {
    	$code=$_GET['code_data'];
    	$phone=$_GET['phone_data']; 
        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置短信接收号码
        $request->setPhoneNumbers($phone);

        // 必填，设置签名名称，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
        $request->setSignName("王杭");

        // 必填，设置模板CODE，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
        $request->setTemplateCode("SMS_115765516");

        // 可选，设置模板参数, 假如模板中存在变量需要替换则为必填项
        $request->setTemplateParam(json_encode(Array(  // 短信模板中字段的值
            "code"=>$code, 
            "product"=>"dsd"
        )));

        // 可选，设置流水号
      //  $request->setOutId("yourOutId");

        // 选填，上行短信扩展码（扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段）
        $request->setSmsUpExtendCode("1234567");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;

    }

    /**
     * 短信发送记录查询
     * @return stdClass
     */
    public static function querySendDetails() {

        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();

        // 必填，短信接收号码
        $request->setPhoneNumber("12345678901");

        // 必填，短信发送日期，格式Ymd，支持近30天记录查询
        $request->setSendDate("20170718");

        // 必填，分页大小
        $request->setPageSize(10);

        // 必填，当前页码
        $request->setCurrentPage(1);

        // 选填，短信发送流水号
        $request->setBizId("yourBizId");

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);

        return $acsResponse;
    } 
	} 
?>   