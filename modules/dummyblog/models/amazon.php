<?php

/**
 * Description of amazon
 *
 * @author gofrendi
 */
class Amazon extends CMS_Model{
    //put your code here
    public function get_product(){
        include(APPPATH.'../modules/dummyblog/API/AmazonAPI/amazon_api_class.php');
        $obj = new AmazonProductAPI();
    
        try
        {
            $result = $obj->searchProducts("X-Men Origins",
                                           AmazonProductAPI::DVD,
                                           "TITLE");
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
        return $result;
    }
}

?>
