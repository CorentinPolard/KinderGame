import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';


// function supprimer() {
//     const exampleModal = document.getElementById('deleteModal')
//     if (exampleModal) {
//         exampleModal.addEventListener('show.bs.modal', event => {
//             // Button that triggered the modal
//             const button = event.relatedTarget
//             // Extract info from data-bs-* attributes
//             const category = button.getAttribute('data-bs-id')

//             const supprimer = document.querySelector("#supprimer")

//             supprimer.setAttribute("href", `/admin/category/remove/${category}`)
//         })
//     }
// }

// const showModal = document.querySelectorAll(".showModal")

// for (let button of showModal) {
//     button.addEventListener('click', supprimer())
// }



console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
