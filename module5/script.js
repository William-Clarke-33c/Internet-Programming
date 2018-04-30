function addMovie(movieID){
	window.location.href = "./index.php?action=add&id=" + movieID;
	return true;
}


function createAccount(){
    window.location.href = "logon.php?form=create";
}

function confirmLogout(){
    if (confirm("Please Confirm You Want to Leave myMovies Xpress?")) {
		window.location.href = "./logon.php";
		return true;
    }
    else{
		return false;
	}
}

function confirmRemove(title, movieID){
	if (confirm("Please Confirm You Want to Remove the Following Movie:\n\n" + title)) {
		window.location.href = "./index.php?action=remove&id=" + movieID;
		return true;
	}
	else{
		return false;
	}
}

function cancel(form){
    if (confirm("Are You Sure You Want To Cancel The " + form + " Form ?" )) {
        window.location.replace("logon.php");
        return true;
    }
    else{
        return false;
    }

}

function checkout(){
    window.location.replace("index.php?action=checkout");
    return true;

}



function displayMovieInformation(movie_id){
    xml = new XMLHttpRequest();
    
    xml.onreadystatechange = function(){
        document.getElementById("modalWindowContent").innerHTML= this.responseText;
        showModalWindow();
    }
    xml.open("GET", "movieinfo.php?movie_id=" + movie_id, true);
    xml.send();

}

//SECTION 7
function forgotPassword(username){
    window.location.replace("logon.php?action=forgot&username="+ username);

}

function showModalWindow() {
    var modal = document.getElementById('modalWindow');
    var span = document.getElementsByClassName("close")[0];

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    modal.style.display = "block";
}

//SECTION 6
function validateCreateAccountForm(){
    var userName = document.forms["createAccountForm"]["userName"].value;
    var email = document.forms["createAccountForm"]["emailAdd"].value;
    var emailCheck = document.forms["createAccountForm"]["confirmemailAdd"].value;
    var password = document.forms["createAccountForm"]["password"].value;
    var passwordCheck = document.forms["createAccountForm"]["confirmPassword"].value;

    if(userName.indexOf(' ') >= 0){
        alert("Username Can't Contain a Space");
            return false;
    }
    if(email.indexOf(' ') >= 0){
        alert("Email Can't Contain a Space");
        return false;
    }

    if(password.indexOf(' ') >= 0){
        alert("Password Can't Contain a Space");
        return false;
    }

    if(email != emailCheck){
        alert("Emails Don't Match");
        return false;
    }
    if(password != passwordCheck){
        alert("Passwords Don't Match");
        return false;
    }
}

//SECTION 7
function validateResetPasswordForm(){
    var password = document.forms["resetForm"]["passwordReset"].value;
    var passwordCheck = document.forms["resetForm"]["confirmPasswordReset"].value;

    if(password.indexOf(' ') >= 0){
        alert("Password Can't Contain a Space");
        return false;
    }

    if(password != passwordCheck){
        alert("Passwords Don't Match");
        return false;
    }

}