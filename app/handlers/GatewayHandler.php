<?php
namespace handlers;
/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */
//declare(ticks=1);

use App\handlers\WorkerControllers\RouteController;
use App\Model\CustomServe;
use App\Models\User;
use \GatewayWorker\Lib\Gateway;


/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class GatewayHandler
{

    public function __construct()
    {
        //加载index文件的内容
//        require __DIR__ . '/../vendor/autoload.php';
//        require_once __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * 
     * @param int $client_id 连接id
     */
    public static function onConnect($client_id)
    {

        // 向当前client_id发送数据
//        Gateway::sendToClient($client_id, 'ss');
      msgReturn( ['client_id'=>$client_id],  $client_id, 'sys', 'connect');
//        Gateway::sendToClient($client_id,$res);
        // 向所有人发送
//        Gateway::sendToAll($res);
    }
    
   /**
    * 当客户端发来消息时触发
    * @param int $client_id 连接id
    * @param mixed $message 具体消息
    */
   public static function onMessage($client_id, $message)
   {
       $uid = Gateway::getUidByClientId($client_id);
        // 如果没有uid
       if(!$uid)
       {
            # 关闭 链接
           Gateway::closeClient($client_id);
       } else {
           app(RouteController::class)->init($client_id, $message,$uid);
       };
   }

   /**
    * 当用户断开连接时触发
    * @param int $client_id 连接id
    */
   public static function onClose($client_id)
   {

       $uid  = Gateway::getUidByClientId($client_id);
       $first = CustomServe::query()->where('person_id', $uid)->first();
       if ($first) {
           $first->delete();
       }
       msgReturn(['client_id'=>$client_id],null, 'sys', 'close', ' 链接已断开');
   }
}
