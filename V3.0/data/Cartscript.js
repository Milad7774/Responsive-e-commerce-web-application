let toShow = JSON.parse(localStorage.getItem('favourite'));
const toInject = document.getElementById('toInject');
if(toShow.length === 0){
    check();
}
else{
    build();
}
function build(){
    console.log(toShow, 'in build');
    for(let i = 0; i < toShow.length; i++){
        let current_img = products[toShow[i]].img;
        let current_price = products[toShow[i]].price + "$";
        
        const img_create = document.createElement('img');
        img_create.alt = 'Failed To load';
        img_create.src = current_img;
        img_create.classList.add("product-image");
        
        const anchor = document.createElement('a');
        anchor.href = `product-detail.html?id=${toShow[i]}`;
        
        const div_clothes = document.createElement('div');
        div_clothes.classList.add('clothes');
        
        const span_hoverEffect = document.createElement('span');
        const span = document.createElement('span');
        heart = document.createElement('span');

        span.innerHTML = current_price;
        span.classList.add("priceTag");

        span_hoverEffect.innerHTML = "Go to";
        span_hoverEffect.classList.add('hover-effect');
        
        heart.innerHTML = '&#9829;';
        heart.setAttribute('onclick', `remove('${toShow[i]}', this)`);
        heart.classList.add('favourite');

        toInject.append(div_clothes);
        div_clothes.append(anchor);
        div_clothes.append(span);
        div_clothes.append(heart);
        anchor.append(span_hoverEffect);
        anchor.append(img_create);
    }
}
function remove(item, element){
    element.parentElement.style.animation = 'fade 0.3s ease forwards';

    setTimeout(() =>{
        let indexToRemove = toShow.indexOf(item);
    toShow.splice(indexToRemove, 1);
    localStorage.setItem('favourite', JSON.stringify(toShow));
    console.log(toShow);
    if(toShow.length == 0){
        check();
    }
    element.parentElement.remove();
    }, 300)
}
function check(){
    toInject.remove();
    let span = document.createElement('span');
    span.innerHTML = 'No Favourites have been added yet!<br><br><a href = "index.html">Browse Now here</a>';
    span.classList.add('fail');
    document.body.append(span);
}