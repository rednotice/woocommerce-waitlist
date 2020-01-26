class WaitlistForm {
    constructor() {
        this.form = document.getElementById('wpbits-waitlist-form');
        this.container = document.getElementById('wpbits-waitlist-container');

        this.form.addEventListener('submit', this.subscriptionHandler.bind(this));
    }

    async subscriptionHandler() {
        event.preventDefault();
        this.resetValidationMessages();

        this.email = this.container.querySelector('[name="email"]').value;
        this.setVariationId();
        this.setConfirmationCheckbox();

        if(!this.email || !this.validateEmail(this.email)) {
            this.displayValidationMessage('[data-error="invalidEmail"]');
            return false;
        }

        if(this.confirmationCheckbox && !this.confirmationCheckbox.checked) {
            this.displayValidationMessage('[data-error="invalidConfirmation"]');
            return false;
        }

        const formData = new FormData(this.form);

        if(this.variationId) {
            formData.append('variationId', this.variationId);
        }

        const params = new URLSearchParams(formData);
        const url = this.form.dataset.url;

        this.displayValidationMessage('.js-form-submission');

        try {
            const res = await fetch(url, {
                method: 'POST',
                body: params
            });

            const result = await res.json();
            
            this.resetValidationMessages();

            if (result.status === 'alreadySubscribed') {
                this.displayValidationMessage('.js-already-subscribed-error');
                return false;
            }

            if (result === 0 || result.status === 'error') {
                this.displayValidationMessage('.js-form-error');
                return false;
            }

            this.displayValidationMessage('.js-form-success');
            this.resetForm();
            return true;
        } catch (error) {
            resetValidationMessages();
            this.displayValidationMessage('.js-form-error');
            return false;
        }
    }

    setVariationId() {
        if(this.container.querySelector('[name="variation_id"]')) {
            this.variationId = this.container.querySelector('[name="variation_id"]').value;
        }
    }

    setConfirmationCheckbox() {
        if(this.container.querySelector('[name="confirmation"]')) {
            this.confirmationCheckbox = this.container.querySelector('[name="confirmation"]');
        }
    }

    validateEmail(email) {
        const regEx = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return regEx.test(String(email).toLowerCase());
    }

    displayValidationMessage(querySelector) {
        this.container.querySelector(querySelector).classList.add('show');
    }

    resetValidationMessages() {
        document.querySelectorAll('.field-msg').forEach(field => field.classList.remove('show'));
    }

    resetForm() {
        this.container.querySelector('[name="email"]').value = '';
        if(this.confirmationCheckbox) {
            this.container.querySelector('[name="confirmation"]').checked = false;
        }
    }
}

document.addEventListener('DOMContentLoaded', (event) => {
    const waitlistForm = new WaitlistForm();
})