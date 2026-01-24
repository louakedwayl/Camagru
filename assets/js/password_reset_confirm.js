const form = document.querySelector('form');
const pass1 = document.getElementById('pass1');
const pass2 = document.getElementById('pass2');
const btn = document.getElementById('submit-btn');


const errSize = document.querySelector('.error.password');
const errUpper = document.querySelector('.error.uppercase');
const errMatch = document.querySelector('.errorMatch');
const errTimeout = document.querySelector('.errorTimeout');


function validatePass1() {
    errSize.style.display = "none";
    errUpper.style.display = "none";
    pass1.style.borderColor = "";
    Pass1.style.marginBottom = "12px";


    if (pass1.value === "") return;

    if (pass1.value.length < 6) {
        errSize.style.display = "block";
        pass1.style.borderColor = "red";
        Pass1.style.marginBottom = "0px";
    } 
    else if (!/[A-Z]/.test(pass1.value)) 
    {
        errUpper.style.display = "block";
        pass1.style.borderColor = "red";
        Pass1.style.marginBottom = "0px";
    }
}

function validatePass2() 
{
    errMatch.style.display = "none";
    pass2.style.borderColor = "";

    if (pass2.value === "") return;

    if (pass1.value !== pass2.value) 
    {
        errMatch.style.display = "block";
        pass2.style.borderColor = "red";
    }
}

function checkButton() {
    const v1 = pass1.value;
    const v2 = pass2.value;
    
    const isStrong = v1.length >= 6 && /[A-Z]/.test(v1);
    const match = v1 === v2 && v1 !== "";

    if (isStrong && match) {
        btn.classList.add('active');
    } else {
        btn.classList.remove('active');
    }
}

pass1.addEventListener("blur", () => {
    validatePass1();
    if (pass2.value !== "") validatePass2(); 
});

pass2.addEventListener("blur", validatePass2);
pass1.addEventListener("input", checkButton);
pass2.addEventListener("input", checkButton);


form.addEventListener('submit', async (e) => 
{
    e.preventDefault();

    if (!btn.classList.contains('active'))
        return;

    const formData = new FormData(form);

    try 
    {
        btn.disabled = true;
        
        const response = await fetch("index.php?action=update_password", 
        {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        if (data.success) 
        {
            window.location.href = "index.php?action=dashboard";
        }
        else
        {
            btn.disabled = false;
            errTimeout.style.display = "block";
        }
    } 
    catch (error)
    {
        btn.disabled = false;
    }
});