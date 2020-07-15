<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Square implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $param;
	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct($param)
	{
		$this->param = $param;
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		//return new Channel('channel-square');
		return new PrivateChannel('channel-square');
	}


	/**
	 * 广播别名
	 * @return [type] [description]
	 */
	public function broadcastAs()
	{
		return 'server.square';
	}

	/**
	 * 获取广播数据
	 * 如果不加该方法则广播所有public参数
	 * @return array
	 */
	//public function broadcastWith()
	//{
	//	return $this->param;
	//}

	/**
	 * 确定事件是否要被广播
	 * 
	 * @return bool
	 */
	//public function broadcastWhen()
	//{
	//	return $this->value > 100;
	//}


}
