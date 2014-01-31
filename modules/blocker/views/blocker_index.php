<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<h4>Script</h4>
<form method="POST" class="form">
    <div class="form-group">
        <label>Blocker Script</label>
        <textarea name="script" class="form-control" style="height:500px;"><?php echo $script; ?></textarea>
    </div>
    <div class="form-group">
        <input type="submit" name="submit" value="Apply blocker rule" class="btn btn-primary" />
    </div>
</form>
<hr />
<h4>Manage</h4>
<?php echo $menu; ?>
