Tags: localhost apache php 域名 二级 mamp hosts example.site mac
Status: public






# Mac OS X下设置本地二级域名

在MAMP－Hosts中添加多个server，如

- www.example.site(_设置Alias:example.site_): /Volumes/Websites/www.example.site/ 
- admin.example.site: /Volumes/Websites/www.example.site/admin/
- users.example.site: /Volumes/Websites/www.example.site/users/
- blog.example.site: /Volumes/Websites/www.example.site/blog/

通过以上设置会发现，在本机hosts文件中增加了多条记录

- ::1 www.example.site
- ::1 example.site
- ::1 admin.example.site
- ::1 users.example.site
- ::1 blog.example.site
- 127.0.0.1 www.example.site
- 127.0.0.1 example.site
- 127.0.0.1 admin.example.site
- 127.0.0.1 users.example.site
- 127.0.0.1 blog.example.ste

这时访问blog.example.site则会直接访问到/Volumes/Websites/www.example.site/blog/目录，并且地址栏为blog.example.site

推荐一个hosts文件编辑软件
<a href="https://github.com/specialunderwear/Hosts.prefpane" target="_blank">Hosts.prefpane</a>