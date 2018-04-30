<?php

include 'dbConnect.php';

/*
	Add a new movie to the database.
	
	Parameters:
		imdbId - The IMDB ID (tt0123456) of the movie.
		title - The title of the movie.
		year - The year of the movie.
		rating - The rating (G, PG, PG-13, R, etc.) of the movie.
		runtime - The runtime (in minutes) of the movie.
		genre - The genre(s) of the movie.
		actors - The actor(s) of the movie.
		director - The director(s) of the movie.
		writer - The writer(s) of the movie.
		plot - The plot of the movie.
		poster - The URL of the movie poster.
		
	Return:
		-1 - Error: The specified movie already exists.
		>0 - The ID of the movie added.
 */
function addMovie($imdbId, $title, $year, $rating, $runtime, $genre, $actors, $director, $writer, $plot, $poster)
{
	try 
	{
		if(!checkMovieExistsInDB($imdbId)) // Movie does not already exist
		{
			$conn = dbConnect();
			$query = $conn->prepare("INSERT INTO Movies (IMDB_ID, Title, Year, Rating, Runtime, Genre, Actors, Director, Writer, Plot, Poster) VALUES (:imdbId, :title, :year, :rating, :runtime, :genre, :actors, :director, :writer, :plot, :poster)");
			$query->execute(array('imdbId' => $imdbId, 'title' => $title, 'year' => $year, 'rating' => $rating, 'runtime' => $runtime, 'genre' => $genre, 'actors' => $actors, 'director' => $director, 'writer' => $writer, 'plot' => $plot, 'poster' => $poster));
			return $conn->lastInsertId(); // Get the ID of the movie added
		}
		else 
		{
			return -1; // Movie already exists
		}
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}  
}

/*
	Add a movie to the specified user's shopping cart.
		Note: A specific movie can only be added to the user's cart once.
	
	Parameters:
		userId - The ID of the user.
		movieId - The ID of the movie (not the IMDB ID).
				
	Return:
		Nothing
 */
function addMovieToShoppingCart($userId, $movieId)
{
	try 
	{
		if(checkUniqueMovieInCart($userId, $movieId)) // Movie is not already in the cart
		{
			$conn = dbConnect();
			$query = $conn->prepare("INSERT INTO Cart (UserId, MovieId) VALUES (:userid, :movieid)");
			$query->execute(array('userid' => $userId, 'movieid' => $movieId));
		}
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}  
}

/*
	Create a new user account.
		Note: The new user account is disabled by default.
	
	Parameters:
		username - Username selected by the user.
		password - Password selected by the user.
		displayName - First and Last name of the user.
		email - Email address of the user.
		
	Return:
		-1 - Error: The specified username already exists.
		>0 - The ID of the user account created.
 */
function addUser($username, $password, $displayName, $email)
{
	try 
	{
		if(checkUniqueUsername($username)) // Username does not already exist
		{
			echo "SQL: Adding User";
			$conn = dbConnect();
			$query = $conn->prepare("INSERT INTO Users (Username, Password, DisplayName, Email) VALUES (:username, :password, :displayname, :email)");
			$query->execute(array('username' => $username, 'password' => $password, 'displayname' => $displayName, 'email' => $email));
			echo $conn->lastInsertId();
			return $conn->lastInsertId(); // Get the ID of the user account created
		}
		else 
		{
			echo "User Not Unique";
			return -1; // Username already exists
		}
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}  
}

/*
	Tests whether the specified movie already exists.
	
	Parameters:
		imdbId - The IMDB ID (tt0123456) to test.
	
	Return:
		true - The movie already exists (no need to add it to the database).
		false - The movie does not exist (it needs to be added to the database).
 */
function checkMovieExistsInDB($imdbId)
{
	try 
	{
		$conn = dbConnect();
		$query = $conn->prepare("SELECT ID FROM Movies WHERE IMDB_ID = ?");
		$query->execute([$imdbId]);
		return $query->fetchColumn() > 0 ? true : false;
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}
}

/*
	Tests whether the specified username already exists.
	
	Parameters:
		username - The username to test.
	
	Return:
		true - The username does not exist (it can be added to the database).
		false - The username already exists (a new username must be selected).
 */
function checkUniqueUsername($username)
{
	try 
	{
		$conn = dbConnect();
		$query = $conn->prepare("SELECT COUNT(*) as Count FROM Users WHERE Username = ?");
		$query->execute([$username]);
		return $query->fetchColumn() == 0 ? true : false;
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}
}

/*
	Retrieve information about the specified movie.
	
	Parameters:
		movieId - The ID of the movie to retrieve.
	
	Return:
		NULL - Error: The movieId does not exist in the database.
		Not NULL - A single data row containing the movie's data (ID, IMDB_ID, Title, Year, Rating, Runtime, Genre, Actors, Director, Writer, Plot and Poster).
 */
function getMovieData($movieId)
{
	try 
	{
		$conn = dbConnect();
		$query = $conn->prepare("SELECT * FROM Movies WHERE ID = ?");
		$query->execute([$movieId]);
		return $query->fetch() ?: null;
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}
}

