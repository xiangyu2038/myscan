<?php
require_once '../vendor/autoload.php';
use Illuminate\Database\Capsule\Manager as Capsule;
////扫描模块
class Scan{
    static $client_id=null;///客户端id
    static $message=null;///扫描的消息
    static $batch_id=null;///批次id
    static $scan_type=null;///扫描类型
    ///
    ///
    static $pre_collection=null;//上一批次扫描的集合
    static $cur_data=['collection'=>'','units'=>[]];//初始化内容

     static $is_close='false';///是否执行了封箱
    ////初始化数据库
    public static function connect($client_id)
    {
        ////初始化数据库链接
        $capsule = new Capsule;

//        $config = [
//                    // 数据库类型
//                    'driver'            => 'mysql',
//                    // 服务器地址
//                    'host'        => '106.14.127.60',
//                    'collation' => 'utf8_unicode_ci',
//                    // 数据库名
//                    'database'        => 'halfrintest',
//                    // 用户名
//                    'username'        => 'halfrintest',
//                    // 密码
//                    'password'        => 'halfrintest',
//                    // 端口
//                    'hostport'        => '',
//                    // 连接dsn
//                    'dsn'             => '',
//                    // 数据库连接参数
//                    'params'          => [],
//                    // 数据库编码默认采用utf8
//                    'charset'         => 'utf8',
//                    // 数据库表前缀
//                    'prefix'          => '',
//                    // 数据库调试模式
//                    'debug'           => true,
//                    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
//                    'deploy'          => 0,
//                    // 数据库读写是否分离 主从式有效
//                    'rw_separate'     => false,
//                    // 读写分离后 主服务器数量
//                    'master_num'      => 1,
//                    // 指定从服务器序号
//                    'slave_no'        => '',
//                    // 是否严格检查字段是否存在
//                    'fields_strict'   => true,
//                    // 数据集返回类型
//                    'resultset_type'  => 'array',
//                    // 自动写入时间戳字段
//                    'auto_timestamp'  => false,
//                    // 时间字段取出后的默认时间格式
//                    'datetime_format' => 'Y-m-d H:i:s',
//                    // 是否需要进行SQL性能分析
//                    'sql_explain'     => false,
//                    // Builder类
//                    'builder'         => '',
//                    // Query类
//                    'query'           => '\\think\\db\\Query',
//                    // 是否需要断线重连
//                    'break_reconnect' => false,
//                    // 断线标识字符串
//                    'break_match_str' => [],
//                ];
        $config = [
                    // 数据库类型
                    'driver'            => 'mysql',
                    // 服务器地址
                    'host'        => 'rm-bp14ky94j030f0gb09o.mysql.rds.aliyuncs.com',
                    'collation' => 'utf8_unicode_ci',
                    // 数据库名
                    'database'        => 'size',
                    // 用户名
                    'username'        => 'halfrin',
                    // 密码
                    'password'        => 'Halfrin@888',
                    // 端口
                    'hostport'        => '',
                    // 连接dsn
                    'dsn'             => '',
                    // 数据库连接参数
                    'params'          => [],
                    // 数据库编码默认采用utf8
                    'charset'         => 'utf8',
                    // 数据库表前缀
                    'prefix'          => '',
                    // 数据库调试模式
                    'debug'           => true,
                    // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
                    'deploy'          => 0,
                    // 数据库读写是否分离 主从式有效
                    'rw_separate'     => false,
                    // 读写分离后 主服务器数量
                    'master_num'      => 1,
                    // 指定从服务器序号
                    'slave_no'        => '',
                    // 是否严格检查字段是否存在
                    'fields_strict'   => true,
                    // 数据集返回类型
                    'resultset_type'  => 'array',
                    // 自动写入时间戳字段
                    'auto_timestamp'  => false,
                    // 时间字段取出后的默认时间格式
                    'datetime_format' => 'Y-m-d H:i:s',
                    // 是否需要进行SQL性能分析
                    'sql_explain'     => false,
                    // Builder类
                    'builder'         => '',
                    // Query类
                    'query'           => '\\think\\db\\Query',
                    // 是否需要断线重连
                    'break_reconnect' => false,
                    // 断线标识字符串
                    'break_match_str' => [],
                ];

// 创建链接
            $capsule->addConnection($config, 'default');
// 设置全局静态可访问
            $capsule->setAsGlobal();
// 启动Eloquent
            $capsule->bootEloquent();

        }
    public static function init($client_id, $message,$batch_id,$scan_type){
        self::setScanInfo($client_id, $message,$batch_id,$scan_type);////初始化扫描信息

        if(!file_exists('scan/'.self::getClientId())){
            ///如果不存在 写入空文件
            self::inWrite(self::$cur_data,'scan/'.self::getClientId());///写入空文件
        }
        if(!file_exists('scanpackage/'.self::getBatchId())){
            ///如果不存在 写入本批次所存入的包裹信息
            $data=[];
            self::inWrite($data,'scanpackage/'.self::getBatchId());///写入空文件
        }

        if(!file_exists('scanbox/'.self::getBatchId())){
            ///如果不存在 写入本批次所存入的包裹信息
            $data=[];
            self::inWrite($data,'scanbox/'.self::getBatchId());///写入空文件
        }

    }
    /////前端发来扫描数据
    public static function onMessage()
    {

        try{
          if(self::getMessage()=='over'){
              $collection=self::read()['collection'];
              //手动触发结束扫描
              self::dealPrevious(); //处理上一个集合的数据
              self::clearCollection();                  ///处理完毕 清除上一个集合的所有数据


              if(self::getScanType()=='package'){

                  $scan_data= self::readCache('scanpackage/'.self::getBatchId());
                  if(isset($scan_data[$collection])){
                      $scan_data=$scan_data[$collection];
                  }else{
                      $scan_data=[];
                  }

              }elseif(self::getScanType()=='box'){
                  $scan_data = self::readCache('scanbox/'.self::getBatchId());
                  if(isset($scan_data[$collection])){
                      $scan_data=$scan_data[$collection];
                  }else{
                      $scan_data=[];
                  }
              }else{
                  $scan_data=[];
              }


            //  dd(['code'=>1,'msg'=>'ok','data'=>['cur_code'=>self::getMessage(),'collection'=>$collection,'collection_data'=>$scan_data],'is_close'=>self::$is_close,'pre_collection'=>self::$pre_collection]);



              echo  json_encode(['code'=>1,'msg'=>'ok','data'=>['cur_code'=>self::getMessage(),'collection'=>$collection,'collection_data'=>$scan_data],'is_close'=>self::$is_close,'pre_collection'=>self::$pre_collection]);
          }else{
              self::handle();
              $collection=self::read()['collection'];
              if(self::getScanType()=='package'){
                  $scan_data= self::readCache('scanpackage/'.self::getBatchId());
                  if(isset($scan_data[$collection])){
                      $scan_data=$scan_data[$collection];
                  }
              }elseif(self::getScanType()=='box'){

                  $scan_data = self::readCache('scanbox/'.self::getBatchId());
                  if(isset($scan_data[$collection])){
                      $scan_data=$scan_data[$collection];
                  }
              }else{
                 $scan_data=[];
              }

//dd(['code'=>1,'msg'=>'ok','data'=>['cur_code'=>self::getMessage(),'collection'=>$collection,'collection_data'=>$scan_data],'is_close'=>self::$is_close,'pre_collection'=>self::$pre_collection]);
              echo  json_encode(['code'=>1,'msg'=>'ok','data'=>['cur_code'=>self::getMessage(),'collection'=>self::read()['collection'],'collection_data'=>$scan_data],'is_close'=>self::$is_close,'pre_collection'=>self::$pre_collection]);
          }


       }
       catch (Exception $e){
           $msg = $e->getMessage();
         //dd($msg);

           //dd(['code'=>0,'msg'=>'ok','data'=>$msg]);
           echo  json_encode(['code'=>0,'msg'=>$msg,'data'=>[]]);
       }

        //  self::cache($client_id,$message);

    }

