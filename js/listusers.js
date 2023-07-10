const submitBtn = document.querySelector('.submit-btn');

submitBtn.addEventListener('click', (ev) => {
    ev.preventDefault();
    console.log("Evento previnido!");

    function getData()
    {
        const userSearch = document.querySelector('.search-input').value;

        return userSearch;
    }
    function sendData(data)
    {
        $.ajax({
            type: 'GET',
            dataType: 'json',
            url: 'http://localhost/test/listusers',
            data: {
                username: data
            }
        }).done(function(result) {
            const resultContainer = document.querySelector('.result-container');
            resultContainer.innerHTML = '';
            listUsers(result);
        }).fail(function(result) {
            document.querySelector('.result-container').innerHTML = result.responseJSON;
        })
    }

    function listUsers(result) {
        const resultContainer = document.querySelector('.result-container');

        result.forEach((element) => {
            resultContainer.innerHTML += `<div class="card-container">
<div class="username-area">
<span class="username">Usuário: ${element.username}</span>
</div>

<div class="email-area">
<span class="email">E-mail: ${element.useremail}</span>
</div>
</div>`;
        })
    }

    const userSearch = getData();

    sendData(userSearch);

})