/*
	Get a list of the movies in the specified user's shoppting cart (ordered by runtime).
	
	Parameters:
		userId - The ID of the user.
		ascending - The order in which to list movies (true: ascending order [A to Z], false: descending order [Z to A]).
	
	Return:
		Empty array - No movies exist in the specified user's shopping cart.
		Otherwise - An array of rows containing the movies in the shopping cart (order by runtime).
			Note: Only the following movie information is included (ID, IMDB_ID, Title, Year and Poster).
 */
function getMoviesInCartByRuntime($userId, $ascending)
{
	try 
	{
		$conn = dbConnect();
		$query = $conn->prepare("SELECT ID, IMDB_ID, Title, Year, Poster FROM Movies, Cart WHERE ID = MovieId AND UserID = ? AND Active = 1 ORDER BY Runtime " . ($ascending ? "ASC" : "DESC"));
		$query->execute([$userId]);
		return $query->fetchAll();
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}
}

/*
	Get a list of the movies in the specified user's shoppting cart (ordered by title).
	
	Parameters:
		userId - The ID of the user.
	
	Return:
		Empty array - No movies exist in the specified user's shopping cart.
		Otherwise - An array of rows containing the movies in the shopping cart (order by title).
			Note: Only the following movie information is included (ID, IMDB_ID, Title, Year and Poster).
 */
function getMoviesInCartByTitle($userId)
{
	try 
	{
		$conn = dbConnect();
		$query = $conn->prepare("SELECT ID, IMDB_ID, Title, Year, Poster FROM Movies, Cart WHERE ID = MovieId AND UserID = ? AND Active = 1 ORDER BY Title");
		$query->execute([$userId]);
		return $query->fetchAll();
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}
}

/*
	Get a list of the movies in the specified user's shoppting cart (ordered by year).
	
	Parameters:
		userId - The ID of the user.
		ascending - The order in which to list movies (true: ascending order [A to Z], false: descending order [Z to A]).
	
	Return:
		Empty array - No movies exist in the specified user's shopping cart.
		Otherwise - An array of rows containing the movies in the shopping cart (order by year).
			Note: Only the following movie information is included (ID, IMDB_ID, Title, Year and Poster).
 */
function getMoviesInCartByYear($userId, $ascending)
{
	try 
	{
		$conn = dbConnect();
		$query = $conn->prepare("SELECT ID, IMDB_ID, Title, Year, Poster FROM Movies, Cart WHERE ID = MovieId AND UserID = ? AND Active = 1 ORDER BY Year " . ($ascending ? "ASC" : "DESC"));
		$query->execute([$userId]);
		return $query->fetchAll();
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}
}

/*
	Tests user's credentials and returns user data.
	
	Parameters:
		username - The username to test.
		password - The password to test.
	
	Return:
		NULL - The credentials do not match.
		Not NULL - A single data row containing the user's ID, Display Name and Email Address.
 */
function getUserData($username, $password)
{
	try 
	{
		$conn = dbConnect();
		$query = $conn->prepare("SELECT ID, DisplayName, Email FROM Users WHERE Username = :username AND Password = :password");
		$query->execute(array('username' => $username, 'password' => $password));
		return $query->fetch() ?: null;
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}
}

/*
	Get a user's ID, display name and email address.
	
	Parameters:
		username - The username of the user.
			
	Return:
		Not NULL - A single data row containing the user's ID, Display Name and Email Address.
		NULL - The username does not match.
 */
function prepareResetPassword($username)
{
	try 
	{
		$conn = dbConnect();
		$query = $conn->prepare("SELECT ID, DisplayName, Email FROM Users WHERE Username = ?");
		$query->execute([$username]);
		return $query->fetch() ?: null;
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}
}

/*
	Remove a movie from the specified user's shopping cart.
		Note: A virtual delete is performed (Active is set to 0).
	
	Parameters:
		userId - The ID of the user.
		movieId - The ID of the movie (not the IMDB ID).
				
	Return:
		true - The specified movie was successfully removed (virtually) from the specified user's shopping cart.
		false - The specified movie does not exist in the specified user's shopping cart or an error occurred.
		
 */
function removeMovieFromShoppingCart($userId, $movieId)
{	
	try 
	{
		$conn = dbConnect();
		$query = $conn->prepare("UPDATE Cart SET Active = 0 WHERE UserId = :userid AND MovieId = :movieid");
		$query->execute(array('userid' => $userId, 'movieid' => $movieId));
		return $query->rowCount() == 1 ? true : false;
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}
}

/*
	Reset the specified user's password.
	
	Parameters:
		username - The username of the user.
		password - The user's new password.
				
	Return:
		true - The specified user's password was successfully changed.
		false - The specified username is invalid or an error occurred.
 */
function preformPasswordReset($username, $password)
{	
	try
	{
		$conn = dbConnect();
		$query = $conn->prepare("UPDATE Users SET Password = :password WHERE Username = :username");
		$query->execute(array('password' => $password, 'username' => $username));
		return $query->rowCount() == 1 ? true : false;
	}
	catch(PDOException $e)
	{
		if(DEBUG)
		{
			echo "Database Connection Failed: " . $e->getMessage();
		}
		return null;
	}
}

?>
