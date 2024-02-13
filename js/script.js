let images = ["Images/image1", "Images/image2", "Images/image3", "Images/image4", "Images/image5", "Images/image6", "Images/image7", "Images/image8", "Images/image9"];
let currentImage = 0;

function changeImage() {
    let image = document.getElementById("plante");
    image.src = images[currentImage];
    currentImage++;
    if (currentImage == images.length) {
        currentImage = 0;
    }
}

setInterval(changeImage, 3000);