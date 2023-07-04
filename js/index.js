//Todo o app está dentro de uma função como medida para evitar colisão de variáveis.

function app() {
    const changeInputBtn = document.getElementById('change-input-btn')
    const submitBtn = document.getElementById('submit-btn')


    submitBtn.addEventListener('click', (event) => {
        
        if (secondInputDiv.style.display === "block") {
            event.submit()
        } else {
            event.preventDefault()
        }

    })


    //Importando inputs//
    const firstInput = document.getElementById('first-input')
    const secondInput = document.getElementById('second-input')

    //Importando divs

    const firstInputDiv = document.getElementById('first-input-div')
    const secondInputDiv = document.getElementById('second-input-div')

    const formText = document.getElementById('text')

    firstInput.focus()



    changeInputBtn.addEventListener('click', () => {

        formText.style.animation = 'changeFormText 3s';

        setTimeout(() => {
            formText.innerHTML = 'Digite o seu e-mail:'
        }, 2 * 1000)

        firstInputDiv.style.display = "none"
        secondInputDiv.style.display = "block"
        changeInputBtn.style.display = "none"
        submitBtn.style.display = "inline-block";
        secondInput.focus()

    })

    //Error log

    const logText = document.querySelector('#log-status').innerHTML;

    console.log(logText)

    let count = 0

    function playErrorSound() {


        setTimeout(() => {

            const errorSound = document.querySelector('audio');

            for (i = 0; i < 1; i++) {

        
                errorSound.play();

                count++
            }
            
    
        }, 1 * 1000)
    }

    if (logText == "Erro: este e-mail já foi cadastrado!" || logText == "Erro: não foi possível cadastrar o seu e-mail" || logText == "E-mail cadastrado com sucesso!") {
        playErrorSound();
    }

}

app()