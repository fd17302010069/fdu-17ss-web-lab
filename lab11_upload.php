<?php
if($_FILES["file_upload"]["error"]>0){
    echo 'Error:'.$_FILES["file_upload"]["error"];
}
else{
    if(file_exists("media/".$_FILES["file_upload"]["name"])){
        echo $_FILES["file_upload"]["name"].' already exists';
    }
    else{
        move_uploaded_file($_FILES["file_upload"]["tmp_name"],"media/".$_FILES["file_upload"]["name"]);
        echo 'Success';
    }
}

$fileName=$_FILES["file_upload"]["name"];
$fileNameArray=explode(".",$fileName);
$lyricName="";
for($i=0;$i<count($fileNameArray)-1;$i++){
    $lyricName.=$fileNameArray[$i];
}
$lyricName.=".lrc";

$lyricFile=fopen("media/".$lyricName,"w") or die("Error!");
$lyricContent=$_POST["edit_lyric"];
fwrite($lyricFile,$lyricContent);
fclose($lyricFile);
