<?php
function imagecreatefromfile( $filename ) {
    if (!file_exists($filename)) {
        throw new InvalidArgumentException('File "'.$filename.'" not found.');
    }
    switch ( strtolower( pathinfo( $filename, PATHINFO_EXTENSION ))) {
        case 'jpeg':
        case 'jpg':
            return imagecreatefromjpeg($filename);
        break;

        case 'png':
            return imagecreatefrompng($filename);
        break;

        case 'gif':
            return imagecreatefromgif($filename);
        break;

        default:
            throw new InvalidArgumentException('File "'.$filename.'" is not valid jpg, png or gif image.');
        break;
    }
}

function create_histogram($filename){
    $img        = imagecreatefromfile($filename);
    $size       = getimagesize($filename);
    $width      = $size[0];
    $height     = $size[1];
    $thumbnail_gd_image = imagecreatetruecolor(100, 100);
    imagecopyresampled($thumbnail_gd_image, $img, 0, 0, 0, 0, 100, 100, $width, $height);
    $img        = $thumbnail_gd_image;
    $histogram  = array();
    for($x=0; $x<100; $x++){
        for($y=0; $y<100; $y++){
            $rgb = imagecolorat($img, $x, $y);
            $colors = imagecolorsforindex($img, $rgb);
            $r = str_pad(dechex($colors['red']),2,"0",STR_PAD_LEFT);
            $g = str_pad(dechex($colors['green']),2,"0",STR_PAD_LEFT);
            $b = str_pad(dechex($colors['blue']),2,"0",STR_PAD_LEFT);
            $hex_color = $r.$g.$b;
            if(!array_key_exists($hex_color, $histogram)){
                $histogram[$hex_color] = 0;
            }
            $histogram[$hex_color] ++;
        }
    }
    return $histogram;
}

function decompose_color($hex){
    $r = hexdec(substr($hex,0,2));
    $g = hexdec(substr($hex,2,2));
    $b = hexdec(substr($hex,4,2));
    return array($r,$g,$b);
}

function find_dominant_color($filename, $count = 7, $distance_threshold = array(5,5,5)){  
    $max_threshold  = 10000;
    $histogram      = create_histogram($filename);
    if($count>count($histogram)){
        $count = count($histogram);
    }
    $dominant_color = array();
    $no_more_color = FALSE;
    while(!$no_more_color && count($dominant_color)<$count){
        $max_count  = 0;
        $new_colors = array();
        $no_more_color = TRUE;
        foreach($histogram as $key=>$value){
            if($value >= $max_threshold){
                continue;
            }
            // if the distance is too small, ignore it
            $key_decomposed = decompose_color($key);
            $in = TRUE;
            foreach($dominant_color as $existing){
                $existing_decomposed = decompose_color($existing);
                $bad = FALSE;
                for($i=0; $i<3; $i++){
                    $distance = abs($existing_decomposed[$i] - $key_decomposed[$i]);
                    if($distance < $distance_threshold[$i]){
                        $bad = $bad || TRUE;
                    }else{
                        $bad = $bad || FALSE;
                    }
                }
                if($bad){
                    $in = FALSE;
                    break;
                }
            }
            foreach($new_colors as $existing){
                $existing_decomposed = decompose_color($existing);
                $bad = FALSE;
                for($i=0; $i<3; $i++){
                    $distance = abs($existing_decomposed[$i] - $key_decomposed[$i]);
                    if($distance < $distance_threshold[$i]){
                        $bad = $bad || TRUE;
                    }else{
                        $bad = $bad || FALSE;
                    }
                }
                if($bad){
                    $in = FALSE;
                    break;
                }
            }
            if(!$in){
                continue;
            }
            if($value > $max_count){
                $max_count = $value;
                $new_colors = array($key);
            }else if($value == $max_count){
                $new_colors[] = $key;
                $no_more_color = FALSE;
            }
        }
        $max_threshold = $max_count;
        foreach($new_colors as $color){
            $dominant_color[] = $color;
        }
    }
    return $dominant_color;
}