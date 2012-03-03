<?php

    /* Example usage of the Amazon Product Advertising API */
    include("amazon_api_class.php");

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
    
    print_r($result);
    
    echo "Sales Rank : {$result->Items->Item->SalesRank}<br>";
    echo "ASIN : {$result->Items->Item->ASIN}<br>";
    echo "<br><img src=\"" . $result->Items->Item->MediumImage->URL . "\" /><br>";
    

?>