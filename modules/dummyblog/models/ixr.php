<?php

/**
 * Description of ixr
 *
 * @author gofrendi
 */
class ixr extends CMS_Model{
    public function post(){
        include("../wp-includes/class-IXR.php");
        
        $client = new IXR_Client('http://www.example.com/xmlrpc.php');


        if (!$client->query('wp.getCategories','', 'admin','password')) {
            die('An error occurred - '.$client->getErrorCode().":".$client->getErrorMessage());
        }

        $response = $client->getResponse();
        $content['title'] = 'Test Draft Entry using MetaWeblog API';
        $content['categories'] = array($response[1]['categoryName']);
        $content['description'] = '<p>Hello World!</p>';
        if (!$client->query('metaWeblog.newPost','', 'admin',’password’, $content, false)) {
            die('An error occurred - '.$client->getErrorCode().":".$client->getErrorMessage());
        }

        echo $client->getResponse();    //with Wordpress, will report the ID of the new post
    }
}

?>
