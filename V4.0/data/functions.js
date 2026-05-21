window.addEventListener("products-loaded", ()=>{
     Choices = ['Default', 'Price Acsending', 'Price Descending'];
    select = document.getElementById('filter');
     itemsPerPage = 3;
     injectNumbers = document.getElementById('totalPages');
    console.log("This should be first im functoins and productst are", products);
   window.replace =  function (Number , keys = keysProducts) {
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
                let current_img = products[keys[i]].img;
                let current_price =  products[keys[i]].price + "$";
                
                const img_create = document.createElement('img');
                img_create.alt = 'Failed To load';
                img_create.src = current_img;
                img_create.classList.add("product-image");
                
                const anchor = document.createElement('a');
                anchor.href = `product-detail.html?id=${keys[i]}`;
                
                const div_clothes = document.createElement('div');
                div_clothes.classList.add('clothes');
                
                const span_hoverEffect = document.createElement('span');
                const span = document.createElement('span');
                span.classList.add("priceTag");
                span.innerHTML = current_price;
                span_hoverEffect.innerHTML = "Check Out";
                span_hoverEffect.classList.add('hover-effect');
                
                container.append(div_clothes);
                div_clothes.append(anchor);
                div_clothes.append(span);
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
    window.build = function(keys = keysProducts, currentPage = 1){
        To_Inject.innerHTML = "";
        for (let i = itemsPerPage*(currentPage - 1); i < Math.min(itemsPerPage + itemsPerPage*(currentPage - 1),keys.length); i++) {
            try{
            let current_img = products[keys[i]].img;
            let current_price = products[keys[i]].price + "$";
            
            const img_create = document.createElement('img');
            img_create.alt = 'Failed To load';
            img_create.src = current_img;
            img_create.classList.add("product-image");
            
            const anchor = document.createElement('a');
            anchor.href = `product-detail.html?id=${keys[i]}`;
            
            const div_clothes = document.createElement('div');
            div_clothes.classList.add('clothes');
            
            const span_hoverEffect = document.createElement('span');
            const span = document.createElement('span');
            span.innerHTML = current_price;
            span.classList.add("priceTag");
            span_hoverEffect.innerHTML = "Check Out";
            span_hoverEffect.classList.add('hover-effect');
            
            To_Inject.append(div_clothes);
            div_clothes.append(anchor);
            div_clothes.append(span);
            anchor.append(span_hoverEffect);
            anchor.append(img_create);
            }
            catch(error){
                console.warn(`Product at search_keyword ${i} not found`, error);
                break;
            }
        }
        usedKeys = keys;
        buildPageNumbers(usedKeys);
    }
     window.checkActivePage = function(){
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
    window.buildPageNumbers =  function (keys = keysProducts){
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
   window.buildOptions =  function (){
        for(let i = 0; i < Choices.length; i++){
            const add = document.createElement('option');
            add.innerHTML = Choices[i];
            add.setAttribute('value', `${add.innerHTML}`.toLowerCase());
            select.append(add);
        }
    }
    window.sort =  function(order){
        console.log(order.toLowerCase());
        if(order.toLowerCase() == 'default'){
            window.history.pushState({}, "", '?page=1');
            build(keysProducts);
            return;
        }
        else if(order.toLowerCase() == 'price acsending'){
            priceAcs(usedKeys);
        }
        else{
            priceDec(usedKeys);
        }
    }
     window.search =  function (searchvalue , page = 1){
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
                if((products[keysProducts[i]].search_keyword).includes(searchvalue.toLowerCase())){
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
    window.showToast =  function (message, duration = 2000) {
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
            z-search_keyword: 9999;
            animation: fadeInOut 0.3s;
        `;
        
        // Add to page
        document.body.appendChild(toast);
        
        // Remove after duration
        setTimeout(() => {
            toast.remove();
        }, duration);
    }
    window.priceAcs =  function(usedKeys, page = 1){
        const url = new URL(window.location.href);
        url.searchParams.set('filter' , 'acs');
        url.searchParams.set('page' , page);
        window.history.pushState({} , '' , url);
        usedKeys = [...usedKeys].sort((a,b) => products[a].price - products[b].price);
        build(usedKeys , page);
    }
    window.priceDec =  function(usedKeys , page = 1){
        const url = new URL(window.location.href);
        url.searchParams.set('filter' , 'dec');
        url.searchParams.set('page' , page);
        window.history.pushState({} , '' , url);
        usedKeys = [...usedKeys].sort((a,b) => products[b].price - products[a].price);
        build(usedKeys , page);
    }
     window.searchBtn = function(searchValue){
        if(searchValue.length > 3){
            window.history.pushState({} , '' , `?page=1&search=${searchValue}`);
        }
        search(searchValue);
    }
    console.log("made fs event")
    window.dispatchEvent(new Event("fs"))
})

