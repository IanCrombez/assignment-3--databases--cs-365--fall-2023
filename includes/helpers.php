<?php

require_once 'config.php';

function search($search) 
{
    try
    {
        $db = new PDO(
            "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8",
            DBUSER, DBPASS
        );
        
        $encryptionKeyFind = "SET @Key = unhex('" . DBKEY . "')";
        $db->exec($encryptionKeyFind);
        //i hate this formating but its as clean as i can get
        $select_query = 
            "
            SELECT
                sites.site_name,
                sites.site_url,
                accounts.username,
                CONVERT(AES_DECRYPT(accounts.password, @Key) USING utf8) AS real_password,
                accounts.email,
                accounts.comment
            From sites JOIN accounts ON sites.site_id = accounts.site_id
            WHERE
                sites.site_name LIKE :search
                OR accounts.username LIKE :search
                OR CONVERT(AES_DECRYPT(accounts.password, @Key) USING utf8) LIKE :search
                OR accounts.email LIKE :search
                OR accounts.comment LIKE :search
            ";

            
        $statement = $db->prepare($select_query);
        $statement->bindValue(':search', "%" . $search . "%", PDO::PARAM_STR);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        //find nothing
        if (count($results) === 0)
        {
            return 0;
        } 
        //find literaly anything
        else
        {
            //make table not 1 line (remove if you want unreadable)
            echo "<table>\n";
            echo "<thead>\n";
            echo "<tr>\n";
            //put in all the collom names
            foreach ($results[0] as $key => $value) 
            {
                echo "<th>" . htmlspecialchars($key) . "</th>\n";
            }

            echo "</tr>\n";
            echo "</thead>\n";
            echo "<tbody>\n";
            //put in data
            foreach ($results as $row) 
            {
                echo "<tr>\n";
                foreach ($row as $key => $value) 
                {
                    if ($key === 'real_password') 
                    {
                        echo "<td>" . ($value !== null ? htmlspecialchars($value) : 'N/A') . "</td>\n";
                    } 
                    else 
                    {
                        echo "<td>" . htmlspecialchars($value) . "</td>\n";
                    }
                }
                echo "</tr>\n";
            }

            echo "</tbody>\n";
            echo "</table>\n";
        }
    } 
    //this is here to tell me if it broke and becuase a try needs a catch
    catch (PDOException $e) 
    {
        echo '<p>the search funtion broke </p>' . '\n';
        exit;
    }
}




function update($table, $current_field, $new_value, $find_field, $pattern) 
{
    try 
    {
        //same as the rest
        $db = new PDO(
            "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8",
            DBUSER, DBPASS
        );
        
        $encryptionKeyFind = "SET @Key = unhex('" . DBKEY . "')";
        $db->exec($encryptionKeyFind);
        //update password based on passord
        if ($current_field === 'password') 
        {
            $update_query = "UPDATE {$table} SET {$current_field} = AES_ENCRYPT(:new_value, @Key) WHERE {$find_field} = :pattern";
        }
        //update current field based on passsowrd 
        else if ($find_field === 'password') 
        {
            $update_query = "UPDATE {$table} SET {$current_field} = :new_value WHERE {$find_field} = AES_ENCRYPT(:pattern, @Key)";
        }
        //upadte other based on other
        else 
        {
            $update_query = "UPDATE {$table} SET {$current_field} = :new_value WHERE {$find_field} = :pattern";
        }
        
        $statement = $db->prepare($update_query);
        $statement->bindParam(':new_value', $new_value);

        if ($current_field === 'password' || $find_field === 'password') 
        {
            $statement->bindParam(':pattern', $pattern, PDO::PARAM_STR);
        }
        else
        {
            $statement->bindParam(':pattern', $pattern);
        }

        $statement->execute();
    }
    catch (PDOException $e) 
    {
        echo '<p>The update function dint work. </p>' . "\n";
        exit;
    }
}




function insert($site_name, $site_url, $username, $password, $email, $comment) 
{
    try 
    {
        //same as the rest
        $db = new PDO(
            "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8",
            DBUSER, DBPASS
        );
        $encryptionKeyFind = "SET @Key = unhex('" . DBKEY . "')";
        $db->exec($encryptionKeyFind);
        //make the request to update the database
        $encryptedPassword = "AES_ENCRYPT(:password, @Key)";
        $insert_query = "
            INSERT INTO sites (site_name, site_url)
            VALUES (:site_name, :site_url);
            INSERT INTO accounts (site_id, username, password, email, comment)
            SELECT LAST_INSERT_ID(), :username, {$encryptedPassword}, :email, :comment;
        ";
        //do all the updating
        $statement = $db->prepare($insert_query);
        $statement->bindParam(':site_name', $site_name);
        $statement->bindParam(':site_url', $site_url);
        $statement->bindParam(':username', $username);
        $statement->bindParam(':password',$password);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':comment', $comment);
        $statement->execute();

    } 
    //if something fails
    catch (PDOException $e) 
    {
        echo '<p>The insert function didnt work. </p>' . "\n";
        exit;
    }
}



function delete($siteName, $username) {
    try 
    {
        //same as the rest
        $db = new PDO(
            "mysql:host=" . DBHOST . ";dbname=" . DBNAME . ";charset=utf8",
            DBUSER, DBPASS
        );
        
        $encryptionKeyFind = "SET @Key = unhex('" . DBKEY . "')";
        $db->exec($encryptionKeyFind);

        $deleteAccountsQuery = "DELETE FROM accounts WHERE site_id IN (SELECT site_id FROM sites WHERE site_name = :siteName) And username IN (SELECT username WHERE username = :username)";
        $statementAccounts = $db->prepare($deleteAccountsQuery);
        $statementAccounts->bindParam(':siteName', $siteName, PDO::PARAM_STR);
        $statementAccounts->bindParam(':username', $username, PDO::PARAM_STR);
        $statementAccounts->execute();
    } 
    catch (PDOException $e) 
    {
        echo '<p>The delete function dint work. </p>' . "\n";
        exit;
    }
}


