document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('wp-ai-form');
    const responseDiv = document.getElementById('ai-response');
    const promptInput = document.getElementById('ai-prompt');
    const querySelect = document.getElementById('ai-query-select');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const promptText = promptInput.value;
            const queryId = querySelect.value;
            
            if (!promptText) {
                return;
            }

            responseDiv.style.display = 'block';
            responseDiv.innerText = 'Nacitam...';

            const formData = new FormData();
            formData.append('action', 'wp_ai_ask');
            formData.append('prompt', promptText);
            formData.append('query_id', queryId);

            fetch(wpAiData.ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    responseDiv.innerText = res.data;
                } else {
                    responseDiv.innerText = 'Chyba: ' + res.data;
                }
            })
            .catch(error => {
                responseDiv.innerText = 'Chyba komunikace.';
            });
        });
    }
});