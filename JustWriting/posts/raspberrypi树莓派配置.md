Status: public
Toc: yes


# 安装系统

下载树莓派官方系统raspbian，下载地址：  
https://www.raspberrypi.org/downloads/raspbian/

遵照安装指导：  
https://www.raspberrypi.org/documentation/installation/installing-images/README.md

Mac OS X下的安装主要为：  

- 格式化TF卡为FAT32格式
- 找到TF卡的标识

> 根据About This Mac-->System Report-->Hardware-->Card Reader(如果是通过USB读卡器应该是USB处查看)-->BSD Name  
> 比如为disk4

- Unmound分区，但不是Eject，以此确保有override权限
- 打开终端，输入

> sudo dd bs=1m if=path_of_your_image.img of=/dev/rdisk4

- 如果失败了，可以使用

> sudo dd bs=1m if=path_of_your_image.img of=/dev/disk4

- 等待写入成功，Eject TF卡

系统写入成功后，将TF卡插到设备上，连接电源开机，等待进入系统。

# 基本设置

## CLI

进入系统后，如果以后不需要桌面（不需要显示器查看，而是ssh连接的话）,可以通过设置，选择从CLI启动。  
如果要使用桌面环境，可以在CLI启动后，输入命令startx启动桌面。  

## 中文字体
默认情况下，系统不支持中文，需要安装中文字体：
`sudo apt-get install ttf-wqy-zenhei`

## 安装全功能的vim

`sudo apt-get remove vim-common`
`sudo apt-get install vim`

# 安装LAMP＋PMA

使用的命令主要有：

```
sudo apt-get install apache2
sudo apt-get install mysql-server //设置密码root-pwd:root
sudo apt-get install php5
sudo apt-get install php5-mysql
sudo apt-get install phpmyadmin // mysql application pwd for phpmyadmin:phpmyadmin
// 可以创建软链接(不创建也行) 
sudo ln -s /usr/share/phpmyadmin /var/www/html
```

开启apache路径大小写

```
// 使得localhost/PHPINFO.php 与localhost/phpinfo.php均可以访问
sudo cp /etc/apache2/mods-available/speling.load  /etc/apache2/mods-enabled/speling.load
sudo vi speling.conf // 写入"CheckSpelling on"，不带双引号
/etc/init.d/apache2 start // 重启apache
```

# 安装SFTP

```
sudo apt-get install vsftpd //需要在添加用户前面

// 添加用户并赋权
sudo useradd -d /var/www -G www-data admin_web
sudo passwd admin_web
sudo usermod -a -G www-data admin_web
sudo usermod -d /var/www admin_web
sudo chgrp -R www-data /var/www
sudo chmod -R g+w /var/www
sudo chmod g+s /var/www
```

# 设置example.com

## rewrite

复制/etc/apache2/modes-available/rewrite.load  
到达/etc/apache2/modes-enabled/rewrite.load

里面内容为：
```
LoadModule rewrite_module /usr/lib/apache2/modules/mod_rewrite.so
```

## VirtualHost

修改 /etc/apache2/sites-enabled $ sudo vi 000-default.conf 

```
# 只显示修改的部分
<VirtualHost *:80>
        ServerName www.example.com
        ServerAlias example.com

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html
</VirtualHost>
```

增加 /etc/apache2/sites-enabled $ sudo vi uses.example.com.conf 

```
# 只显示修改的部分
<VirtualHost *:80>
        ServerName users.example.com

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html/users
</VirtualHost>
```

增加 /etc/apache2/sites-enabled $ sudo vi justwriting.example.com.conf 

```
# 只显示修改的部分
<VirtualHost *:80>
        ServerName justwriting.example.com

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html/justwriting
</VirtualHost>
```

## apache2.conf

更改/etc/apache2/apache2.conf文件内容

```
# 只显示修改的部分

# 增加此处用于支持users.example.com
<Directory /var/www/html/users/>
        Options Indexes FollowSymLinks
        AllowOverride None
        Require all granted
</Directory>

# 增加此处用于支持justwriting.example.com
# 使用“AllowOverride All" 配合justwriting的.htaccess文件
<Directory /var/www/html/justwriting/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
</Directory>
```

## .htaccess

为了正确显示justwriting，需要放置.htaccess文件到/var/www/html/justwriting/.htaccess

```
RewriteEngine on

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

RewriteCond $1 !^(index\.php|img|robots\.txt)
RewriteRule ^(.*)$ /index.php/$1 [L]
```

## justwring设置

修改/var/www/html/justwriting/settings.php以下几项

```
$blog_config['avatar']= 'http://JustWriting.example.com/avatar.jpeg';
$blog_config['base_url'] = 'http://JustWriting.example.com';
$blog_config['image_prefix'] = 'http://JustWriting.example.com/posts/';
```

将avatar.jpeg放到 ` /var/www/html/justwriting/avatar.jpeg `  
将md文件放置在 ` /var/www/html/justwriting/posts/ `  
将md配图放置在 ` /var/www/html/justwriting/posts/images `  
md文件中引用时使用 `![描述](images/image_001.png)`

## 客户机访问

客户机访问时，添加hosts项目(192.168.31.123为树莓派ip地址)

```
192.168.31.123 example.com
192.168.31.123 www.example.com
192.168.31.123 users.example.com
```

# 附录
 
 访问国外网页时，有时候访问很慢，查看后是加载fonts.google.com等失败，可以在hosts文件中添加以下内容，使用镜像解决：

参考：   

https://servers.ustclug.org/2014/07/ustc-blog-force-google-fonts-proxy/  
http://libs.useso.com

```
// 本来的内容，此段不添加
fonts.googleapis.com         fonts.lug.ustc.edu.cn或fonts.useso.com
ajax.googleapis.com          ajax.lug.ustc.edu.cn或ajax.useso.com
themes.googleusercontent.com google-themes.lug.ustc.edu.cn
fonts.gstatic.com            fonts-gstatic.lug.ustc.edu.cn
libs.googleapis.com       libs.useso.com


// 增加以下内容
202.38.93.153 fonts.googleapis.com
202.38.93.153 ajax.googleapis.com  
202.38.93.153 themes.googleusercontent.com
202.38.93.153 fonts.gstatic.com 

221.204.14.195 libs.googleapis.com
221.204.14.195 fonts.googleapis.com
221.204.14.195 ajax.googleapis.com
```