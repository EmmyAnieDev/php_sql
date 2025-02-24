<?php

// Enable error reporting for all errors, warnings, and notices
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/db_connect.php';

$errors = array('email'=>'', 'title'=>'', 'ingredients'=>'');
$title = $ingredients = $email = '';

// check if submit is clicked or set.
if(isset($_POST['submit'])){    

    // check email
    if(empty($_POST['email'])){
        $errors['email'] = 'An email is required!';  
    }else{
        $email = $_POST['email'];
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = "Email must be a valid email address.";
        }
    }

    // check title
    if(empty($_POST['title'])){
        $errors['title'] = 'A title is required!';  
    }else{
        $title = $_POST['title'];
        if(!preg_match('/^[a-zA-Z\s]+$/', $title)){
            $errors['title'] = "Title must be letters and space only.";
        }
    }

    // check ingredients
    if(empty($_POST['ingredients'])){
        $errors['ingredients'] = 'At least one ingredient is required!';  
    }else{
        $ingredients = $_POST['ingredients'];
        if(!preg_match('/^([a-zA-Z\s]+)(,\s*[a-zA-Z\s]*)*$/', $ingredients)){
            $errors['ingredients'] = "Ingredients must be a comma separated list..";
        }
    }

    if(array_filter($errors)){
        echo "error in form!";
    }else{

        // Sanitize user input to prevent SQL injection before sending to database.
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $ingredients = mysqli_real_escape_string($conn, $_POST['ingredients']);

        // create sql
        $sql = "INSERT INTO pizzas (title, email, ingredients) VALUES('$title', '$email', '$ingredients')";

        // save to the database
        if(mysqli_query($conn, $sql)){
            // if success
            header('Location: index.php');
        }else{
            // if failed
            echo "query error: " .  mysqli_error($conn);
        }

    }
}  // end of post check



?>


<!DOCTYPE html>
<html>
<?php include "templates/header.php"; ?>

<section class="container grey-text">
    <h4 class="center">Add a Pizza</h4>
    <form class="white" action="<?php  echo $_SERVER['PHP_SELF']  ?>" method="POST">
        <label>Your Email:</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($email) ?>">
        <div class="red-text"><?php echo $errors['email']; ?></div>
        <label>Pizza Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($title) ?>">
        <div class="red-text"><?php echo $errors['title']; ?></div>
        <label>Ingredients (coma separated):</label>
        <input type="text" name="ingredients" value="<?php echo htmlspecialchars($ingredients) ?>">
        <div class="red-text"><?php echo $errors['ingredients']; ?></div>
        <div class="center">
            <input type="submit" name="submit" value="submit" class="btn brand z-depth-0">
        </div>
    </form>
</section>

<?php include "templates/footer.php"; ?>
    

</html>