document.addEventListener('DOMContentLoaded', () => {
    const toolLabel = document.querySelector('#field_hiv46_label');
    const toolContent = document.querySelector('.tolltip-block.age');

    if (toolLabel) {
        toolLabel.addEventListener('mouseenter', () => {
            toolContent.style.display = 'block';
        });


        toolLabel.addEventListener('mouseover', () => {
            toolContent.style.display = 'block';
        });

        toolLabel.addEventListener('mouseleave', () => {
            toolContent.style.display = 'none';
        });
    }


    const eyes = document.querySelectorAll('.passwords-eye');
    if (eyes) {
        let i = 1;
        for (let eye of eyes) {

            let  eyeIcon = eye.querySelector('.frm_inline_box');
            const passwordField = eye.querySelector('input[type = "password"]');
 
            eyeIcon.addEventListener('click', function() {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                eyeIcon.classList.toggle('show');
                
            });
        }

    }

})