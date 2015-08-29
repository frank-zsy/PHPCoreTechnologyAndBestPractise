##相关知识点总结
***
###PDO(PHP Data Objects)
---
* PDO是一个抽象接口层，本身不实现任何数据库操作，必须使用特定的数据库PDO驱动访问数据库
* 使用PDO需要打开extension=php_pdo.so
* 使用PDO需要三个类：PDO、PDOStatement、PDOException

###数据库优化原则
---
* 避免在列上进行运算，这样会导致索引失效
* 使用JOIN时应该使用小结果集驱动大结果集。同时把复杂的JOIN查询拆分成多个Query
* LIKE查询的效率较低，应该避免使用%%
* 查询时应该限制字段，主要考虑节省内存，在远程数据库中节省网络资源
* 使用批量操作节省与数据库的交互次数
* limit基数较大时应该使用between，大量数据访问时between限定速度比limit快
* 尽量不要在Query中使用随机数函数，应该在php中先计算随机数再传入Query中
* 避免使用NULL
* 不要使用count(id)，而应该是count(*)
* 可以使用索引完成的排序不要使用order by，无谓的排序会导致性能损失

###数据库性能分析
---
* 使用EXPLAIN对Query进行性能分析
* EXPLAIN的返回结果中重要的几项
 + select_type 查询类型，包括普通查询、联合查询和子查询
 + type 联合查询使用的类型
 + possible_keys 使用的MySQL索引，如果为空则表示没有使用索引，应该进行优化
 + key 决定使用的键，如果没有则表示没有使用索引
 + key_len MySQL决定使用的键的长度
 + rows 该值表示需要遍历多少数据才能找到结果集，该值在InnoDB上不准确
 + Extra 其他信息。如果是Only index表示只使用索引，速度较快。如果是where used表示使用了where限定，但不足够。如果是impossible where表示通过收集到的统计信息不可能存在结果

###数据库建立索引的原则
---
* 合理设计和使用索引
* 在关键字段索引上，建立与不建索引性能差距上百倍
* 差的索引不如没有索引，且索引并非越多越好，因为维护成本很高
* 每个表的索引字段不应超过5个，应合理使用部分索引和联合索引
* 在结果集中结果单一的列上建索引并没有太大帮助
* 建立索引的结果集最好是分布均匀或符合正态分布的

###MySQL存储引擎的比较
---
<table>
	<tbody>
		<tr><td></td><td><em>MyISAM</em></td><td><em>Memory</em></td><td><em>InnoDB</em></td></tr>
		<tr><td>用途</td><td>快读</td><td>内存数据</td><td>完整事务支持</td></tr>
		<tr><td>锁</td><td>全表锁定</td><td>全表锁定</td><td>多种隔离级别的行锁</td></tr>
		<tr><td>持久性</td><td>基于表恢复</td><td>无磁盘I/O，无可持久性</td><td>基于日志的恢复</td></tr>
		<tr><td>事务</td><td>不支持</td><td>不支持</td><td>支持</td></tr>
		<tr><td>支持索引类型</td><td>B-tree/FullText/R-tree</td><td>Hash/B-tree</td><td>Hash/B-tree</td></tr>
	</tbody>
</table>
* 普遍认为MyISAM注重性能，InnoDB注重事务
* 选择引擎的基本原则
 + R / W > 100 : 1 且update较少；并发不高，不需要事务；表数据量少；硬件资源有限时使用MyISAM
 + R / W较小，频繁更新大字段；表数据量超过1000W，并发高；安全性和可用性要求高时使用InnoDB
 + 内存充足；数据一致性要求不高；需要定期归档的数据使用Memory

###SQL注入与防范
---
* 在参数中使用一些SQL语句可以实现SQL注入，一般在Where之后的条件上会有注入点存在
* 预防方法
 + 使用intval转换整型变量或字段
 + 使用addslashes函数为特殊字符进行转义，或者使用PDO的bindParam函数来绑定参数
 + 转义或过滤可能的特殊字符
 + 进行数据库的定时备份
