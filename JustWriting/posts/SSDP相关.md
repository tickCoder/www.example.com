Status: public
Toc: yes

<!-- SSDP -->

# 1.0.0.什么是SSDP

SSDP = Simple Service Discovery Protocol, 简单服务发现协议

# 2.0.0.百度百科的解释

http://baike.baidu.com/view/277232.htm

> 简单服务发现协议（SSDP，Simple Service Discovery Protocol）是一种应用层协议，是构成通用即插即用(UPnP)技术的核心协议之一。
> 
> 简单服务发现协议提供了在局部网络里面发现设备的机制。
> 控制点（也就是接受服务的客户端）可以通过使用简单服务发现协议，根据自己的需要查询在自己所在的局部网络里面提供特定服务的设备。
> 设备（也就是提供服务的服务器端）也可以通过使用简单服务发现协议，向自己所在的局部网络里面的控制点声明它的存在。
> 
> 简单服务发现协议是在HTTPU和HTTPMU的基础上实现的协议。
> 
> 按照协议的规定，当一个控制点（客户端）接入网络的时候，它可以向一个特定的多播地址的SSDP端口使用M-SEARCH方法发送“ssdp:discover”消息。
> 当设备监听到这个保留的多播地址上由控制点发送的消息的时候，
> 设备会分析控制点请求的服务，
> 如果自身提供了控制点请求的服务，设备将通过单播的方式直接响应控制点的请求。
> 
> 类似的，当一个设备接入网络的时候，它应当向一个特定的多播地址的SSDP端口使用NOTIFY方法发送“ssdp:alive”消息。
> 控制点根据自己的策略，处理监听到的消息。
> 考虑到设备可能在没有通知的情况下停止服务或者从网络上卸载，
> “ssdp:alive”消息必须在HTTP协议头CACHE-CONTROL里面指定超时值，
> 设备必须在约定的超时值到达以前重发“ssdp:alive”消息。
> 如果控制点在指定的超时值内没有再次收到设备发送的“ssdp:alive”消息，控制点将认为设备已经失效。
> 
> 当一个设备计划从网络上卸载的时候，它也应当向一个特定的多播地址的SSDP端口使用NOTIFY方法发送“ssdp:byebye”消息。
> 但是，即使没有发送“ssdp:byebye”消息，控制点也会根据“ssdp:alive”消息指定的超时值，
> 将超时并且没有再次收到的“ssdp:alive”消息对应的设备认为是失效的设备。
> 
> 在IPv4环境，当需要使用多播方式传送相关消息的时候，SSDP一般使用多播地址239.255.255.250和UDP端口号1900。
> 根据互联网地址指派机构的指派，SSDP在IPv6环境下使用多播地址FF0x::C，这里的X根据scope的不同可以有不同的取值。

# 3.0.0.SSDP官方的文档

https://tools.ietf.org/id/draft-cai-ssdp-v1-03.txt

## 3.1.0.官方文档-一些术语(Terminology)

> SSDP Client - A HTTP client that makes use of a service.

↑ SSDP Client - 使用service的http客户端
 
> SSDP Service - A HTTP resource that provides a service used by SSDP clients.

↑ SSDP Service - 提供HTTP资源供SSDP client使用的服务。

> Service Type - A URI that identifies the type or function of a particular service.

↑ Servcie Type - 一个标识特定服务的种类或功能的URI。

> Unique Service Name (USN) - A URI that is guaranteed to be unique 
   across the entire URI namespace for all time. It is used to uniquely 
   identify a particular service in order to allow services with 
   identical service type URIs to to be differentiated. 

↑ USN - 一个保证在整个URI命名空间内一直唯一的URI。它用来唯一标识一个特定服务，以允许拥有相同Servcie Type的URIs可以区分。

## 3.2.0.官方文档-介绍

> A mechanism is needed to allow HTTP clients and HTTP resources to 
   discover each other in local area networks. That is, a HTTP client 
   may need a particular service that may be provided by one or more 
   HTTP resources. The client needs a mechanism to find out which HTTP 
   resources provide the service the client desires. 

↑ 我们需要一种机制来允许HTTP客户端和HTTP资源在局域网中互相发现。
也就是说，HTTP客户端需要一种特定的服务，这种服务通过一个或更多HTTP资源提供。
客户端需要一种机制来找到那个HTTP资源提供了客户端需要的服务。

> SSDP clients discover SSDP services using the reserved local 
   administrative scope multicast address 239.255.255.250 over the SSDP 
   port [NOT YET ALLOCATED BY IANA].  

↑ SSDP client使用保留的本地管理範圍的多播地址239.255.255.250來發現SSDP設備
通過SSDP端口「尚未被IANA分配目前使用1900端口」，ipv6地址为FF0x::C。

> Discovery occurs when a SSDP client multicasts a HTTP UDP discovery 
   request to the SSDP multicast channel/Port. SSDP services listen to 
   the SSDP multicast channel/Port in order to hear such discovery 
   requests. If a SSDP service hears a HTTP UDP discovery request that 
   matches the service it offers then it will respond using a unicast 
   HTTP UDP response. 

↑ 發現過程在SSDP client想在SSDP多播頻道／端口多播一個HTTP UDP發現請求時發生。
SSDP service監聽SSDP多播頻道／端口以便發現這些發現請求。
如果SSDP監聽到此請求，並與它提供的服務匹配時，它將通過單播的HTTP UDP響應。

> SSDP services may send HTTP UDP notification announcements to the 
   SSDP multicast channel/port to announce their presence. 

↑ SSDP service可以發送HTTP UDP通知聲明到SSDP多播頻道／端口來聲明他們的存在。

> Hence two types of SSDP requests will be sent across the SSDP 
   multicast channel/port. The first are discovery requests, a SSDP 
   client looking for SSDP services. The second are presence 
   announcements, a SSDP service announcing its presence. 

↑ 因此SSDP請求有兩種方式，他們通過SSDP多播頻道／端口發送。

- 一種為發現請求（Discovery Request）：SSDP client搜尋SSDP servcies。
- 一種為存在聲明（Presence Announcement）：SSDP service聲明其存在。

> Services are identified by a unique pairing of a service type URI 
   and a Unique Service Name (USN) URI. 

↑ 服務（Service）通過一個唯一的由
[a service type URI]、[a Unique Service Name(USN) URI]組合標識。

> Service types identify a type of service, such as a refrigerator, 
   clock/radio, what have you. The exact meaning of a service type is 
   outside the scope of this specification. For the purposes of this 
   specification, a service type is an opaque identifier that 
   identifies a particular type of service. 

↑ 服务类型(servcie type)确定一个类型的服务,比如一台冰箱,时钟/收音机等。
service type的準確定義不在本規格書的範圍內，在此處，
service type是不透明的標識符來表示一種特定的服務類型。

> A USN is a URI that uniquely identifies a particular instance of a 
   service. USNs are used to differentiate between two services with 
   the same service type. 

↑ USN是一個URI來唯一表示一個服務的實例。
USN用來區分同種service的不同實例。

> In addition to providing both a service type and a USN, discovery 
   results and presence announcements also provide expiration and 
   location information. 

↑ 为了提供一个Service Type和一个USN，发现结果(discovery 
   results) 和存在声明(presence announcements)同样提供了失效信息(expiration)和位置(location)信息.

> Location information identifies how one should contact a particular 
   service. One or more location URIs may be included in a discovery 
   response or a presence announcement. 

↑ 位置信息標示如何聯繫特定的服務。在一个發現回應或存在聲明中，可以包含一個或多個位置URI。

> Expiration information identifies how long a SSDP client should keep 
   information about the service in its cache. Once the entry has 
   expired it is to be removed from the SSDP client's cache. 

↑ 失效期信息標示SSDP client應該在其cache中保存信息多久。一旦entry失效，它應該從SSDP client的cache中移除。

> Thus a SSDP client service cache might look like: 
> 
   USN URI          | Service Type URI | Expiration | Location 
   -----------------|------------------|------------|------------------ 
   upnp:uuid:k91... | upnp:clockradio  | 3 days     | http://foo.com/cr 
   -----------------|------------------|------------|------------------ 
   uuid:x7z...      | ms:wince         | 1 week     | http://msce/win 
   -----------------|------------------|------------|------------------ 

