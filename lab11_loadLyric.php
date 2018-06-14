<?php
if(isset($_POST["fileName"])){
    $lyricPath="media/".$_POST["fileName"].".lrc";
    if(file_exists($lyricPath)){
        $myFile = fopen($lyricPath,"r") or die("Unable to open file!");
        while(!feof($myFile)) {
            $lyricRow=fgets($myFile);
            $timeTag=substr($lyricRow,0,13);
            $lyricContent=substr($lyricRow,13);
            echo '<div class="lyric_list" id="'.$timeTag.'">'.$lyricContent.'</div>';
        }
        fclose($myFile);
    }
    else{
        echo '歌词文件不存在';
    }
}
?>
<script>iniTimeArray();</script>
