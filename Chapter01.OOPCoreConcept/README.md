##相关知识点总结：
***
###对象
---
* PHP中对象的序列化存储与数组类似，只存储了对象属性，另外多一个指向该类的指针，以可以访问该对象对应的类函数，函数被加载在共享的类代码空间中，由所有该类的对象共享，故反序列化对象时需要该对象类的定义，否则无法返回正确的结果。
* PHP中的变量在PHP源码中以_zvalue_value的union定义在zend.h中。
* PHP中的类是_zvalue_value中的zend_object_value，采用“属性数组+方法数组”来实现，zend_object_value中包含了类入口zend_class_entry指针、指向属性数组的properties的HastTable指针以及一个用于阻止递归调用的guards指针。序列化时仅包含了属性数组内容与指向类的指针，通过该指针可以找到该类的类属性、静态属性、类常量、标准方法、魔术方法、自定义方法列表等。
* 除了stdClass以外每个对象一定有一个类对其对应。
  
###魔术方法
---
* 以“__”开头的内置PHP方法称为魔术方法，有构造函数__construct，析构函数__destruct，转化为字符串的函数__toString，获取private或不存在属性的函数__get，设置private或不存在属性的函数__set，调用private或者不存在方法的函数__call，调用private或者不存在静态方法的函数__callStatic。
* 注意PHP中的构造方法可以带有参数，但是不可以被重载，只能存在一个__construct方法。
* 所有的魔术方法都应该是public的，其中__callStatic应该是static的，但即便声明为private或者__callStatic没有声明为static的依然可以正常运行，但是会得到一个warning级别的错误，但使用反射查看类的内部时发现其魔法方法依然是private的。
* 后四种魔术方法只有在获取或设置的属性或方法是private或者不存在时才会触发，而若已经可以获取到的public属性或方法并不触发魔法方法。
* 魔法方法的作用并不是在获取或调用不存在的属性方法时不报错，而是提供了为对象动态创建属性与方法的途径，这有时是非常有用的，例如在数据库操作中创建动态代理，代码会变得非常简单。
* __toString方法会使得对象可以被echo出来，否则会遇到一个fatal error。
  
###继承与多态
---
* 某类中包含另一个类对象的属性称为组合，而继承则是通过extends来实现的，使该类获得了父类public和protected的方法和属性。组合是“需要”关系，而继承是“像”的关系。
* 在继承中，使用parent来调用父类的属性和方法，用self来调用自己的属性和方法。
* 在组合和继承的选择中可以尝试倾向于组合，因为继承可能破坏类的封装，并且是紧耦合的，扩展起来相对复杂，需要大量的方法重写，并且有多继承的问题，PHP中不支持多继承。
* 需要多继承时可以使用PHP5.4引入的trait特性来实现，trait可以看做是强化的接口，因为使用了该trait的类不仅实现了其定义的方法，并且trait可以直接带代码到使用它的类中，而且使用某trait的类可以重写其实现的函数。
  
###接口
---
* 接口是一种规范或协议，并不实现具体的方法，但是需要实现该接口的类一定要实现该接口中定义的函数。
* 接口很好的实现了代码设计与实现的分工，架构师完成接口的定义，而由基础开发人员进行具体开发即可。
* PHP的内部实现了一些接口的特性，例如实现DirectoryIterator的类即可使用foreach对其进行遍历，这是PHP内部代码实现的。
  
###反射
---
* 反射提供了在运行时提取类的详细信息的方式。
* 可使用ReflectionObject类对对象进行反射解析，也可以通过ReflectionClass对类进行反射解析，当然简单的话也可以用get_object_vars、get_class_vars和get_class_methods几个方法来获取对象或类的属性与方法，get_class可以获取某对象对应的类。
* 反射由于会暴露类中本不应暴露的属性或方法，所以会破坏类的封装性，而且反射的消耗也较大，所以一般不建议大量使用反射API。
  
###异常和错误
---
* PHP中的异常一般不会自动抛出，而需要手动抛出异常，异常的抛出实现了业务流程和异常处理的代码分离。异常分拣中，需要将超类放在较后的位置。使用抛出异常并合理处理的方式可以实现代码级事务处理。
* PHP中的deprecated、notice、warning、fatal error、parse error其实都是错误，只是不同的错误级别而已。需要注意使用error_reporting和display_errors、error_log等参数进行错误信息的处理，可以打印或输出到log。这些参数的设置可以在php.ini中，也可以在脚本中使用ini_set来实现。
* 可以使用set_error_handler(error_function, error_types)来进行自定义的错误处理，该函数会绕过PHP自己的错误处理机制，所以必要时需要使用die()来终止程序。其中error_function必须有$errno、$errstr、$errfile和$errline四个参数。
* set_error_handler并不能托管所有类型的错误，E_ERROR、E_PARSE、E_CORE_ERROR、E_CORE_WARNING、E_COMPILE_ERROR、E_COMPILE_WARNING和E_STRICT中的部分错误会以原始方式显示。
* 使用set_error_handler后代码里的错误抑制@将失效。
* 使用set_error_handler可以实现类似于异常处理的效果。
* 由于fatal error无法被捕捉，所以在出现fatal error时程序一定会被终止，此时可以使用register_shutdown_function来实现退出前的应急操作，该函数会在程序退出前执行，类似于Java中的finally函数。
  