<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	header("Content-Type: text/html; charset=utf-8");
	$targ_w = $targ_h = 150;      //生成头像的尺寸
	$jpeg_quality = 90;           //生成头像的图片质量，范围0-100
	
    $tmp_name = $_FILES['file']['tmp_name'];
	$name = iconv("utf-8", "gb2312", $_FILES['file']['name']);
	$type = $_FILES['file']["type"];           //获取图片的信息，临时文件名、图片名称、图片类型
	move_uploaded_file($tmp_name,$name);       //将原图片临时存储
	$img_r="";
	
	switch($type){
		case 'image/jpeg':$img_r=imagecreatefromjpeg($name);unlink($name);break;     //根据图片类型，创建一幅图像，可自己添加图片类型，创建完成后，删除原图片
		case 'image/png':$img_r=imagecreatefrompng($name);unlink($name);break;
		default:echo "你选择的图片格式暂不支持，你可以在代码中添加该类型图片支持";unlink($name);exit; 
	}
	
	$dst_r = ImageCreateTrueColor( $targ_w, $targ_h);    //头像的底版

	imagecopyresampled($dst_r,$img_r,0,0,$_POST['ix'],$_POST['iy'],$targ_w,$targ_h,$_POST['iw'],$_POST['ih']);  //讲原图片中的选框中的图片放置到头像底版中
    if(!is_dir("upload/")){
    	mkdir("upload/");      //若没有upload文件夹，则创建
    }
	imagejpeg($dst_r,"upload/".$name,$jpeg_quality);   //将头像存储到upload文件夹中，若想自己定义名称，则将$name更换为自己的名字
	$name = iconv("utf-8", "utf-8", $_FILES['file']['name']);
	echo $name;
	echo "<img src='upload/".$name."'>";      //将图片在浏览器中显示出来
	echo "<p>图片已保存到upload文件夹下</p>";
	
	exit;
}

?>