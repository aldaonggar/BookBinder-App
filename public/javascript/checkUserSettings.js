const nameInput = document.getElementById("firstName");
nameInput.addEventListener("change", function() {
    const name = nameInput.value;
    if (/^[a-zA-Z ]*$/.test(name) == false) {
        // invalid input, disable submit button
        document.getElementById("submitBtn").disabled = true;
        document.getElementById("firstName").style.border = "1px solid red";
        nameInput.setCustomValidity("Name should only contain letters");
    } else {
        // valid input, enable submit button
        document.getElementById("submitBtn").disabled = false;
        document.getElementById("firstName").style.border = "none";
        nameInput.setCustomValidity("");
    }
});

const surnameInput = document.getElementById("lastName");
surnameInput.addEventListener("change", function() {
    const surname = surnameInput.value;
    if (/^[a-zA-Z ]*$/.test(surname) == false) {
        // invalid input, disable submit button
        document.getElementById("submitBtn").disabled = true;
        document.getElementById("firstName").style.border = "1px solid red";
        surnameInput.setCustomValidity("Name should only contain letters");
    } else {
        // valid input, enable submit button
        document.getElementById("submitBtn").disabled = false;
        document.getElementById("firstName").style.border = "none";
        surnameInput.setCustomValidity("");
    }
});

const numberInput = document.getElementById("age");
numberInput.addEventListener("change", function() {
    const number = numberInput.value;
    if (/^(0|[1-9][0-9]?|100)$/.test(number) == false) {
        // invalid input, disable submit button
        document.getElementById("submitBtn").disabled = true;
        document.getElementById("firstName").style.border = "1px solid red";
        numberInput.setCustomValidity("Number should only be between 0 and 100");
    } else {
        // valid input, enable submit button
        document.getElementById("submitBtn").disabled = false;
        document.getElementById("firstName").style.border = "none";
        numberInput.setCustomValidity("");
    }
});

const libraryInput = document.getElementById("favoriteLibrary");
libraryInput.addEventListener("change", function() {
    const library = libraryInput.value;
    if (/^[a-zA-Z ]*$/.test(library) == false) {
        // invalid input, disable submit button
        document.getElementById("submitBtn").disabled = true;
        document.getElementById("firstName").style.border = "1px solid red";
        libraryInput.setCustomValidity("Name should only contain letters");
    } else {
        // valid input, enable submit button
        document.getElementById("submitBtn").disabled = false;
        document.getElementById("firstName").style.border = "none";
        libraryInput.setCustomValidity("");
    }
});
