const btnDelete = document.getElementsByClassName('table-delete');

[...btnDelete].forEach(btn => {
    btn.addEventListener('click', e => {
        const disableBodyScroll = bodyScrollLock.disableBodyScroll;
        const enableBodyScroll = bodyScrollLock.enableBodyScroll;
        const modal = document.getElementById('modal-1');
        const bg = modal.getElementsByClassName('modal-bg')[0];
        const btnCancel = modal.getElementsByClassName('btn-modal-cancel')[0];
        const btnDelete = modal.getElementsByClassName('btn-modal-delete')[0];
        e.preventDefault();
        btnDelete.href = btn.href;
        disableBodyScroll(modal);
        modal.classList.remove('hidden');

        bg.classList.remove('modal-bg-off');
        bg.classList.add('modal-bg-on');

        btnCancel.addEventListener('click', e => {
            bg.classList.remove('modal-bg-on');
            bg.classList.add('modal-bg-off');
            modal.classList.add('hidden');
        })

        btnDelete.addEventListener('click', e => {
            bg.classList.remove('modal-bg-on');
            bg.classList.add('modal-bg-off');
            modal.classList.add('hidden');
        })
    })
});
