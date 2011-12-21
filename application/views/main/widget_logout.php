Welcome, <?php echo $user_name; ?><br />
<?php echo anchor('main/logout', 'Logout'); ?>
<?php
    /**    
    $ci =& get_instance();    
    echo '<pre>';
    echo var_dump($_COOKIE);
    echo var_dump($ci->session->all_userdata());
    echo var_dump(session_id());
    echo var_dump(session_name());
    echo '</pre>';
     * 
     */
?>