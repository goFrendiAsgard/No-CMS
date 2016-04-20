<?php
    if($message != ''){
        $class = $success? 'alert-success': 'alert-danger';
        echo '<div class="alert '.$class.'">'.$message.'</div>';
    }
?>
<form class="form" enctype="multipart/form-data" method="post">
    <div class="form-group">
        <label>Upload Wordpress XML</label><br />
        <input type="file" name="file" /> <br />
    </div>
    <button class="btn btn-primary">Import</button>
</form>
