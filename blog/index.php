

<?php
    echo "</br>";
    echo "</br>";
    //获取当前的域名:  
    echo "当前域名SERVER_NAME：";
    echo $_SERVER['SERVER_NAME'];  
    echo "</br>";
    echo "</br>";

    //获取来源网址,即点击来到本页的上页网址  
    echo "来源网址HTTP_REFERER：";
    echo $_SERVER["HTTP_REFERER"];  
    echo "</br>";
    echo "</br>";

    echo "当前REQUEST_URI：";
    echo $_SERVER['REQUEST_URI'];//获取当前域名的后缀  
    echo "</br>";
    echo "</br>";

    echo "HTTP_HOST：";
    echo $_SERVER['HTTP_HOST'];//获取当前域名  
    echo "</br>";
    echo "</br>";

    echo "当前文件的物理路径：";
    echo dirname(__FILE__);//获取当前文件的物理路径  
    echo "</br>";
    echo "</br>";

    echo "获取当前文件的上一级物理路径：";
    echo dirname(__FILE__)."/../";//获取当前文件的上一级物理路径  
    echo "</br>";
?>  

<br/>
<br/>1.提供给员工的blog系统
<br/>2.允许导出到文件
<br/>3.允许使用markdown格式
<br/>4.允许上传到博客园、简书等博客服务器
<br/>5.可选择是否公开（不公开其实就是笔记了）
<br/>