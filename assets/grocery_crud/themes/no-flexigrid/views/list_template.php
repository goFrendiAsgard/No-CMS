<?php
	$this->set_css($this->default_theme_path.'/no-flexigrid/css/flexigrid.css');
	$this->set_js_lib($this->default_javascript_path.'/'.grocery_CRUD::JQUERY);

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
	$this->set_js_lib($this->default_javascript_path.'/common/lazyload-min.js');

	if (!$this->is_IE7()) {
		$this->set_js_lib($this->default_javascript_path.'/common/list.js');
	}

	$this->set_js($this->default_theme_path.'/no-flexigrid/js/cookies.js');
	$this->set_js($this->default_theme_path.'/no-flexigrid/js/flexigrid.js');
	$this->set_js($this->default_theme_path.'/no-flexigrid/js/jquery.form.js');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.numeric.min.js');
	$this->set_js($this->default_theme_path.'/no-flexigrid/js/jquery.printElement.min.js');

	/** Fancybox */
	$this->set_css($this->default_css_path.'/jquery_plugins/fancybox/jquery.fancybox.css');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.fancybox-1.3.4.js');
	$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.easing-1.3.pack.js');

	/** Jquery UI */
	$this->load_js_jqueryui();

	if(isset($_GET['from'])){
		// add "from" to "add_url"
		if(strpos($add_url, '&from=') === FALSE && strpos($add_url, '?from=') === FALSE){
			if(strpos($add_url, '?') !== FALSE){
				$add_url .= '&from='.$_GET['from'];
			}else{
				$add_url .= '?from='.$_GET['from'];
			}
		}
		// add "from" to "ajax_list_url"
		if(strpos($ajax_list_url, '&from=') === FALSE && strpos($ajax_list_url, '?from=') === FALSE){
			if(strpos($ajax_list_url, '?') !== FALSE){
				$ajax_list_url .= '&from='.$_GET['from'];
			}else{
				$ajax_list_url .= '?from='.$_GET['from'];
			}
		}
	}
?>
<script type='text/javascript'>
	var base_url = '<?php echo base_url();?>';

	var subject = '{{ language:<?php echo $subject?> }}';
	var ajax_list_info_url = '<?php echo $ajax_list_info_url; ?>';
	var unique_hash = '<?php echo $unique_hash; ?>';

	var message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";

