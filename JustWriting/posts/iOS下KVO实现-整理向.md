Tags: Xcode iOS KVO key value coding observing observe observer
Status: public
Toc: yes


# 参考

- <a href="http://www.cnblogs.com/wengzilin/p/4346775.html" target="_blank">编程小翁@博客园</a>
- <a href="http://blog.csdn.net/sakulafly/article/details/14084183" target="_blank">iOS下KVO的使用以及一些实现细节</a>
- <a href="http://www.2cto.com/kf/201502/378470.html" target="_blank">IOS SDK详解之KVO</a>

# 大体流程
## 流程一.注册
是KVC的一种实现，在UserInfoViewController中如下代码，UserManager为单件，Person非单件
### 流程一.1.代码示例
```
- (void)viewDidLoad {
    [super viewDidLoad];
    // Do any additional setup after loading the view, typically from a nib.

    // 如果UserManager包含一个对象Person *person，则keyPath可以设置@"person.personid"
    UserManager *userManager = [UserManager sharedInstance];
    [userManager addObserver:self forKeyPath:@"userid" options:NSKeyValueObservingOptionNew context:nil];

    Person *person = [[Person alloc] init];
    [Person addObserver:self forKeyPath:@"personid" options:NSKeyValueObservingOptionNew context:nil];
}
```

### 流程一.2.方法参数
- observer: 观察者，也就是KVO通知的订阅者。
- keyPath：描述将要观察的属性，相对于被观察者。为被观察者的属性名，如person.personid的@"personid"
- options：KVO的一些属性配置。
- context: 上下文，这个会传递到订阅着的函数中，用来区分消息，所以应当是不同的。

### 流程一.3.options的值的意义
- NSKeyValueObservingOptionNew, change字典包括改变后的值
- NSKeyValueObservingOptionOld, change字典包括改变前的值
- NSKeyValueObservingOptionInitial, 注册后立刻触发KVO通知
- NSKeyValueObservingOptionPrior, 值改变前是否也要通知(这个key决定了是否在改变前改变后通知两次)

## 流程二.触发
当修改被监听者的属性时
```
- (IBAction)changeBtnAction:(id)sender {
	// 或者设置property, [_person setValue:@"xx" forKey:@"personid"];
	_person.personid = (NSInteger)arc4random();
	[UserManager sharedInstance].userid = (NSInteger)arc4random();
}
```
会自动调用一下方法，在其中设置UI更改等

```
- (void)observeValueForKeyPath:(NSString *)keyPath ofObject:(id)object change:(NSDictionary<NSString *,id> *)change context:(void *)context {

    // 从change获取新旧值
    id oldValue = change[NSKeyValueChangeOldKey];
    id newValue = change[NSKeyValueChangeNewKey];

    if ([keyPath isEqualToString:@"PIN"] && object == _person) {
        _personInfoLabel.text = [NSString stringWithFormat:@"%@'s PIN is %ld", _person.name, (long)_person.PIN];
    } else if ([keyPath isEqualToString:@"userid"] && object == [UserManager sharedInstance]) {
    	NSString *username = [[UserManager sharedInstance] username];
    	NSInteger userid = (long)[UserManager sharedInstance].userid;
        _useridLabel.text = [NSString stringWithFormat:@"%@'s userid is %ld", username, userid];
    } else {
        // 若当前类无法捕捉到这个KVO，可能他的super中有，若无此else则会截断KVO，造成其super异常
        [super observeValueForKeyPath:keyPath ofObject:object change:change context:context];
    }
}
```
## 流程三.移除
```
- (void)dealloc {
     /*
     Apple官方Foundation/NSKeyValueObserving.h对此有一段说明：

     You should use -removeObserver:forKeyPath:context: instead of -removeObserver:forKeyPath:
      whenever possible because it allows you to more precisely specify your intent. 
      When the same observer is registered for the same key path multiple times, 
      but with different context pointers each time, 
      -removeObserver:forKeyPath: has to guess at the context pointer when deciding what exactly to remove, 
      and it can guess wrong.

      大概翻译一下是：
      无论在任何时候，你应该使用-removeObserver:forKeyPath:context:
      而不是-removeObserver:forKeyPath:
      当同样一个observer被多次以同一个key path注册时，
      后者-removeObserver:forKeyPath:需要猜测context指针来决定移除哪个，而且可能会猜错
     */ 
    
     // 因为remove的时候，无法判读remove是否成功了，为防止崩溃，可以在remove的时这么写(使用@try-@catch-@finally).
     @try {
     	// 不能remove多次（比如super也remove），可以在context中作区分（比如使用唯一标识符）。
     	// 此处就暂不作此处理了。
        [[UserManager sharedInstance] removeObserver:self forKeyPath:@"userid" context:nil];
        [_person removeObserver:self forKeyPath:@"PIN" context:nil];
     }
     @catch (NSException *exception) {
        NSLog(@%@,exception);
     }
     @finally {
    }
}
```

