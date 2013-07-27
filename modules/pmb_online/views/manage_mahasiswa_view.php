<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<style>
	#field-anak_ke					{width:50px  !important;}
	#field-Jumlah_saudara_kandung	{width:50px  !important;}
	#field-total_nilai_UN			{width:50px  !important;}
	#field-No_telpon_HP 			{width:150px !important;}
	#field-Nama_Mhs					{width:300px !important;}
	#field-alamat					{width:300px !important;}
	#field-tempat_Lahir				{width:300px !important;}
	#field-SMA_SMK_asal				{width:300px !important;}
	#field-Nama_Orang_Tua_Ibu		{width:300px !important;}
	#field-alamat_orang_tua			{width:300px !important;}
	#field-alamat_malang			{width:300px !important;}
	#field-No_telpon_HP_orang_tua	{width:150px !important;}

	#field_id_jurusan_SMA_SMK_chzn	{width:188px !important;}
	#field_id_agama_chzn 		  	{width:175px !important;}
	#field_warga_negara_chzn 	  	{width:175px !important;}
	#field_Provinsi_chzn 		  	{width:175px !important;}
	#field_id_kota_orang_tua_chzn 	{width:175px !important;}
	#field_id_pekerjaan_ayah_chzn 	{width:175px !important;}
	#field_id_pekerjaan_ibu_chzn  	{width:175px !important;}
	#field_ID_info_stiki_chzn	  	{width:175px !important;}
	#field_id_prodi_chzn		  	{width:180px !important;}
	#field_jenis_kelamin_chzn 	  	{width:175px !important;}
	#field-Email				  	{width:250px !important;}
	#field-user_name			  	{width:250px !important;}
	#field-password				  	{width:250px !important;}

	.form-display-as-box {
		text-align: right;
		margin-right: 10px;
	}
</style>
<?php
    // membedakan dari frontend atau dari manage_mahasiswa
    if(isset($frontend)){
        $FRONTEND = TRUE;
    }else{
        $FRONTEND = FALSE;
    }
    
	$asset = new CMS_Asset();
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
<script type="text/javascript">

    // ************************************************************************************
    // VARIABLES DECLARATION
    // ************************************************************************************

    // things that are only visible in tab 1
    // determine wheter it come from FRONTEND (Registrasi Mahasiswa) or Manage_Mahasiswa
    var FRONTEND = <?php echo $FRONTEND?"true":"false" ?>;
    var TAB_1_FIELDS = new Array(
        'Nama_Mhs', 'alamat', 'jenis_kelamin', 'anak_ke', 'Jumlah_saudara_kandung',
        'tanggal_lahir', 'tempat_Lahir', 'Provinsi', 'warga_negara', 'id_agama', 'SMA_SMK_asal',
        'id_jurusan_SMA_SMK', 'total_nilai_UN', 'No_telpon_HP', 'Email', 'user_name', 'password');

    // things that are only visible in tab 2
    var TAB_2_FIELDS = new Array ('Nama_Orang_Tua_Ibu', 'alamat_orang_tua', 'id_kota_orang_tua',
        'id_pekerjaan_ayah', 'id_pekerjaan_ibu', 'alamat_malang', 'No_telpon_HP_orang_tua', 'alamat_malang',
        'transkrip_nilai','ID_info_stiki','id_prodi','Bukti_Pembayaran');

    var BTN_SAVE = '.pDiv .form-button-box input[value="Save"]';
    var BTN_SAVE_AND_GO_BACK = '#save-and-go-back-button';
    var BTN_CANCEL = '.pDiv .form-button-box input[value="Cancel"]';

    // ************************************************************************************
    // MAIN PROGRAM
    // ************************************************************************************

	$(document).ready(function(){

	    // add aditional buttons to control appearance of TAB_1_FIELDS & TAB_2_FIELDS
        $('.pDiv').prepend('<div class="form-button-box"><input type="button" id="tab_1" value="Prev" class="btn btn-large btn-inverse " /></div>');
        $('.pDiv').prepend('<div class="form-button-box"><input type="button" id="tab_2" value="Next" class="btn btn-large btn-info" /></div>');

		// add some styles
		$(BTN_SAVE).addClass('btn-primary');
		$(BTN_SAVE_AND_GO_BACK).addClass('btn-primary');
		$(BTN_CANCEL).addClass('btn-warning');

		// add some place holders
		$('#field-Nama_Mhs').attr('placeholder', 'Nama Lengkap');
        $('#field-alamat').attr('placeholder', 'Alamat');
        $('#field-tempat_Lahir').attr('placeholder', 'Tempat Lahir');
        $('#field-SMA_SMK_asal').attr('placeholder', 'Asal SMA/SMK');
        $('#field-total_nilai_UN').attr('placeholder', '0-99')
        $('#field-Nama_Orang_Tua_Ibu').attr('placeholder', 'Nama Orang Tua Ibu');
        $('#field-alamat_orang_tua').attr('placeholder', 'Alamat Orang Tua');
        $('#field-alamat_malang').attr('placeholder', 'Alamat di Malang');
        $('#field-password').prop('type','password');

        hide_tab_2();
        show_tab_1();

        $(BTN_SAVE).hide();
        $(BTN_SAVE_AND_GO_BACK).hide();
        $(BTN_CANCEL).hide();

		// if tab_1 clicked
		$("#tab_1").live('click', function()
		{
			hide_tab_2();
			show_tab_1();

			$(BTN_SAVE).hide();
            $(BTN_SAVE_AND_GO_BACK).hide();
            $(BTN_CANCEL).hide();
		});

		// if tab_2 clicked
		$("#tab_2").live('click', function()
		{
			hide_tab_1();
			show_tab_2();
            
            if(!FRONTEND){
			     $(BTN_SAVE).show();
			}
            $(BTN_SAVE_AND_GO_BACK).show();
            $(BTN_CANCEL).show();
		});

	});

	// ************************************************************************************
    // FUNCTIONS
    // ************************************************************************************

	// hide everything in tab_2
    function hide_tab_2(){
        for(i=0; i <TAB_2_FIELDS.length; i++){
            $("#"+TAB_2_FIELDS[i]+"_field_box").hide();
        }
        $('#tab_1').hide();
    }

    // hide everything in tab_1
    function hide_tab_1(){
        for(i=0; i <TAB_1_FIELDS.length; i++){
            $("#"+TAB_1_FIELDS[i]+"_field_box").hide();
        }
        $('#tab_2').hide();
    }

    // show everything in tab_2
    function show_tab_2(){
        for(i=0; i <TAB_2_FIELDS.length; i++){
            $("#"+TAB_2_FIELDS[i]+"_field_box").show();
        }
        $('#tab_1').show();
    }

    // show everything in tab_1
    function show_tab_1(){
        for(i=0; i <TAB_1_FIELDS.length; i++){
            $("#"+TAB_1_FIELDS[i]+"_field_box").show();
        }
        $('#tab_2').show();
    }

</script>