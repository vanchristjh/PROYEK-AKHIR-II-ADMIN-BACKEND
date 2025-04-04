<div id="debug-helper" class="small text-muted mt-4 border-top pt-3" style="display: none;">
    <h6 class="fw-bold">Debug Information</h6>
    <div class="row">
        <div class="col-md-6">
            <ul>
                <li>Page loaded at: <span id="debug-time">{{ now() }}</span></li>
                <li>Form contains changes: <span id="debug-has-changes">No</span></li>
                <li>Original data: <pre id="debug-original-data"></pre></li>
            </ul>
        </div>
        <div class="col-md-6">
            <ul>
                <li>Session success: <span>{{ session('success') ?: 'None' }}</span></li>
                <li>Session errors: <span>{{ $errors->any() ? count($errors->all()) : 'None' }}</span></li>
                <li>Last submission: <span id="debug-last-submit">None</span></li>
            </ul>
        </div>
    </div>
    <button class="btn btn-sm btn-outline-secondary" id="debug-toggle-dev-mode">View Session Data</button>
    <div id="debug-session-data" class="mt-2" style="display: none;">
        <pre>{{ json_encode(session()->all(), JSON_PRETTY_PRINT) }}</pre>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show debug panel with keyboard shortcut (Ctrl+Shift+D)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'D') {
            const debugHelper = document.getElementById('debug-helper');
            if (debugHelper) {
                debugHelper.style.display = debugHelper.style.display === 'none' ? 'block' : 'none';
            }
        }
    });
    
    // Toggle session data view
    document.getElementById('debug-toggle-dev-mode')?.addEventListener('click', function() {
        const sessionData = document.getElementById('debug-session-data');
        if (sessionData) {
            sessionData.style.display = sessionData.style.display === 'none' ? 'block' : 'none';
        }
    });
    
    // Track form changes for debugging
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        // Store original values
        const originalData = {};
        form.querySelectorAll('input, select, textarea').forEach(field => {
            if (field.name) {
                originalData[field.name] = field.type === 'checkbox' ? field.checked : field.value;
            }
        });
        
        document.getElementById('debug-original-data').textContent = JSON.stringify(originalData, null, 2);
        
        // Track changes
        form.addEventListener('change', function() {
            let hasChanges = false;
            form.querySelectorAll('input, select, textarea').forEach(field => {
                if (field.name) {
                    const currentValue = field.type === 'checkbox' ? field.checked : field.value;
                    if (originalData[field.name] !== currentValue) {
                        hasChanges = true;
                    }
                }
            });
            
            document.getElementById('debug-has-changes').textContent = hasChanges ? 'Yes' : 'No';
        });
        
        // Track form submission
        form.addEventListener('submit', function() {
            const timestamp = new Date().toISOString();
            document.getElementById('debug-last-submit').textContent = timestamp;
            localStorage.setItem('debug_last_submit', timestamp);
            localStorage.setItem('debug_form_id', form.id || 'unnamed_form');
        });
    });
    
    // Check if coming back after a submission
    const lastSubmit = localStorage.getItem('debug_last_submit');
    if (lastSubmit) {
        document.getElementById('debug-last-submit').textContent = lastSubmit;
        // Clear after 5 minutes to avoid confusion
        setTimeout(() => {
            localStorage.removeItem('debug_last_submit');
            localStorage.removeItem('debug_form_id');
        }, 5 * 60 * 1000);
    }
});
</script>
