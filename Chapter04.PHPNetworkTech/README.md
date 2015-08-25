##相关知识点总结
***
###HTTP协议
---
* HTTP协议规范主要定义于RFC 2616中，基于TCP(HTTP默认端口80)或TLS、SSL(HTTPS默认端口443)
* 建立连接
 + 基于TCP，三次握手sync->sync+ack->ack
 + HTTP1.1中，在header中有Connection字段，若为keep-alive可使用长连接，若为close则为一次性请求，结束后断开连接
* 断开连接
 + 基于TCP，四次挥手
* 请求包含
 + 请求行
 + 消息报头
 + 请求正文
* 响应包含
 + 状态行
 + 消息报头
 + 响应正文
* 请求行格式
 + Method Request-URI HTTP-VERSION CRLF
 + 例如POST index.html HTTP\1.1 \r\n
 + 其中Method包含如下一些：GET、POST、HEAD、PUT、DELETE、TRACE、CONNECT、OPTIONS
* 响应状态行格式
 + HTTP-Version Status-Code Reason-Phrase CRLF
 + 例如HTTP\1.1 200 OK \r\n
* 常见状态码及含义
 + 101 Upgrade 要求更换协议
 + 200 OK 请求成功
 + 301 Moved Permanently 资源被永久移动到新的URI
 + 302 Move Temporarily 资源被临时移动到新的URI
 + 304 Not Modified 回应带条件的GET请求，表示当前资源自创建时间或某条件时间后未被修改过
 + 400 Bad Request 请求错误，无法被服务器理解
 + 401 Unauthorized 请求权限不足，需要用户身份验证
 + 403 Forbidden 请求被拒绝，即使身份验证也无法获得该资源
 + 404 Not Found 资源未找到
 + 407 Proxy Authentication Required 与401相似，但要求用户身份验证在服务端发生，客户端可以使用Proxy-Authenticate头来验证身份
 + 500 Internal Server Error 服务器内部错误，一般出现在源码出现错误时
 + 502 Bad Gateway 网关错误
 + 503 Service Unavailable 服务暂不可用，临时状态，一段时间后可能会恢复正常
 + 504 Gateway Timeout 网关超时
* 报头格式
 + 名字: 值
* 常见报头字段
 + Host 资源的Internet主机与端口号，HTTP1.1中不包含Host则直接返回400
 + User-Agent 简称UA，是用户使用客户端的重要判断信息，如Firefox、Chrome、IE、Android、iOS等。
 + Accept 告诉服务器接受的文件格式，WAP浏览器通常较少，常见的有text/html、application/xml、text/json等
 + Cookie 请求报头中为Cookie，可以包含多个key-value的Cookie对；响应报头中为set-Cookie，只能包含一个Cookie的value，并需要指明domain、path等
 + Cache-control 客户端缓存机制。请求中缓存指令包含no-cache、no-store、max-age、max-stale、min-fresh、only-if-cache；响应中缓存指令包含public、private、no-cache、no-store、no-transform、must-revalidate、proxy-revalidate、max-age
 + Referer 头域允许客户端指定请求URI的源资源地址；不太理解
 + Content-Length 正文内容长度
 + Content-Range 响应资源的范围
 + Accept-Encoding 接受的编码方式，一般有gzip、deflate等
  
###PHP与HTTP相关函数
---
* get_headers函数 用于取得服务器响应一个HTTP请求发送的头部信息
* header函数 用于发送HTTP头，但该函数前不能有输出或者空格
* http_build_query函数 用于构造HTTP参数，主要是把数组元素以=连接kv并用&连接构造成字符串
* parse_url函数 用于解析一个URL并分别得到各部分
* file系列 fopen、file_get_contents等函数用于操作文件或网络资源
* stream_系列 可以发送请求，不限于HTTP协议
* socket系列 通过socket发送请求数据，不限于HTTP协议
* cURL扩展 功能强大，具有DNS缓存能力

###Socket通信机制
---
* 基于进程的端对端通信
* 端口号相关内容
 + 常用端口小于256，服务器一般使用这些端口
 + 基于TCP/IP的服务一般使用1~1023间的端口，这些端口由IANA管理
 + 临时端口号为1024~5000，这些端口为临时使用，只要保证唯一即可
 + 大于5000是为其他服务器预留的
* 常见的端口号
 + FTP 20、21
 + SMTP 25
 + HTTP 80
 + HTTPS 443
  
###PHP与Socket相关函数
---
* resource socket_create(int $domain, int $type, int $protocol) 用户创建一个Socket
 + $domain 协议族，IPv4、IPv6或UNIX本地通信协议
 + $type 交互类型，可以是基于TCP的流模式、基于UDP的流模式、基于TCP的分组模式、原始套接字(IP层协议)及一个基于TCP的无序模式
 + $protocol 使用协议类型，可以是ICMP、UDP或TCP
* bool socket_bind($resource $socket, string $address[, int $port = 0]) 用于将IP地址和端口绑定到socket句柄上
 + $socket 要绑定的socket句柄
 + $address 要绑定的IP地址
 + $port 要绑定的端口，当$domain是IPv4时需要指定该参数
* bool socket_listen(resource $socket[, int $backlog = 0]) 用于监听客户端数据
 + $socket 创建的socket句柄
 + $backlog 允许的最大连接数
* bool set_socket_nonblock(resource $socket) 用于将socket设置为非阻塞模式，单进程服务器中设置为非阻塞非常必要
 + $socket 需要设置的socket句柄
* int socket_write(resource $socket, string $buffer[, int $length = 0]) 向socket中写入数据
 + $socket 需要写入的socket句柄
 + $buffer 需要写入的内容
 + $length 写入内容的长度，若大于buffer的长度，则截断为buffer的长度
* string socket_read(resource $socket, int $length[, int $type = PHP_BINARY_READ]) 从socket中读取指定长度的数据
 + $socket 读取的socket句柄
 + $length 需要读取的长度
 + $type 默认值为安全读取二进制数据，另一个值是PHP_NORMAL_READ，遇到"\r"或"\n"时即停止
* resource pfsockopen 打开一个长连接的socket套接字，是fsockopen的长连接版本
* bool socket_set_option(resource $socket, int $level, int $optname, mixed $optval) 用于设置socket选项
* int socket_last_error([resource $socket]) 获取任何socket函数最近一次产生的错误信息，可以使用socket_strerror函数将此错误号转换为字符串描述

###PHP与cURL
---
* cURL是利用URL语法规定传输文件和数据的工具，在PHP中有相应的cURL扩展
* 具体用法见例
