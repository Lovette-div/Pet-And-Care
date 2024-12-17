<?php
include '../db/config.php';  



// Enable error reporting to display errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Collect and trim form data to remove unnecessary whitespace
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
   
    

    // Check if any required fields are empty
    if (empty($fname) ||empty($lname)|| empty($email) || empty($password) || empty($confirm_password)) {
        die('Please fill in all required fields.'); 
    }

    // Check if password and confirm password match
    if ($confirm_password != $password) {
        echo 'Passwords do not match.'; 
    }


    // Prepare a statement to check if the email is already registered in the database
    $stmt = $conn->prepare('SELECT email FROM users WHERE email = ?');
    $stmt->bind_param('s', $email); // Bind the email parameter to the query
    $stmt->execute(); 
    $results = $stmt->get_result(); 


    // Check if the email already exists in the database
    if ($results->num_rows > 0) {
        echo '<script>alert("User already registered.");</script>';

        echo '<script>window.location.href = "../view/register.php";</script>';

    } else {
        // Hash the password for security before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $role = 'user';   //set default to user
        // Prepare an INSERT statement to add the new user to the database
        $sql = "INSERT INTO users (fname, lname, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
    
        $stmt->bind_param('sssss', $fname, $lname, $email, $hashed_password, $role);


        // Execute the statement and check if it was successful
        if ($stmt->execute()) {
            $_SESSION['fname'] = $fname;
            header('Location: ../view/login.php'); // Redirect to the login page if successful
        } else {
            header('Location: ../view/signup.php'); 
        }
    }


    // Close the statement after execution
    $stmt->close();
}


$conn->close();

?>