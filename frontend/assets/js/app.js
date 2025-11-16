// frontend/assets/js/app.js
// EN: JS that calls create_db.php and shows result. This file runs when index.html loads.
// FA: جاوااسکریپتی که create_db.php را فراخوانی می‌کند و نتیجه را نمایش می‌دهد.

(async function() {
    const statusEl = document.getElementById('status');
    try {
        // EN: The path is relative from /frontend/index.html to ../database/create_db.php
        // FA: مسیر نسبتاً از /frontend/index.html به ../database/create_db.php
        const res = await fetch('../database/create_db.php', { method: 'GET' });
        const data = await res.json();
        if (data.status === 'ok') {
            statusEl.innerText = 'دیتابیس با موفقیت ایجاد شد.'; // FA
        } else {
            statusEl.innerText = 'Error: ' + (data.message || 'Unknown');
        }
    } catch (e) {
        statusEl.innerText = 'Request failed: ' + e.message;
    }
})();