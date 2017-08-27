<?php
require 'Medoo.php';
use Medoo\Medoo;

$database = new Medoo([
	// required
	'database_type' => 'mysql',
	'database_name' => 'madoka',
	'server' => 'localhost',
	'username' => 'root',
	'password' => 'xuekui1997',
]);

$re=['msg'=>0,
	'data'=>null];
$method = $_GET["method"];

if($method=="get")
{
	if(isset($_POST['page']))
	{
		$re['msg']=200;
		$re['data']=get_comment($_POST['page']);
	}
}
else if($method=="post")
{
	if(isset($_POST['name'])&&isset($_POST['email'])&&isset($_POST['content']))
	{
		$re['msg']=200;
		$re['data']=post_comment($_POST['name'],$_POST['email'],$_POST['content']);
	}
}

echo json_encode($re);


function get_comment($page) {
	global $database;
	$re=[];
	$page=intval($page);
	$re['count'] = ceil($database->count("comment")/5);
	if($page<=$re['count']&&$page>0)
	{
		$re['comment'] = $database->select("comment", [
		"name",
		"email",
		"time",
		"content"
		], 
		[
		"LIMIT" => [($page-1)*5,5],
		"ORDER" => ["time" => "DESC","id" => "DESC"]
		]);
	}
	return $re;
}

function post_comment($name,$email,$content) {
	global $database;
	$database->insert("comment", [
	"name" => $name,
	"email" => $email,
	"content" => $content,
	"time" => date('Y-m-d H:i:s',time())
	]);
	return null;
}