</script>
<div id='list-report-error' class='report-div error alert alert-danger'></div>
<div id='list-report-success' class='report-div success report-list alert alert-success ' <?php if($success_message !== null){?>style="display:block"<?php }?>><?php
if($success_message !== null){?>
	<p><?php echo $success_message; ?></p>
<?php }
?></div>
<div class="flexigrid container col-md-12" data-unique-hash="<?php echo $unique_hash; ?>">
	<div id="hidden-operations" class="hidden-operations row"></div>
	<div id='main-table-box' class="main-table-box row">

    <?php echo form_open( $ajax_list_url, 'method="post" id="filtering_form" class="filtering_form" autocomplete = "off" data-ajax-list-info-url="'.$ajax_list_info_url.'"'); ?>

    <div class="quickSearchBox form-inline row" id='quickSearchBox'>

        <?php if(isset($search_form_components)) echo $search_form_components; ?>

        <?php if(isset($unset_default_search) && $unset_default_search) echo '<div style="display:none">'; ?>
        <div class="form-group">
            <input type="text" class="qsbsearch_fieldox search_text form-control" name="search_text" id="search_text" placeholder="<?php echo $this->l('list_search');?>">
        </div>
        <div class="form-group">
            <select name="search_field" id="search_field" class="form-control">
                <option value=""><?php echo $this->l('list_search_all');?></option>
                <?php foreach($columns as $column){
                        if(isset($unsearchable_field)){
                            if(in_array($column->field_name, $unsearchable_field)){
                                continue;
                            }
                        }
                ?>
                <option value="<?php echo $column->field_name?>">{{ language:<?php echo $column->display_as?> }}&nbsp;&nbsp;</option>
                <?php }?>
            </select>
        </div>
        <div class="form-group">
            <input type="button" value="<?php echo $this->l('list_search');?>" class="crud_search btn btn-primary" id='crud_search'>
        </div>
        <div class="form-group">
            <input type="button" value="<?php echo $this->l('list_clear_filtering');?>" id='search_clear' class="search_clear btn btn-primary">
        </div>
        <?php if(isset($unset_default_search) && $unset_default_search) echo '</div>'; ?>

    </div>

	<?php if(!$unset_add || !$unset_export || !$unset_print){?>
	<div class="tDiv row">
		<div class="tDiv3  ">
            <?php if(!$unset_add){?>
            <a href='<?php echo $add_url?>' title='<?php echo $this->l('list_add'); ?> {{ language:<?php echo $subject?> }}' class='add-anchor add_button btn btn-default'>
            <div class="fbutton">
                <div>
                    <span class="add"><i class="glyphicon glyphicon-plus-sign"></i>&nbsp;<?php echo $this->l('list_add'); ?> {{ language:<?php echo $subject?> }}</span>
                </div>
            </div>
            </a>&nbsp;
            <div class="btnseparator"></div>
            <?php }?>
			<?php if(!$unset_export) { ?>
            <a class="export-anchor btn btn-default" data-url="<?php echo $export_url; ?>" target="_blank">
                <div class="fbutton">
                    <div>
                        <span class="export"><i class="glyphicon glyphicon-share"></i>&nbsp;<?php echo $this->l('list_export');?></span>
                    </div>
                </div>
            </a>&nbsp;
            <div class="btnseparator"></div>
            <?php } ?>
            <?php if(!$unset_print) { ?>
            <a class="print-anchor btn btn-default" data-url="<?php echo $print_url; ?>">
                <div class="fbutton">
                    <div>
                        <span class="print"><i class="glyphicon glyphicon-print"></i>&nbsp;<?php echo $this->l('list_print');?></span>
                    </div>
                </div>
            </a>
            <div class="btnseparator"></div>
            <?php }?>
		</div>
		<div class='clear'></div>
	</div>
	<?php }?>

    <div id='ajax_list' class="ajax_list row">
        <?php echo $list_view?>
    </div>

    <div class="pDiv  ">
        <div class="pDiv2  ">
            <div class="pGroup">
                <select name="per_page" id='per_page' class="per_page">
                    <?php foreach($paging_options as $option){?>
                        <option value="<?php echo $option; ?>" <?php if($option == $default_per_page){?>selected="selected"<?php }?>><?php echo $option; ?>&nbsp;&nbsp;</option>
                    <?php }?>
                </select>
                <input type='hidden' name='order_by[0]' id='hidden-sorting' class='hidden-sorting' value='<?php if(!empty($order_by[0])){?><?php echo $order_by[0]?><?php }?>' />
                <input type='hidden' name='order_by[1]' id='hidden-ordering' class='hidden-ordering'  value='<?php if(!empty($order_by[1])){?><?php echo $order_by[1]?><?php }?>'/>
            </div>
            <div class="btnseparator">
            </div>
            <div class="pGroup">
                <div class="pFirst pButton first-button">
                    <span>&nbsp;<i class="glyphicon glyphicon-fast-backward"></i></span>
                </div>
                <div class="pPrev pButton prev-button">
                    <span>&nbsp;<i class="glyphicon glyphicon-chevron-left"></i>&nbsp;</span>
                </div>
            </div>
            <div class="btnseparator">
            </div>
            <div class="pGroup">
                <span><label><?php echo $this->l('list_page'); ?></label>&nbsp;</span>
            </div>
            <div class="pGroup">
                <input name='page' type="text" value="1" size="4" id='crud_page' class="crud_page">&nbsp;
            </div>
            <div class="pGroup">
                <span>
                    <label><?php echo $this->l('list_paging_of'); ?></label>&nbsp;
                    <label id='last-page-number' class="last-page-number"><?php echo ceil($total_results / $default_per_page)?></label>
                </span>
            </div>
            <div class="btnseparator">
            </div>
            <div class="pGroup">
                <div class="pNext pButton next-button" >
                    <span>&nbsp;<i class="glyphicon glyphicon-chevron-right"></i>&nbsp;</span>
                </div>
                <div class="pLast pButton last-button">
                    <span><i class="glyphicon glyphicon-fast-forward"></i>&nbsp;</span>
                </div>
            </div>
            <div class="btnseparator">
            </div>
            <div class="pGroup">
                <div class="pReload pButton ajax_refresh_and_loading" id='ajax_refresh_and_loading'>
                    <span><i class="glyphicon glyphicon-refresh"></i></span>
                </div>
            </div>
            <div class="btnseparator">
            </div>
            <div class="pGroup">
                <span class="pPageStat">
                    <?php $paging_starts_from = "<span id='page-starts-from' class='page-starts-from'>1</span>"; ?>
                    <?php $paging_ends_to = "<span id='page-ends-to' class='page-ends-to'>". ($total_results < $default_per_page ? $total_results : $default_per_page) ."</span>"; ?>
                    <?php $paging_total_results = "<span id='total_items' class='total_items'>$total_results</span>"?>
                    <?php echo str_replace( array('{start}','{end}','{results}'),
                                            array($paging_starts_from, $paging_ends_to, $paging_total_results),
                                            $this->l('list_displaying')
                                           ); ?>
                </span>
            </div>
        </div>
        <div style="clear: both;">
        </div>
    </div>

	<?php echo form_close(); ?>

	</div>
</div>