    ////对扫描的包裹进行处理
    public static function handle(){
        $info=self::resolve();///解析参数  扫描类型 返回是集合还是单位

        if($info['type']=='collection'){

                 self::dealPrevious(); //处理上一个集合的数据

               self::clearCollection();                  ///处理完毕 清除上一个集合的所有数据

             ///本次扫描数据存入本批次的缓存中
           if(self::getScanType()=='package'){
               self::saveCacheWithPackage();
           }elseif (self::getScanType()=='box'){

               self::saveCacheWithBox();
           }


            self::saveNewCollection();////保存新的集合进去

            ///等待下一次扫描
        }else if ($info['type']=='units'){

           ///////首先把这个单位写入本地
            if(self::getScanType()=='box'){
                ///属于箱子的哦

                self::saveCacheWithUnit('package');
            }else{
                ///属于学生的哦
                self::saveCacheWithUnit('fashion');
            }

            /////首先判断有没有宿主
            if(self::hasPreCollection()){
                ///有集合
                self::debug('有宿主，保存单元数据');
                self::saveNewUnits();///保存起来备用
            }else{
                self::debug('没有宿主，失败');
               ////没有集合
                if(self::getScanType()=='package'){
                    throw new Exception('请先扫描一个包裹');
                }elseif(self::getScanType()=='box'){
                    throw new Exception('请先扫描一个箱子');
                }else{
                    throw new Exception('位置扫描类型,扫描出错');
                }

            }

        }

    }