↑ 因此一個SSDP client中的servcie cache可能會像下面這樣：

   USN URI          | Service Type URI | Expiration | Location 
   -----------------|------------------|------------|------------------ 
   upnp:uuid:k91... | upnp:clockradio  | 3 days     | http://foo.com/cr 
   uuid:x7z...      | ms:wince         | 1 week     | http://msce/win 

> In the previous example both USN URIs are actually UUIDs such as 
   upnp:uuid:k91d4fae-7dec-11d0-a765-00a0c91c6bf6. 
    
↑ 在上面的例子中，USN URI實際上是UUID，如“upnp:uuid:k91d4fae-7dec-11d0-a765-00a0c91c6bf6”

> If an announcement or discovery response is received that has a USN 
   that matches an entry already in the cache then the information in 
   the cache is to be completely replaced with the information in the 
   announcement or discovery response. 

↑ 如果收到的聲明或發現請求的USN已經存在於cache中，cache中的信息將完全被替代。

>  Why use multicast for communication? 
  
>  We needed a solution for communication that would work even if there 
   was no one around to configure things. The easiest solution would 
   have been to build a discovery server, but who would set the server 
   up? Who would maintain it? We needed a solution that could work even 
   if no one had any idea what discovery was. By using multicasting we 
   have the equivalent of a "party channel." Everyone can just grab the 
   channel and scream out what they need and everyone else will hear. 
   This means no configuration worries. Of course it brings up other 
   problems which are addressed throughout this specification. 

↑ 为什么使用多播用于通信。

我们需要一种方案：在没有人为配置的情况下通信可以进行。
每个人都可以获取频道并呼叫他们需要什么，并且其他人都能听到。

>  Why does SSDP support both service discovery requests as well as service presence announcements? 

>  Some discovery protocols only support discovery requests, that is, 
   the client must send out a request in order to find out who is 
   around. The downside to such solutions is that they tend to be very 
   expensive on the wire. For example, we want to display to our user 
   all the VCRs in her house. So we send out a discovery request. 
   However our user has just purchased a new VCR and, after starting 
   our program, plugged it in. The only way we would find out about the 
   new VCR and be able to display it on our user's screen is by 
   constantly sending out discovery requests. Now imagine every client 
   in the network having to send out a torrent of discovery requests 
   for service they care about in order to make sure they don't miss a 
   new service coming on-line. 

>  Other systems use the opposite extreme, they only support 
   announcements. Therefore, when our user opens the VCR display window 
   we would just sit and listen for announcements. In such systems all 
   the services have to send out a constant stream of announcements in 
   order to make sure that no one misses them. Users aren't the most 
   patient people in the world so each service will probably need to 
   announce itself at least every few seconds. This constant stream of 
   traffic does horrible things to network efficient, especially for 
   shared connections like Ethernets. 

> SSDP decided to adopt a hybrid approach and do both discovery and 
   announcements. This can be incredibly efficient. When a service 
   first comes on-line it will send out an announcement so that 
   everyone knows it is there. At that point it shouldn't ever need to 
   send out another announcement unless it is going off-line, has 
   changed state or its cache entry is about to expire. Any clients who 
   come on-line after the service came on-line will discover the 
   desired service by sending out a discovery request. The client 
   should never need to repeat the discovery request because any 
   services that subsequently come on-line will announce themselves. 
   The end result is that no one needs to send out steady streams of 
   messages. The entire system is event driven, only when things change 
   will messages need to be sent out. The cost, however, is that the 
   protocol is more complex. We felt this was a price worth paying as 
   it meant that SSDP could be used successfully in fairly large 
   networks. 

↑ 为什么SSDP要同时支持“设备发现请求”和“服务存在通知”

一些发现协议仅仅支持发现请求，也就是，client必须发送一个请求来找到谁在附近。
它的缺点是往往带宽资源消耗非常大。
比如，我们想要显示给用户他家中所有的VCR，我们发送发现请求。
然而我们的用户在启动了我们的程序后，刚刚买了一个新的VCR并且安装了它。
找到并显示它的唯一方法是不断的发送发现请求。
设想下，网络中的每个client都要发送大量的发现请求，以此避免丢失新加入的服务（设备）。

其他系统使用的是另一个极端，他们只支持“通知”。
因此，当用户打开VCR显示器时，我们需要坐下来监听通知。
此种状况中，所有的服务需要不断的发送通知来确保没有人会错过它。
用户恐怕没有这个耐心，所以每个服务可能会每个几秒钟发送一次通知。
这不断的流量对网络影响很大，尤其是共享的连接网络。

SSDP决定采用一种混合的方法，发现&通知。
这非常的有效率。
当一个service入网，它会发送通知让其他人知道。
此时它不再需要发送另外的通知，直到它离网（也就是它改变了状态或她的cache将要失效）。
任何在此service入网之后才入网的client会通过发送discovery request来发现它。
任何client不需要重复发送discovery request,因为任何随后入网的service都会声明自己。
最终的结果就是，没有人需要不断地发布消息。
整个系统是事件驱动的，只有事情变化时才需要发送消息。
然而，成本就是协议更复杂。
但我们觉得这是一个值得付出的代价，这意味着SSDP可以在相当大的网络上成功运行。

> Doesn't the caching information turn SSDP back into a 
"announcement driven" protocol? 
    
>  Discovery protocols that only support announcements generally have 
   to require services to send announcements every few seconds. 
   Otherwise users screens will take too long to update with 
   information about which services are available. 
    
>  SSDP, on the other hand, allows the service to inform clients how 
   long they should assume the service is around. Thus a service can 
   set a service interval to seconds, minutes, days, weeks, months or 
   even years. 

> Clients do not have to wait around for cache update messages because 
   they can perform discovery. 

↑ 缓存信息会将SSDP变回声明驱动协议么?

只支持声明的发现协议需要service每几秒钟发送一次声明，否则用户将会很久才能更新哪些servcie是可用的。
SSDP则允许service通知client它们(service)假定自己会持续多久。
因此servcie可以将service interval设置为几秒、几分、几天、几周、几月甚至几年。
client不需要等待cache更新消息, 因为它们可以执行发现动作。

> Why do we need USNs, isn't the location good enough? 
> 
> When a service announces itself it usually includes a location 
   identifying where it may be found. However that location can and 
   will change over time. For example, a user may decide to change the 
   DNS name assigned to that device. Were we to depend on locations, 
   not USNs, when the service's location was changed we would think we 
   were seeing a brand new service. This would be very disruptive to 
   the user's experience. Imagine, for example, that the user has set 
   up a PC program that programs their VCR based on schedules pulled 
   off the Internet. If the user decides to change the VCR's name from 
   the factory default to something friendly then a location based 
   system would loose track of the VCR it is supposed to be programming 
   because the name has changed. By using unique Ids instead we are 
   able to track the VCR regardless of the name change. So the user can 
   change the VCR's name at will and the VCR programming application 
   will still be able to program the correct VCR. 

↑ 为什么我们需要USN，”位置信息“还不够好么？

当service声明自己时，它会附加一个位置信息来告诉client怎么找到自己。
然而位置信息是会随时间变化的。比如，用户可能会改变赋给此设备的DNS name。
假如我们依赖位置，而不是USN的话，当设备的位置变化时，我们会认为这是一个全新的服务。
这样的用户体验是糟糕的。
设想，用户已经设置一个电脑程序，这个程序根据时间将VCR从网络上录制下来。
如果用户将VCR的名字从默认名改成了其喜欢的名字，
那么一个基于位置的系统将失去对此VCR的跟踪（注：类似于域名或hostname？）。
而改用unique id的话，我们将会不用理会名称的改变，仍然可以跟踪此VCR。
所以用户可以随意改变VCR的名称，而VCR的程序仍会正确执行。

> Why are USNs URIs and why are they required to be unique across the entire URI namespace for all time? 
> 
> In general making a name universally unique turns out to usually be 
   a very good idea. Mechanisms such as UUIDs allow universally unique 
   names to be cheaply created in a decentralized manner. In this case 
   making USNs globally unique is very useful because services may be 
   constantly moved around, if they are to be successfully tracked they 
   need an identifier that isn't going to change and isn't going to get 
   confused with any other service. 

