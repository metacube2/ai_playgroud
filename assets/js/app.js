const cookieBanner = document.querySelector('.cookie-banner');
const cookieAccept = document.querySelector('[data-cookie-accept]');

if (cookieBanner && cookieAccept) {
    const consent = localStorage.getItem('gyb-cookie');
    if (!consent) {
        cookieBanner.classList.add('active');
    }
    cookieAccept.addEventListener('click', () => {
        localStorage.setItem('gyb-cookie', 'accepted');
        cookieBanner.classList.remove('active');
    });
}

const filterForm = document.querySelector('[data-filter-form]');
if (filterForm) {
    filterForm.addEventListener('input', () => {
        filterForm.submit();
    });
}
