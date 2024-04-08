document.addEventListener('DOMContentLoaded', function() {
    const currentPageUrl = window.location.href;

    document.querySelector('#share-on-facebook').addEventListener('click', function(e) {
        console.log('gfdsdfgfd')
        e.preventDefault();
        const text = encodeURIComponent(`Please donate toward my vasectomy/tubal ligation, so I don't have unintentional children <br>I’m seeking a vasectomy/tubal ligation, so I don't have unintentional children. See my profile ${currentPageUrl}`);
        window.open('https://www.facebook.com/sharer/sharer.php?u=' + currentPageUrl + '&quote=' + text, 'facebookShareWindow', 'width=600,height=400');
    });

    document.getElementById('share-on-twitter').addEventListener('click', function(e) {
        e.preventDefault();
        const text = encodeURIComponent(`Please donate toward my vasectomy/tubal ligation, so I don't have unintentional children <br>I’m seeking a vasectomy/tubal ligation, so I don't have unintentional children. See my profile ${currentPageUrl}`);
        window.open('https://twitter.com/intent/tweet?url=' + currentPageUrl + '&text=' + text, 'twitterShareWindow', 'width=600,height=400');
    });

    document.getElementById('share-on-whatsapp').addEventListener('click', function(e) {
        e.preventDefault();
        const text = encodeURIComponent(`Please donate toward my vasectomy/tubal ligation, so I don't have unintentional children <br>I’m seeking a vasectomy/tubal ligation, so I don't have unintentional children. See my profile ${currentPageUrl}`);
        window.open('whatsapp://send?text=' + text + '%20' + currentPageUrl, 'whatsappShareWindow');
    });

    document.getElementById('share-on-reddit').addEventListener('click', function(e) {
        e.preventDefault();
        const title = encodeURIComponent(`Please donate toward my vasectomy/tubal ligation, so I don't have unintentional children <br>I’m seeking a vasectomy/tubal ligation, so I don't have unintentional children. See my profile ${currentPageUrl}`);
        window.open('https://www.reddit.com/submit?url=' + currentPageUrl + '&title=' + title, 'redditShareWindow', 'width=600,height=400');
    });
   
    document.getElementById('share-on-email').addEventListener('click', function(e) {
        e.preventDefault();
        const subject = encodeURIComponent("Please donate toward my vasectomy/tubal ligation, so I don't have unintentional children");
        const body = encodeURIComponent(  `I’m seeking a vasectomy/tubal ligation, so I don't have unintentional children. See my profile ${currentPageUrl}`);
        window.location.href = `mailto:?subject=${subject}&body=${body}`;

    });
}); 