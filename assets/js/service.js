
[...document.getElementsByClassName('price-card-btn')].forEach((item)=>{
    let button = item.firstElementChild;
    button.addEventListener('click', (e)=>{
        e.preventDefault()
        openDetails(e.target)
    });
});

function openDetails(target) {
    // let details = target.parentElement.parentElement.parentElement.querySelector('div');
    let details = target.parentElement.parentElement.parentElement.getElementsByClassName('price-card-details');
    if (details[0].classList.contains('is-closed')) {
        details[0].classList.replace('is-closed', 'is-opened');
        target.textContent = target.dataset.less;
        details[0].parentElement.setAttribute('style','max-height:calc(420px + 190px);');
    }
    else if (details[0].classList.contains('is-opened')) {
        details[0].classList.replace('is-opened', 'is-closed');
        target.textContent = target.dataset.more;
        details[0].parentElement.setAttribute('style','max-height:420px;');
    }
}
