        ////////////////////////////////////////////////////////////////////////
        // ALSO DELETE CORESPONDING <?php echo $detail_table_name.PHP_EOL; ?>
        ////////////////////////////////////////////////////////////////////////
        $this->db->delete($this->t('<?php echo $detail_table_name; ?>'),
              array('<?php echo $detail_foreign_key_name; ?>'=>$primary_key));
