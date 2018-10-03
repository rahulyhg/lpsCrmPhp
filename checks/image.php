<?
session_start();
$w="505px";$h="330px";
if(isset($_GET["w"])){$w=$_GET["w"];}
if(isset($_GET["h"])){$h=$_GET["h"];}
if(isset($_GET['src'])){$src="../images/".$_GET['src'];}
else{$src=$_GET['src_pu'];}
?>
<div>
<img src="<?=$src?>" width="<?=$w?>" height="<?=$h?>">
</div>