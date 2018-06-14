<?php
$file=scandir("media");
$music=array();

foreach ($file as $name){
    if($name==="."||$name===".."){
        continue;
    }
    $nameArray=explode(".",$name);
    if (end($nameArray)==="mp3"){
        $musicCount=count($music);
        $musicName="";
        for($i=0;$i<count($nameArray)-1;$i++){
            $musicName.=$nameArray[$i];
        }
        $music[$musicCount]["name"]=$musicName;
        $music[$musicCount]["path"]="media/".$name;
    }
}
for($i=0;$i<count($music,0);$i++){
    if(isset ($music[$i]["name"])){
        ?>
        <option value="<?php echo $music[$i]["path"];?>"><?php echo $music[$i]["name"]?></option>
        <?php
    }
}

?>