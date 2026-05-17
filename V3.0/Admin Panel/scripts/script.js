const mainImage = document.getElementById('main_image');
const preview = document.getElementById('previewMain');
const img = document.createElement('img');
const div = document.createElement('div');
const gallery = document.getElementById('images-upload');
const showGallery = document.getElementById('showGallery');
const mainShow = document.getElementById('mainShow');
let arr = gallery.files;

mainImage.addEventListener('input', () => {
    const file = mainImage.files[0];
    if (file) {
        // Create a temporary URL for the local file
        img.src = URL.createObjectURL(file);
        img.style.width = '200px';
        img.style.height = '145px';
        mainShow.append(div);
        div.append(img);
    }
});
gallery.addEventListener('input', () =>{
    showGallery.innerHTML = '';
    for(let i = 0; i < gallery.files.length; i++){
        let div = document.createElement('div');
        let img = document.createElement('img');
        img.src = URL.createObjectURL(gallery.files[i]);
        showGallery.append(div);
        div.append(img);
    }
})