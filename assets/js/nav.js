let played;
const btn = document.getElementById('return-to-top');

window.addEventListener('scroll', ()=> {
    let windowPosY = window.scrollY;
    navbarTransform(windowPosY);
    scrollToTop(windowPosY);

});

function scrollToTop(pos) {
    if (pos > 600 && !played) {
        btn.style.display = 'block';
        btn.classList.add('show')
        played = true;
    } else if (pos < 600 && played){
        btn.style.display = 'none';
        played = false;
    }
}

btn.addEventListener('click',()=>{
    window.scrollTo(0,0);
})

function navbarTransform(pos) {
    let header = document.querySelector('header');
    // let headerHeight = document.querySelector('header div div').offsetHeight;
    let headerHeight = 10;
    let headerLogo = header.getElementsByClassName('header-logo')
    if (pos >= headerHeight) {
        headerLogo[0].classList.remove('header-logo-top')
        headerLogo[0].classList.add('header-logo-sticky')
        header.setAttribute('style', 'top:-'+headerHeight+'px');
    } else {
        headerLogo[0].classList.remove('header-logo-sticky')
        headerLogo[0].classList.add('header-logo-top')
    }
}

let menuIcon = document.getElementById('nav-menu-icon')
let menuList = menuIcon.parentElement.querySelector('ul');
let nav = document.querySelector('nav');

menuIcon.addEventListener('click', (e)=>{
    e.preventDefault();
    closeMenu();
});

let navListLi = document.querySelectorAll('.nav-list-small li');
navListLi.forEach((item)=>{
    item.addEventListener('click', (e)=>{
        closeMenu()
    });
});

function closeMenu(){
    menuIcon.classList.forEach((item)=>{
        if(item === 'is-opened'){
            menuIcon.classList.add('is-closed');
            menuIcon.classList.remove('is-opened');
            menuList.classList.add('is-closed');
            menuList.classList.remove('is-opened');
            setTimeout(function (){
                close();
            }, 300);
        } else if(item === 'is-closed'){
            document.querySelector('body').classList.add('overflow-hidden')
            document.getElementById('nav-vertical-list').style.right = '0';
            nav.classList.add('modal-on');
            nav.classList.remove('modal-off');
            menuIcon.classList.add('is-opened');
            menuIcon.classList.remove('is-closed');
            menuList.classList.add('is-opened');
            menuList.classList.remove('is-closed');
        }
    });
}

function close() {
    nav.classList.add('modal-off');
    nav.classList.remove('modal-on');
    document.getElementById('nav-vertical-list').style.right = '-200px';
    document.querySelector('body').classList.remove('overflow-hidden');
}


