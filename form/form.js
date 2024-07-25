const burgerMenuButton = document.querySelector('.burger-menu-button');
const burgerMenuButtonIcon = document.querySelector('.burger-menu-button i');
const burgerMenu = document.querySelector('.burgeu-menu ');



burgerMenuButton.onclick =function ()
{
    burgerMenu.classList.toggle('open');
    const isOpen = burgerMenu.classList.contains('open');
    burgerMenuButtonIcon.classList= isOpen? 'fa-solid fa-xmark':'fa-solid fa-bars'
}

let btn = document.getElementById('btn');

window.onscroll = function () {
if (window.scrollY >= 100) {
    btn.style.display = 'block';
} else {
    btn.style.display = 'none';
}
}

btn.onclick = function () {
window.scroll({
    left: 0,
    top: 0,
    behavior: 'smooth',
});
}
/*******/
$(document).ready(function() {
    $('#filiere').select2({
        placeholder: "Sélectionner une filière",
        allowClear: true
    });
    $('#etudiant').select2({
        placeholder: "Sélectionner un étudiant",
        allowClear: true
    });
});

function submitForm() {
    // Modifier la valeur du champ caché pour indiquer que le bouton a été cliqué
    document.getElementById('insertData').value = '1';
    // Soumettre le formulaire
    document.forms[0].submit();
}

function filterMembers() {
let selectedValues = Array.from(document.querySelectorAll('select[name^="membre_"]')).map(select => select.value);
document.querySelectorAll('select[name^="membre_"] option').forEach(option => {
    if (option.value) {
        option.disabled = selectedValues.includes(option.value) && !option.selected;
        }
    });
}

document.querySelectorAll('select[name^="membre_"]').forEach(select => {
    select.addEventListener('change', filterMembers);
});

window.addEventListener('DOMContentLoaded', filterMembers); // Filtre initial au chargement de la page