/**
 * Load Bootstrap 5 frontend toolkit
 */
// Import Bootstrap Icons font
import 'bootstrap-icons/font/bootstrap-icons.scss';

// Import Bootstrap styles
import '../scss/bootstrap.scss';

// Import Popper JS
import * as Popper from '@popperjs/core';
window.Popper = Popper;

// Import all of Bootstrap’s JS
import * as bootstrap  from 'bootstrap';
window.bootstrap  = bootstrap;


/**
 * Add extra functions below
 */
// Example starter JavaScript for disabling form submissions if there are invalid fields
(() => {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)

        const inputs =  form.querySelectorAll('input.is-invalid')

        Array.from(inputs).forEach(input => {
            input.addEventListener('input', () => {
                input.classList.remove('is-invalid')
                form.classList.add('was-validated')
            }, false)
        })
    })
})()
