let select1=document.getElementById("select1");
let select2=document.getElementById("select2");
let content1=document.getElementById("content1");
let content2=document.getElementById("content2");
let commit=document.getElementById("commit");
let tables=[];

select1.onchange=function () {
    changeAction();
};

select2.onchange=function () {
    changeTable();
    changeAction();
};

function changeAction(){
    content1.innerHTML="";
    if(select1.options[0].selected===true){
        commit.style.display="none";
    }
    else if(select1.options[1].selected===true){
        commit.style.display="none";
        createTable();
    }
    else if(select1.options[2].selected===true){
        commit.style.display="none";
        addRow();
    }
    else if(select1.options[3].selected===true){
        deleteRow();
    }
    else if(select1.options[4].selected===true){
        deleteTable();
    }
}

function changeTable() {
    content2.innerHTML="";
    let index=select2.selectedIndex;
    if(index===0){
        return;
    }

    let table=document.createElement("table");
    let rowHead=document.createElement("tr");
    for(let i=0;i<tables[index].attr.length;i++){
        let tableHead=document.createElement("th");
        tableHead.innerHTML=tables[index].attr[i];
        rowHead.appendChild(tableHead);
    }
    table.appendChild(rowHead);

    for(let rowNum=0;rowNum<tables[index].tableContent.length;rowNum++){
        let tableRow=document.createElement("tr");
        if(rowNum % 2!==0){
            tableRow.className="odd_row";
        }
        for (let colNum=0;colNum<tables[index].attr.length;colNum++){
            let tableCell=document.createElement("td");
            tableCell.innerHTML=tables[index].tableContent[rowNum][colNum];
            tableRow.appendChild(tableCell);
        }
        table.appendChild(tableRow);
    }

    content2.appendChild(table);
}

class Table{
    constructor(name,attr){
        this.name=name;
        this.attr=attr;
        this.tableContent=[];
    }
}

function createTable() {
    let inputName=document.createElement("input");
    inputName.type="text";
    inputName.placeholder="Table Name";
    content1.appendChild(inputName);

    let inputNumber=document.createElement("input");
    inputNumber.type="number";
    inputNumber.placeholder="Columns Numbers";
    content1.appendChild(inputNumber);

    let attrBox=document.createElement("div");
    content1.appendChild(attrBox);

    inputNumber.onchange=function () {
        attrBox.innerHTML="";
        if(inputNumber.value > 0){
            for(let i=0;i<inputNumber.value;i++){
                let inputAttr=document.createElement("input");
                inputAttr.type="text";
                inputAttr.placeholder="Attribute";
                inputAttr.className="inputAttr";
                attrBox.appendChild(inputAttr);
            }
            checkAll();
        }
    };

    commit.onclick=function () {
        let newTable=document.createElement("option");
        select2.appendChild(newTable);

        let attr=[];
        let attrList=document.getElementsByClassName("inputAttr");
        for(let i=0;i<inputNumber.value;i++){
            attr[i]=attrList[i].value;
        }
        tables[newTable.index]=new Table(inputName.value,attr);

        newTable.innerHTML=tables[newTable.index].name;
        newTable.selected=true;

        changeTable();
    }
}

function checkAll() {
    let inputBoxes=document.getElementsByTagName("input");
    for (let num=0;num<inputBoxes.length;num++){
        inputBoxes[num].onchange=function () {
            let x=0;
            for(;x<inputBoxes.length;x++){
                if(inputBoxes[x].value===""){
                    commit.style.display="none";
                    return;
                }
            }
            if(x===inputBoxes.length){
                commit.style.display="block";
            }
        }
    }
}

function checkOne() {
    let inputBoxes=document.getElementsByTagName("input");
    for (let num=0;num<inputBoxes.length;num++){
        inputBoxes[num].onchange=function () {
            let x=0;
            for(;x<inputBoxes.length;x++){
                if(inputBoxes[x].value!==""){
                    commit.style.display="block";
                    return;
                }
            }
            if(x===inputBoxes.length){
                commit.style.display="none";
            }
        }
    }
}

function addRow() {
    let index=select2.selectedIndex;
    if(index===0){
        commit.style.display="none";
        return;
    }

    let currentTable=tables[index];
    let attrBox=document.createElement("div");
    content1.appendChild(attrBox);
    for(let i=0;i<currentTable.attr.length;i++){
        let inputAttr=document.createElement("input");
        inputAttr.type="text";
        inputAttr.placeholder=currentTable.attr[i];
        inputAttr.className="inputAttr";
        attrBox.appendChild(inputAttr);
    }
    checkOne();

    commit.onclick=function () {
        let newRow=[];
        let attrList=document.getElementsByClassName("inputAttr");
        for(let i=0;i<currentTable.attr.length;i++){
            newRow[i]=attrList[i].value;
        }
        currentTable.tableContent.push(newRow);

        changeTable();
    }
}

function deleteRow() {
    let index=select2.selectedIndex;
    if(index===0){
        commit.style.display="none";
        return;
    }

    let currentTable=tables[index];
    let attrBox=document.createElement("div");
    content1.appendChild(attrBox);
    for(let i=0;i<currentTable.attr.length;i++){
        let inputAttr=document.createElement("input");
        inputAttr.type="text";
        inputAttr.placeholder="Attr"+(i+1);
        inputAttr.className="inputAttr";
        attrBox.appendChild(inputAttr);
        commit.style.display="block";
    }

    commit.onclick=function () {
        let attrList = document.getElementsByClassName("inputAttr");
        for (let rowNum = 0; rowNum < currentTable.tableContent.length; rowNum++) {
            let colNum = 0;
            for (; colNum < currentTable.attr.length; colNum++) {
                if(attrList[colNum].value!=="" &&
                   currentTable.tableContent[rowNum][colNum]!==attrList[colNum].value){
                    break;
                }
            }
            if(colNum===currentTable.attr.length){
                currentTable.tableContent[rowNum]=null;
            }
        }

        let newTableContent=[];
        for (let rowNum = 0; rowNum < currentTable.tableContent.length; rowNum++){
            if(currentTable.tableContent[rowNum]!==null){
                newTableContent.push(currentTable.tableContent[rowNum]);
            }
        }
        currentTable.tableContent=newTableContent;

        changeTable();
    }
}

function deleteTable() {
    let index=select2.selectedIndex;
    if(index===0){
        commit.style.display="none";
        return;
    }

    let warningBox=document.createElement("div");
    warningBox.innerHTML="<p>WARNING: You cannot undo this action!</p>";
    content1.appendChild(warningBox);
    commit.style.display="block";
    
    commit.onclick=function () {
        index=select2.selectedIndex;
        tables.splice(index,1);
        select2.removeChild(select2.options[index]);
        if(select2.options.length===1){
            select2.options[0].selected=true;
            commit.style.display="none";
            content1.innerHTML="";
        }
        else{
            select2.options[1].selected=true;
        }
        changeTable();
    }
}