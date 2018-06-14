<html>
<head>
<title>LRC 歌词编辑器</title>
<meta charset="UTF-8">
<style>
    nav ul {
        position: fixed;
        z-index: 99;
        right: 5%;
        border: 1px solid darkgray;
        border-radius: 5px;
        list-style:none;
        padding: 0;
    }

    .tab {
        padding: 1em;
        display: block;
    }

    .tab:hover {
        cursor: pointer;
        background-color: lightgray !important;
    }

    td {
        padding:0.2em;
    }

    textarea[name="edit_lyric"] {
        width: 100%;
        height: 50em;
    }

    input[type="button"] {
        width: 100%;
        height: 100%;
    }

    input[type="submit"] {
        width: 100%;
        height: 100%;
    }

    #td_submit {
        text-align: center;
    }

    select {
        display: block;
    }

    #lyric {
        width: 35%;
        height: 60%;
        border: 0;
        resize: none;
        font-size: large;
        line-height: 2em;
        text-align: center;
        overflow: scroll;
        font-weight: lighter;
    }

    .thicker{
        font-weight: bolder;
    }

</style>
</head>
<body>
    <nav><ul>
        <li id="d_edit" class="tab">Edit Lyric</li>
        <li id="d_show" class="tab">Show Lyric</li>
    </ul></nav>

<!--歌词编辑部分-->
<section id="s_edit" class="content">
<form id="f_upload" enctype="multipart/form-data" method="post" action="lab11_upload.php">
    <p>请上传音乐文件</p>

    <!--TODO: 在这里补充 html 元素，使 file_upload 上传后若为音乐文件，则可以直接播放-->
    <audio autoplay="autoplay" controls="controls"></audio>

    <input type="file" name="file_upload">
    <table>
        <tr><td>Title: <input type="text"></td><td>Artist: <input type="text"></td></tr>
        <tr><td colspan="2"><textarea name="edit_lyric" id="edit_lyric"></textarea></td></tr>
        <tr><td><input type="button" value="插入时间标签" id="add"></td><td><input type="button" value="替换时间标签"></td></tr>
        <tr><td colspan="2" id="td_submit"><input type="submit" value="Submit"></td></tr>   
    </table>
</form>
</section>

<!--歌词展示部分-->
<section id="s_show" class="content">
    <select id="select">
    <!--TODO: 在这里补充 html 元素，使点开 #d_show 之后这里实时加载服务器中已有的歌名-->
        <option value="">选择音乐</option>
        <?php include "lab11_loadMusic.php";?>
    </select>

    <div id="lyric">
    </div>
    
    <!--TODO: 在这里补充 html 元素，使选择了歌曲之后这里展示歌曲进度条，并且支持上下首切换-->
    <audio autoplay="autoplay" controls="controls" preload="auto" id="show_audio"></audio>
    <button type="button" id="pre_btn" disabled>上一首</button>
    <button type="button" id="next_btn" disabled>下一首</button>

</section>
</body>
<script type="text/javascript" src="http://ajax.microsoft.com/ajax/jquery/jquery-1.4.min.js"></script>
<script>

//播放选中音频
document.getElementsByName("file_upload")[0].onchange=function(){
    document.getElementsByTagName("audio")[0].src = window.URL.createObjectURL(this.files[0]);
};

// 界面部分
document.getElementById("d_edit").onclick = function () {click_tab("edit");};
document.getElementById("d_show").onclick = function () {click_tab("show");};

document.getElementById("d_show").click();

function click_tab(tag) {
    for (let i = 0; i < document.getElementsByClassName("tab").length; i++) document.getElementsByClassName("tab")[i].style.backgroundColor = "transparent";
    for (let i = 0; i < document.getElementsByClassName("content").length; i++) document.getElementsByClassName("content")[i].style.display = "none";

    document.getElementById("s_" + tag).style.display = "block";
    document.getElementById("d_" + tag).style.backgroundColor = "darkgray";
} 

// Edit 部分
var edit_lyric_pos = 0;
document.getElementById("edit_lyric").onmouseleave = function () {
    edit_lyric_pos = document.getElementById("edit_lyric").selectionStart;
};

// 获取所在行的初始位置。
// function get_target_pos() {
//     return get_target_pos(edit_lyric_pos);
// }

function get_target_pos(n_pos) {
    if(n_pos===undefined)
        n_pos=edit_lyric_pos;
    let value = document.getElementById("edit_lyric").value; 
    let pos = 0;
    for (let i = n_pos; i >= 0; i--) {
        if (value.charAt(i) === '\n') {
            pos = i + 1;
            break;
        }
    }
    return pos;
}

// 选中所在行。
function get_target_line(n_pos) {
    let value = document.getElementById("edit_lyric").value; 
    let f_pos = get_target_pos(n_pos);
    let l_pos = 0;

    for (let i = f_pos;; i++) {
        if (value.charAt(i) === '\n') {
            l_pos = i + 1;
            break;
        }
    }
    return [f_pos, l_pos];
}