> URIs were chosen because they have become the de facto managed 
   namespace for use on the Internet. Anytime someone wants to name 
   something it is easy to just use a URI. 

↑ 为什么是USNs URIs，为什么它们需要在整个URI命名空间中一直保持唯一？

通常使一个名称唯一是非常好的主意。
UUID之类的机制允许统一的唯一的名称，这是一种便捷的分散方式。
这种情况下，将USN全球唯一是非常有用的，
因为service可能会不断移动，它们需要一个标识符一边可以被正确跟踪，而不会在改变时与其它service混淆。
选择URI是因为它们已经成为事实上的管理在互联网上的命名空间。每当人们想要命名时很容易想到URI。

## 3.3.0.官方文档-SSDP Discovery Requests

> The SEARCH method, introduced by [DASL], is extended using the [MAN] 
   mechanism to provide for SSDP discovery. 
   The SSDP SEARCH extension is identified by the URI ssdp:discover.  

↑ 通过[MAN]机制来扩展在DASL中介绍的SEARCH方法，提供给SSDP discovery使用。
SSDP SEARCH 扩展通过ssdp:discover这个URI来识别。

> ssdp:discover requests MUST contain a ST header. ssdp:discover 
   requests MAY contain a body but the body MAY be ignored if not 
   understood by the HTTP service. 

↑ ssdp:discover请求必须(MUST)包含一个ST头部。 
ssdp:discover请求可能(MAY)包含一个body，如果HTTP servcie不识别的话，这个body可能(MAY)被忽略。

> The ST header contains a single URI. SSDP clients may use the ST 
   header to specify the service type they want to discover. 

↑ ST头部包含一个单独的URI。SSDP client可能使用ST头部来表明想要发现的服务类型。

> This specification only specifies the use of ssdp:discover requests 
   over HTTP Multicast UDP although it is expected that future 
   specifications will expand the definition to handle ssdp:discover 
   requests sent over HTTP TCP. 

↑ 虽然将来可能将其扩展到HTTP TCP上，但本规范书仅指出了HTTP UDP多播上的用法。

> ssdp:discover requests sent to the SSDP multicast channel/port MUST 
   have a request-URI of "\*". Note that future specifications may allow 
   for other request-URIs to be used so implementations based on this 
   specification MUST be ready to ignore ssdp:discover requests on the 
   SSDP multicast channel/port with a request-URI other than "\*". 

<!--↑ 为了在markdown上显示星号*,在前面添加了反斜杠转义字符-->

↑ 向SSDP多播频道／端口发送的ssdp:discover请求，必须(MUST)有一个"\*"的request-URI。
记住，因为未来的规范可能允许使用其它的request-URI，
所以根据此规范的实现必须(MUST)准备好忽略未包含"\*"的request-URI的ssdp:discover请求。
（注：也就是根据此规范，没有带”*”的ssdp:dscover请求要忽略掉）

> Only SSDP services that have a service type that matches the value 
   in the ST header MAY respond to a ssdp:discover request on the SSDP 
   multicast channel/port. 

↑ 只有SSDP service拥有一个与ssdp:discover请求的ST头部匹配的service type时，才可能(MAY)回应。

> Responses to ssdp:discover requests sent over the SSDP multicast 
   channel/port are to be sent to the IP address/port the ssdp:discover 
   request came from. 

↑ 对在SSDP多播频道／端口上发送的ssdp:discover请求，需要发送回应到ssdp:discover请求的ip地址／端口号上。

> A response to a ssdp:discover request SHOULD include the service's 
   location expressed through the Location and/or AL header. A 
   successful response to a ssdp:discover request MUST also include the 
   ST and USN headers.  

↑ 对ssdp:discover请求的回应应该(SHOULD)包含servcie location，
这个location通过(Location and/or AL header)表述。
一个成功的对ssdp:discover请求的回应必须(MUST)同时包含ST和USN头部。

> Response to ssdp:discover requests SHOULD contain a cache-control: 
   max-age or Expires header. If both are present then they are to be 
   processed in the order specified by HTTP/1.1, that is, the cache-
   control header takes precedence of the Expires header. If neither 
   the cache-control nor the Expires header is provided on the response 
   to a ssdp:discover request then the information contained in that 
   response MUST NOT be cached by SSDP clients. 

↑ 对ssdp:discover请求的回应应该(SHOULD)包含一个cache-control: max-age或Expires header。
如果这两个都有，那么它们会按照HTTP/1.1识别的顺序处理。
也就是说，cache-control header比Expires header优先处理。
如果对ssdp:discover request的回应中没有cache-control，也没有Expires header，
那么在回应中包含的信息必须不能(MUST NOT)缓存在SSDP client中。

> Why is the ST header so limited? Why doesn't it support at 
least and/or/not? Why not name/value pair searching? 
    
>  Deciding the "appropriate" level of search capability is a hopeless 
   task. So we decided to pare things back to the absolute minimum, a 
   single opaque token, and see what happens. The result so far has 
   been a very nice, simple, easy to implement, easy to use discovery 
   system. There are lots of great features it doesn't provide but most 
   of them, such as advanced queries and scoping, require a search 
   engine and a directory. This level of capability is beyond many 
   simple devices, exactly the sort of folks we are targeting with 
   SSDP. Besides, search functionality seems to be an all or nothing 
   type of situation. Either you need a brain dead simple search 
   mechanism or you need a full fledged near SQL class search system. 
   Instead of making SSDP the worst of both worlds we decided to just 
   focus on the dirt simple search problem and leave the more advanced 
   stuff to the directory folk. 

↑ 为什么ST Header如此有限？什么不支持至少 and/or/not? 为什么不是name/value搜索。

决定“合适”的搜索功能是一个hopeless的任务。
所以我们决定削减事情到绝对最低,单一的不透明的令牌,看看会发生什么。
结果到目前为止是一个非常好、简单、容易实现、易于使用的发现系统。
它不提供大量伟大的特性，但是大多数的特性(如高级查询和范围)，需要一个搜索引擎和一个目录。
这种级别的能力超出许多简单的设备,这种类型正是我们SSDP的目标。
此外,搜索功能似乎是一种全有或全无类型的情况。
你需要一个足以让你脑死亡的简单的搜索机制，或你需要一个完整的成熟的SQL类搜索系统。
为了不让SSDP不在两方面都是最糟糕的，我们决定仅仅专注于简单搜索问题，并把高级的东西交给目录来解决。

> If we are using the SEARCH method why aren't you using the 
DASL search syntax? 

>  We couldn't come up with a good reason to force our toaster ovens to 
   learn XML. The features the full-fledged DASL search syntax provides 
   are truly awesome and thus way beyond our simple scenarios. We fully 
   expect that DASL will be the preferred solution for advanced search 
   scenarios, but that isn't what this draft is about. 

↑ 为什么使用SEARCH方法，为什么不使用DASL搜索语法？

我们无法想出一个好理由强迫我们的toaster学习XML。
成熟的DASL搜索语法提供的特性真的了不起,因此超出我们的简单的愿景。
我们完全希望DASL成为高级搜索的首选解决方案,但这并不是这个草案关心的。

> Why can we only specify one search type in the ST header of a 
ssdp:discover request? 
    
>  We wanted to start as simple as possible and be forced, kicking and 
   screaming, into adding additional complexity. The simplest solution 
   was to only allow a single value in the ST header. We were also 
   concerned that if we allowed multiple values into the ST headers 
   somebody would try to throw in and/or/not functionality. Given the 
   minimal byte savings of allowing multiple values into the ST header 
   it seems better to just leave the protocol simpler. 

↑ 为什么仅仅在ssdp:discover request的ST Header中指定一种search type

我们想要尽可能简单，［被迫开始,踢尖叫,添加额外的复杂性。］
最简单的方案是在ST Header中只允许单一值。

> Why do we only provide support for multicast UDP, not TCP, 
ssdp:discover requests? 
>
>  We only define what we need to make the discovery protocol work and 
   we don't need TCP to make the discovery protocol work. Besides to 
   make TCP discovery really work you need to be able to handle 
   compound responses which means you need a compound response format 
   which is probably XML and that is more than we wanted to handle. 
   Eventually we expect that you will be able to go up to the SSDP port 
   on a server using a HTTP TCP request and discover what service, if 
   any, lives there. But that will be described in a future 
   specification. 

