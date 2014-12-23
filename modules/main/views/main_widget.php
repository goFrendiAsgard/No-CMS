<style type="text/css">
    <?php foreach($undeleted_id as $id){ ?>
    tr[rowid="<?=$id?>"] a.delete-row{
        display:none;
    }
    <?php } ?>
</style>
<?php echo $output; ?>
<script type="text/javascript" src="{{ module_base_url }}assets/scripts/navigation.js"></script>