// frontend/assets/js/auth.js
// EN: Handles user login and registration interactions with API.
// FA: مدیریت تعاملات ورود و ثبت‌نام کاربر با API.

// EN: Helper function to send POST requests
// FA: تابع کمکی برای ارسال درخواست‌های POST
async function postData(url = '', data = {}) {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    });
    return response.json();
}

// EN: Login form handler
// FA: مدیریت فرم ورود
const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;
        const result = await postData('../backend/routes/api.php/auth/login', { email, password });
        if (result.status === 'ok') {
            alert('Login successful!');
            // Save token in localStorage (optional)
            localStorage.setItem('token', result.token);
            window.location.href = 'feed.html';
        } else {
            alert('Error: ' + result.message);
        }
    });
}

// EN: Register form handler
// FA: مدیریت فرم ثبت‌نام
const registerForm = document.getElementById('registerForm');
if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const username = document.getElementById('registerUsername').value;
        const email = document.getElementById('registerEmail').value;
        const password = document.getElementById('registerPassword').value;
        const result = await postData('../backend/routes/api.php/auth/register', { username, email, password });
        if (result.status === 'ok') {
            alert('Registration successful!');
            window.location.href = 'login.html';
        } else {
            alert('Error: ' + result.message);
        }
    });
}