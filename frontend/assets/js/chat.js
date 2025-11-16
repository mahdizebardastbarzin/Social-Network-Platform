// frontend/assets/js/chat.js
// EN: Handles private and group chat interface.
// FA: مدیریت رابط کاربری چت خصوصی و گروهی.

async function fetchMessages() {
    const token = localStorage.getItem('token');
    const chatContainer = document.getElementById('chatContainer');
    if (!chatContainer) return;

    try {
        const res = await fetch('../backend/routes/api.php/messages', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });
        const data = await res.json();
        if (data.status === 'ok') {
            chatContainer.innerHTML = '';
            data.messages.forEach(msg => {
                const msgEl = document.createElement('div');
                msgEl.className = msg.sender_id === data.current_user_id ? 'message-outgoing' : 'message-incoming';
                msgEl.innerText = msg.message;
                chatContainer.appendChild(msgEl);
            });
        } else {
            chatContainer.innerText = 'Error: ' + data.message;
        }
    } catch (e) {
        chatContainer.innerText = 'Request failed: ' + e.message;
    }
}

async function sendMessage(content) {
    const token = localStorage.getItem('token');
    if (!content) return;

    try {
        const res = await fetch('../backend/routes/api.php/messages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + token
            },
            body: JSON.stringify({ message: content })
        });
        const data = await res.json();
        if (data.status === 'ok') {
            fetchMessages();
        } else {
            alert('Error sending message: ' + data.message);
        }
    } catch (e) {
        alert('Request failed: ' + e.message);
    }
}

// EN: Fetch messages every 3 seconds for live chat
// FA: دریافت پیام‌ها هر 3 ثانیه برای چت زنده
setInterval(fetchMessages, 3000);

window.addEventListener('DOMContentLoaded', fetchMessages);