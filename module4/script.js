/*jslint devel: true */


//Adds movie to user shopping cart
function addMovie(movieID){
    window.location.replace("index.php?action=add&movie_id=" + movieID);
    return true;
}

//Confirms if the user wants to remove a movie
function confirmRemove(title, movieID){
    var remove = confirm("Are You Sure You Want To Remove " + " " + movieID + " " + " From Your Shopping Cart?");
    if(remove){
        window.location.replace("index.php?action=remove&movie_id=" + movieID);
    }else{
        return false;
    }
}

//Confirms if the user wants to logout
function confirmLogout(){
    var logout = confirm("Are You Sure You Want To Logout?");
    if(logout){
        window.location.replace("logon.php");
        return true;
    }else{
        return false;
    }
}