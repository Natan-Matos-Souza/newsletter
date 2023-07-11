let emailHistoric;

function getEmails()
{
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: 'http://localhost/test/historic',
        data: {
            get_historic: 'a'
        }
    }).done(function(reuslt) {
        emailHistoric = reuslt;
    }).fail(function (result) {
        console.log(result.responseJSON);
    })

}

getEmails();