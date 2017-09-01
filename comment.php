<?php
require 'Medoo.php';
require 'Akismet.class.php';
use Medoo\Medoo;

$WordPressAPIKey = '9595e242592d';
$MyBlogURL = 'https://madoka.bid/';

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
		"isCommentSpam" => 0,
		"LIMIT" => [($page-1)*5,5],
		"ORDER" => ["time" => "DESC","id" => "DESC"]
		]);
	}
	return $re;
}

function post_comment($name,$email,$content) {
	global $database;
	global $WordPressAPIKey;
	global $MyBlogURL;
	
	$name=strip_tags($name);
	$email=strip_tags($email);
	$content=strip_tags($content);
	
	$akismet = new Akismet($MyBlogURL ,$WordPressAPIKey);
	$akismet->setCommentAuthor($name);
	$akismet->setCommentAuthorEmail($email);
	$akismet->setCommentAuthorURL('https://madoka.bid/');
	$akismet->setCommentContent($content);
	$akismet->setPermalink('https://madoka.bid/');
	if($akismet->isCommentSpam())
	{
		return "isCommentSpam";
	}
	// store the comment but mark it as spam (in case of a mis-diagnosis)
	else if($name==""||$email==""||$content=="")
	{
		return "isnull";
	}
	else
	{
		$database->insert("comment", [
		"name" => $name,
		"email" => $email,
		"content" => $content,
		"time" => date('Y-m-d H:i:s',time()),
		"user_ip" => $_SERVER["REMOTE_ADDR"]
		]);
		return null;
	}
	// store the comment normally
	

}