# 流程总结
- viewDidLoad()添加
- btnAction:改变对象属性值
- observeValueForKeyPath:ofObject:change:context:接收改变并设置UI
- dealloc()移除

# KVO实现原理
- 有段博客这么说的 
<a href="http://blog.csdn.net/sakulafly/article/details/14084183" target="_blank">iOS下KVO的使用以及一些实现细节</a>
```
因为Cocoa是严格遵循MVC模式的，所以KVO在观察Modal的数据变化时很有用。那么KVO是怎么实现的呢，苹果官方文档上说的比较简单：
“Automatic key-value observing is implemented using a technique called isa-swizzling.” 
“When an observer is registered for an attribute of an object the isa pointer of the observed object is modified, 
pointing to an intermediate class rather than at the true class. 
As a result the value of the isa pointer does not necessarily reflect the actual class of the instance.”
就是说在运行时会生成一个派生类，在这个派生类中重写基类中任何被观察属性的 setter 方法，用来欺骗系统顶替原先的类。
```
测试代码
```
@interface myPerson : NSObject  
{  
    NSString *_name;  
}  
  
@property (nonatomic)int height;  
@property (nonatomic)int weight;  
@property (nonatomic)int age;  
@end  
  
@implementation myPerson  
@synthesize height, weight, age;  
@end  
  
#import "objc/runtime.h"  
static NSArray * ClassMethodNames(Class c)  
{  
    NSMutableArray * array = [NSMutableArray array];  
      
    unsigned int methodCount = 0;  
    Method * methodList = class_copyMethodList(c, &methodCount);  
    unsigned int i;  
    for(i = 0; i < methodCount; i++) {  
        [array addObject: NSStringFromSelector(method_getName(methodList[i]))];  
    }  
      
    free(methodList);  
      
    return array;  
}  
  
static void PrintDescription(NSString * name, id obj)  
{  
    NSString * str = [NSString stringWithFormat:  
                      @"\n\t%@: %@\n\tNSObject class %s\n\tlibobjc class %s\n\timplements methods <%@>",  
                      name,  
                      obj,  
                      class_getName([obj class]),  
                      class_getName(obj->isa),  
                      [ClassMethodNames(obj->isa) componentsJoinedByString:@", "]];  
    NSLog(@"%@", str);  
}  
  
- (void)testKVOImplementation  
{  
    myPerson * anything = [[myPerson alloc] init];  
    myPerson * hObserver = [[myPerson alloc] init];  
    myPerson * wObserver = [[myPerson alloc] init];  
    myPerson * hwObserver = [[myPerson alloc] init];  
    myPerson * normal = [[myPerson alloc] init];  
      
    [hObserver addObserver:anything forKeyPath:@"height" options:0 context:NULL];  
    [wObserver addObserver:anything forKeyPath:@"weight" options:0 context:NULL];  
      
    [hwObserver addObserver:anything forKeyPath:@"height" options:0 context:NULL];  
    [hwObserver addObserver:anything forKeyPath:@"weight" options:0 context:NULL];  
      
    PrintDescription(@"normal", normal);  
    PrintDescription(@"hObserver", hObserver);  
    PrintDescription(@"wObserver", wObserver);  
    PrintDescription(@"hwOBserver", hwObserver);  
      
    NSLog(@"\n\tUsing NSObject methods, normal setHeight: is %p, overridden setHeight: is %p\n",  
          [normal methodForSelector:@selector(setHeight:)],  
          [hObserver methodForSelector:@selector(setHeight:)]);  
    NSLog(@"\n\tUsing libobjc functions, normal setHeight: is %p, overridden setHeight: is %p\n",  
          method_getImplementation(class_getInstanceMethod(object_getClass(normal),  
                                                           @selector(setHeight:))),  
          method_getImplementation(class_getInstanceMethod(object_getClass(hObserver),  
                                                           @selector(setHeight:))));  
}  
```

