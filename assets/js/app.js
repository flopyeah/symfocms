console.log('Hello Webpack assets/js/app.js');

import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {

    burgerMenu();

    deleteMessage();

    loginCaroussel();
});


const leftMenu = () => {

    // Get all "navbar-burger" elements
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    // Check if there are any navbar burgers
    if ($navbarBurgers.length > 0) {

        // Add a click event on each of them
        $navbarBurgers.forEach( el => {
            el.addEventListener('click', () => {

                // Get the target from the "data-target" attribute
                const target = el.dataset.target;
                const $target = document.getElementById(target);

                // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
                el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        });
    }
}

const burgerMenu = () => {

    // Get all "navbar-burger" elements
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    // Check if there are any navbar burgers
    if ($navbarBurgers.length > 0) {

        // Add a click event on each of them
        $navbarBurgers.forEach( el => {
            el.addEventListener('click', () => {

                // Get the target from the "data-target" attribute
                const target = el.dataset.target;
                const $target = document.getElementById(target);

                // Toggle the "is-active" class on both the "navbar-burger" and the "navbar-menu"
                el.classList.toggle('is-active');
                $target.classList.toggle('is-active');
            });
        });
    }
}

export const handleSubmit = (formName) => {

    //const   formName        = 'registration_form',
      const      registerForm    = document.forms.namedItem(formName);

    if ( registerForm !== null ) {

        registerForm.addEventListener('submit',  (event) => {

            const formHtml = event.target,
                formData = new FormData(formHtml),
                url = formHtml.action;

            // loader submit Button
            const   submitButton = registerForm.querySelector('button.submit');
                    submitButton.classList.add('is-loading');

            const formPost =  axios.post(url, formData );

            formPost.then((response) => {

                // Traitement des erreurs
                displayErrors(response, formName);

                if (response.data.message) {
                    toastMessage(response.data.message);
                }

                if (response.data.action) {

                    formHtml.action = response.data.action;
                    //window.history.pushState("object or string", "Title", response.data.action);
                    window.history.replaceState("object or string", "Title", response.data.action);
                }

                // not loading anymore
                submitButton.classList.remove('is-loading')

            }).catch((error) => {
                console.log(error.response)
            });

            event.preventDefault();
        });
    }
};

export const displayErrors = (response, formName) => {

    const registerForm    = document.forms.namedItem(formName);

    // remove errors panel
    const errorsHtml = registerForm.querySelectorAll('.error-panel')

    if (errorsHtml !== null) {
        errorsHtml.forEach(el => el.remove());
    }

    //  remove errors input
    const errorsInput = registerForm.querySelectorAll('.is-invalid')

    if (errorsInput !== null) {
        errorsInput.forEach(el => el.classList.remove('is-invalid'));
    }

    // affichage des error
    if (response.data.error) {
        const errorList = response.data.error;

        console.log(response.data.error, formName)

        errorList.map((err) => {
            let el = document.getElementById(formName + '_' + err.field);

            if (el !== null) {
                //el.className += ' is-danger';
                el.classList.add('is-invalid');
                el.insertAdjacentHTML('afterend', '<div class="invalid-feedback error-panel">' + err.message + '</div>');
            }
        })
    }
}

export const toastMessage = (message, type = 'success') => {

    const MessageHtml = '<div class="alert alert-'+ type +' alert-absolute" role="alert">' +
                             message +
                            '<button class="close delete"><span>&times;</span></button>'+
                        '</div>';

    document.body.insertAdjacentHTML('beforeend', MessageHtml);

    // TODO suppression du toast apres 5 sec
    //setTimeout()


    deleteMessage();
}

export const deleteMessage = () => {
    // Get all "delete button" elements
    const deleteAction = Array.prototype.slice.call(document.querySelectorAll('.delete'), 0);

    // Check if there are any delete button
    if (deleteAction.length > 0) {

        // Add a click event on each of them
        deleteAction.forEach( el => {
            el.addEventListener('click', () => {
                let parent = el.parentNode;
                parent.classList.add('d-none');
            });
        });
    }
}


export const loginCaroussel = () => {
    // Get all "delete button" elements
    const deleteAction = Array.prototype.slice.call(document.querySelectorAll('.login-click'), 0);

    // Check if there are any delete button
    if (deleteAction.length > 0) {

        // Add a click event on each of them
        deleteAction.forEach( el => {
            el.addEventListener('click', () => {

                const target = el.dataset.form;


                let parent = document.querySelectorAll('.box-frame');

                if (parent.length > 0) {
                    // Add a click event on each of them
                    parent.forEach( el => {
                        el.classList.remove('active');
                    });
                }

                let elTarget = document.querySelector('.'+target);
                elTarget.classList.add('active')
                //let parent = target.parentNode;
                //parent.classList.remove('active');

                return false;
            });
        });
    }
}


