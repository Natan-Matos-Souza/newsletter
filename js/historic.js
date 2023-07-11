let emailHistoric;


function showData(result)
{
    const emailContainer = document.querySelector('.email-container');
    const emailAreaResponseText = document.querySelector('.historic-title');

    emailAreaResponseText.innerHTML = 'Histórico';

    result.forEach((element) => {

        if (element.sent == 1) {
            element.sent = 'Enviado';
        } else {
            element.sent = 'Enviando...';
        }

        emailContainer.innerHTML += `<div class="email-card"><div class="title-area"><span class="email-title">${element.email_title}</span></div><div class="content-area"><span class="email-content">${element.email_content}</span></div><div class="email-status-area"><span class="email-status">${element.sent}</span></div></div>`;

    });

    const statusText = document.getElementsByClassName('email-status');

    const newArray = [...statusText];

    newArray.forEach((element) => {
        if (element.innerHTML == 'Enviado') {
            element.style.color = '#C4D7B2';
        } else {
            element.style.color = '#FF0000';
        }
    })
}
function getEmails()
{
    $.ajax({
        type: 'GET',
        dataType: 'json',
        url: 'http://localhost/test/historic',
        data: {
            get_historic: ''
        }
    }).done(function(result) {
        showData(result);
    }).fail(function (result) {
        console.log(result.responseJSON);
    })

}

getEmails();