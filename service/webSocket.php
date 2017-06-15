<?php

/**
 * Created by PhpStorm.
 * User: zhouqiang
 * Date: 2017/6/12
 * Time: 下午4:27
 */
class webSocket
{
    //小心类型
    const CONNECT_TYPE = 'connect';
    const DISCONNECT_TYPE = 'disconnect';
    const MESSAGE_TYPE = 'message';
    const INIT_SELF_TYPE = 'self_init';
    const INIT_OTHER_TYPE = 'other_init';
    const COUNT_TYPE = 'count';

    private $server;

    private $table;

    protected $config;

    public function __construct()
    {
        $this->createTable();
        $this->config = require_once(__DIR__ . "/config.php");
    }

    /**
     * 启动
     */
    public function run()
    {
        $this->server = $server = new swoole_websocket_server($this->config['ip'], $this->config['port']);
        $server->set([
            'task_worker_num' => 1
        ]);
        $server->on('open', [$this, 'open']);
        $server->on('message', [$this, 'message']);
        $server->on('close', [$this, 'close']);
        $server->on('task', [$this, 'task']);
        $server->on('finish', [$this, 'finish']);

        $server->start();
    }

    /**
     * @param Server $server
     * @param $request
     */
    public function open(swoole_websocket_server $server, swoole_http_request $req)
    {
        $nickname = array_rand($this->config['avatar']);
        $avatar = array_rand($this->config['nick_name']) . $req->fd;
        $user = [
            'fd' => $req->fd,
            'nick_name' => $nickname,
            'avatar' => $avatar
        ];
        //记录数据
        $this->table->set($req->fd, $user);


        //推送数据
        $server->push($req->fd, json_encode(
                array_merge(['user' => $user], ['all' => $this->allUser()], ['type' => 'openSuccess'])
            )
        );
        $this->pushMessage($server, "欢迎" . $user['name'] . "进入聊天室", 'open', $request->fd);

        //初始化自己的数据
        $userMsg = $this->formateMsg([
            'id' => $req->fd,
            'avatar' => $avatar,
            'nickname' => $nickname,
            'count' => count($this->table)
        ], self::INIT_SELF_TYPE);
        $this->server->task([
            'to' => [$req->fd],
            'except' => [],
            'data' => $userMsg
        ]);

        //init others data
        $others = $this->allUser();
        $otherMsg = $this->formateMsg($others, self::INIT_OTHER_TYPE);
        $this->server->task([
            'to' => [$req->fd],
            'except' => [],
            'data' => $otherMsg
        ]);

        //broadcast a user is online
        $msg = $this->buildMsg([
            'id' => $req->fd,
            'avatar' => $avatar,
            'nickname' => $nickname,
            'count' => count($this->table)
        ], self::CONNECT_TYPE);
        $this->server->task([
            'to' => [],
            'except' => [$req->fd],
            'data' => $msg
        ]);
    }

    /**
     * @param Server $server
     * @param $frame
     */
    public function message(swoole_websocket_server $server, swoole_websocket_frame $frame)
    {
        $receive = json_decode($frame->data,true);
        $msg = $this->formateMsg($receive,self::MESSAGE_TYPE);
        $task = [
            'to' => [],
            'except' => [$frame->fd],
            'data' => $msg
        ];

        if ($receive['to'] != 0) {
            $task['to'] = [$receive['to']];
        }
        $server->task($task);
    }

    public function task($server, $task_id, $from_id, $data){
        $clients = $server->connections;
        if (count($data['to']) > 0) {
            $clients = $data['to'];
        }
        foreach ($clients as $fd) {
            if (!in_array($fd, $data['except'])) {
                $this->server->push($fd,$data['data']);
            }
        }
    }

    public function close(swoole_websocket_server $server, $fd){
        $this->table->del($fd);
        $msg = $this->formateMsg([
            'id' => $fd,
            'count' => count($this->table)
        ],self::DISCONNECT_TYPE);
        $this->server->task([
            'to' => [],
            'except' => [$fd],
            'data' => $msg
        ]);
        //可以广播离开聊天室
    }

    public function finish(){
        //广播服务端关闭
    }

    /**
     * 创建内存表
     */
    private function createTable()
    {
        $this->table = new \swoole_table(1024);
        $this->table->column('fd', \swoole_table::TYPE_INT);
        $this->table->column('nickname', \swoole_table::TYPE_STRING, 64);
        $this->table->column('avatar', \swoole_table::TYPE_STRING, 255);
        $this->table->create();
    }

    private function allUser()
    {
        $users = [];
        foreach ($this->table as $row) {
            $users[] = $row;
        }
        return $users;
    }

    private function formateMsg($data, $type, $status = 200)
    {
        return json_encode([
            'status' => $status,
            'type' => $type,
            'data' => $data
        ]);
    }
}


$server = new webSocket();

$server->run();
