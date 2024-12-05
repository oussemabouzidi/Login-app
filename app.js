const signUpButton = document.getElementById('signUp');
const signInButton = document.getElementById('signIn');
const container = document.getElementById('container');

signUpButton.addEventListener('click', () => {
    container.classList.add("right-panel-active");
});

signInButton.addEventListener('click', () => {
    container.classList.remove("right-panel-active");
});

// Sign-up form submission handling
document.addEventListener("DOMContentLoaded", function() {
    const signupForm = document.querySelector(".sign-up-container form");
    signupForm.addEventListener("submit", function(event) {

        const username = document.getElementById("newusername").value;
        const email = document.getElementById("email").value;
        const password = document.getElementById("newpassword").value;
        const confirmPassword = document.getElementById("newconfirm_password").value;
        var response = grecaptcha.getResponse();

        if(response.length==0){
            alert('Please verify you are not a robot');
            return;
        }

        // Basic validation
        if (!username || !email || !password || !confirmPassword) {
            alert("Veuillez remplir tous les champs!");
            return;
        }

        // Email validation
        const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        if (!emailPattern.test(email)) {
            alert("Veuillez entrer une adresse e-mail valide!");
            return;
        }

        // Password validation
        const passwordPattern = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (!passwordPattern.test(password)) {
            alert("Le mot de passe doit comporter au moins 8 caractères, y compris au moins un chiffre et une lettre!");
            return;
        }

        // Check if passwords match
        if (password !== confirmPassword) {
            alert("Les mots de passe ne correspondent pas!");
            return;
        }

    });

    const signinForm = document.querySelector(".sign-in-container form");
    signinForm.addEventListener("submit", function(event){
        console.log("test");
        var response = grecaptcha.getResponse();
        /*
        if(response.length==0){
            alert('Please verify you are not a robot');
            return;
        }*/
    })
});
