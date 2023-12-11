<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ian's Password Manager</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<header>
    <h1>Ian's Password Manager</h1>
</header>

<?php
const SEARCH = 'SEARCH';
const UPDATE = 'UPDATE';
const INSERT = 'INSERT';
const DELETE = 'DELETE';

require_once "includes/config.php";
require_once "includes/helpers.php";

$option = (isset($_POST['submitted']) ? $_POST['submitted'] : null);

if (isset($_POST['refresh'])) 
{
    $_SESSION['refresh'] = true;
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
if (!isset($_SESSION['refresh'])) 
{
    $_SESSION['refresh'] = false;
}

if ($option != null) 
{
    //these are all the function calls
    switch ($option) 
    {
        case SEARCH:
            if (empty($_POST['search'])) 
            {
                echo '<div id="error">Search query empty. Please try again.</div>' . "\n";
            } 
            else {
                $result = search($_POST['search']);
                if ($result === 0) 
                {
                    echo '<div id="error">Nothing found.</div>' . "\n";
                }
            }
            break;

        case UPDATE:
            if (empty($_POST['new-attribute']) || empty($_POST['pattern'])) 
            {
                echo '<div id="error">One or both fields were empty, ' . 'but both must be filled out. Please try again.</div>' . "\n";
            } 
            else 
            {
                update($_POST['table'], $_POST['current-attribute'], $_POST['new-attribute'], $_POST['query-attribute'], $_POST['pattern']);
            }
            break;

        case INSERT:
            if (empty($_POST['site_name']) || empty($_POST['site_url']) || empty($_POST['username']) || empty($_POST['password']) || empty($_POST['email'])) 
            {
                echo '<div id="error">At least one field in your insert request ' . 'is empty. Please try again.</div>' . "\n";
            } 
            else 
            {
                insert(
                    $_POST['site_name'],
                    $_POST['site_url'],
                    $_POST['username'],
                    $_POST['password'],
                    $_POST['email'],
                    $_POST['comment']
                );
            }
            break;

        case DELETE:
            if (empty($_POST['site_name']) || empty($_POST['username'])) 
            {
                echo '<div id="error">Please enter a site name or username to delete.</div>' . "\n";
            } 
            else 
            {
                $deleted = delete($_POST['site_name'], $_POST['username']);
            }
            break;
    }
}
?>

<!--html start-->

<!--refresh page-->
<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <button type="submit" name="refresh">Refresh</button>
</form>


<!--search-->
<section>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Search</legend>
            <input type="text" name="search" autofocus required>
            <input type="hidden" name="submitted" value="SEARCH">
            <p><input type="submit" value="Search"></p>
        </fieldset>
    </form>
</section>


<!--update-->
<section>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Update Website</legend>
            Update
            <select name="table" id="table">
                <option value="sites">sites</option>
            </select>
            <br>
            new <select name="current-attribute" id="current-attribute">
                <option value="site_name">site_name</option>
                <option value="site_url">site_url</option>
            </select>
            = <input type="text" name="new-attribute" placeholder="New Input" required>
            <br>
            where <select name="query-attribute" id="query-attribute">
                <option value="site_name">site_name</option>
                <option value="site_url">site_url</option>
            </select>
            = <input type="text" name="pattern" placeholder="Current Value" required>
            <input type="hidden" name="submitted" value="UPDATE">
            <p><input type="submit" value="Update"></p>
            <br>
        </fieldset>
    </form>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Update Account</legend>
            Update <select name="table" id="table">
                <option value="accounts">accounts</option>
            </select>
            <br>
            new <select name="current-attribute" id="current-attribute">
                <option value="username">username</option>
                <option value="password">password</option>
                <option value="email">email</option>
                <option value="comment">comment</option>
            </select>
            = <input type="text" name="new-attribute" placeholder="New Input" required>
            <br>
            where<select name="query-attribute" id="query-attribute">
                <option value="username">username</option>
                <option value="password">password</option>
                <option value="email">email</option>
                <option value="comment">comment</option>
            </select>
            = <input type="text" name="pattern" placeholder="Current Value" required>
            <input type="hidden" name="submitted" value="UPDATE">
            <p><input type="submit" value="Update"></p>
        </fieldset>
    </form>
</section>



<!--insert-->
<section>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend>Insert</legend>
            <!--name-->
            <label for="site_name">Website Name:</label>
            <input type="text" name="site_name" placeholder="Website Name" required>
            <br>
            <!--url-->
            <label for="site_url">Website URL:</label>
            <input type="text" name="site_url" placeholder="Website URL" required>
            <br>
            <!--username-->
            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="Username" required>
            <br>
            <!--password-->
            <label for="password">Password:</label>
            <input type="password" name="password" placeholder="Password" required>
            <br>
            <!--email-->
            <label for="email">Email:</label>
            <input type="email" name="email" placeholder="Email" required>
            <br>
            <!--comment-->
            <label for="comment">Comment:</label>
            <textarea name="comment" placeholder="Comment"></textarea>
            <br>
            <!---->
            <input type="hidden" name="submitted" value="INSERT">
            <br>
            <!--button-->
            <p><input type="submit" value="Insert"></p>
        </fieldset>
    </form>
</section>


<!--delete-->
<section>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <!--site name-->
            <legend>Delete Account</legend>
            <label for="site_name">Website Name:</label>
            <input type="text" name="site_name" placeholder="Website Name" required>
            <br>
            <!--username-->
            <label for="username">Username:</label>
            <input type="text" name="username" placeholder="username" required>
            <br>
            <!--button-->
            <input type="hidden" name="submitted" value="DELETE">
            <p><input type="submit" value="Delete"></p>
        </fieldset>
    </form>
</section>

</body>

</html>