    ////对数据进行解析  返回这个数据的类型
    public static function resolve(){

        ////解析数据
        $str=substr(self::getMessage(),0,2);///前两位

       if(self::getScanType()=='package'){
           ////这是扫描包裹装箱的
           if($str=='XX'&&strlen(self::getMessage())==12){
               ///说明是包裹集合
               return ['type'=>'collection','belong'=>'student'];///是一个集合 还是学生的集合
           }else{
               return ['type'=>'units','belong'=>'fashions'];
           }
       }else if (self::getScanType()=='box'){

           ////扫描的是装箱这一个动作
           if($str=='PS'&&strlen(self::getMessage())==15){
               ///这是一个箱子的编码
               return ['type'=>'collection','belong'=>'box'];
           }else if($str=='XX'&&strlen(self::getMessage())==12){
               ////是单位 学生包裹单位
                   return ['type'=>'units','belong'=>'student'];
           }else{
               return ['type'=>'units','belong'=>'fashions'];
           }
       }else{
           throw new Exception('未知扫描类型');
       }




    }

    ////缓存数据
    public static function cache($client_id,$message){
         $array=[];

         $array['batch_id']='1';
         $array['batch_id']='1';

    }

    ////设置被刺扫描请求的数据
    public static function setScanInfo($client_id, $message,$batch_id,$scan_type){
        self::$client_id=$client_id;
        self::$message=$message;
        self::$batch_id=$batch_id;
        self::$scan_type=$scan_type;
    }

    ////获取客户端id
    public static function getClientId(){
        return self::$client_id;
    }
    ///获取消息
    public static function getMessage(){
        return self::$message;
    }

    ///获取批次id
    public static function getBatchId(){
        return self::$batch_id;
    }
    /////获取扫描类型
    public static function getScanType(){
        return self::$scan_type;
    }
    ///获取扫描数据集
    public static function getScanData(){
        return self::read();
    }

    public static function dealPrevious(){
         ////判断是否有上一个集合且集合有数据  不为空

         $has_pre_collection=self::hasPreCollection();

        if($has_pre_collection){
            ///r如果存在上一个集合
            ////如果数据不为空吗
              self::$is_close='true';///这是封箱动作
                self::debug('上一个集合存在且不为空');
                self::debug('开始处理上一个集合数据');
                $pre_collection=self::getPreCollectionData();///集合数据

                $pre_units=self::getPreUnitsData();///单元数据

                self::saveData($pre_collection,$pre_units);
            self::debug('上一个集合数据处理完毕');
        }else{
            self::debug('没有上一个集合数据');

        }
    }
////判断是否存在上一个集合 且集合数据不为空
    public static function hasPreCollection(){
        $data = self::read();
         if($data['collection']){
             self::$pre_collection=$data['collection'];
          return true;
         }

        return false;
    }

////判断是否存在上一个单元数据 且集合数据不为空
    public static function hasPreUnits(){
        $units_data=self::getPreUnitsData();
        if($units_data){
            return true;
        }
        return false;
    }
    ////获取上一个集合的数据
    public static function getPreCollectionData(){
       if(self::getScanType()=='package'){
           $array=[];
           $array['student_code']=self::$pre_collection;
           return $array;
       }elseif((self::getScanType()=='box')){
           $array=[];
           $array['order_batch_id']=self::getBatchId();

           $array['code']=substr(self::getScanData()['collection'],-3,strlen(self::getScanData()['collection'])-3);
           return $array;
       }else{
           throw new Exception('未知扫描类型');
       }


    }

