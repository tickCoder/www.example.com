<!--
    Xcode build Settings 参数
-->

- Installation Directory

> 安装路径。
> 
> 静态库编译时，Build Settings中Installation Directory设置为“$(BUILT_PRODUCTS_DIR)”
> Skip Install应设为YES，因为Installation Directory默认为/usr/local/lib，Skip Install如果是NO,可能会被安装到默认路径/usr/local/lib
> 

- Public Headers Folder Path

> 对外公开头文件路径。
> 
> 如果设为“include”（具体的头文件路径为：$(BUILT_PRODUCTS_DIR)/include/xx.h）
> 在最终文件.a同级目录下生成一个include目录
> 默认：/usr/local/include
> 这个路径就是使用这lib的某工程需要依赖的外部头文件.导入这路径后，#include/import "xx.h"才能看到
> 

- User Header Search Paths

> 依赖的外部头文件搜索路径
> 
> 如果设置为“$(BUILT_PRODUCTS_DIR)/include”，则与Public Headers Folder Path对应
> 

- Per-configuration Build Products Path

> 最终文件路径
> 
> 比如设为“../app”，就会在工程文件.xcodeproj上一层目录下的app目录里，创建最终文件。
> 默认为$(BUILD_DIR)/$(CONFIGURATION)$(EFFECTIVE_PLATFORM_NAME) 等于$(BUILT_PRODUCTS_DIR)
> 

- Per-configuration Intermediate Build Files Path

> 临时中间文件路径
> 
> 默认为：$(PROJECT_TEMP_DIR)/$(CONFIGURATION)$(EFFECTIVE_PLATFORM_NAME)
> 

- Code Signing Identity

> 真机调试的证书选择
> 
> 选一个和Bundle identifier相对应的证书
> 

- Library Search Paths

> 库搜索路径
> 

- Architectures

> 工程将被编译成支持哪些指令集，如armv7
> 
> 该编译选项指定了工程将被编译成支持哪些指令集，
> 支持指令集是通过编译生成对应的二进制数据包实现的，
> 如果支持的指令集数目有多个，
> 就会编译出包含多个指令集代码的数据包，造成最终编译的包很大。
> 
> 指令集都是可以向下兼容的,比如，你的设备是armv7s指令集，那么它也可以兼容运行比armv7s版本低的指令集：armv7、armv6
> 
> - armv6: iPhone, iPhone2, iPhone 3G, 第一代、第二代iPod Touch等
> - armv7: iPhone 3GS, iPhone 4, iPhone 4S，iPad , iPad 2,  the new iPad，iPod Touch 3G, iPod Touch 4等
> - armv7s: 在iPhone5和5C上使用(可以忽略，因为这些设备可以兼容armv7架构的程序)
> - arm64: 运行于iPhone5S及以上的64位ARM处理器上
> - i386: 32位模拟器上使用
> - x86_64: 64位模拟器上使用
>

- Valid Architectures

> 可能支持的指令集，可以设为 armv7 或i386
> 
> 该编译项指定可能支持的指令集，
> 该列表和Architectures列表的交集，
> 将是Xcode最终生成二进制包所支持的指令集。
> 
> 比如，你的Valid Architectures设置的支持arm指令集版本有：armv7/armv7s/arm64，
> 对应的Architectures设置的支持arm指令集版本有：armv7s，
> 这时Xcode只会生成一个armv7s指令集的二进制包。
> 
> Xcode4.5起不再支持armv6
> 
> 如果你的软件对安装包大小非常敏感，你可以减少安装包中的指令集数据包，而且这能达到立竿见影的效果。
> 
> 可以改成只支持armv7后，目前AppStore上的一些知名应用，比如百度地图、腾讯地图通过反汇编工具查看后，也都只支持armv7指令集。
> 根据向下兼容原则，armv7指令集的应用是可以正常在支持armv7s/arm64指令集的机器上运行的。
> 
> 不过对于armv7s/arm64指令集设备来说，使用运行armv7应用是会有一定的性能损失，不过这种损失有多大缺乏权威统计数据，个人认为是不会影响用户体验的。
> 

- Build Active Architecture Only

> 是否只编译当前使用的设备对应的arm指令集
> 
> 该编译项用于设置是否只编译当前使用的设备对应的arm指令集。
> 
> 当该选项设置成YES时，你连上一个armv7指令集的设备，
> 就算你的Valid Architectures和Architectures都设置成armv7/armv7s/arm64，
> 还是依然只会生成一个armv7指令集的二进制包。
> 
> 当然该选项起作用的前提是你的Xcode必须成功连接了调试设备。
> 如果你没有任何活跃设备，即Xcode没有成功连接调试设备，
> 就算该设置项设置成YES依然还会编译Valid Architectures和Architectures指定的二进制包。
> 
> 通常情况下，该编译选项在Debug模式都设成YES，Release模式都设成NO。
> 

- Product Name

> 工程文件名，默认为$(TARGET_NAME)
> 

- Other Linker Flags

> 其他链接标签，如“-ObjC”
> 

- Prefix Header

> 预编头文件，如xxx.pch
> 

- Precompile Prefix Header

> 设为“Yes”，表示允许加入预编译头
> 

附录：获取CPU的architecture

```
#include <sys/types.h>
#include <sys/sysctl.h>
#include <mach/machine.h>

NSString *getCPUType(void)
{
    NSMutableString *cpu = [[NSMutableString alloc] init];
    size_t size;
    cpu_type_t type;
    cpu_subtype_t subtype;
    size = sizeof(type);
    sysctlbyname("hw.cputype", &type, &size, NULL, 0);

    size = sizeof(subtype);
    sysctlbyname("hw.cpusubtype", &subtype, &size, NULL, 0);

    // values for cputype and cpusubtype defined in mach/machine.h
    if (type == CPU_TYPE_X86)
    {
            [cpu appendString:@"x86 "];
             // check for subtype ...

    } else if (type == CPU_TYPE_ARM)
    {
            [cpu appendString:@"ARM"];
            switch(subtype)
            {
                    case CPU_SUBTYPE_ARM_V7:
                    [cpu appendString:@"V7"];
                    break;
                    // ...
            }
    }
    return [cpu autorelease];
}
```