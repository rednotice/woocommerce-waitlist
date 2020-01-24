if(document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function(event) {
        generateButton();
        buttonHandler();
    })
}

function generateButton() {
    const heading = document.querySelector('.wp-heading-inline');
    const exportButton = document.createElement('a');
    heading.insertAdjacentElement('afterend', exportButton);
    exportButton.classList.add('page-title-action');
    exportButton.id = 'woobits-export';
    exportButton.innerHTML = 'Export';
}

function buttonHandler() {
    const exportButton = document.getElementById('woobits-export');
    const url = '/wp-admin/admin-ajax.php?action=woobits_export';

    exportButton.addEventListener('click', async function(event) {
        const res = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'
            }
        });

        const blob = await res.blob();
        const downloadUrl = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.style.display = 'none';
        a.href = downloadUrl;
        a.download = 'waitlist.csv';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(downloadUrl);
    })
}