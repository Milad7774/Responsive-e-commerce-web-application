//Grabbing Elements
const images = document.getElementById('injectImages');
const urlParams = new URLSearchParams(window.location.search);
const key = urlParams.get('id');
const imageCounter = products[key].gallery.length;
const heart = document.getElementById('heart');
const current_stock = products[key].stock;

//Retrieving Data
let favourite = JSON.parse(localStorage.getItem('favourite') || '[]');
console.log("AT START: ", favourite);

//Adding imageCounter
let imageTracker = document.getElementsByClassName('imageTracker')[0];
let currentImage = 1;
imageTracker.innerHTML = `${currentImage}/${imageCounter}`;



//Arrow Events
const rightArrow = document.getElementsByClassName('rightArrow')[0];
const leftArrow = document.getElementsByClassName('leftArrow')[0];
rightArrow.addEventListener('click' , transofromRight);
leftArrow.addEventListener('click', transofromLeft);

//Adding Description
const description = document.getElementById('injectDescription');
description.innerHTML = products[key].description;

//Adding Price
const price = document.getElementById('injectPrice');
const pNum = document.createElement('div');
pNum.classList.add('priceNumber');
pNum.innerHTML =  products[key].price + '$';
price.append(pNum);
//Adding Stock

const stock = document.createElement('div');

if(current_stock == 0){
    stock.innerHTML = "No Stock Left!";
    stock.classList.add("noStock");
}
else if(current_stock < 5){
    stock.innerHTML = "Only " + current_stock + " left in Stock";
    stock.classList.add("noStock");
}
else{
    stock.innerHTML = "Stock Left " + current_stock;
    stock.classList.add("stock");
}
price.append(stock);



//Adding Images
for(let i = 0; i < imageCounter; i++){
    let gallery = document.createElement('img');
    gallery.src = products[key].gallery[i];
    gallery.classList.add('images');
    images.append(gallery);
}
//functions
function transofromRight(){
    const style = window.getComputedStyle(images);
    rightArrow.removeEventListener('click' , transofromRight);
    let matrix = new DOMMatrixReadOnly(style.transform);
    if(!isNaN(matrix.m41)){
        var currentX = matrix.m41;
    }
    else{
        var currentX = 0;
    }
    if(currentX <= (products[key].gallery.length - 1)*(-317.8)){
        rightArrow.addEventListener('click' , transofromRight);
        return;
    }
    images.style.transform = `translateX(${-317.8 +(currentX)}px)`;
    setTimeout(() =>{
        //ImageCounter
        currentImage++;
        imageTracker.innerHTML = `${currentImage}/${imageCounter}`;
        rightArrow.addEventListener('click' , transofromRight);
    }, 500);
}
function transofromLeft(){
    const style = window.getComputedStyle(images);
    leftArrow.removeEventListener('click' , transofromLeft);
    let matrix = new DOMMatrixReadOnly(style.transform);
    if(!isNaN(matrix.m41)){
        var currentX = matrix.m41;
    }
    else{
        var currentX = 0;
    }
    if(currentX == 0){
        leftArrow.addEventListener('click' , transofromLeft);
        return;
    }
    images.style.transform = `translateX(${317.8 +(currentX)}px)`;
    setTimeout(() =>{
        currentImage--;
        imageTracker.innerHTML = `${currentImage}/${imageCounter}`;
        leftArrow.addEventListener('click' , transofromLeft);
    }, 500);
}

if(favourite.includes(key)){
    heart.style.color = 'red';
}

function toggle(){
    if(window.getComputedStyle(heart).color == 'rgb(255, 255, 255)'){
        favourite.unshift(key);
        localStorage.setItem('favourite', JSON.stringify(favourite));
        console.log("AFTER RED: ", favourite);
        heart.style.animation = 'scale 1s 1';
        heart.style.color = 'red';
    }
    else{
        favourite.splice(favourite.indexOf(key), 1);
        localStorage.setItem('favourite' , JSON.stringify(favourite));
        localStorage.getItem('favourite');
        console.log("AFTER WHITE: ", favourite);
        heart.style.color = 'white';
        heart.style.animation = "";
    }
}