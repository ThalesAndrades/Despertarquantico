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
});
