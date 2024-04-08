document.addEventListener('DOMContentLoaded', function () {
    const roleOptions = {
        'subscriber': 'Donor',
        'candidate': 'Candidate',
        'customer': 'Advocate',
        'medical_provider': 'Physician'
    };
    
    const select = document.getElementById('userTypeSelect');
    let defaultRole = window.location.href;
    
    const switchBtn = document.getElementById('switchAcc');
    
    switchBtn.addEventListener('click', ()=> {
        const selectedOption = select.options[select.selectedIndex];
        const selectedValue = selectedOption.value;
        const selectedText = roleOptions[selectedValue];
        if (selectedText) {
            const url = '/dashboard-' + selectedText.toLowerCase();
            window.location.href = url;
        }
    });
});
