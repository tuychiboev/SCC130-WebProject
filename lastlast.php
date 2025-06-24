<html>
<head>
	<meta charset = "utf-8">
	<link rel="stylesheet" type="text/css" href="add.css">
</head>

<body>

<ul>
  <li><a class="active">Home</a></li>
</ul>

<?php // connect.php allows connection to the database

  require 'connect.php'; //using require will include the connect.php file each time it is called.

  function validate()
  {
      global $error;
      global $conn;
      $error = "Book successfully added.";
  
      //book id check
  
      /* presence check */
      if(empty($_POST['id']))
      {
          $error = "Please enter the Book id...";
          return false;
      }
  
      /* Type check on ID */
      if(!is_numeric($_POST['id']))
      {
          $error = "Book ID must contain only numbers...";
          return false;   
      }
  
      /* Length checks. */
      if(strlen($_POST['id']) != 3)
      {
          $error = "Book ID must be 3 digit number...";
          return false;
      }
  
      /* Unique id check: if the book with the id already exists.
         This is to prevent primary key calshes with the database.
      */
      $id = $_POST['id'];
      $query = "SELECT id FROM table1 WHERE id = "."'$id'";
  
      $result = $conn -> query($query);
  
      if(mysqli_num_rows($result) > 0)
      {
          $error = "Error: ID already found in database.";
          return false;
      }
  
      //title check
      if(empty($_POST['title']))
      {
          $error = "Please enter your Book title...";
          return false;
      }
      else if(strlen($_POST['title']) > 30)
      {
          $error = "title name too long";
          return false;
      }
  
      //author name check
      if(empty($_POST['author']))
      {
          $error = "Please enter the author name...";
          return false;
      }
      else if(strlen($_POST['author']) > 20)
      {
          $error = "author name too long";
          return false;
      }
  
      return true;
  }

  
  if (isset($_POST['id'])&&
      isset($_POST['title'])&&
      isset($_POST['author'])
      )
      
  {
      $id     = assign_data($conn, 'id');
      $title  = assign_data($conn, 'title');
      $author = assign_data($conn, 'author');
      
  
      //validation process
  
      $validated = validate();
  
      if ($validated) {
        $query  = "INSERT INTO table1 VALUES ('$id', '$title', '$author')";
        $result = $conn->query($query);
        if (!$result) {
            echo "<br><br>INSERT failed: $query<br>" . $conn->error . "<br><br>";
        }
      } 
  }

print<<<_HTML
  <form action="" method="post">
  
    <label for "id"> Book id: (3 digits)</label><br>
    <input type="text" name="id" value = ""><br>
  
    <label for "title"> Book title: (up to 30 characters)</label><br>
    <input type="text" name="title" value = ""><br>

    <label for "id"> Author name: (up to 20 characters)</label><br>
    <input type="text" name="author" value = ""><br>


    <input type="submit" value="ADD RECORD">
  </form>
_HTML;
 
//IF VALIDATION PICKS UP ERRORS, PRINT THIS UNDER THE FORM.

echo "<p>";
if(isset($error))
    { echo "<p><em>$error</em></p>";} //Displays error message if error exists.}
echo "</p>";


if (isset($_POST['delete_btn']) && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Delete the row from the database
    $delete_query = "DELETE FROM table1 WHERE id = '$delete_id'";
    $delete_result = $conn->query($delete_query);

    if ($delete_result) {
        echo "<p>Row with ID $delete_id has been deleted.</p>";
    } else {
        echo "<p>Error deleting row with ID $delete_id.</p>";
    }
}

function validEdit()
{
    global $error;
    global $conn;
    $error = "Book successfully edited.";

    //title check
    if(empty($_POST['edit_title']))
    {
        $error = "Please enter your Book title...";
        return false;
    }
    else if(strlen($_POST['edit_title']) > 30)
    {
        $error = "title name too long";
        return false;
    }

    //author name check
    if(empty($_POST['edit_author']))
    {
        $error = "Please enter the author name...";
        return false;
    }
    else if(strlen($_POST['edit_author']) > 20)
    {
        $error = "author name too long";
        return false;
    }

    return true;
}


 
 echo "<table id='book_table'>";
 echo "<tr>";
 echo "<th>Book id</th>";
 echo "<th>Title</th>";
 echo "<th>Author</th>";
 echo "<th class = 'ed1'>Edit</th>";
 echo "<th class = 'del1'>Delete</th>";
 echo "</tr>";

 

if (isset($_POST['edit_btn']) && isset($_POST['edit_id'])) {
  $edit_id = $_POST['edit_id'];


  // Fetch the row from the database for editing
  $fetch_query = "SELECT * FROM table1 WHERE id = '$edit_id'";
  $fetch_result = $conn->query($fetch_query);


  if ($fetch_result->num_rows > 0) {
    $row = $fetch_result->fetch_assoc();
    $edit_id = $row['id'];
    $edit_title = $row['title'];
    $edit_author = $row['author'];
  
    // Show the fetched values in the "Edit Record" section
    echo '<form action="" method="post">';
    echo '  <label for="id"> Book id:</label><br>';
    echo '  <input type="text" name="edit_id" value="' . $edit_id . '" readonly><br>';
    echo '  <label for="title"> Book title:</label><br>';
    echo '  <input type="text" name="edit_title" value="' . $edit_title . '"><br>';
    echo '  <label for="author"> Author name:</label><br>';
    echo '  <input type="text" name="edit_author" value="' . $edit_author . '"><br>';
    echo '  <input type="submit" name="update_btn" value="UPDATE">';
    echo '</form>';
  }
  
}

if (isset($_POST['update_btn']) &&
    isset($_POST['edit_id']) && 
    isset($_POST['edit_title']) &&
    isset($_POST['edit_author']))
{
    $id1 = $_POST['edit_id'];
    $title1 = $_POST['edit_title'];
    $author1 = $_POST['edit_author'];
    

   $validEditForm = validEdit(); // Perform validation for the edit operation

    if($validEditForm){
      $edit_query    = " UPDATE table1 SET title = '$title1', author = '$author1' WHERE id = '$id1'";
      $edit_result = $conn->query($edit_query);

      if ($edit_result) {
        echo "<p>Row has been edited.</p>";
    } else {
        echo "<p>Error deleting row.</p>";
    }
  }
}



$query = "SELECT * FROM table1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
      echo '  <tr>';
      echo '      <td>' . $row['id'] . '</td>';
      echo '      <td>' . $row['title'] . '</td>';
      echo '      <td>' . $row['author'] . '</td>';
      echo '      <td>';
      echo '          <form method="POST" action="">';
      echo '              <input type="hidden" name="edit_id" value="' . $row['id'] . '">';
      echo '              <button type="submit" name="edit_btn">Edit</button>';
      echo '          </form>';
      echo '      </td>';
      echo '      <td>';
      echo '          <form method="POST" action="">';
      echo '              <input type="hidden" name="delete_id" value="' . $row['id'] . '">';
      echo '              <button type="submit" name="delete_btn">Delete</button>';
      echo '          </form>';
      echo '      </td>';
      echo '  </tr>';
  }
} else {
  echo '  <tr><td>No records found.</td></tr>';
}

echo '</table>';

$result->close();
$conn->close();


function assign_data($conn, $var)
{
  return $conn->real_escape_string($_POST[$var]);
}
?>

</body>
</html>


