window.onload = () => {
    const messagesBox = document.querySelector('#messages-box');
    const attachButton = document.querySelector('#form-attach-button');
    const detachButton = document.querySelector('#form-detach-button');
    const chatForm = document.querySelector('#message-form-instance');
    const messageFormBody = document.querySelector('#message-form-instance > [name=body]');
    const messageFormImageURL = document.querySelector('#message-form-instance > [name=image-url]');
    const inputFile = document.querySelector('#file-input');

    messageFormBody.addEventListener('keypress', event => {
        if (event.which === 13 && !event.shiftKey) {
            event.preventDefault();
           chatForm.dispatchEvent(new Event('submit'));
        }
    })

    attachButton.addEventListener('click', () => inputFile.click());
    detachButton.addEventListener('click', () => {
        messageFormImageURL.value = '';
        detachButton.hidden = true;
        attachButton.hidden = false;
    });

    inputFile.addEventListener('change', async event => {
        const file = event.target.files[0];
        event.target.value = '';
        if (file) {
            messageFormImageURL.value = await readBase64(file);
            detachButton.hidden = false;
            attachButton.hidden = true;
        }
    });

    chatForm.addEventListener('submit', (event) => {
        event.preventDefault();

        const body = messageFormBody.value;
        const imageURL = messageFormImageURL.value;
        detachButton.hidden = true;
        attachButton.hidden = false;

        sendMessage(body, imageURL);
        messageFormBody.value = '';
        messageFormImageURL.value = '';
    });

    const messagesStream = new MessagesStream();
    messagesStream.onMessage(messages => {
        const newElements = messages.map(generateMessageHTMLElement);
        newElements.forEach(newElement => messagesBox.append(newElement));
        scrollChatDown();
    });

    scrollChatDown(true, false);
}

function sendMessage(body, attachedImageUrl) {
    if (body || attachedImageUrl) {
        fetch('/api/sendMessage.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
            },
            body: urlEncodeObject({body, image_url: attachedImageUrl}),
        })
    }
}

function readBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = () => resolve(reader.result);
        reader.onerror = error => reject(error);
    })
}


function urlEncodeObject(object) {
    return Object.keys(object).map((key) => {
        return encodeURIComponent(key) + '=' + encodeURIComponent(object[key])
    }).join('&');
}

function scrollChatDown(fromTop = false, animation = true) {
    const chatMessagesBox = document.querySelector('#messages-box');
    if (animation) {
    if (fromTop || chatMessagesBox.scrollHeight - (chatMessagesBox.scrollTop + chatMessagesBox.offsetHeight) < 250) {
        chatMessagesBox.scroll({ behavior: 'smooth', top: chatMessagesBox.scrollHeight});
    }
    } else {
        chatMessagesBox.scrollTop = chatMessagesBox.scrollHeight;
    }

}

function MessagesStream() {
    const eventSource = new EventSource('/api/subscribeMessages.php', {withCredentials: true});
    this.onMessage = function (eventHandler) {
        eventSource.addEventListener('message', event => {
            try {
                eventHandler(JSON.parse(event.data));
            } catch (e) {
                console.error(e);
            }
        })
    };
    this.close = function () {
        eventSource.close();
    }
}

function generateMessageHTMLElement(message) {
    let dateFormat = new Date(message.createdOn).toLocaleString();
    const template = `
        <div class="meta">
            <div class="name">
                ${message.name}
            </div>
            <div class="time">
                ${dateFormat}
            </div>
        </div>
        <div class="body">
            <p>${message.body}</p>
        </div>
    ` + (message.attachedImage ? `
        <div class="attachment">
            <img src="${message.attachedImage}">
        </div>
    ` : '');
    const element = document.createElement('div');
    element.classList.add('message');
    element.innerHTML = template;
    return element;
}