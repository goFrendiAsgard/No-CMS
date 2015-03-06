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
            $hex_color = $r.$g.$b.'';
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

function find_dominant_color($filename, $count = 8, $distance_threshold = 20){  
    $max_threshold  = 10000;
    $histogram      = create_histogram($filename);
    if($count>count($histogram)){
        $count = count($histogram);
    }
    array_multisort($histogram, SORT_DESC, $histogram);
    // get chosen colors
    $chosen_colors = array();
    $secondary_colors = array();
    foreach($histogram as $color=>$pixel_count){
        if(is_int($color)){continue;}
        $current_color_component = decompose_color($color);
        $is_chosen = TRUE;
        $is_secondary = FALSE;
        // if the current color is similar to those on chosen, don't choose it
        foreach($chosen_colors as $chosen_color){
            $chosen_color_component = decompose_color($chosen_color);
            for($i=0; $i<3; $i++){
                if(abs($chosen_color_component[$i] - $current_color_component[$i]) < $distance_threshold){
                    $is_chosen = FALSE;
                    break;
                }
            }
            if($is_chosen){
                $rg_chosen = $chosen_color_component[0] - $chosen_color_component[1];
                $rb_chosen = $chosen_color_component[0] - $chosen_color_component[2];
                $rg_current = $current_color_component[0] - $current_color_component[1];
                $rb_current = $current_color_component[0] - $current_color_component[2];
                if(abs($rg_chosen - $rg_current) < $distance_threshold && abs($rb_chosen - $rb_current) < $distance_threshold){
                    $is_secondary = TRUE;
                    $is_chosen = FALSE;
                }
            }else{
                break;
            }
        }
        // add to chosen_colors or dominant_colors
        if($is_chosen){
            $chosen_colors[] = $color;
        }else if($is_secondary && count($secondary_colors) < $count){
            $secondary_colors[] = $color;
        }
        if(count($chosen_colors) >= $count){
            break;
        }
    }
    return array_merge($chosen_colors, $secondary_colors);
}