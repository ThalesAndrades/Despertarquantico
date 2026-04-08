/**
 * Community Interactions
 */
document.addEventListener('DOMContentLoaded', function () {
    // Auto-expand textarea
    document.querySelectorAll('textarea').forEach(function (textarea) {
        textarea.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });

    // Character count for topic title
    var titleInput = document.getElementById('title');
    if (titleInput && titleInput.maxLength > 0) {
        var hint = document.createElement('span');
        hint.className = 'text-xs text-muted';
        hint.style.float = 'right';
        titleInput.parentNode.appendChild(hint);
        function updateCount() {
            hint.textContent = titleInput.value.length + '/' + titleInput.maxLength;
        }
        titleInput.addEventListener('input', updateCount);
        updateCount();
    }
});
