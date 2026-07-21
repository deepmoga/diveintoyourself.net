document.addEventListener('DOMContentLoaded', function() {

    // Auto-dismiss flash messages
    document.querySelectorAll('.alert').forEach(function(alert) {
        setTimeout(function() {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(function() { alert.remove(); }, 300);
        }, 5000);
    });

    // Alert close buttons
    document.querySelectorAll('.alert-close').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var alert = this.closest('.alert');
            alert.style.opacity = '0';
            setTimeout(function() { alert.remove(); }, 300);
        });
    });

    // Confirm delete
    document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
                e.preventDefault();
            }
        });
    });

    // Image preview on file input change
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function(input) {
        input.addEventListener('change', function() {
            var previewId = this.getAttribute('data-preview');
            var preview = document.getElementById(previewId);
            if (preview && this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // Sidebar toggle for mobile
    var toggleBtn = document.getElementById('sidebarToggle');
    var sidebar = document.getElementById('sidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('open');
        });
        document.addEventListener('click', function(e) {
            if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && e.target !== toggleBtn && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var tabGroup = this.closest('.tabs');
            var container = tabGroup.parentElement;
            var target = this.getAttribute('data-tab');

            tabGroup.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); });
            this.classList.add('active');

            container.querySelectorAll('.tab-content').forEach(function(c) { c.classList.remove('active'); });
            var targetEl = document.getElementById(target);
            if (targetEl) targetEl.classList.add('active');
        });
    });

    // Icon preview
    document.querySelectorAll('.icon-input').forEach(function(input) {
        var previewEl = input.closest('.form-group').querySelector('.icon-preview i');
        if (previewEl) {
            function updateIcon() {
                previewEl.className = input.value || 'fas fa-question';
            }
            input.addEventListener('input', updateIcon);
            updateIcon();
        }
    });

    // TinyMCE save before form submit
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function() {
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
        });
    });
});
