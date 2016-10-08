<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['module_table_prefix']  = 'blog';
$config['module_prefix']        = 'blog';

// Record template configuration

$config['blog_article_record_template'] = '<div id="record_{{ record:id }}">'.PHP_EOL.
     ''.PHP_EOL.
     '      <!-- title & author -->'.PHP_EOL.
     '      <a href="{{ record:article_url }}"><h2>{{ record:title }}</h2></a>'.PHP_EOL.
     '      ({{ record:author }}, {{ record:date }})'.PHP_EOL.
     ''.PHP_EOL.
     '      <!-- photos -->'.PHP_EOL.
     '      {{ record:photos }}'.PHP_EOL.
     ''.PHP_EOL.
     '      <!-- content -->'.PHP_EOL.
     '      <div>'.PHP_EOL.
     '          {{ record:content }}'.PHP_EOL.
     '          <div style="clear:both;"></div>'.PHP_EOL.
     '      </div>'.PHP_EOL.
     ''.PHP_EOL.
     '      <!-- categories -->'.PHP_EOL.
     '      {{ record:categories }}'.PHP_EOL.
     ''.PHP_EOL.
     '      <!-- read more & backend urls -->'.PHP_EOL.
     '      <div class="edit_delete_record_container">'.PHP_EOL.
     '          <a href="{{ record:article_url }}" class="btn btn-primary"><i class="glyphicon glyphicon-plus"></i> {{ language:Read More }} {{ record:comment_count_caption }}</a>'.PHP_EOL.
     '          <!-- backend url -->'.PHP_EOL.
     '          {{ record:backend_url }}'.PHP_EOL.
     '      </div>'.PHP_EOL.
     '</div>';

