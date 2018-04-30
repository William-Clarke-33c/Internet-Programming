/*jslint devel: true */
//Checks to see if the given value is the correct length
function testLength(value, length, exactLength) {
    "use strict";
    if(value.length == length){
        exactLength = true;
    }else if(value.length > length){
        exactLength = false;
    }else if(value.length < length){
        exactLength = false;
    }
    return exactLength;
}

//Test whether the value reprsents a number
function testNumber(value) {
    "use strict";
    
    var numberTest = parseInt(value);
    if(isNaN(numberTest)){
        return false;
    }else{
        return true;
    }
}

//Changes payment information controls upon selection

function updateForm(control) {
    "use strict";
    let credit = document.getElementsByName("credit-option"),
        paypal = document.getElementsByName("paypal-option");
    if (control === 1) {
        Array.from(credit).forEach(x => x.classList.remove("hide"));
        Array.from(paypal).forEach(x => x.classList.add("hide"));
    } else if (control === 2) {
        Array.from(credit).forEach(x => x.classList.add("hide"));
        Array.from(paypal).forEach(x => x.classList.remove("hide"));
    }
}


function validateControl(control, name, length) {
    "use strict";
    alert("IN CONTROL");
    var controlLengthTest = false;
    if(name == "ZIP"){
        if(testNumber(control)){
            if(testLength(control,length,controlLengthTest)){
                controlLengthTest = true;
            }else{
                alert("ZIP Needs to Be Of Length 5");
            } 
        }else{
            alert("Please Enter a Valid ZIP");
        }
    }
    if(name == "CVC"){
        if(testNumber(control)){
            if(testLength(control,length,controlLengthTest)){
                controlLengthTest = true;
            }else{
                alert("CVC Needs to Be Of Length 3");
            }
        }else{
            alert("Please Enter a Valid CVC");
        }
    }
    return controlLengthTest;
}

//Test if the user entered a valide Credit Card
function validateCreditCard(value) {
    "use strict";
    var lengthTest;
    if(testNumber(value)){     
        if(value.charAt(0) == '3'){
            if(testLength(value, 15, lengthTest)){
                lengthTest = true;
            }else{
                alert("Enter in 15 numbers");
                lengthTest = false;
            }
        }
        if(value.charAt(0) == '4'){
            if(testLength(value, 16, lengthTest)){
                lengthTest = true;
            }else{
                alert("Enter in 16 numbers");
                lengthTest = false;
            }
        }
        if(value.charAt(0) == '5'){
            if(testLength(value, 16, lengthTest)){
                lengthTest = true;
            }else{
                alert("Enter in 16 numbers");
                lengthTest = false;
            }
        }
        if(value.charAt(0) == '6'){
            if(testLength(value, 16, lengthTest)){
                lengthTest = true;
            }else{
                alert("Enter in 16 numbers");
                lengthTest = false;
            }
        }
    }else{
            alert("Enter a Valid Card Number");
            lengthTest = false;
    }
    return lengthTest;
}

function validateDate(value) {
    "use strict";
    var today = new Date().toISOString().slice(0,10);
    if(value <= today){
        alert("Please Enter in a Valid Date");
        return false;
    }
    if(value > today){
        return true;   
    }
}

//Checks to see if users email is valid
function validateEmail(value) {
    "use strict";
    var email = value;
    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    if(emailPattern.test(value)){
        return true;
    }else{
        alert("Please Enter a Valid Email");
        return false;
    }  
    return true;
}

function validateForm() {
    "use strict";
    if (document.getElementById("ppradio").checked == true) {
        var paypallogin = document.getElementById("payemail").value;
        var pswd = document.getElementById("userpsw").value;
        if(validateEmail(paypallogin) && validatePassword(pswd, 8)){
            alert("Payment Submitted");
        }else{
            alert("Payment Not Accepted");
        }
    }
    if (document.getElementById("ccradio").checked == true) {
        var ccTemp = document.getElementById("ccnum").value;
        var zipHold = document.getElementById("userzip").value;
        var cvcHold = document.getElementById("usercvc").value;
        var userEmail = document.getElementById("creditemail").value;
        var expDate = document.getElementById("expdate").value;
        if(validateEmail(userEmail) && validateState() && validateDate(expDate) && validateCreditCard(ccTemp) && validateControl(zipHold, "ZIP", 5) && validateControl(cvcHold, "CVC", 3)){
            alert("Payment Submitted");    
        }else{
            alert("Payment Not Accepted");
        }
        
    }   
    
}

//Valids the user entered a password of at least length 8
function validatePassword(value, minLength) {
    "use strict";
    var length = value.length
    minLength = 8;
    if(length <= minLength){
        alert("Password must have length of at least 8.")
        return false;
    }
    return true;   
}

// Validates that the user has selected a state
function validateState() {
    "use strict"
    if(document.getElementById("select").value == "0"){
        alert("Please Select a Valid State");
        document.getElementById("select").focus();
        return false;
    }
    return true;
}
