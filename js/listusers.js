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
            console.log(result);
        })
    }

    const userSearch = getData();

    sendData(userSearch);

})