const changeInputBtn = document.getElementById('change-input-btn')
const submitBtn = document.getElementById('submit-btn')

//Importando inputs//
const firstInput = document.getElementById('first-input')
const secondInput = document.getElementById('second-input')

//Importando divs

const firstInputDiv = document.getElementById('first-input-div')
const secondInputDiv = document.getElementById('second-input-div')

const formText = document.getElementById('text')

firstInput.focus()



changeInputBtn.addEventListener('click', () => {

    firstInputDiv.style.display = "none"
    secondInputDiv.style.display = "block"
    changeInputBtn.style.display = "none"
    submitBtn.style.display = "inline-block"
    formText.innerHTML = 'Digite o sua senha:'

})