略微改写了一下myPerson，age/height/weight两个属性增加了getter/setter方法，然后运用runtime的方法，打印相应的内容，运行的log如下：
```
2013-11-02 20:36:22.391 test[2438:c07] 
normal: <myPerson: 0x886b840>
NSObject class myPerson
libobjc class myPerson
implements methods <weight, setWeight:, age, setAge:, height, setHeight:>
2013-11-02 20:36:22.393 test[2438:c07] 
hObserver: <myPerson: 0x886b7e0>
NSObject class myPerson
libobjc class NSKVONotifying_myPerson
implements methods <setWeight:, setHeight:, class, dealloc, _isKVOA>
2013-11-02 20:36:22.393 test[2438:c07] 
wObserver: <myPerson: 0x886b800>
NSObject class myPerson
libobjc class NSKVONotifying_myPerson
implements methods <setWeight:, setHeight:, class, dealloc, _isKVOA>
2013-11-02 20:36:22.393 test[2438:c07] 
hwOBserver: <myPerson: 0x886b820>
NSObject class myPerson
libobjc class NSKVONotifying_myPerson
implements methods <setWeight:, setHeight:, class, dealloc, _isKVOA>
2013-11-02 20:36:22.394 test[2438:c07] 
Using NSObject methods, normal setHeight: is 0x37e0, overridden setHeight: is 0x37e0
2013-11-02 20:36:22.394 test[2438:c07] 
Using libobjc functions, normal setHeight: is 0x37e0, overridden setHeight: is 0xb859e0

```

从log信息可以清楚的看到派生了一个NSKVONotifying_XXX的类，这个派生类集合了每个KVO观察者的信息，所以这个派生类可以全局公用。 另外，观察原来类的方法和派生类的方法，每个被观察的属性都重写了，比如：setWeight:方法和setHeight:方法，没被观察的属性都没有重新生成，比如：height:方法、weight:方法、age:方法和setAge:方法。

- 另一个这么说的：<a href="http://www.bjbkws.com/apply/1210/" target="_blank">iiOS培训：如何理解 Objective-C编程的KVO 原理</a>

1、当一个object有观察者时，动态创建这个object的类的子类

2、对于每个被观察的property，重写其set方法

3、在重写的set方法中调用- willChangeValueForKey:和- didChangeValueForKey:通知观察者

4、当一个property没有观察者时，删除重写的方法

5、当没有observer观察任何一个property时，删除动态创建的子类

Sark.h/m
```
@interface Sark : NSObject 
@property (nonatomic, copy) NSString *name; 
@end 
 
@implementation Sark 
@end 
```

```
Sark *sark = [Sark new]; 
// breakpoint 1 
[sark addObserver:self forKeyPath:@"name" options:NSKeyValueObservingOptionNew context:nil]; 
// breakpoint 2 
sark.name = @"萨萨萨"; 
[sark removeObserver:self forKeyPath:@"name"]; 
// breakpoint 3 
```

断住后分别使用- class和object_getClass()打出sark对象的Class和真实的Class
```
// breakpoint 1 
(lldb) po sark.class 
Sark 
(lldb) po object_getClass(sark) 
Sark 
 
// breakpoint 2 
(lldb) po sark.class 
Sark 
(lldb) po object_getClass(sark) 
NSKVONotifying_Sark 
 
// breakpoint 3 
(lldb) po sark.class 
Sark 
(lldb) po object_getClass(sark) 
Sark 
```

上面的结果说明，在sark对象被观察时，framework使用runtime动态创建了一个Sark类的子类NSKVONotifying_Sark，而且为了隐藏这个行为，NSKVONotifying_Sark重写了- class方法返回之前的类，就好像什么也没发生过一样。但是使用object_getClass()时就暴露了，因为这个方法返回的是这个对象的isa指针，这个指针指向的一定是个这个对象的类对象
然后来偷窥一下这个动态类实现的方法，这里请出一个NSObject的扩展NSObject+DLIntrospection，它封装了打印一个类的方法、属性、协议等常用调试方法，一目了然。

```
@interface NSObject (DLIntrospection) 
+ (NSArray *)classes; 
+ (NSArray *)properties; 
+ (NSArray *)instanceVariables; 
+ (NSArray *)classMethods; 
+ (NSArray *)instanceMethods; 
 
+ (NSArray *)protocols; 
+ (NSDictionary *)descriptionForProtocol:(Protocol *)proto; 
 
+ (NSString *)parentClassHierarchy; 
@end 
```

