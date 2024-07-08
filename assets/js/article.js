const url = document.querySelector('[data-type="elfinder-input-field"]'+'[id="article_mainPicture"]');
const img = document.getElementById('article-img');

if(url) {
    url.addEventListener('focus', changeImg);

    window.addEventListener("load", function(event) {
        img.src = url.value;
    });
}


function changeImg() {
    img.src = url.value;
}
