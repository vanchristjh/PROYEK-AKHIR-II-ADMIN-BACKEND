/**
 * Subject Management JavaScript functions
 */
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete confirmation
    const deleteSubjectForms = document.querySelectorAll('.delete-subject-form');
    
    deleteSubjectForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const subjectName = this.dataset.subjectName;
            
            if (confirm(`Apakah Anda yakin ingin menghapus mata pelajaran "${subjectName}"? Semua data yang terkait akan dihapus secara permanen.`)) {
                this.submit();
            }
        });
    });
    
    // Auto-capitalize subject code as the user types
    const subjectCodeInput = document.getElementById('code');
    if (subjectCodeInput) {
        subjectCodeInput.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Handle teacher selection
    const teacherCheckboxes = document.querySelectorAll('input[name="teachers[]"]');
    const teacherCounter = document.getElementById('selected-teacher-count');
    
    if (teacherCheckboxes.length > 0 && teacherCounter) {
        const updateTeacherCount = () => {
            const selectedCount = [...teacherCheckboxes].filter(checkbox => checkbox.checked).length;
            teacherCounter.textContent = selectedCount;
        };
        
        teacherCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTeacherCount);
        });
        
        // Initialize count on page load
        updateTeacherCount();
    }
});
