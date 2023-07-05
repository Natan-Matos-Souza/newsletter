function app()
{
    let emailData;

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
        emailData = getData();

        function testEmail(emailData) {
            const testEmailArea = document.querySelector('.post-area');
            const emailTitle = document.querySelector('.email-title');
            const emailContent = document.querySelector('.email-content');

            testEmailArea.style.display = 'block';

            emailTitle.innerHTML = emailData.email_title;
            emailContent.innerHTML = emailData.email_content;

            const testEmailCoordenates = testEmailArea.getBoundingClientRect();
            scrollTo(0, testEmailCoordenates.top);

        }

        testEmail(emailData);



    })

    const cancelEmailBtn = document.querySelector('.deny-btn');

    cancelEmailBtn.addEventListener('click', () => {


        const emailFormCoordanates = emailForm.scrollTop

        scrollTo(0, emailFormCoordanates);

        function removeData()
        {
            emailData = '';
            document.querySelector('.first-input').value = '';
            document.querySelector('.second-input').value = '';

        }

        removeData();

        document.addEventListener('scroll', () => {
            
            if (window.pageYOffset == emailFormCoordanates)
            {
                document.querySelector('.post-area').style.display = "none";
            }
        })
    })

}

app();