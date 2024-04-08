document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.gender');
    
    checkboxes.forEach(checkbox => {
      checkbox.addEventListener('change', function() {
        if (this.checked) {
          checkboxes.forEach(otherCheckbox => {
            if (otherCheckbox !== this) {
              otherCheckbox.checked = false;
            }
          });
        }
      });
    });
});