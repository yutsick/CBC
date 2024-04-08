jQuery(document).ready(function($){
    $('#copy-accLink').on('click', function(){
        var copyText = $('#accLink');
        copyText.select();
        document.execCommand("copy");
        $('#copiedTextMsg').text('Copied to clipboard!');
    });
});