↑ 为什么仅仅为多播UDP、而不是TCP，提供了ssdp:discover支持。

我们仅仅定义了我们需要什么，来使得discovery协议运行。我们不需要TCP。
除此之外，如果使用TCP来discovery，你需要去处理混合的响应。
这意味着你需要一个混合响应格式，这可能是XML。
这超出了我们想要处理的范围。
最终，我们希望你可以去服务器上的SSDP端口上使用HTTP TCP请求和发现服务。
如果有的话，就那样使用。但是这将是未来的规格书了。

> Why do we require that responses without caching information 
not be cached at all? 

>  Because that was a lot easier thing to do then trying to explain the 
   various heuristics one could use to deal with services who don't 
   provide caching information. 

↑ 为什么不缓存不包含cache信息的回应。

因为这是容易得多的事情，你可以使用各种方法来处理未提供cache信息的service。

## 3.4.0.官方文档-SSDP Pesence Announcements

### 3.4.1.官方文档-SSDP Presence Announcements, 介绍

> SSDP Presence Announcements 
> SSDP存在声明
> 
>  A mechanism is needed for SSDP services to be able to let interested 
   SSDP clients know of their presence. 
>  一种让SSDP client知道SSDP Service存在的机制。

>  A mechanism is needed to allow SSDP services to update expiration 
   information in cache entries regarding them. 
>  一种允许SSDP Service更新cache过期信息的机制。

>  A mechanism is needed to allow SSDP services to notify interested 
   SSDP clients when their location changes. 
>  一种允许SSDP Service通知SSDP Client，service位置变化的机制。

>  A mechanism is needed to allow SSDP services to inform interested 
   SSDP clients that they are going to de-activate themselves.
>  一种允许SSDP servcie通知SSDP client，servcie将要de-active的机制。

### 3.4.2.官方文档-SSDP Presence Announcements, ssdp:alive

↑ SSDP存在声明

> SSDP services may declare their presence on the network by sending a 
   [GENA] NOTIFY method using the NTS value ssdp:alive to the SSDP 
   multicast channel/port. 

↑ SSDP service可以使用NTS值ssdp:alive，
在网络上发送[GENA] NOTIFY方法到SSDP多播频道／端口来宣示其存在。


> When a ssdp:alive request is received whose USN matches the USN of 
   an entry already in the SSDP client's cache then all information 
   regarding that USN is to be replaced with the information on the 
   ssdp:alive request. Hence ssdp:alive requests can be used to update 
   location information and prevent cache entries from expiring. 
    
↑ 当接收到ssdp:alive request，
如果它的USN与保存在SSDP client的cache中的USN一致时，
所有cache中关于USN的信息将被替代。
因此ssdp:alive request可以用来更新位置信息，阻止cache失效。

> The value of NT on a ssdp:alive request MUST be set to the service's 
   service type. ssdp:alive requests MUST contain a USN header set to 
   the SSDP service's USN. 

↑ ssdp:alive request的NT值必须(MUST)是servic的service type，
ssdp:alive request必须(MUST)包含一个USN Header，这个Header设置为SSDP Servcie的USN。

> ssdp:alive requests SHOULD contain a Location and/or AL header. If 
   there is no DNS support available on the local network then at least 
   one location SHOULD be provided using an IP address of the SSDP 
   service. 