    public static function getPreUnitsData(){

        if(self::getScanType()=='package'){
            $units_data=self::getScanData();
            if(isset($units_data['units'])){
                return $units_data['units'];
            }
            return [];
        }elseif((self::getScanType()=='box')){
            $units_data=self::getScanData();
            if(isset($units_data['units'])){
                return $units_data['units'];
            }
            return [];
        }else{
            throw new Exception('未知扫描类型');
        }


    }
    public static function saveData($pre_collection,$pre_units){
        ////对数据 处理
        self::debug('开始保存集合数据');
        $collection_info= self::saveCollection($pre_collection);////保存集合数据  获取保存的属性
        self::debug('集合数据保存成功');
        if($pre_units){
            self::debug('开始保存单位数据');
            self::saveunits($collection_info,$pre_units);///保存单位数据
              self::debug('单位数据保存成功');
        }else{
            self::debug('这个集合没有单元数据');
        }


    }
    public static function saveCollection($pre_collection){

            if(self::getScanType()=='package'){
                ///为扫描的包裹
                 ////首先判断这个包裹存在不
                $has_pre = \App\Models\Admin\ScanStudentModel::where('student_code',$pre_collection['student_code'])->first();
                if($has_pre){
                    self::debug('数据库有这个包裹');
                    $collection_id=$has_pre->id;
                }else{
                    $scan_student = \App\Models\Admin\ScanStudentModel::create($pre_collection);
                    if(!$scan_student){
                        throw new Exception('保存包裹信息失败,扫描异常');
                    }
                    $collection_id=$scan_student->id;
                }

                return ['belong'=>'student','collection_id'=>$collection_id];
            }elseif(self::getScanType()=='box'){
                ////为扫描箱子
                $collection =  \Model\ScanCnModel::where('code',$pre_collection['code'])->where('order_batch_id',$pre_collection['order_batch_id'])->first();

                if(!$collection){
                    throw new Exception('保存箱子信息失败,不存在的箱子,请先结束包裹扫描后再试');
                }
                $collection_id=$collection->id;
                return ['belong'=>'box','collection_id'=>$collection_id];
            }else{
                throw new Exception('不确定扫描类型,扫描异常');
            }

    }

    public static function saveunits($collection_info,$pre_units){
        if(self::getScanType()=='package'){
            ////为扫描包裹
            $data = self::getUnitsSaveDataWithStudent($collection_info,$pre_units);///获取保存包裹下面的产品的信息格式
            \App\Models\Admin\ScanStudentDetailsModel::Insert($data);

        }elseif(self::getScanType()=='box'){
            ////为扫描箱子
            $data = self::getUnitsSaveDataWithBox($collection_info,$pre_units);
            \App\Models\Admin\ScanStudentDetailsModel::Insert($data);
        }else{
            throw new Exception('不确定扫描类型,扫描异常');
        }
    }

    public static function getUnitsSaveDataWithStudent($collection_info,$units){
        $function=function($code,$collection_id){
            $array=[];
            $array['scan_student_id']=$collection_id;
            $array['fashion_code']=$code;
            $array['created_at']=date('Y-m-d H:i:s');
            $array['updated_at']=date('Y-m-d H:i:s');
            // $array['type']=2;
            return $array;
        };
        $array=[];
        foreach ($units as $v){
            $array[]=$function($v,$collection_info['collection_id']);
        }

        return $array;
    }

    public static function clearCollection(){
        if(self::hasPreCollection()){
           //有上一个集合数据 才清除上一个集合数据
            self::debug('清除上一个集合数据');
            self::write('','collection');
            self::debug('清除上一个集合成功');
        }

        if(self::hasPreUnits()){

            self::debug('清除上一个单元数据');
            self::write([],'units');
            self::debug('清除上一个单元数据成功');
        }



    }
    public static function saveNewCollection(){
         self::debug('保存这一次扫描的集合');

         self::write(self::getMessage(),'collection');
        self::debug('保存这一次扫描的集合成功');

        return true;
    }

    public static function saveNewUnits(){

        $units=self::getMessage();
       $pre_units=self::getPreUnitsData();
        array_push($pre_units,$units);
        self::write($pre_units,'units');
    }

    public static function debug($msg){
        // dds($msg);
    }
    public static function getUnitsSaveDataWithBox($collection_info,$units){
        $function=function($code,$collection_id,$units_type){
            $array=[];
            $array['scan_cn_id']=$collection_id;
            $array['code']=$code;
            $array['type']=$units_type;
            $array['created_at']=date('Y-m-d H:i:s');
            $array['updated_at']=date('Y-m-d H:i:s');
            return $array;
        };

        $units_type=self::getUnitType($units);///得到一个单位的类型

        $array=[];
        foreach ($units as $v){
            /////判断单位是什么
            $array[]=$function($v,$collection_info['collection_id'],$units_type);
        }

        return $array;
    }
    public static function getUnitType($units){
        foreach ($units as $v){
            $str=substr($v,0,2);
            if($str=='XX'&&strlen($v)==12){
                return '2';//包裹
            }else{
                return 1;///商品
            }
        }
    }

