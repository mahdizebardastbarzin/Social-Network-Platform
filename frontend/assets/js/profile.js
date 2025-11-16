// frontend/assets/js/profile.js
// EN: Handles user profile fetching and display.
// FA: مدیریت دریافت و نمایش پروفایل کاربر.

async function fetchProfile() {
    const token = localStorage.getItem('token');
    const profileContainer = document.getElementById('profileContainer');
    if (!profileContainer) return;

    try {
        const res = await fetch('../backend/routes/api.php/profile', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });
        const data = await res.json();
        if (data.status === 'ok') {
            profileContainer.innerHTML = `
                <img src='${data.user.profile_image || 'assets/img/default-profile.png'}' alt='Profile Image' class='profile-img'>
                <h2>${data.user.username}</h2>
                <p>${data.user.bio || ''}</p>
            `;
        } else {
            profileContainer.innerText = 'Error: ' + data.message;
        }
    } catch (e) {
        profileContainer.innerText = 'Request failed: ' + e.message;
    }
}

window.addEventListener('DOMContentLoaded', fetchProfile);