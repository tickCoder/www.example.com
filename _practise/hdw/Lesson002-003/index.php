<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/*
 * 弱类型语言
 * 变量,可变变量,外部变量,常量,魔术常量
 */

// 弱类型语言
$webname = "houdunwang";
var_dump($webname);
echo "</br>";

$webname = "bbs.houdunwang.com";
var_dump($webname);
echo "</br>";

$webname = 123;
var_dump($webname);
echo "</br>";

// 区分大小写,以字母或下划线开头,可以由数字\字母\下划线组成
$WEBNAME = "后盾网";
echo $WEBNAME;
echo "</br>";

// 可变变量:把一个变量的值作为一个新变量的变量名
$webname = "houdunwang";
$$webname = "后盾网";
echo $houdunwang;//$$webname
echo "</br>";

// 外部变量
$_GET["get"];//地址栏:不安全,数据量小
$_POST["post"];//更安全,数据量无限制,可上传文件(php.ini中post_max_size=8M, 单个文件大小upload_max_filesize=2M修改,后者要小于前者,post大小要小于memory_limit=128M)
$_REQUEST["request"];//可同时接收get/post,效率较低


echo '<br/>';
echo '<br/>';


echo 'get_getname: ';
echo $_GET["getname"];
echo '<br/>';

echo 'post_postname: ';
echo $_POST["postname"];
echo '<br/>';

echo 'requeest_getname: ';
echo $_REQUEST["getname"];
echo '<br/>';

echo 'request_postname: ';
echo $_REQUEST["postname"];
echo '<br/>';
echo '<br/>';
echo '<br/>';

?>

<a href="index.php?getname=get后盾网">点击提交表单</a>
<!--action="" 这样的区别get网址的内容不会消失-->
<form action="index.php" method="post">
    <input type="text" name="postname" value="post后盾网"><br/>
    <input type="submit" value="提交"><br/>
</form>
<br/><br/><br/>

<?php

//传值
$hdw = "hdw";
$bbs = $hdw;
echo $bbs;
echo '<br/>';

//传址
$bbs2 = &$hdw;
echo $bbs2;
echo '<br/>';
$hdw = "hdw_modify";
echo $bbs2;
echo '<br/>';

?>

<br/><br/><br/> Lesson 003 <br/><br/>

<?php

// 常量define('常量名称-建议大写', '常量值')
// 修改无效
// 仅支持标量:string,int,float,bool
// 
define('CONST_VAR', '常量var');
echo CONST_VAR;
echo '<br/>';

// 作用域
function func1() {
    echo $hdw;//超出作用域,无输出
    echo '<br/>';
    echo CONST_VAR;
}
func1();

// 系统常量
echo PHP_OS;
echo '<br/>';
echo M_PI;
echo '<br/>';
echo PHP_VERSION;
echo '<br/>';

// 魔术常量
echo __FILE__;
echo '<br/>';

echo __LINE__;
echo '<br/>';

function showFuncName($arg1='x') {
    echo __FUNCTION__;
    echo '<br/>';   
}
showFuncName();

class showClassName {
    function show() {
        echo __CLASS__;
        echo '<br/>';
        echo __FUNCTION__;
        echo '<br/>';
        echo __METHOD__;
        echo '<br/>';
    }
}
$classVar = new showClassName();
$classVar->show();

// 检测变量是否存在
if (isset($houdunwang)) {
    echo 'isset';
} else {
    echo '!isset';
}
echo '<br/>';

// 检测常量是否存在
define('URL', 'xxxx');
var_dump(defined("URL"));

?>


