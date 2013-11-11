[Up](../tutorial.md)

Extended Login
==============

Usually No-CMS authentication system is more than enough, since it can also contains third-party-authentication (e.g: facebook, twitter, etc). 

However, there are some cases when you need to use an already exist user table. Let's say you have already install joomla, drupal, wordpress, or other CMS that has it's own user authentication system. Or maybe you have already build a system with authentication system before decide to use No-CMS. You need the old system to keep run as usual, and you want the old authentication system can be used in No-CMS. In this case, you will need No-CMS's `extended login` feature. To use this feature, you need some PHP-programming capability.

Let's say you already has a table with `user_name`, `email`, `password` and `real_name` like this one:

| user_name  | email                      | password | real_name |
| :--------- | :------------------------- | :------- | :-------- |
| graywizard | gandalf@middle-earth.com   | wizard   | Gandalf   |
| strider    | aragorn@gondor.com         | king     | Aragorn   |

The table was named `user_table` and was located at another database-schema named `old_db`. You want the users of the old system (graywizard and strider) can login to No-CMS with their own old user-name and password.

First, open up `/application/config/cms_extended_login.php`. If the file is `read-only`, please change the permission by using `chmod 766` first. There should be a function named `extended_login`

```php
    function extended_login($user_name, $password){
        // if the login should be success, you can 
        //  - return TRUE
        //  - return array('user_real_name', 'user_email')
        return FALSE;
    }
```

To make the old-validation system to work, you need to change the function a bit. Remember, the old system's database schema is `old_db` and the table name is `user_table`.

```php
    function extended_login($user_name, $password){
        // connect to localhost server by using root user with no password
        mysql_connect('localhost', 'root', '');
        // use old_db
        mysql_select_db('old_db');
        // run the query to see if there is a match user
        $result = mysql_query("SELECT real_name, email 
            FROM user_table WHERE user_name='$user_name' AND password='$password'");
        if(mysql_num_rows($result)>0){
            $row = mysql_fetch_array($result);
            // if the table doesn't have real_name & email field, just return TRUE otherwise, it is better to return an array
            return array($row['real_name'], $row['email']);
        }
        // fail to login
        return FALSE;
    }
```

Now, your old system's user (of middle earth) can use No-CMS system.