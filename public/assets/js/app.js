var app = {

    init: function () {

        /**
        * *****************************
        * L I S T E N E R S
        * *****************************
        */
        $('.dl-btn').on('click', app.clearFolder);
    },

    clearFolder: (e) => {
        setTimeout(() => {
            $.ajax(
                {
                    url: Routing.generate('clear_folder'),
                    method: "POST",
                }).done(function (response) {
                    e.preventDefault();
                    if (null !== response) {
                        app.reload();
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

    reload: () => {
        location.reload()
    },
}

document.addEventListener('DOMContentLoaded', app.init)
