// frontend/assets/js/feed.js
// EN: Handles fetching and displaying user feed from API.
// FA: مدیریت دریافت و نمایش فید کاربران از API.

async function fetchFeed() {
    const token = localStorage.getItem('token');
    const feedContainer = document.getElementById('feedContainer');
    if (!feedContainer) return;

    try {
        const res = await fetch('../backend/routes/api.php/feed', {
            method: 'GET',
            headers: {
                'Authorization': 'Bearer ' + token
            }
        });
        const data = await res.json();
        if (data.status === 'ok') {
            feedContainer.innerHTML = '';
            data.posts.forEach(post => {
                const postEl = document.createElement('div');
                postEl.className = 'post';
                postEl.innerHTML = `<strong>${post.username}</strong>: ${post.content}`;
                feedContainer.appendChild(postEl);
            });
        } else {
            feedContainer.innerText = 'Error: ' + data.message;
        }
    } catch (e) {
        feedContainer.innerText = 'Request failed: ' + e.message;
    }
}

// EN: Call fetchFeed on page load
// FA: فراخوانی fetchFeed هنگام بارگذاری صفحه
window.addEventListener('DOMContentLoaded', fetchFeed);