//Grabbing Elements
const main_img = document.getElementById('main_image');

const gallery = document.getElementById('gallery');

const gallery_show = document.getElementById('showGallery');

const main_show = document.getElementById('mainShow');

const img = document.createElement('img');

const div = document.createElement('div');

main_img.addEventListener('input', () =>{
    let file = main_img.files[0];

    let url = URL.createObjectURL(file);

    img.src = url;

    img.style.width = '175px';

    img.style.height = '148px';

    main_show.append(div);

    div.append(img);

});

gallery.addEventListener('input', () =>{

    gallery_show.innerHTML = "";

    for(let i = 0; i < gallery.files.length; i++){


        let file = gallery.files[i];

        let url = URL.createObjectURL(file);

        let div = document.createElement('div');

        let img = document.createElement('img');

        img.src = url;

        img.style.width = "100%";

        img.style.height = '100px';



        gallery_show.append(div);

        div.append(img);

    }
})
