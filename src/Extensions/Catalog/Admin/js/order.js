function mergeOrder() {
    const d = [];
    document.querySelectorAll('.table-data-select .form-control__checkbox:checked').forEach(function (e) {
        d.push(e.value);
    });

    if (!d.length) {
        return;
    }

    window.location.href = `?action=merge&ids=${d.join(',')}`;
}

function exportOrder(t) {
    const d = [];
    document.querySelectorAll('.table-data-select .form-control__checkbox:checked').forEach(function (e) {
        d.push(e.value);
    });

    if (!d.length) {
        return;
    }

    window.location.href = `?action=downloadDoc&tpl=${t}&ids=${d.join(',')}`;
}

window.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('input[value="Отменен"]').forEach(function (e) {
        e.parentElement.querySelector('.form-control__dropdown-current').style.color = '#ff4949';
    });

    document.querySelectorAll('div[data-value="canceled"]').forEach(function (e) {
        e.style.color = '#ff4949';
    });

    document.querySelectorAll('input[value="Завершен"]').forEach(function (e) {
        e.parentElement.querySelector('.form-control__dropdown-current').style.color = '#2fbe0f';
    });

    document.querySelectorAll('div[data-value="finished"]').forEach(function (e) {
        e.style.color = '#2fbe0f';
    });
});