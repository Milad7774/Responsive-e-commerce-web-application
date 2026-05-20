// Global variables
let products = {};
let keysProducts = Object.keys(products);
let usedKeys = keysProducts;
let totalItems = keysProducts.length;
const select = document.getElementById('filter');
const Choices = ['Default', 'Price Acsending', 'Price Descending'];
const To_Inject = document.getElementById("To_Inject");
const injectNumbers = document.getElementById('totalPages');
const urlParams = new URLSearchParams(window.location.search);
let currentPage = urlParams.get('page');
let currentSearch = "";
const itemsPerPage = 3;
const inputFocus = document.querySelector('label');
const input = document.querySelector('input');

// Fetch products
fetch('../API/get-products.php')
    .then(response => response.json())
    .then(data => {
        products = data;
         keysProducts = Object.keys(products);
         usedKeys = keysProducts;
         totalItems = keysProducts.length;
        start()
    })
    .catch(error => console.error('Error loading products:', error));

function start() {

    //Building Select Tag Options
    buildOptions();

    //Checking URL(Filter and Page preserving)
    if(urlParams.has('filter') && urlParams.has('page')){
        let filterKind = urlParams.get('filter');
        if(filterKind == 'acs'){
            priceAcs(usedKeys, urlParams.get('page'));
            select.options[1].setAttribute('selected' , true);
        }
        else{
            priceDec(usedKeys, urlParams.get('page'));
            select.options[2].setAttribute('selected' , true);
        }
    }

    // Checking URL(Page needed)
    if(!urlParams.has('page')){
        window.history.pushState({}, '' , '?page=1');
        currentPage = '1';
    }

    //Checking URL(Page and Search preserve With optional filtering)
    if(urlParams.has('search')){
        let savedSearch = urlParams.get('search');
        let page = urlParams.get('page');
        search(savedSearch, page);
        if(window.innerWidth > 515){
            inputFocus.classList.add('focus');
        }
        else{
            inputFocus.style.opacity = '0';
        }
    }

    //Checking URL(Page Preserve)
    else{
        // Make sure currentPage is valid
        const page = currentPage ? parseInt(currentPage) : 1;
        build(usedKeys, page);
    }

    //Handling the Search Bar
    input.addEventListener('focusin', () => {
        if(window.innerWidth > 515){
            inputFocus.classList.add('focus');
        }
        else{
            inputFocus.style.opacity = '0';
        }
      });
      input.addEventListener('blur', () => {
        if(window.innerWidth > 515){
            if(input.value !== ""){
                return;
            }
            inputFocus.classList.remove('focus');
        }
        else{
            if(input.value !== ""){
                return;
            }
            inputFocus.style.opacity = '1';
        }
      });
      input.addEventListener('keydown', (event) => {
        if (event.key === "Enter") {
            event.preventDefault();
            if (input.value.trim() !== '') {
                searchBtn(input.value);
            }
        }
    });
    
}




