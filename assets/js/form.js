document.addEventListener('DOMContentLoaded', (event) => {

    const waitlistForm = document.getElementById('woobits-waitlist-form');

    waitlistForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        resetMessages();

        const waitlistContainer = document.getElementById('woobits-waitlist-container');
        const email = waitlistContainer.querySelector('[name="email"]').value;
        if( !email || !validateEmail(email) ) {
            waitlistContainer.querySelector('[data-error="invalidEmail"]').classList.add('show');
            return;
        }

        if( waitlistContainer.querySelector('[name="confirmation"]') ) {
            const checkbox = waitlistContainer.querySelector('[name="confirmation"]');
            if( !checkbox.checked ) {
                waitlistContainer.querySelector('[data-error="invalidConfirmation"]').classList.add('show');
                return;
            }
        }

        const url = waitlistForm.dataset.url;
        const formData = new FormData(waitlistForm);

        if( document.querySelector('[name="variation_id"]') ) {
            const variationId = document.querySelector('[name="variation_id"]').value;
            formData.append('variationId', variationId);
        }

        const params = new URLSearchParams(formData);

        waitlistContainer.querySelector('.js-form-submission').classList.add('show');

        try {
            const res = await fetch(url, {
                method: 'POST',
                body: params
            });

            const result = await res.json();

            resetMessages();

            // Handle the response
            if (result.status === 'alreadySubscribed') {
                waitlistContainer.querySelector('.js-already-subscribed-error').classList.add('show');
                return null;
            }

            if (result === 0 || result.status === 'error') {
                waitlistContainer.querySelector('.js-form-error').classList.add('show');
                return null;
            }

            waitlistContainer.querySelector('.js-form-success').classList.add('show');
            waitlistContainer.querySelector('[name="email"]').value = '';
            waitlistContainer.querySelector('[name="confirmation"]').checked = false;
            
        } catch (error) {
            resetMessages();
            waitlistContainer.querySelector('.js-form-error').classList.add('show');
        }
    });
});

function resetMessages() {
    document.querySelectorAll('.field-msg').forEach(field => field.classList.remove('show'));
}

function validateEmail(email) {
    const regEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return regEx.test(String(email).toLowerCase());
}