/* HINT: 
 * 已经帮你写好了寻找每行开头的位置，可以使用 get_target_pos()
 * 来获取第一个位置，从而插入相应的歌词时间。
 * 在 textarea 中，可以通过这个 DOM 节点的 selectionStart 和
 * selectionEnd 获取相对应的位置。
 *
 * TODO: 请实现你的歌词时间标签插入效果。
 */
document.getElementById("add").onclick=function () {
    let currentTime=document.getElementsByTagName("audio")[0].currentTime;
    let currentMinute=Math.floor(currentTime/60);
    let currentSecond=currentTime%60;
    currentSecond=currentSecond.toFixed(2);

    let timeTag="[00:";
    timeTag+=(currentMinute<10)?("0"+currentMinute):currentMinute;
    timeTag+=":";
    timeTag+=(currentSecond<10)?("0"+currentSecond):currentSecond;
    timeTag+="]";

    let startPos=get_target_pos();
    let text=document.getElementById("edit_lyric");
    let content=text.value;
    text.value=content.substr(0,startPos)+timeTag+content.substr(startPos,content.length);
};

/* TODO: 请实现你的上传功能，需包含一个音乐文件和你写好的歌词文本。
 在lab11_upload.php中进行上传操作*/

//选择歌曲时加载歌曲
let select=document.getElementById("select");
let preBtn=document.getElementById("pre_btn");
let nextBtn=document.getElementById("next_btn");
let optionNum=select.options.length;
let showAudio=document.getElementById("show_audio");
let lyricList;
let timeArray=[];

function changeSRC() {
    showAudio.src=select.options[select.selectedIndex].value;
    $("#lyric").load("lab11_loadLyric.php",{fileName:select.options[select.selectedIndex].innerText});
}
function changeBtn(){
    if(select.selectedIndex===0||optionNum===1||optionNum===2){
        preBtn.setAttribute("disabled","disabled");
        nextBtn.setAttribute("disabled","disabled");
        return;
    }
    if(select.selectedIndex===1){
        preBtn.setAttribute("disabled","disabled");
        nextBtn.removeAttribute("disabled");
    }
    if(select.selectedIndex===optionNum-1){
        preBtn.removeAttribute("disabled");
        nextBtn.setAttribute("disabled","disabled");
    }
    if(select.selectedIndex>1&&select.selectedIndex<optionNum-1){
        preBtn.removeAttribute("disabled");
        nextBtn.removeAttribute("disabled");
    }
}
select.onchange=function () {
    changeBtn();
    changeSRC();
    count=0;
    document.getElementById("lyric").scrollTop=0;
};
preBtn.onclick=function () {
    select.options[select.selectedIndex-1].selected=true;
    changeBtn();
    changeSRC();
    count=0;
    document.getElementById("lyric").scrollTop=0;
};
nextBtn.onclick=function () {
    select.options[select.selectedIndex+1].selected=true;
    changeBtn();
    changeSRC();
    count=0;
    document.getElementById("lyric").scrollTop=0;
};

/* HINT: 
 * 实现歌词和时间的匹配的时候推荐使用 Map class，ES6 自带。
 * 在 Map 中，key 的值必须是字符串，但是可以通过字符串直接比较。
 * 每一行行高可粗略估计为 40，根据电脑差异或许会有不同。
 * 当前歌词请以粗体显示。
 * 从第八行开始，当歌曲转至下一行的时候，需要调整滚动条，使得当前歌
 * 词保持在正中。
 *
 * TODO: 请实现你的歌词滚动效果。
 */

function changeTagToTime(timeTag){
    let temp=timeTag.substr(1,11);
    let timeArray=temp.split(":");
    return timeArray[0] * 3600 + timeArray[1] * 60 + parseFloat(timeArray[2]);
}





function iniTimeArray() {
    lyricList=document.getElementsByClassName("lyric_list");
    for(let i=0;i<lyricList.length;i++){
        let timeTag=lyricList[i].id;
        timeArray[i]=changeTagToTime(timeTag);
    }
    timeArray[lyricList.length]=showAudio.duration;
}


let count=0;
showAudio.ontimeupdate=function () {
    let currentTime=showAudio.currentTime;
    if (currentTime>=timeArray[count]&&currentTime<timeArray[count+1]){
        for(let i=0;i<lyricList.length;i++){
            lyricList[i].className="lyric_list";
        }
        lyricList[count].className="lyric_list thicker";
        if(count>=7 && count<lyricList.length){
            document.getElementById("lyric").scrollTo(lyricList[count].offsetLeft,lyricList[count].offsetTop-240);
        }
    }
    else if(currentTime>=timeArray[count+1]){
        count++;
    }
};

showAudio.onseeked=function () {
    let curTime=showAudio.currentTime;
    if(curTime<timeArray[0]){
        count=0;
        document.getElementById("lyric").scrollTo(lyricList[count].offsetLeft,lyricList[count].offsetTop-200);
        return;
    }
    for(let n=0;n<timeArray.length;n++){
        if(curTime>=timeArray[n]&&curTime<timeArray[n+1]){
            count=n;
            document.getElementById("lyric").scrollTo(lyricList[count].offsetLeft,lyricList[count].offsetTop-200);
            break;
        }
    }
}

</script>
</html>