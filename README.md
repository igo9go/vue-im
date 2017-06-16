# vue-im

利用websocket开发的IM，swoole作服务端，vuejs前端

#部署
1. 需要安装Swoole和npm以及webpack作为构建工具

2. 构建项目
   
   安装依赖:  `npm install`
   
   构建项目:
   `npm run build`
3. 启动服务

   启动服务端
   `PHP service/webSocket.php`
   dist目录配置HTTP server
   `php -S localhost:8080 -t dist`

4.访问

  http://localhost:8080


###数据
- boradcast array
    - connection 链接
    - currentCount 当前人数

- currentSession  object 当前会话
    - avatar
    - chat
    - id
    - nickname
    
- currentUser object 当前个人信息
    - avatar
    - chat
    - id
    - nickname
    
- filterUser 搜索的人

- notice object
    
    - mgs
    - show
    - type

- online 

- user array
    - object
    - avatar
    - chat
    - id
    - nickname



