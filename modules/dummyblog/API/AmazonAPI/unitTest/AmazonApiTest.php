<?php

require_once '../amazon_api_class.php';
require_once 'PHPUnit/Framework.php';

class AmazonApiTest extends PHPUnit_Framework_TestCase
{
    public function testapiTest()
    {
        
        $obj = new AmazonProductAPI();
    
        try 
        {
            $result = $obj->searchProducts("spice girls", AmazonProductAPI::DVD, "TITLE");
            $data = $result->Items->Item->ItemAttributes->Title;
            echo $data . "\n";
            $this->assertNotNull($data);
        } catch(Exception $e) 
        {
            echo $e->getMessage();
        }
        
        try 
        {
            $result = $obj->searchProducts("024543602859", AmazonProductAPI::DVD, "UPC");
            $data = $result->Items->Item->ItemAttributes->Title;
            echo $data . "\n";
            $this->assertNotNull($data);
        } catch(Exception $e) 
        {
            echo $e->getMessage();
        }
        
        try 
        {
            $result = $obj->getItemByUpc("014633190168", AmazonProductAPI::GAMES);
            $data = $result->Items->Item->ItemAttributes->Title;
            echo $data . "\n";
            $this->assertNotNull($data);
        } catch(Exception $e) 
        {
            echo $e->getMessage();
        }
        
        try 
        {
            $result = $obj->getItemByAsin("B001AVCFK6", AmazonProductAPI::DVD);
            $data = $result->Items->Item->ItemAttributes->Title;
            echo $data . "\n";
            $this->assertNotNull($data);
        } catch(Exception $e) 
        {
            echo $e->getMessage();
        }
        
        try 
        {
            $result = $obj->getItemByKeyword("tom petty", AmazonProductAPI::MUSIC);
            $data = $result->Items->Item->ItemAttributes->Title;
            echo $data . "\n";
            $this->assertNotNull($data);
        } catch(Exception $e) 
        {
            echo $e->getMessage();
        }
    }
}

?>