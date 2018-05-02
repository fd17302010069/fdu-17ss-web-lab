for(let i=0;i<countries.length;i++){
    let item=document.createElement("div");
    item.className="item";

    let title=document.createElement("div");
    title.innerHTML+="<h2>"+countries[i].name+"</h2>";
    title.innerHTML+="<p>"+countries[i].continent+"</p>";
    item.appendChild(title);

    let innerBox=document.createElement("div");
    innerBox.className="inner-box";
    innerBox.innerHTML+="<h3>Cities</h3>";
    let cityList=document.createElement("ul");
    for(let num=0;num<countries[i].cities.length;num++){
        cityList.innerHTML+="<li>"+countries[i].cities[num]+"</li>";
    }
    innerBox.appendChild(cityList);
    item.appendChild(innerBox);

    let photoBox=document.createElement("div");
    photoBox.className="inner-box";
    photoBox.innerHTML+="<h3>Popular Photos</h3>";
    for(let num=0;num<countries[i].photos.length;num++){
        let image=document.createElement("img");
        image.className="photo";
        image.src="images/"+countries[i].photos[num];
        photoBox.appendChild(image);
    }
    item.appendChild(photoBox);

    let button=document.createElement("button");
    button.innerHTML="Visit";
    item.appendChild(button);

    document.getElementById("content").appendChild(item);
}