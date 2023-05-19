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
        document.getElementById("lastName").style.border = "1px solid red";
        surnameInput.setCustomValidity("Name should only contain letters");
    } else {
        // valid input, enable submit button
        document.getElementById("submitBtn").disabled = false;
        document.getElementById("lastName").style.border = "none";
        surnameInput.setCustomValidity("");
    }
});

const libraryInput = document.getElementById("favoriteLibrary");
libraryInput.addEventListener("change", function() {
    const library = libraryInput.value;
    if (/^[a-zA-Z ]*$/.test(library) == false) {
        // invalid input, disable submit button
        document.getElementById("submitBtn").disabled = true;
        document.getElementById("favoriteLibrary").style.border = "1px solid red";
        libraryInput.setCustomValidity("Name should only contain letters");
    } else {
        // valid input, enable submit button
        document.getElementById("submitBtn").disabled = false;
        document.getElementById("favoriteLibrary").style.border = "none";
        libraryInput.setCustomValidity("");
    }
});