    ///初始化写入数据
    public static function inWrite($array,$filename){



        $content=serialize($array);
        // file_put_contents('scan/test', $content);//写入缓存文件
        $data = file_put_contents($filename, $content);//写入缓存文件

        if(!$data){
            throw new Exception('初始化写入数据失败');
        }
    }

    /////保存一个客户端的数据到本地
    public static function write($array,$type){

        $con=self::getScanData();

           $con[$type]=$array;

        $content=serialize($con);
        // file_put_contents('scan/test', $content);//写入缓存文件
       $data = file_put_contents('scan/'.self::getClientId(), $content);//写入缓存文件

         if(!$data){
             throw new Exception('写入扫描数据失败');
         }
    }

    ////读取一个客户端的数据
public static function read(){
    $data =   file_get_contents('scan/'.self::getClientId());//读取缓存文件
    if(!$data){
        throw new Exception('读取扫描数据失败');
    }
    return unserialize($data);

}
public static function dealScanPackageData($data){

    if($data){
       $array=[];
        foreach ($data->studentDetails as $v){
           $array[]=$v->fashion_code;
       }
return $array;
    }
    return '';
}

public static function saveCacheWithPackage(){
    self::debug('保存这一次扫描的包裹到缓存');

    self::writeCacheWithPackage(self::getMessage());
    self::debug('保存这一次扫描的集合到缓存成功');

    return true;
}
public static function saveCacheWithBox(){
    self::debug('保存这一次扫描的箱子到缓存');

    self::writeCacheWithBox(self::getMessage());
    self::debug('保存这一次扫描的箱子到缓存成功');

    return true;
}
public static function writeCacheWithPackage($message){
    $scan_package=self::readCache('scanpackage/'.self::getBatchId());
    if(!isset($scan_package[$message])){
        ///如果这个包裹编号不在里面 那么就写入
        $scan_package[$message]=[];
        self::inWrite($scan_package,'scanpackage/'.self::getBatchId());
    }


}
public static function writeCacheWithBox($message){
    $scan_box=self::readCache('scanbox/'.self::getBatchId());
    if(!isset($scan_box[$message])){
        ///如果这个包裹编号不在里面 那么就写入
        $scan_box[$message]=[];
        self::inWrite($scan_box,'scanbox/'.self::getBatchId());
    }


}

    ////读取一个缓存的数据
    public static function readCache($file_name){
        $data =   file_get_contents($file_name);//读取缓存文件
        if(!$data){
            throw new Exception('读取扫描数据失败');
        }
        return unserialize($data);

    }

    public static function saveCacheWithUnit($type){

        if(self::getScanType()=='box'){
            /////箱子的单位
            $scan_box=self::readCache('scanbox/'.self::getBatchId());
            $cur_box=self::getScanData()['collection'];
            $scan_box[$cur_box][]=self::getMessage();
            self::inWrite($scan_box,'scanbox/'.self::getBatchId());
        }elseif(self::getScanType()=='package'){
            /////包裹单位
            $scan_package=self::readCache('scanpackage/'.self::getBatchId());
            $cur_package=self::getScanData()['collection'];
                $scan_package[$cur_package][]=self::getMessage();
            self::inWrite($scan_package,'scanpackage/'.self::getBatchId());
        }
    }

}

date_default_timezone_set('Asia/Shanghai');//'Asia/Shanghai'   亚洲/上海
$message='PS0729841111002';///扫描过来的信息
//$message='XXD66463F46F';///扫描过来的信息
$message='W1608035A100';///扫描过来的信息
//$message='over';///扫描过来的信息
$client_id='4';///扫描随机分配的客户端id
$batch_id='9';///批次的id

$scan_type='package';///扫描的类型  'package' 为扫描包裹   'box'为扫描的箱子
    $scan_type='box';///扫描的类型  'package' 为扫描包裹   'box'为扫描的箱子

$message=$_GET['message'];
$client_id=$_GET['client_id'];
$batch_id=$_GET['batch_id'];
$scan_type=$_GET['scan_type'];



    Scan::connect(1);
    Scan::init($client_id, $message,$batch_id,$scan_type);
    Scan::onMessage();
    //dd(__LINE__);
?>