然后继续在刚才的断点处调试：
```
// breakpoint 1 
(lldb) po [object_getClass(sark) instanceMethods] 
<__NSArrayI 0x8e9aa00>( 
- (void)setName:(id)arg0 , 
- (void).cxx_destruct, 
- (id)name 
) 
// breakpoint 2 
(lldb) po [object_getClass(sark) instanceMethods] 
<__NSArrayI 0x8d55870>( 
- (void)setName:(id)arg0 , 
- (class)class, 
- (void)dealloc, 
- (BOOL)_isKVOA 
) 
// breakpoint 3 
(lldb) po [object_getClass(sark) instanceMethods] 
<__NSArrayI 0x8e9cff0>( 
- (void)setName:(id)arg0 , 
- (void).cxx_destruct, 
- (id)name 
) 
```

大概就是说arc下这个方法在所有dealloc调用完成后负责释放所有的变量，当然这个和KVO没啥关系了，回到正题。
从上面breakpoint2的打印可以看出，动态类重写了4个方法：
1、- setName:最主要的重写方法，set值时调用通知函数
2、- class隐藏自己必备啊，返回原来类的class
3、- dealloc做清理犯罪现场工作
4、- _isKVOA这就是内部使用的标示了，判断这个类有没被KVO动态生成子类
接下来验证一下KVO重写set方法后是否调用了- willChangeValueForKey:和- didChangeValueForKey:
最直接的验证方法就是在Sark类中重写这两个方法：

```
@implementation Sark 
 
- (void)willChangeValueForKey:(NSString *)key 
{ 
    NSLog(@"%@", NSStringFromSelector(_cmd)); 
    [super willChangeValueForKey:key]; 
} 
 
- (void)didChangeValueForKey:(NSString *)key 
{ 
    NSLog(@"%@", NSStringFromSelector(_cmd)); 
    [super didChangeValueForKey:key]; 
} 
 
@end 
```


# 手动实现KVO
KVO是对注册的keyPath中自动实现了两个函数，在setter中自动调用以下函数
```
- (void)willChangeValueForKey:(NSString *)key {
}

- (void)didChangeValueForKey:(NSString *)key {
}
```
手动实现KVO的好处是可以加上自己的定制，如以下
```
// 系统会每个属性都自动对应一个函数
+ (BOOL)automaticallyNotifiesObserversForKey:(NSString *)key {
    return YES;
}

+ (BOOL)automaticallyNotifiesObserversOfPerson {
    return YES;
}

+ (BOOL)automaticallyNotifiesObserversOfUserid {
    return YES;
}

+ (BOOL)automaticallyNotifiesObserversOfUsername {
    return YES;
}

// 关闭userid自动生成KVO通知
+ (BOOL)automaticallyNotifiesObserversOfUserid {
    return NO;
}

// 重写userid的setter方法
- (void)setUserid:(NSInteger)userid {
    if (userid < 0) {
        return;
    }
    [self willChangeValueForKey:@"userid"];
    _userid = userid;
    [self didChangeValueForKey:@"userid"];
}

```

# KVO框架

# 注意事项

- 若不将observer移除，会造成内存问题。对于此问题，一段博客上是这么说的
<a href="http://www.cnblogs.com/wengzilin/p/4346775.html" target="_blank">编程小翁@博客园</a>
```
潜在的问题有可能出现在dealloc中对KVO的注销上。
KVO的一种缺陷(其实不能称为缺陷，应该称为特性)是，
当对同一个keypath进行两次removeObserver时会导致程序crash，
这种情况常常出现在父类有一个kvo，
父类在dealloc中remove了一次，
子类又remove了一次的情况下。
不要以为这种情况很少出现！
当你封装framework开源给别人用或者多人协作开发时是有可能出现的，
而且这种crash很难发现。
不知道你发现没，目前的代码中context字段都是nil，
那能否利用该字段来标识出到底kvo是superClass注册的，还是self注册的？
回答是可以的。
我们可以分别在父类以及本类中定义各自的context字符串，
比如在本类中定义context为@"ThisIsMyKVOContextNotSuper";
然后在dealloc中remove observer时指定移除的自身添加的observer。
这样iOS就能知道移除的是自己的kvo，
而不是父类中的kvo，避免二次remove造成crash。
```

- 必须通过KVC的方法来修改属性，KVO的回调才能被调用；调用类的其他方法来修改属性，是不会得到通知的。

- 由于context通常用来区分不同的KVO，所以context的唯一性很重要。通常，我的使用方式是通过在当前.m文件里用静态变量定义。
```
static void * privateContext = 0;
```

- KVO的响应和KVO观察的值变化是在一个线程上的，所以，大多数时候，不要把KVO与多线程混合起来。除非能够保证所有的观察者都能线程安全的处理KVO