↑ ssdp:alive request应该(SHOULD)包含一个(Location and/or AL header)。
如果局域网内没有DNS支持的话，至少应该(SHOULD_使用SSDP service的IP地址来提供一个location。

> ssdp:alive requests SHOULD contain a cache-control: max-age or 
   Expires header. If both are present then they are to be processed in 
   the order specified by HTTP/1.1, that is, the cache-control header 
   takes precedence of the Expires header. If neither the cache-control 
   nor the Expires header is provided the information in the ssdp:alive 
   request MUST NOT be cached by SSDP clients. 

↑ ssdp:alive requests 应该(SHOULD)包含一个cache-control: max-age或者Expires header。
如果两者都有，将会按照HTTP/1.1的识别顺序，
也就是cache-control header优先于Expires header。
如果都没有，那么SSDP client必须不能(MUST NOT)缓存ssdp:alive请求中的信息。

> There is no response to a ssdp:alive sent to the SSDP multicast 
   channel/port. 

↑ 无需对ssdp:alive进行回应。

### 3.4.3.官方文档-SSDP Presence Announcements, ssdp:byebye

> SSDP services may declare their intention to cease operating by 
   sending a [GENA] NOTIFY method using the NTS value ssdp:byebye to 
   the SSDP multicast channel/port. 

↑ SSDP service可以使用NTS值ssdp:byebye，通过发送[GENA] NOTIFY方法到SSDP多播频道／端口来终止操作。

> The value of NT on a ssdp:byebye request MUST be set to the 
   service's service type. ssdp:byebye requests MUST contain a USN 
   header set to the SSDP service's USN. 

↑ ssdp:byebye request的NT值必须(MUST)设置为service的service type。
ssdp:byebye requests必须(MUST)包含一个USN Header,这个header被设置为SSDP Service的USN。

> There is no response to a ssdp:byebye sent to the SSDP multicast 
   channel/port. 

↑ 无需对ssdp:byebye request进行回应。

> When a ssdp:byebye request is received all cached information 
   regarding that USN SHOULD be removed. 

↑ 当接收到ssdp:byebye request时，所有的关于USN的cache信息应该(SHOULD)被移除。

### 3.4.4.官方文档-SSDP Presence Announcements, 问答

> Why are we using GENA NOTIFY requests? 
    
>  We needed to use some notification format and GENA seemed as good as 
   any. Moving forward,   gives us a framework to do notification 
   subscriptions which will be necessary if SSDP services are to be 
   able to provide status updates across the wilds of the Internet 
   without depending on the largely non-existent Internet multicast 
   infrastructure. 

↑ 为什么使用GENA NOTIFY requests

我们需要使用一些看起来足够好的通知格式和GENA。
更进一步，如果SSDP Servcie可以穿过wilds of the Internet来提供状态更新，
而不依赖大型的不存在网络多播的设施，
给我们一个framework来做通知订阅是必要的。

> Could NTS values other than ssdp:alive/ssdp:byebye be sent to 
the SSDP multicast channel/port? 
    
> Yes. 

↑ NTS值可以是除了ssdp:alive/ssdp:byebye之外的值么？

当然可以

> Why do we include the NT header on ssdp:byebye requests? 
> 
> Technically it isn't necessary since the only useful information is 
   the USN. But we want to stick with the GENA format that requires a 
   NT header. In truth the requirement of including the NT header is a 
   consequence of the next issue. 

↑ 为甚么把NT header包含进ssdp:bybye请求中？

技术上这不是必须的，因为有用的消息只有USN。
但是我们坚持需要一个带NT Header的GENA格式。
事实上包括NT头是为了下一个issue(应该为了SSDP规范的下一步演进)。

> Shouldn't the NT and NTS values be switched? 
> 
> Yes, they should. Commands such as ssdp:alive and ssdp:byebye should 
   be NT values and the service type, where necessary, should be the 
   NTS. The current mix-up is a consequence of a previous design where 
   the NT header was used in a manner much like we use the USN today. 
   This really needs to change. 

↑ NT和NTS的值不能交换？

可以。
像ssdp:alive和ssdp:byebye这样的命令应该是NT值，service type应该是NTS(如果哪里需要)。
当前的混乱是因为先前设计的结果，以前NT Header的使用类似现在的USN。
这里确实需要改变。

## 3.5.0.官方文档-SSDP Auto-Shut-Off Algorithm

> A mechanism is needed to ensure that SSDP does not cause such a high 
   level of traffic that it overwhelms the network it is running on. 

↑ 一种保证SSDP不引起高流量从而压垮网络的机制。

> Why do we need an auto-shut-off algorithm? 
> 
> The general algorithm for figuring out how much bandwidth SSDP uses 
   over a fixed period of time based on the number of ssdp:discover 
   requests is : 

>  DR = Total number of SSDP clients making ssdp:discover requests over 
   the time period in question. 

>  RS = Total number of services that will respond to the ssdp:discover 
   requests over the time period in question. 

>  AM = Average size of the ssdp:discover requests/responses. 
   TP = Time period in question.

>  `((DR*3 + DR*9*RS)*AM)/TP `

>  The 3 is the number of times the ssdp:discover request will be 
   repeated. 

>  The 9 is the number of times the unicast responses to the 
   ssdp:discover requests will be sent out assuming the worst case in 
   which all 3 original requests are received.

>  So let's look at a real world worst-case scenario. Some companies, 
   in order to enable multicast based services such as voice or video 
   streaming to be easily configured set their local administrative 
   multicast scope to encompass their entire company. This means one 
   gets networks with 100,000 machines in a single administrative 
   multicast scope. Now imagine that there is a power outage and all 
   the machines are coming back up at the same time. Further imagine 
   that they all want to refresh their printer location caches so they 
   all send out ssdp:discover requests. Let us finally imagine that 
   there are roughly 5000 printers in this network. To simplify the 
   math we will assume that the ssdp:discover requests are evenly 
   distributed over the 30 seconds. 

>  DR = 100,000 requesting clients 
  
> RS = 5000 services 

>  AM = 512 bytes 

>  TP = 30 seconds 

>  `((100000*3+100000*9*5000)*512)/30 = 76805120000 bytes/s = 585976.5625 Megabits per second `

>  In a more reasonably sized network SSDP is able to handle this worst 
   case scenario much better. For example, let's look at a network with 
   1000 clients and 50 printers. 

>  DR = 1000 requesting clients 
  
>  RS = 50 services 

>  AM = 512 bytes 

>  TP = 30 seconds 

>  `((1000*3+1000*9*50)*512)/30 = 7731200 bytes/s = 59 Mbps`

>  Now this looks like an awful amount but remember that that this is 
   the total data rate needed for 30 seconds. This means that the total 
   amount of information SSDP needs to send out to survive a reboot is 
   59*30 = 1770 Mb. Therefore a 10 Mbps network, assuming an effective 
   data rate 5 Mbps under constant load that means it will take 1770/5 
   = 354 seconds = 6 minutes for the network to settle down. 

>  That isn't bad considering that this is an absolute worst case in a 
   network with 1000 clients and 50 services all of whom want to talk 
   to each other at the exact same instant. 

>  In either case, there are obvious worst-case scenarios and we need 
   to avoid network storms, therefore we need a way for SSDP to de-
   activate before it causes a network storms. 

↑ 为什么需要auto-shut-off算法?

一般的基于ssdp:discover数量,找出SSDP在一段固定的时间,使用多少带宽d的算法是:

DR:一定时间内SSDP client发出的ssdp:discover数量 

RS:一定时间内SSDP Service对ssdp:discover请求的response的数量

AM:ssdp:discover requests/responses的平均大小

TP:一定时间

`((DR*3 + DR*9*RS)*AM)/TP `

3为ssdp:discover请求重复的次数,

9为对ssdp:discover请求的单播响应，假设最糟糕的情况，所有的3个请求都收到了

让我们看看一个真正的世界最坏的情况.一些公司,为了使基于多播的语音或视频等服务流很容易配置，会在当地行政多播范围涵盖整个公司。

这意味着一个人获得在管理网络多播范围内得到100000台机器。

现在想象停电后，所有机器同时回来了。

进一步想象他们都想刷新缓存,所以他们打印机位置所有发送ssdp:discover请求。

最后让我们想象大约有5000个打印机在此网络中。

为了简化数学我们假定ssdp:discover请求均匀分布在30秒。

这是一个可怕的数字。

这看起来像一个可怕的但请记住,这是30秒内所需的全部数据速率。

这意味着SSDP需要发送的大小为59 * 30 = 1770 Mb,

因此10 Mbps的网络,假设一个有效固定负载数据率5 Mbps,

意味着它将在1770/5= 354秒= 6分钟的网络安定下来。

这并不坏，考虑这样一个绝对坏的情况，1000个客户端和50个服务在完全相同的时刻互相交流。

在这两种情况下,显而易见，我们需要在最坏的情况下，避免网络风暴,因此我们需要在网络风暴前，为SSDP想一种方法来de-active。


> Why not just require everyone to support directories and thus 
get around the scaling issue? 

> Many manufacturers stick every protocol they can think of in their 
   clients and services. So if your network administrator happened to 
   buy some clients and servers that supported SSDP but didn't know 
   they supported SSDP then you can imagine the problems. Therefore 
   even if we required directory support there are still many cases 
   where SSDP clients and services may inadvertently end up in a 
   network without anyone knowing it and cause problems.  

↑ 为什么不要求每个人都支持目录,来避免可伸缩性问题？

许多制造商把他们所能想到的每一个协议客户和服务都塞进去。
所以如果您的网络管理员买一些支持SSDP的客户端和服务器，但不知道他们支持SSDP的话，那你可以想象问题。
因此即使我们要求支持目录，支持SSDP的客户端和服务仍然可能无意中在网络中引起问题，却没有人知道它并导致问题。

## 3.6.0.官方文档-ssdp:all

> A mechanism is needed to enable a client to enumerate all the 
   services available on a particular SSDP multicast channel/port. 

↑ 一种使得client可以在SSDP多播频道／端口上列舉所有service的機制。

> All SSDP services MUST respond to SEARCH requests over the SSDP 
   multicast channel/port with the ST value of ssdp:all by responding 
   as if the ST value had been their service type. 

↑ 所有的SSDP servcie必须(MUST)响应ST值為ssdp:all的SEARCH請求，就像它們(Service)的ST值与其匹配似的。

> This feature is mostly for network analysis tools. It also will 
   prove very useful in the feature when directories become SSDP aware. 
   They will be able to discover all services, record information about 
   them and make that information available outside the local 
   administrative multicast scope. 
    
↑ 這個功能絕大多數用來分析網絡。這可以發現所有service，紀錄下它們的信息，使得它們在本地管理多播範圍外可用。

> Why didn't SSDP just get a static local administrative scope 
address rather than a relative address? 

> We got a relative address because we expect that SSDP may be used to 
   discover basic system services such as directories. In that case if 
   you can't find a directory in your local scope you may want to try a 
   wider multicast scope. This is exactly the sort of functionality 
   enabled by MALLOC (http://www.ietf.org/html.charters/malloc-
   charter.html). MALLOC allows one to enumerate all the multicast 
   scopes that are supported on the network. The SSDP client can then 
   try progressively larger scopes to find the service they are seeing. 
   However this progressively wider discovery only works if SSDP uses a 
   relative address. 

↑ 为什么SSDP不使用静态地址，而是相对地址？

我们使用了一个相对地址,因为我们希望SSDP可用于发现系统服务，如基本目录。
在这种情况下,如果你在当地找不到目录范围你可能想尝试更广泛的多播范围。
This is exactly the sort of functionality enabled by MALLOC.
MALLOC允许遍历网络上支持的所有多播范围。SSDP client就可以尝试逐步大范围找到他们所看到的服务。
但是这个逐步扩大的发现仅在SSDP使用相对地址时有效。

> Why does SSDP need to use a port other than 80? 

> There is a bug in the Berkley Sockets design that was inherited by 
   WinSock as well. The bug is as follows: One can not grab a 
   particular port on a particular multicast address without owning the 
   same port on the local unicast address. 

> The result is that if we used port 80 on the SSDP multicast scope 
   then we would require that the SSDP software also grab port 80 for 
   the local machine. This would mean that SSDP could only be 
   implemented on machines which either didn't have HTTP servers or 
   whose HTTP servers had been enhanced to support SSDP. 

> We felt this was a unnecessary restriction. Therefore we are 
   choosing to use a port other than 80 on the SSDP multicast channel. 

↑ 为什么SSDP需要使用一个端口,而不是80?

Berkley Socket的设计有一个bug，这个设计是从WinSock中继承而来。
这个bug是：未拥有本地单播地址上的端口A时，则不能从特定的多播地址上抓取这个端口A。

结果就是如果我们使用了80端口，我们会要求SSDP软件为本地设备抓取80端口。
这代表着SSDP仅可以在这样的设备上实现：①没有HTTP服务、②http服务已经被增强来支持SSDP。

我们觉得这样的限制时不必要的。因此我们选择了其它的端口而不是80。

## 3.8.0.官方文档-常量

> MAX_UNIQUE - 50

↑ 在trip(脱扣) auto-shut-off算法前，可以通过UDP发送的ip/port组的最大数量。

> MAX_COUNT - 30 seconds

↑ 当”go quiet”过程开始后，一个消息会在随机的0～MAX_COUNT秒内发送。

## 3.9.0.官方文档-一些例子

ssdp:discover request示例:
```
M-SEARCH * HTTP/1.1 
S: uuid:ijklmnop-7dec-11d0-a765-00a0c91e6bf6 
Host: 239.255.255.250:reservedSSDPport 
Man: "ssdp:discover" 
ST: ge:fridge 
MX: 3 
```

对ssdp:discover request的回应示例:
```
HTTP/1.1 200 OK 
S: uuid:ijklmnop-7dec-11d0-a765-00a0c91e6bf6 
Ext: 
Cache-Control: no-cache="Ext", max-age = 5000 
ST: ge:fridge 
USN: uuid:abcdefgh-7dec-11d0-a765-00a0c91e6bf6 
AL: <blender:ixl><http://foo/bar> 
```

ssdp:alive示例:
```
NOTIFY * HTTP/1.1 
Host: 239.255.255.250:reservedSSDPport 
NT: blenderassociation:blender 
NTS: ssdp:alive 
USN: someunique:idscheme3 
AL: <blender:ixl><http://foo/bar> 
Cache-Control: max-age = 7393 
```

ssdp:byebye示例:
```
NOTIFY * HTTP/1.1 
Host: 239.255.255.250:reservedSSDPport 
NT: someunique:idscheme3 
NTS: ssdp:byebye 
USN: someunique:idscheme3 
```

## 4.0.0.总结

ST: Service Type 服务类型

USN: Unique Service Name 标识一个服务实例

多播地址与端口: 239.255.255.250:1900(IPv4), FF0x::C(IPv6)

设备类型：

设备类型                       | 表示方法                                                   | 说明
------------------------------|-----------------------------------------------------------|------------
UPnP_RootDevice               | upnp:rootdevice                                           | xxx   
UPnP_InternetGatewayDevice1   | urn:schemas-upnp-org:device:InternetGatewayDevice:1       | xxx
UPnP_WANConnectionDevice1     | urn:schemas-upnp-org:device:WANConnectionDevice:1         | xxx   
UPnP_WANDevice1               | urn:schemas-upnp-org:device:WANConnectionDevice:1         | xxx   
UPnP_WANCommonInterfaceConfig1| urn:schemas-upnp-org:service:WANCommonInterfaceConfig:1   | xxx   
UPnP_WANIPConnection1         | urn:schemas-upnp-org:device:WANConnectionDevice:1         | xxx   
UPnP_Layer3Forwarding1        | urn:schemas-upnp-org:service:WANIPConnection:1            | xxx   
UPnP_WANConnectionDevice1     | urn:schemas-upnp-org:service:Layer3Forwarding:1           | xxx   

服务类型：

服务类型                | 表示方法                                         | 说明
-----------------------|-------------------------------------------------|------------
UPnP_MediaServer1      | urn:schemas-upnp-org:device:MediaServer:1       | xxx   
UPnP_MediaRenderer1    | urn:schemas-upnp-org:device:MediaRenderer:1     | xxx   
UPnP_ContentDirectory1 | urn:schemas-upnp-org:service:ContentDirectory:1 | xxx   
UPnP_RenderingControl1 | urn:schemas-upnp-org:service:RenderingControl:1 | xxx   
UPnP_ConnectionManager1| urn:schemas-upnp-org:service:ConnectionManager:1| xxx   
UPnP_AVTransport1      | urn:schemas-upnp-org:service:AVTransport:1      | 投屏   

## 4.1.0.总结-发送ssdp:discover请求

发送：发送到多播地址，如：

```
M-SEARCH * HTTP/1.1             // 请求头 不可改变（为了以后的兼容性，service端需要将没有*的过滤掉）
MAN: "ssdp:discover"            // 设置协议查询的类型，必须是：ssdp:discover
MX: 5                           // 设置设备响应最长等待时间，设备响应在0和这个值之间随机选择响应延迟的值。这样可以为控制点响应平衡网络负载。
HOST: 239.255.255.250:1900      // 设置为协议保留多播地址和端口，必须是：239.255.255.250:1900（IPv4）或FF0x::C(IPv6
ST: upnp:rootdevice             // ST一般可以用来自定义设备，如urn:schemas-upnp-org:device:Server:1)
                                // 设置服务查询的目标，它必须是下面的类型：
                                // ssdp:all  搜索所有设备和服务 
                                // upnp:rootdevice  仅搜索网络中的根设备 
                                // uuid:device-UUID  查询UUID标识的设备 
                                // urn:schemas-upnp-org:device:device-Type:version  查询device-Type字段指定的设备类型，设备类型和版本由UPNP组织定义。 
                                // urn:schemas-upnp-org:service:service-Type:version  查询service-Type字段指定的服务类型，服务类型和版本由UPNP组织定义。
```

SSDP官方例子：
```
M-SEARCH * HTTP/1.1 
S: uuid:ijklmnop-7dec-11d0-a765-00a0c91e6bf6 
Host: 239.255.255.250:reservedSSDPport 
Man: "ssdp:discover" 
ST: ge:fridge 
MX: 3 
```

其它例子
```
M-SEARCH * HTTP/1.1
HOST:239.255.255.250:1900:
MAN:"ssdp:discover"
MX:5
ST:urn:www-example-com:device:gateway
CLIENTID:ABCD123FFFF28234
```

## 4.2.0.总结-回应ssdp:discover请求

接收：监听多播地址与端口，如果发现ST与自己提供的Servcie一致，发送响应, 下面为结构：  
其中主要关注带有 * 的部分即可。这里还有一个大坑，有些设备返回来的字段名称可能包含有小写，如LOCATION和Location，需要做处理。  
此外还需根据LOCATION保存设备的IP和端口地址。

```
HTTP/1.1 200 OK             // * 消息头
LOCATION:                   // * 包含根设备描述得URL地址  device 的webservice路径（如：http://127.0.0.1:2351/1.xml) 
CACHE-CONTROL:              // * max-age指定通知消息存活时间，如果超过此时间间隔，控制点可以认为设备不存在 （如：max-age=1800）
SERVER:                     // 包含操作系统名，版本，产品名和产品版本信息( 如：Windows NT/5.0, UPnP/1.0, product/version)
EXT:                        // 为了符合HTTP协议要求，并未使用。(向控制点确认MAN头域已经被设备理解)
BOOTID.UPNP.ORG:            // 可以不存在，初始值为时间戳，每当设备重启并加入到网络时+1，用于判断设备是否重启。也可以用于区分多宿主设备。
CONFIGID.UPNP.ORG:          // 可以不存在，由两部分组成的非负十六进制整数，由两部分组成，第一部分代表跟设备和其上的嵌入式设备，第二部分代表这些设备上的服务。
USN:                        // * 表示不同服务的统一服务名, 它提供了一种标识出相同类型服务的能力。
ST:                         // * 服务的服务类型(Search Target)
DATE:                       // 响应生成时间
```

SSDP官方例子：
```
HTTP/1.1 200 OK 
S: uuid:ijklmnop-7dec-11d0-a765-00a0c91e6bf6 
Ext: 
Cache-Control: no-cache="Ext", max-age = 5000 
ST: ge:fridge 
USN: uuid:abcdefgh-7dec-11d0-a765-00a0c91e6bf6 
AL: <blender:ixl><http://foo/bar> 
```

其它例子：
```
HTTP/1.1 200 OK
Cache-control: max-age=1800
Usn: uuid:88024158-a0e8-2dd5-ffff-ffffc7831a22::urn:schemas-upnp-org:service:AVTransport:1
Location: http://192.168.1.243:46201/dev/88024158-a0e8-2dd5-ffff-ffffc7831a22/desc.xml
Server: Linux/3.10.33 UPnP/1.0 Teleal-Cling/1.0
Date: Tue, 01 Mar 2016 08:47:42 GMT+00:00
Ext: 
St: urn:schemas-upnp-org:service:AVTransport:1
```


```
HTTP/1.1 200 OK
CACHE-CONTROL: max-age = seconds until advertisement expires
DATE: when reponse was generated
EXT:
LOCATION: URL for UPnP description for root device
SERVER: OS/Version UPNP/1.0 product/version
ST: search target
USN: advertisement UUID
```

```
HTTP/1.1 200 OK
CACHE-CONTROL: max-age = 30
DATE: 2016-04-11 16:30:03
EXT:
LOCATION: http://192.168.4.71:8081/igd.xml
SERVER: tick_gateway_os/4.4.1115,UPnP/1.0
ST: urn:example-com-cn:device:gateway
USN: uuid:AABBCC7286234::label:设备名称::debug:0::urn:example-com-cn:device:gateway
// debug作为参数，或者也可以加入version
```


```
CACHE-CONTROL：max-age指定通知消息存活时间，如果超过此时间间隔，控制点可以认为设备不存在
DATE：指定响应生成的时间
EXT：向控制点确认MAN头域已经被设备理解
LOCATION：包含根设备描述得URL地址
SERVER：饱含操作系统名，版本，产品名和产品版本信息
ST：内容和意义与查询请求的相应字段相同
USN：表示不同服务的统一服务名，它提供了一种标识出相同类型服务的能力。
```

## 4.3.0.总结-ssdp:alive

```
NOTIFY * HTTP/1.1           // 消息头
NT:                         // 在此消息中，NT头必须为服务的服务类型。（如：upnp:rootdevice）
HOST:                       // 设置为协议保留多播地址和端口，必须是：239.255.255.250:1900（IPv4）或FF0x::C(IPv6)
NTS:                        // 表示通知消息的子类型，必须为ssdp:alive
LOCATION:                   // 包含根设备描述得URL地址  device 的webservice路径（如：http://127.0.0.1:2351/1.xml) 
CACHE-CONTROL:              // max-age指定通知消息存活时间，如果超过此时间间隔，控制点可以认为设备不存在 （如：max-age=1800）
SERVER:                     // 包含操作系统名，版本，产品名和产品版本信息( 如：Windows NT/5.0, UPnP/1.0)
USN:                        // 表示不同服务的统一服务名，它提供了一种标识出相同类型服务的能力。如：
                            // 根/启动设备 uuid:f7001351-cf4f-4edd-b3df-4b04792d0e8a::upnp:rootdevice
                            // 连接管理器  uuid:f7001351-cf4f-4edd-b3df-4b04792d0e8a::urn:schemas-upnp-org:service:ConnectionManager:1
                            // 内容管理器 uuid:f7001351-cf4f-4edd-b3df-4b04792d0e8a::urn:schemas-upnp-org:service:ContentDirectory:1
```

```
一个发现响应可以包含0个、1个或者多个服务类型实例。
为了做出分辨，每个服务发现响应包括一个USN：根设备的标识。
在同样的设备里，一个服务类型的多个实例必须用包含USN:ID的服务标识符标识出来。
例如，一个灯和电源共用一个开关设备，对于开关服务的查询可能无法分辨出这是用于灯的。
UPNP论坛工作组通过定义适当的设备层次以及设备和服务的类型标识分辨出服务的应用程序场景。
这么做的缺点是需要依赖设备的描述URL。
```

SSDP官方例子：
```
NOTIFY * HTTP/1.1 
Host: 239.255.255.250:reservedSSDPport 
NT: blenderassociation:blender 
NTS: ssdp:alive 
USN: someunique:idscheme3 
AL: <blender:ixl><http://foo/bar> 
Cache-Control: max-age = 7393 
```

其它例子：
```
NOTIFY * HTTP/1.1
HOST: 239.255.255.250:1900
CACHE-CONTROL: max-age = seconds until advertisement expires
LOCATION: URL for UPnP description for root device
NT: search target
NTS: ssdp:alive
USN: advertisement UUID
```

```
NOTIFY * HTTP/1.1
Host: 239.255.255.250:reservedSSDPport
NT: blenderassociation:blender
NTS: ssdp:alive
USN: someunique:idscheme3
AL: <blender:ixl><http://foo/bar>
Cache-Control: max-age = 7393
```

## 4.4.0.总结-ssdp:byebye

```
NOTIFY * HTTP/1.1       // 消息头
HOST:                   // 设置为协议保留多播地址和端口，必须是：239.255.255.250:1900（IPv4）或FF0x::C(IPv6
NTS:                    // 表示通知消息的子类型，必须为ssdp:byebye
USN:                    // 同上
```

SSDP官方例子：
```
NOTIFY * HTTP/1.1 
Host: 239.255.255.250:reservedSSDPport 
NT: someunique:idscheme3 
NTS: ssdp:byebye 
USN: someunique:idscheme3 
```

其它例子：
```
NOTIFY * HTTP/1.1
Host: 239.255.255.250:reservedSSDPport
NT: someunique:idscheme3
NTS: ssdp:byebye
USN: someunique:idscheme3
```

```
NOTIFY * HTTP/1.1
HOST: 239.255.255.250:1900//设置为协议保留多播地址和端口，必须是239.255.255.250:1900
NT: search target//在此消息中，NT头必须为服务的服务类型。
NTS: ssdp:byebye//表示通知消息的子类型，必须为ssdp:alive
USN: advertisement UUID各HTTP协议头的含义简介：//表示不同服务的统一服务名，它提供了一种标识出相同类型服务的能力。
```

## 4.5.0.其它

在所有的发现通知中，表示UPnP根设备描述的LOCATION和统一服务名(USN)必须提供。  
此外，在响应消息中查询目标头(ST)必须与LOCATION和统一服务名(USN)一起提供。  
专有设备或服务可以不遵循标准的UPNP模版。  
但如果设备或服务提供UPNP发现、描述、控制和事件过程的所有对象，它的行为就像一个标准的UPNP设备或服务。  
为了避免命名冲突，使用专有设备命名时除了UPNP域之外必须包含一个前缀"urn:schemas-upnp-org"。  
在与标准模版相同时，应该使用整数版本号。但如果与标准模版不同，不可以使用设备复用和徽标。  
简单设备发现协议不提供高级的查询功能，也就是说，不能完成某个具有某种服务的设备这样的复合查询。  
在完成设备或者服务发现之后，控制点可以通过设备或服务描述的URL地址完成更为精确的信息查询。

Date格式:
http://www.w3.org/Protocols/rfc822/#z28
```
date-time   =  [ day "," ] date time        ; dd mm yy
                                            ;  hh:mm:ss zzz

day         =  "Mon"  / "Tue" /  "Wed"  / "Thu"
            /  "Fri"  / "Sat" /  "Sun"

date        =  1*2DIGIT month 2DIGIT        ; day month year
                                            ;  e.g. 20 Jun 82

month       =  "Jan"  /  "Feb" /  "Mar"  /  "Apr"
            /  "May"  /  "Jun" /  "Jul"  /  "Aug"
            /  "Sep"  /  "Oct" /  "Nov"  /  "Dec"

time        =  hour zone                    ; ANSI and Military

hour        =  2DIGIT ":" 2DIGIT [":" 2DIGIT]
                                            ; 00:00:00 - 23:59:59

zone        =  "UT"  / "GMT"                ; Universal Time
                                            ; North American : UT
            /  "EST" / "EDT"                ;  Eastern:  - 5/ - 4
            /  "CST" / "CDT"                ;  Central:  - 6/ - 5
            /  "MST" / "MDT"                ;  Mountain: - 7/ - 6
            /  "PST" / "PDT"                ;  Pacific:  - 8/ - 7
            /  1ALPHA                       ; Military: Z = UT;
                                            ;  A:-1; (J not used)
                                            ;  M:-12; N:+1; Y:+12
            / ( ("+" / "-") 4DIGIT )        ; Local differential
                                            ;  hours+min. (HHMM)

// 如： Tue, 01 Mar 2016 08:47:42 GMT+00:00
```

```
Date头域表示消息发送的时间，时间的描述格式由rfc822定义。
例如， Date: Mon, 31 Dec 2001 04:25:57 GMT。
Date描述的时间表示世界标准时，换算成本地时间，需要知道用户所在的时区。
```

## 4.6.0.智能网关设计

如果要添加自定义的字段，不要与其它已经被定义的冲突，如TICKxxx。  
网关端维护一个连接到该网关的客户端信息，分为连接过、连接中、连接失败(尝试连接但失败的)。
允许删除这些设备信息。删除后，需要重新授权。

### 4.6.1.客户端搜索网关

客户端要发送的discover信息需要包含以下内容，如：
```
我是客户端('Client')，
我的搜索码是'Xs8s987234f'，(可以通过客户端SDK计算，如编号＋日期＋版本＋算法计算得出，网关将验证这个编号是否非法，不响应非法的搜索)
发送日期是'23862934'，
我的编号是'C39873FDLSUH92'，(可以通过客户端SDK通过硬件编号计算，网关将验证这个编号是否非法，不响应非法的搜索)
我的IP是'192.168.1.7:45345'，
我的系统是'iOS-9.3'，
我的版本(SDK)为'v4.3'，
［我要找id为GW923JJSDHK79274、GW823497HDI2342394的网关］
```

客户端搜索网关示例：
```
M-SEARCH * HTTP/1.1
HOST:239.255.255.250:1900
MAN:"ssdp:discover"
MX:5
ST:ssdp:all
ST:urn:schemas-upnp-org:device:InternetGatewayDevice:1
ST:urn:schemas-upnp-org:device:tickGateway:3//代表搜索gateway版本在3以上的，客户端SDK规定最低支持的gateway版本
DATE:Tue, 01 Mar 2016 08:47:42 GMT+00:00

// 以下待验证，或通过LOCATION进一步验证
// IP地址可以从Servcie获得的消息中直接获取？
TICK_SEARCH_CODE: Xs8s987234f //搜索码
TICK_CLIENT_ID: C39873FDLSUH92 // 客户端id
TICK_CLIENT_IP:192.168.1.7:45345
TICK_CLIENT_SYS:iOS9.3 // 客户端系统信息
TICK_CLIENT_SDK:iOS4.3 // 客户端sdk版本
TICK_GATEWAY_ID:GW923JJSDHK79274-GW823497HDI2342394 // 客户端要搜索指定id的网关（可以多个）
```

### 4.6.2.网关端响应搜索

网关端需要对discover响应的信息为：
```
我是网关('Gateway')，
我的编号为'GW72349CDU'，(计算方法同discover，客户端将验证这个编号是否非法，不响应非法的回应)
我的版本为'4.8.9234'，
我的IP地址为'192.168.1.45:3948'，
我的系统为'Linux/4.5/ksduo2'，
我的描述文件在'19.168.1.45:23498/des.xml'，
我的回应码为'xljs024'，(计算方法同discover，客户端将验证这个编号是否非法，不响应非法的回应))
我的回应日期为'8293479274'，
```

网关回应搜索请求
```
HTTP/1.1 200 OK
Cache-Control: no-cache="Ext", max-age = 5000
Ext:
LOCATION: http://192.168.4.71:8081/igd.xml
SERVER: Linux/3.10.33 UPnP/1.0 Teleal-Cling/1.0
SERVER: tick_gateway_os/4.4.1115,UPnP/1.0
DATE:Tue, 01 Mar 2016 08:47:42 GMT+00:00

// USN后面与ST相同，ST与ssdp:discover发送的一致

ST:urn:schemas-upnp-org:device:InternetGatewayDevice:1
USN: uuid:GW923JJSDHK79274::urn:schemas-upnp-org:device:InternetGatewayDevice:1

ST:urn:schemas-upnp-org:device:tickGateway:4//代表gateway版本为4
USN:uuid:GW923JJSDHK79274::urn:schemas-upnp-org:device:tickGateway:4

ST: urn:tick-site:device:gateway
USN:uuid:AA0205D50827::label:智能网关::compile:0::urn:tick-site:device:gateway

ST: urn:schemas-upnp-org:service:AVTransport:1
USN:uuid:88024158-a0e8-2dd5-ffff-ffffc7831a22::urn:schemas-upnp-org:service:AVTransport:1

// 以下待验证，或通过LOCATION进一步验证
// IP地址可以从Localtion获得的消息中直接获取？
TICK_RESPONSE_CODE: xljs024 //回应码
TICK_GATEWAY_ID:GW923JJSDHK79274-GW823497HDI2342394 // 客户端要搜索指定id的网关（可以多个）
TICK_GATEWAY_IP:192.168.1:8888
TICK_GATEWAY_SYS:Linux8.3/tick_gateway_os72.34
TICK_GATEWAY_SDK:gw_linux_8.3 // 网关端sdk版本
```

### 4.6.3.客户端获取网关端描述文件

通过LOCATION的XML文件获取，解析。具体处理待续。

### 4.6.4.客户端TCP长连接网关端

需要网关端授权，或者验证用户名密码才可以连接。具体处理待续。

### 4.6.5.客户端与网关端进行各种交互

具体处理待续。

### 4.6.6.网关端保持活跃或者上线

可以每隔n秒发送ssdp:alive保持活跃（或者TCP长连接保持）。  
上线或者网络由无网络转为有网络时发送ssdp:alive

```
NOTIFY * HTTP/1.1
NT:urn:tick-site:device:gateway
HOST:239.255.255.250:1900（IPv4）或FF0x::C(IPv6)
NTS:ssdp:alive
LOCATION:http://192.168.32.1:2351/des.xml
CACHE-CONTROL: max-age = 5000
SERVER: tick_gateway_os/4.4.1115,UPnP/1.0
USN:uuid:GW923JJSDHK79274::urn:schemas-upnp-org:device:tickGateway:4

// 以下待验证，或通过LOCATION进一步验证
// IP地址可以从Localtion获得的消息中直接获取？
TICK_RESPONSE_CODE: xljs024 //回应码
TICK_GATEWAY_ID:GW923JJSDHK79274-GW823497HDI2342394 // 客户端要搜索指定id的网关（可以多个）
TICK_GATEWAY_IP:192.168.1:8888
TICK_GATEWAY_SYS:Linux8.3/tick_gateway_os72.34
TICK_GATEWAY_SDK:gw_linux_8.3 // 网关端sdk版本
```

### 4.6.7.TCP长连接中断情况

当TCP长连接中断时：客户端失去连接，提示掉线。  
网关端连接不到客户端，将之前连接的客户端id从连接列表中删除。

### 4.6.8.网关端主动掉线

发送ssdp:byebye

```
NOTIFY * HTTP/1.1
NT:urn:tick-site:device:gateway
HOST:239.255.255.250:1900（IPv4）或FF0x::C(IPv6)
NTS:ssdp:byebye
USN:uuid:GW923JJSDHK79274::urn:schemas-upnp-org:device:tickGateway:4

LOCATION:http://192.168.32.1:2351/des.xml
CACHE-CONTROL: max-age = 5000
SERVER: tick_gateway_os/4.4.1115,UPnP/1.0


// 以下待验证，或通过LOCATION进一步验证
// IP地址可以从Localtion获得的消息中直接获取？
TICK_RESPONSE_CODE: xljs024 //回应码
TICK_GATEWAY_ID:GW923JJSDHK79274-GW823497HDI2342394 // 客户端要搜索指定id的网关（可以多个）
TICK_GATEWAY_IP:192.168.1:8888
TICK_GATEWAY_SYS:Linux8.3/tick_gateway_os72.34
TICK_GATEWAY_SDK:gw_linux_8.3 // 网关端sdk版本
```

## X.X.X.参考

- https://tools.ietf.org/id/draft-cai-ssdp-v1-03.txt  
- http://baike.baidu.com/view/277232.htm  
- https://eliyar.biz/code/iOS/DLNA_with_iOS_Android_Part_1_Find_Device_Using_SSDP/
- http://www.ibm.com/developerworks/cn/linux/other/UPnP/part2/index.html









