<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['module_table_prefix']  = 'pf';
$config['module_prefix']        = 'portfolio';

$config['portfolio_record_template'] ='<div id="record_{{ record:id }}" class="record_container panel panel-default">'.PHP_EOL.
    '<div class="panel-body">'.PHP_EOL.
    ''.PHP_EOL.
    '   <table class="table">'.PHP_EOL.
    '       <tbody>'.PHP_EOL.
    ''.PHP_EOL.
    '           <tr>'.PHP_EOL.
    '               <td class="row col-md-5" rowspan="3">'.PHP_EOL.
    '                   <!-- Image --> '.PHP_EOL.
    '                   {{ record:image_component }}'.PHP_EOL.
    ''.PHP_EOL.
    '                   <!-- Buttons for showing image and open url --> '.PHP_EOL.
    '                   <div class="col-md-12" style="padding-top:10px;">'.PHP_EOL.
    '                       {{ record:url }}'.PHP_EOL.
    '                       {{ record:image_link }}'.PHP_EOL.
    '                   </div>'.PHP_EOL.
    ''.PHP_EOL.
    '               </td>'.PHP_EOL.
    '               <!-- Name --> '.PHP_EOL.
    '               <th class="col-md-3">Name</th>'.PHP_EOL.
    '               <td class="col-md-4">{{ record:name }}</td>'.PHP_EOL.
    '           </tr>'.PHP_EOL.
    '           <tr>'.PHP_EOL.
    '               <!-- Category --> '.PHP_EOL.
    '               <th>Category</th>'.PHP_EOL.
    '               <td>{{ record:category_name }}</td>'.PHP_EOL.
    '           </tr>'.PHP_EOL.
    '           <tr>'.PHP_EOL.
    '               <th>Description</th>'.PHP_EOL.
    '               <td>{{ record:description }}</td>'.PHP_EOL.
    '           </tr>'.PHP_EOL.
    ''.PHP_EOL.
    '       </tbody>'.PHP_EOL.
    '   </table>'.PHP_EOL.
    '   <!-- Backend Url -->'.PHP_EOL.
    '   <div class="edit_delete_record_container pull-right">{{ backend_urls }}</div>'.PHP_EOL.
    ''.PHP_EOL.
    '   <div style="clear:both;"></div>'.PHP_EOL.
    '</div>'.PHP_EOL.
    '</div>';
