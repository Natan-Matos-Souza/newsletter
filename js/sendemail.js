function app()
{
    const emailForm = document.querySelector('form');

    //Evita que o formulário sofra submit.
    emailForm.addEventListener('submit', (e) => {

        e.preventDefault();

        function getData() {
            const firstInputData = document.querySelector('.first-input').value;
            const secondInputData = document.querySelector('.second-input').value;

            const postData = {
                email_title: firstInputData,
                email_content: secondInputData
            }

            return postData;
        }

        //Recebo os dados da função getData();
        const emailData = getData();

        function testEmail(emailData) {

            const emailTitle = document.querySelector('.email-title');
            const emailContent = document.querySelector('.email-content');

            emailTitle.innerHTML = emailData.email_title;
            emailContent.innerHTML = emailData.email_content;

        }

        testEmail(emailData);



    })
}

app();