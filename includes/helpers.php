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
        echo 'the search funtion broke',
        exit;
    }
}
