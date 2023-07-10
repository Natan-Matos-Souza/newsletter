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
            listUsers(result);
        })
    }

    function listUsers(result) {
        result.forEach((element) => {
            console.log(element.username);
        })
    }

    const userSearch = getData();

    sendData(userSearch);

})