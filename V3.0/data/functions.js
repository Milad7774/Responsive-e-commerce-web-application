function replace(Number , keys = keysProducts) {
    const updatedUrl = new URL(window.location.href);
    const currentUrl = new URLSearchParams(window.location.search);
    if(Number.classList[0] == 'active'){
        return;
    }
    const container = To_Inject;
    
    container.classList.add('fade-out');
    
    setTimeout(() => {
        container.innerHTML = "";
        
        const Selected = document.querySelectorAll(".numbers div");
        Selected.forEach(div => {
            div.classList.remove('active');
        })
        Number.classList.add('active');
        
        let Num = parseInt(Number.innerHTML, 10);
        if(currentUrl.has('search')){
            updatedUrl.searchParams.set('search' , currentUrl.get('search'));
        }
        if(currentUrl.has('filter')){
            updatedUrl.searchParams.set('filter' , currentUrl.get('filter'))
        }
        updatedUrl.searchParams.set('page' , Num);
        window.history.pushState({}, '', updatedUrl);
        let P_shows = 0;
        const start = itemsPerPage * (Num - 1);
        
        for (let i = start; i < keys.length && P_shows < itemsPerPage; i++) {
            //data
            let current_img = products[keys[i]].img;

            let current_price =  products[keys[i]].price + "$";
            
            let current_stock = products[keys[i]].stock;
            //creating images
            const img_create = document.createElement('img');
            img_create.alt = 'Failed To load';
            img_create.src = current_img;
            img_create.classList.add("product-image");
            //creating anchor
            const anchor = document.createElement('a');
            anchor.href = `product-detail.html?id=${keys[i]}`;
            //creating div
            const div_clothes = document.createElement('div');
            div_clothes.classList.add('clothes');
            //creeating span
            const span_hoverEffect = document.createElement('span');
            const span = document.createElement('span');
            const stock = document.createElement('span');
            //price
            span.classList.add("priceTag");
            span.innerHTML = current_price;
            //checkout
            span_hoverEffect.innerHTML = "Check Out";
            span_hoverEffect.classList.add('hover-effect');
            //Stock
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
            //adding
            container.append(div_clothes);

            div_clothes.append(anchor);

            div_clothes.append(span);

            div_clothes.append(stock);

            anchor.append(span_hoverEffect);

            anchor.append(img_create);
            P_shows++;
        }
        
        container.classList.remove('fade-out');
    }, 200);
    window.scrollTo({
        top:To_Inject.offsetTop,
        behavior: 'smooth'
    });
}
function build(keys = keysProducts, currentPage = 1){
    To_Inject.innerHTML = "";
    for (let i = itemsPerPage*(currentPage - 1); i < Math.min(itemsPerPage + itemsPerPage*(currentPage - 1),keys.length); i++) {
        try{
        let current_img = products[keys[i]].img;
        let current_price = products[keys[i]].price + "$";
        let current_stock = products[keys[i]].stock;
        
        //Creating Images
        const img_create = document.createElement('img');
        img_create.alt = 'Failed To load';
        img_create.src = current_img;
        img_create.classList.add("product-image");
        
        //Anchor Tags for whole Picture
        const anchor = document.createElement('a');
        anchor.href = `product-detail.html?id=${keys[i]}`;
        
        //Creating Divs
        const div_clothes = document.createElement('div');
        div_clothes.classList.add('clothes');
        
        //Creating Spans
        const span_hoverEffect = document.createElement('span');
        const price = document.createElement('span');
        const stock = document.createElement('span');

        //Price
        price.innerHTML = current_price;
        price.classList.add("priceTag");
        //Check Out
        span_hoverEffect.innerHTML = "Check Out";
        span_hoverEffect.classList.add('hover-effect');
        //Stock
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

        //Adding elements to body
        To_Inject.append(div_clothes);

        div_clothes.append(anchor);

        div_clothes.append(price);

        div_clothes.append(stock);

        anchor.append(span_hoverEffect);

        anchor.append(img_create);

        }
        catch(error){
            console.warn(`Product at index ${i} not found`, error);
            break;
        }
    }
    usedKeys = keys;
    buildPageNumbers(usedKeys);
}
function checkActivePage(){
    let pages = document.querySelectorAll('.numbers div');
    const urlParams = new URLSearchParams(window.location.search);
    const num = urlParams.get('page');
    pages.forEach(div =>{
        div.classList.remove('active');
    })
    for(let i = 0; i < pages.length; i++){
        if(num === pages[i].innerHTML){
            pages[i].classList.add('active');
            return;
        }
    }
}
function buildPageNumbers(keys = keysProducts){
    let totalNumbers = Math.ceil(keys.length/itemsPerPage);
    injectNumbers.innerHTML = "";
    for(let i = 0; i < totalNumbers; i++){
        const PageNumber = document.createElement('div');
        PageNumber.innerHTML = i + 1;
            PageNumber.setAttribute("onclick" , `replace(this, usedKeys)`);
            injectNumbers.append(PageNumber);
    }
    checkActivePage();
}
function buildOptions(){
    const select = document.getElementById('filter');
    const Choices = ['Default', 'Price Acsending', 'Price Descending', 'Price Range'];
    for(let i = 0; i < Choices.length; i++){
        const add = document.createElement('option');
        add.innerHTML = Choices[i];
        add.setAttribute('value', `${add.innerHTML}`.toLowerCase());
        select.append(add);
    }
}
function sort(order){
    if(order.toLowerCase() == 'default'){
        window.history.pushState({}, "", '?page=1');
        build(keysProducts);
        return;
    }
    else if(order.toLowerCase() == 'price acsending'){
        priceAcs(usedKeys);
    }
    else if(order.toLowerCase() == 'price descending'){
        priceDec(usedKeys);
    }
}
function search(searchvalue , page = 1){
    let filteredKeys = [];
    input.value = searchvalue;
    if(searchvalue === ""){
        select.options[0].removeAttribute('selected');
        select.options[0].setAttribute('selected' , true);
        window.history.pushState({} , '' , '?page=1');
        build();
    }
    else if(searchvalue.length < 3){
        showToast("Please Enter Atleast 3 Characters!");
    }
    else{
        let updatedUrl = new URL(window.location.href);
        let currentUrl = new URLSearchParams(window.location.search);
        for(let i = 0; i < keysProducts.length; i++){
            if((products[keysProducts[i]].index).includes(searchvalue.toLowerCase())){
                filteredKeys.push(keysProducts[i]);
            }
        }
        if(filteredKeys == ""){
            select.options[0].removeAttribute('selected');
            select.options[0].setAttribute('selected' , true);
            To_Inject.innerHTML = "No Products Found!";
            injectNumbers.innerHTML = "";
        }
        else{
            select.options[0].removeAttribute('selected');
            select.options[0].setAttribute('selected' , true);
            if(currentUrl.has('filter')){
                let filter = currentUrl.get('filter');
                updatedUrl.searchParams.set('filter' , filter);
                if(filter == 'acs'){
                    filteredKeys = [...filteredKeys].sort((a,b) => products[a].price - products[b].price);
                    select.options[1].setAttribute('selected' , true);
                }
                else{
                    filteredKeys = [...filteredKeys].sort((a,b) => products[b].price - products[a].price);
                    select.options[2].setAttribute('selected' , true);
                }
            }
            updatedUrl.searchParams.set('page', page);
            updatedUrl.searchParams.set('search', searchvalue);
            window.history.pushState({}, '', updatedUrl);
            build(filteredKeys , page);
        }
    }
}
function showToast(message, duration = 2000) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    
    // Style it
    toast.style.cssText = `
        position: fixed;
        bottom: 50%;
        left: 50%;
        transform: translateX(-50%);
        background: #333;
        color: white;
        padding: 12px 24px;
        border-radius: 30px;
        font-size: 14px;
        z-index: 9999;
        animation: fadeInOut 0.3s;
    `;
    
    // Add to page
    document.body.appendChild(toast);
    
    // Remove after duration
    setTimeout(() => {
        toast.remove();
    }, duration);
}
function priceAcs(usedKeys, page = 1){
    const url = new URL(window.location.href);
    url.searchParams.set('filter' , 'acs');
    url.searchParams.set('page' , page);
    window.history.pushState({} , '' , url);
    usedKeys = [...usedKeys].sort((a,b) => products[a].price - products[b].price);
    build(usedKeys , page);
}
function priceDec(usedKeys , page = 1){
    const url = new URL(window.location.href);
    url.searchParams.set('filter' , 'dec');
    url.searchParams.set('page' , page);
    window.history.pushState({} , '' , url);
    usedKeys = [...usedKeys].sort((a,b) => products[b].price - products[a].price);
    build(usedKeys , page);
}
function searchBtn(searchValue){
    filter()
    if(searchValue.length > 3){
        window.history.pushState({} , '' , `?page=1&search=${searchValue}`);
    }
    search(searchValue);
}
function filter(){
    if(range.innerHTML.includes("select")){
        return;
    }
    //play animation
    range.style.animation = 'filterFade 0.3s 1 linear';
    //then add elements
    setTimeout(()=>{
        range.innerHTML = "<div class='filterContainer' id = 'filterContainer'>" +
                     "<label for='filter' class='filterLabel'>Filter By:</label>" +
                     "<select name='filter' id='filter'></select>" +
                    "<button onclick='sort(select.value)'>Apply</button>" +
                    "</div>"
        buildOptions();
        //remove animation to use it again
        range.style.animation = '';
        //add event lsitener again
        const select = document.getElementById('filter');
        select.value = 'default';
        select.addEventListener('change', priceRange)
    }, 300)
}
//Price Range implementing
function priceRange(){
    select = document.getElementById('filter');
    if(select.value == 'price range'){
        range.style.animation = 'filterFade 0.3s 1 linear';

        setTimeout(()=>{
            range.innerHTML = '';
             //creating range
            const min = document.createElement('input');

            const max = document.createElement('input');

            const button = document.createElement('button');

            const filter = document.createElement('button');

            const buttonJoiner = document.createElement('div');

            buttonJoiner.classList.add('buttonJoiner');

            //sytle min
            min.setAttribute('placeholder', 'Minimum Price ($)');

            min.setAttribute('name', 'minimum');

            min.setAttribute('id', 'minimum');

            min.classList.add("minRange");

            //style max
            max.setAttribute('placeholder', 'Maximum Price ($)');

            max.setAttribute('name', 'maximum');

            max.setAttribute('id', 'maximum');

            max.classList.add("maxRange");
            //style button
            button.innerHTML = "Apply";

            button.setAttribute('onclick' , 'searchByRange()')

            filter.innerHTML = 'Default';

            filter.setAttribute('onclick', 'filter()');
            //Adding
            range.append(min);

            range.append(max);

            buttonJoiner.append(button);

            buttonJoiner.append(filter);

            range.append(buttonJoiner);
            // range.append(button);

            // range.append(filter);

            range.style.animation = "";

            select.value = 'default';

        }, 300)
    }
}
function searchByRange(){

    //looking if there is a search value
    let currentUrl = new URLSearchParams(window.location.search);
    //updating url
    let updatedUrl = new URL(window.location.href);
    //setting page to 1
    updatedUrl.searchParams.set('page', 1);

    if(currentUrl.has('search')){
        let search = currentUrl.get('search');

        updatedUrl.searchParams.set('search', search);
    }

    //Range Data
    let min = Number(document.getElementById('minimum').value);

    let max =Number(document.getElementById('maximum').value);


    console.log(min, max);

    if(min == "" || max == ""){
        showToast("All Fileds must be Filled!");
    }
    else if(isNaN(parseFloat(min)) || isNaN(parseFloat(max))){
        showToast("Please Enter Numbers!");
    }
    else if(min > max){
        showToast("Minimum Price must Be lower or equal to Maximum Price!", 3000);
        console.log(min, max, "After");
    }
    else{
        window.history.pushState({}, '', updatedUrl);
        let rangedKeys = [];
        for(let i = 0; i < usedKeys.length; i++){
            if(products[usedKeys[i]].price >= min && products[usedKeys[i]].price <= max ){
                rangedKeys.push(usedKeys[i]);
            }
        }
        if(rangedKeys == ''){
            To_Inject.innerHTML = "No Products Found!";
            injectNumbers.innerHTML = "";
        }
        else{
            rangedKeys = [...rangedKeys].sort((a,b) => products[a].price - products[b].price);
            build(rangedKeys);
        }
    }
}
