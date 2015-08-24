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
* file系列 fopen、file_get_contents等函数用于操作文件或网络资源
* stream_系列 可以发送请求，不限于HTTP协议
* socket系列 通过socket发送请求数据，不限于HTTP协议
* cURL扩展 功能强大，具有DNS缓存能力

###Socket通信机制
---
* 