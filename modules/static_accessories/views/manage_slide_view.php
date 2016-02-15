<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style type="text/css">
    a.image-thumbnail img{
        max-width:200px;
    }
</style>
<?php if($state == 'list' || $state == 'success'){ ?>
    <h3>Slideshow Configuration</h3>
    <form method="post" class="form form-horizontal">
        <div class="form-group">
            <label class="control-label col-md-4">Slideshow Height (px) </label>
            <div class="controls col-md-8">
                <input class="form-control" name="static_accessories_slide_height"
                    value="<?php echo $config['static_accessories_slide_height']; ?>" />
                <p class="help-block">Height in pixel or left it blank</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4">Parralax </label>
            <div class="controls col-md-8">
                <select class="form-control" name="static_accessories_slide_parralax">
                <?php
                    $options = array('TRUE' => 'True', 'FALSE' => 'False');
                    foreach($options as $key=>$value){
                        $selected = $config['static_accessories_slide_parralax'] == $key? 'selected' : '';
                        echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                    }
                ?>
                </select>
                <p class="help-block">True for parralax</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4">Hide on Smallscreen </label>
            <div class="controls col-md-8">
                <select class="form-control" name="static_accessories_slide_hide_on_smallscreen">
                <?php
                    $options = array('TRUE' => 'True', 'FALSE' => 'False');
                    foreach($options as $key=>$value){
                        $selected = $config['static_accessories_slide_hide_on_smallscreen'] == $key? 'selected' : '';
                        echo '<option '.$selected.' value="'.$key.'">'.$value.'</option>';
                    }
                ?>
                </select>
                <p class="help-block">True for parralax</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4">Image Size </label>
            <div class="controls col-md-8">
                <input class="form-control" name="static_accessories_slide_image_size"
                    value="<?php echo $config['static_accessories_slide_image_size']; ?>" />
                <p class="help-block">Image size (e.g: "cover", "contain", "auto", "50%")</p>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-4">Image Top </label>
            <div class="controls col-md-8">
                <input class="form-control" name="static_accessories_slide_image_top"
                    value="<?php echo $config['static_accessories_slide_image_top']; ?>" />
                <p class="help-block">Image top in pixel or left it blank</p>
            </div>
        </div>
        <div class="form-group">
            <div class="controls col-md-12">
                <button name="apply" class="btn btn-primary">Save Configuration</button>
            </div>
        </div>
    </form>
    <hr />
    <h3>Slideshow Images</h3>
<?php
}

$asset = new Cms_asset();
foreach($css_files as $file){
	$asset->add_css($file);
}
echo $asset->compile_css();

foreach($js_files as $file){
	$asset->add_js($file);
}
echo $asset->compile_js();
echo $output;
?>
