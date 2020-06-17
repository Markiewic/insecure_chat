window.onload = () => {
    const attachButton = document.querySelector('#form-attach-button');
    const charForm = document.querySelector('#message-form-instance');

    charForm.addEventListener('submit', (event) => alert(JSON.stringify(event.target.prototype)));
}