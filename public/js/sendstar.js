$(document).ready(() => {
    $('.give-star').submit((event) => {
        let form = $(event.target).closest("form")
        event.preventDefault();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        console.log(form.attr('action'));
        $.ajax({
            type: "PUT",
            url: form.attr('action'),
            data: {},
            dataType: 'json',
            success: function (data) {
                $("#input-star-"+data["post_id"]).val(data["number"]);
            },
            error: function(data) {
                console.log("error")
                console.log(data);
            }
        });
    });
});
