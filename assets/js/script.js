jQuery( function ( $ ) {

    let htmlInput = '<tr>' +
                    '<td>' +
                        '<input class="input-item__text-input" type="text" name="section-name[]">' +
                    '</td>' +
                    '<td>' +
                        '<textarea class="input-item__textarea" name="section-content[]"></textarea>' +
                    '</td>' +
                    '<td>' +
                        '<div class="button input-item__button input-item__button--red delete-button">Delete</div>' +
                    '</td>' +
                '</tr>';

    $('#add-item-button').click( function() {
        $('#table-body').append(htmlInput);
    });

    $(document).on('click', '.delete-button', function () {
        $(this).parents('tr').remove();
    });

});
