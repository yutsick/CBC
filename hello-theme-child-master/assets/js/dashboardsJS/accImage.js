document.addEventListener('DOMContentLoaded', function () {

    // Upload image 

    let ajaxurl = '/wp-admin/admin-ajax.php';

    document.getElementById('uploadProductImage').addEventListener('click', function () {
        document.getElementById('newProductImage').click();
    });

    document.getElementById('newProductImage').addEventListener('change', function () {
        const file_data = document.getElementById('newProductImage').files[0];
        const form_data = new FormData();
        form_data.append('file', file_data);

        const xhr = new XMLHttpRequest();
        xhr.open('POST', ajaxurl, true);
        xhr.onload = function () {
            if (xhr.status === 200) {
                let response = JSON.parse(xhr.responseText);

                if (response.status === 'success') {
                    document.querySelector('.acc-avatar').innerHTML = response.imageHTML;
                } else {
                    console.log('Image upload error');
                }
            } else {
                console.log('Image upload error');
            }
        };

        xhr.send(form_data);
    });

    // Delete Account Image 
   
    document.getElementById('deleteProductImage').addEventListener('click', function () {
        const productID = +document.querySelector('h1[data-id]').getAttribute('data-id');
        const xhr = new XMLHttpRequest();
        xhr.open('POST', ajaxurl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function () {
            if (xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);
                if (data.success) {
                    // const newImageSrc = 'https://staging.childfreebc.com/wp-content/uploads/woocommerce-placeholder-300x300.png';
                    const newImageSrc = 'https://childfreebc.com/wp-content/uploads/woocommerce-placeholder-300x300.png';
                    document.querySelector('.acc-avatar img').src = newImageSrc;
                    document.querySelector('.acc-avatar img').srcset = '';
                    document.querySelector('#delImg').removeAttribute('open');
                }
            } else {
                console.log('Error while deleting product image.');
            }
        };

        xhr.send('action=delete_product_image&product_id=' + productID);
    });
});