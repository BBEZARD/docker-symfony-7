const form = document.querySelector('form');
const input = document.getElementsByTagName('input');
const textarea = document.getElementsByTagName('textarea');
const btn =  document.getElementById('form-submit');
const recaptcha = document.getElementById('contact-Captcha');
const gRecaptcha = document.getElementsByClassName('g-recaptcha');
const messageClose = document.getElementById('HW-flash-close');

[...gRecaptcha].forEach((item) => {
    item.setAttribute('data-callback', 'recaptchaCallback')
    item.setAttribute('data-expired-callback', 'recaptchaCallbackExpired')
});

window.recaptchaCallback = recaptchaCallback;
window.recaptchaCallbackExpired = recaptchaCallback;
function recaptchaCallback(){
    let response = grecaptcha.getResponse();
    if (response.length === 0) {
        submitBtnStyle(false);
        return false;
    } else {
        submitBtnStyle(true);
        return true;
    }
}

function submitBtnStyle(bool=false) {
    if (bool) {
        btn.lastElementChild.classList.add('form-btn');
        btn.lastElementChild.classList.remove('form-btn-off');
    } else {
        btn.lastElementChild.classList.add('form-btn-off');
        btn.lastElementChild.classList.remove('form-btn');
    }
}

[...input].forEach((item) => {
    item.addEventListener('focusout', ()=>{
        ctrlInput(item)
    });
});

[...textarea].forEach((item) => {
    item.addEventListener('focusout', ()=>{
        ctrlInput(item)
    });
});

if (btn) {
    btn.onclick = function (e) {
        if (!recaptchaCallback()) {
            e.preventDefault();
        } else {
            input.forEach((item) => {
                ctrlInput(item, true)
            });
            textarea.forEach((item) => {
                ctrlInput(item, true);
            });
        }
    }
}

[...input].forEach((item)=>{
    if (item.type === 'checkbox') {
        let target = item.parentNode;
        if (item.checked){
            target.lastElementChild.classList.add('checked');
        } else {
            target.lastElementChild.classList.remove('checked');
        }
        item.addEventListener('click', ()=>{
            target.lastElementChild.classList.remove('missing');
            if (item.checked){
                target.lastElementChild.classList.add('checked');
            } else {
                target.lastElementChild.classList.remove('checked');
            }
        });
    }
});

function ctrlInput(item, active = false) {
    let target = item;
    if (item.type === 'checkbox') {
        target = item.parentNode.lastElementChild;
    }
    if (active) {
        if (item.validity.valueMissing||item.validity.typeMismatch) {
            target.classList.add('missing');
        }
    } else {
        target.classList.remove('missing');
    }
}

if (messageClose) {
    messageClose.addEventListener('click', ()=>{
        let message = document.getElementById('HW-flash');
        message.classList.add('is-closed');
        setTimeout(function (){
            message.remove()
        }, 300);
    });
}

if (document.getElementById('map-id')) {
    // Leaflet Map
    const latX = 47.2594;
    const latY = -2.24487;
    let myIcon = L.icon({
        iconUrl: 'build/images/logos/HW_gps.svg',
        iconSize: [24, 35],
        iconAnchor: [12, 35]
    });

    let myMap = L.map('map-id', {
        scrollWheelZoom: false,
        dragging: false
    }).setView([latX, latY], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.openstreetmap.fr/">OpenStreetMap</a>',
        maxZoom: 18
    }).addTo(myMap);

   L.marker([latX, latY], {
        icon: myIcon,
        title:'Hourraweb',
        keyboard: false,
        alt: 'Logo Hourraweb',
    }).addTo(myMap);
}
