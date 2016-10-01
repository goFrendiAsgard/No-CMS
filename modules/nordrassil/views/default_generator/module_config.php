&lt;?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['module_table_prefix']  = '{{ table_prefix }}';
$config['module_prefix']        = '{{ module_prefix }}';
<?php
if(count($record_template_configuration) > 0){
    echo PHP_EOL.'// Record template configuration'.PHP_EOL.PHP_EOL;
}
foreach($record_template_configuration as $key => $val){
    $val = implode('\'.PHP_EOL.' . PHP_EOL . '    \'', explode(PHP_EOL, $val));
    echo '$config[\''.$key.'\'] = \''.$val.'\';'.PHP_EOL;
}
