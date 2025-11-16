// frontend/assets/js/admin.js
// EN: Handles admin dashboard actions (user management, content moderation, analytics).
// FA: مدیریت عملکردهای داشبورد ادمین (مدیریت کاربران، بررسی محتوا، تحلیل‌ها).

async function fetchUsers() {
    const token = localStorage.getItem('token');
    const usersContainer = document.getElementById('usersContainer');
    if (!usersContainer) return;

    try {
        const res = await fetch('../backend/routes/api.php/admin/users', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });
        const data = await res.json();
        if (data.status === 'ok') {
            usersContainer.innerHTML = '';
            data.users.forEach(user => {
                const userEl = document.createElement('div');
                userEl.className = 'admin-user-item';
                userEl.innerHTML = `<strong>${user.username}</strong> - ${user.email}`;
                usersContainer.appendChild(userEl);
            });
        } else {
            usersContainer.innerText = 'Error: ' + data.message;
        }
    } catch (e) {
        usersContainer.innerText = 'Request failed: ' + e.message;
    }
}

window.addEventListener('DOMContentLoaded', fetchUsers);