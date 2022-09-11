var app = {

    /**
    * Listeners are ready when DOM is loaded
    */
    init: function () {

        /**
        * *****************************
        * L I S T E N E R S
        * *****************************
        */
        $('.dl-btn').on('click', app.clearFolder);
        $('.info').on('click', app.showInfo);
        $('.close-btn').on('click', app.closeModal);
        $('.loader').on('click', app.loaderAnim);

    },

    /**
    * Delete img uploaded in public folder
    */
    clearFolder: (e) => {
        // Avoid multiple download
        $('.dl-btn').css('display', 'none');
        let imgToDelete = $('.deleteImg').val()
        setTimeout(() => {
            $.ajax(
                {
                    url: Routing.generate('clear_img', {'fileId': imgToDelete}),
                    method: "POST"
                }).done(function (response) {
                    e.preventDefault();
                    if (null !== response) {
                        console.log('Fichier téléchargé');
                        window.location.replace('https://pydonze.fr/imgreducer/public/');
                    } else {
                        console.log('error');
                    }
                }).fail(function (jqXHR, textStatus, error) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(error);
                });
        }, "2000")
    },

    /**
    * Show info modal
    */
    showInfo: () => {
        $('.modal-info').addClass('visible').removeClass('hidden');
        $('.container-center').addClass('hidden').removeClass('visible');
    },

    /**
    * Close modals
    */
    closeModal: () => {
        $('.modal').addClass('hidden').removeClass('visible');
        $('.container-center').addClass('visible').removeClass('hidden');
    },

    /**
     * Loader
     */
    loaderAnim: () => {
        $('.loading-anim').removeClass("hidden");
        $('.container-center').css('opacity', .2);
    }
}

document.addEventListener('DOMContentLoaded', app.init)
