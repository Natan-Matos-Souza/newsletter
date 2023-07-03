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

    formText.style.animation = 'changeFormText 2s';

    setTimeout(() => {
        formText.innerHTML = 'Digite o seu e-mail:'
    }, 1,9 * 1000)

    firstInputDiv.style.display = "none"
    secondInputDiv.style.display = "block"
    changeInputBtn.style.display = "none"
    submitBtn.style.display = "inline-block";
    secondInput.focus()

})

