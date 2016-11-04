<?php if (!defined('BASEPATH')) { exit('No direct script access allowed');}
    if(!function_exists('escape_html')){
        function escape_html($str){
            $search = array(PHP_EOL, '  ');
            $replace = array('<br />', '&nbsp;&nbsp;');
            $str = str_replace($search, $replace, htmlentities($str));
            return $str;
        }
    }

    if(!function_exists('print_value')){
        function print_value($val){
            if(is_int($val) || is_float($val) || is_null($val)){
                echo $val;
            }else if(is_string($val)){
                echo escape_html($val);
            }else if(is_bool($val)){
                echo $val? 'TRUE' : 'FALSE';
            }else{
                echo escape_html(print_r($val, TRUE));
            }
        }
    }

    if(!function_exists('print_elapsed_time')){
        function print_elapsed_time($seconds){
            $seconds = 0.0 + $seconds;
            echo number_format($seconds, 4) . ' seconds';
        }
    }

    if(!function_exists('print_sql')){
        function print_sql($sql, $sql_keywords){
            $search = array();
            $replace = array();
            $sql = escape_html($sql);
            foreach($sql_keywords as $keyword){
                $search[] = $keyword;
                $replace[] = '<b>'.$keyword.'</b>';
            }
            echo str_replace($search, $replace, $sql);
        }
    }

    if(!function_exists('parse_unit_test_result')){
        function parse_unit_test_result($result){
            if($result == 'Passed'){
                return '<span class="label label-success">'.$result.'</label>';
            }else{
                return '<span class="label label-danger">'.$result.'</label>';
            }
        }
    }
?>
<style type="text/css">
    .small-font{
        font-size : small;
    }
    .padding-left-40{
        padding-left : 40px!important;
    }
    .padding-left-20{
        padding-left : 20px!important;
    }
    ._profiler-container td{
        font-family : monospace;
        overflow-x : auto;
        overflow-y : hidden;
    }
    ._profiler-container td{
        word-break : break-all;
        word-wrap : break-word;
    }
    ._controller{
        cursor : pointer;
    }
</style>
<div class="_profiler-container container well">
    <h3 class="_profiler-toggle-all-controller _controller">Profiler</h3>
    <table class="table col-12-md _profiler-toggle-all">
        <!-- This row contains nothing, and only used to ensure the width of every column is fixed -->
        <tr style="height:0px; padding:0px;">
            <td style="height:0px; padding:0px;" class="col-md-3"></td>
            <td style="height:0px; padding:0px;" class="col-md-9"></td>
        </tr>
        <!-- GENERAL -->
        <tr class="_profiler-toggle-general-controller _controller"><th colspan="2">General Information</th></tr>
        <tr class="_profiler-toggle-general"> <!-- URI -->
            <th class="padding-left-40">URI</th>
            <td><?php echo $uri_string; ?></td>
        </tr>
        <tr class="_profiler-toggle-general"> <!--class_name -->
            <th class="padding-left-40">Class Name</th>
            <td><?php echo $class_name; ?></td>
        </tr>
        <tr class="_profiler-toggle-general"> <!--method_name -->
            <th class="padding-left-40">Method Name</th>
            <td><?php echo $method_name; ?></td>
        </tr>
        <!-- UNIT TEST -->
        <tr class="_profiler-toggle-unit-test-controller _controller"><th colspan="2">Unit Test (Total : <?php echo count($unit_result); ?>)</th></tr>
        <?php
        foreach($unit_result as $unit){
            $test_name = $unit['Test Name'];
            $expected_datatype = $unit['Expected Datatype'];
            $test_datatype = $unit['Test Datatype'];
            $result = $unit['Result'];
            $file_name = $unit['File Name'];
            $line_number = $unit['Line Number'];
            $notes = $unit['Notes'];
            echo '<tr class="_profiler-toggle-unit-test">';
            echo '  <td colspan="2">';
            echo '      <table class="table">';
            echo '          <tr>';
            echo '              <th class="col-md-7"><b>'.$test_name . '</b></th>';
            echo '              <td class="col-md-2" rowspan="2"><b>Expected Datatype:</b><br />'.$expected_datatype.'</td>';
            echo '              <td class="col-md-2" rowspan="2"><b>Test Datatype:</b><br />'.$test_datatype.'</td>';
            echo '              <td class="col-md-1" rowspan="2">'.parse_unit_test_result($result).'</td>';
            echo '          </tr>';
            echo '          <tr>';
            echo '              <td class="padding-left-20 small-font">'.$file_name.' (Line : '.$line_number.')</td>';
            echo '          <tr>';
            echo '              <td class="padding-left-40" colspan="4">'.$notes.'</td>';
            echo '          </tr>';
            echo '      </table>';
            echo '  </td>';
            echo '</tr>';
        }
        ?>
        <!-- BENCHMARK -->
        <tr class="_profiler-toggle-benchmark-controller _controller"><th colspan="2">Benchmark</th></tr>
        <tr class="_profiler-toggle-benchmark"> <!--Memory Usage -->
            <th class="padding-left-40">Total Memory Usage</th>
            <td ><b><?php echo $memory_usage; ?></b></td>
        </tr>
        <tr class="_profiler-toggle-benchmark">
            <th class="padding-left-40">Total Elapsed Time</th> <!-- elapsed_time -->
            <td ><b><?php echo $elapsed_time.' seconds'; ?></b></td>
        </tr>
        <?php foreach($profiles as $key=>$val){ // print profiles ?>
            <tr class="_profiler-toggle-benchmark">
                <th class="padding-left-40"><?php echo ucwords(str_replace('_', ' ', $key)); ?></th>
                <td ><?php print_elapsed_time($val); ?></td>
            </tr>
        <?php } ?>
        <!-- MARKER -->
        <tr class="_profiler-toggle-marker-controller _controller"><th colspan="2">Time Marker (Total : <?php echo count($markers); ?>)</th></tr>
        <?php foreach($markers as $key=>$val){ // markers ?>
            <tr class="_profiler-toggle-marker">
                <th class="padding-left-40"><?php echo ucwords(str_replace('_', ' ', $key)); ?></th>
                <td ><?php echo number_format($val, 4); ?></td>
            </tr>
        <?php } ?>
        <!-- VARIABLES -->
        <tr><th class="_profiler-toggle-variable-controller _controller" colspan="2">Variables (Total : <?php echo count($variables); ?>)</th></tr>
        <?php foreach($variables as $key=>$val){ // variables ?>
            <tr class="_profiler-toggle-variable">
                <th class="padding-left-40"><?php echo $key; ?></th>
                <td><?php print_value($val); ?></td>
            </tr>
        <?php } ?>
        <!-- QUERIES -->
        <tr class="_profiler-toggle-query-controller _controller"><th colspan="2">Queries (Total : <?php echo count($db_queries) ?>)</th></tr>
        <?php foreach($db_queries as $key=>$val){ // query ?>
            <tr class="_profiler-toggle-query">
                <td class="padding-left-40" colspan="2">
                    <div ><?php print_elapsed_time( $db_query_times[$key]);?></div>
                    <?php print_sql($val, $sql_keywords); ?>
                </th>
            </tr>
        <?php } ?>
        <!-- SERVER -->
        <tr class="_profiler-toggle-server-controller _controller"><th colspan="2">$_SERVER (Total : <?php echo count($server); ?>)</th></tr>
        <?php foreach($server as $key=>$val){ // server ?>
            <tr class="_profiler-toggle-server">
                <th class="padding-left-40"><?php echo $key; ?></th>
                <td><?php print_value($val); ?></td>
            </tr>
        <?php } ?>
        <!-- COOKIE -->
        <tr class="_profiler-toggle-cookie-controller _controller"><th colspan="2">$_COOKIE (Total : <?php echo count($cookie); ?>)</th></tr>
        <?php foreach($cookie as $key=>$val){ // cookie ?>
            <tr class="_profiler-toggle-cookie">
                <th class="padding-left-40"><?php echo $key; ?></th>
                <td><?php print_value($val); ?></td>
            </tr>
        <?php } ?>
        <!-- SESSION -->
        <tr class="_profiler-toggle-session-controller _controller"><th colspan="2">$_SESSION (Total : <?php echo count($session); ?>)</th></tr>
        <?php foreach($session as $key=>$val){ // session ?>
            <tr class="_profiler-toggle-session">
                <th class="padding-left-40"><?php echo $key; ?></th>
                <td><?php print_value($val); ?></td>
            </tr>
        <?php } ?>
        <!-- POST -->
        <tr class="_profiler-toggle-post-controller _controller"><th colspan="2">$_POST (Total : <?php echo count($post); ?>)</th></tr>
        <?php foreach($post as $key=>$val){ // post ?>
            <tr class="_profiler-toggle-post">
                <th class="padding-left-40"><?php echo $key; ?></th>
                <td><?php print_value($val); ?></td>
            </tr>
        <?php } ?>
        <!-- GET -->
        <tr class="_profiler-toggle-get-controller _controller"><th colspan="2">$_GET (Total : <?php echo count($get); ?>)</th></tr>
        <?php foreach($get as $key=>$val){ // get ?>
            <tr class="_profiler-toggle-get">
                <th class="padding-left-40"><?php echo $key; ?></th>
                <td><?php print_value($val); ?></td>
            </tr>
        <?php } ?>
        <!-- TOTAL -->
        <tr class="_profiler-toggle-config-controller _controller"><th colspan="2">CodeIgniter's configuration (Total : <?php echo count($config); ?>)</th></tr>
        <?php foreach($config as $key=>$val){ // config ?>
            <tr class="_profiler-toggle-config">
                <th class="padding-left-40"><?php echo $key; ?></th>
                <td><?php print_value($val); ?></td>
            </tr>
        <?php } ?>
    </table>
</div>

<script type="text/javascript">
    if(typeof($) == 'function'){ //JQuery exists
        var keys = ['all', 'js-log', 'general', 'benchmark', 'marker', 'unit-test', 'query', 'server', 'cookie', 'session', 'post', 'get', 'config', 'variable'];
        var shown_keys = ['all', 'js-log', 'general', 'benchmark', 'unit-test'];
        $.map(keys, function(key){
            var $controller = $('._profiler-toggle-'+key+'-controller');
            var $component = $('._profiler-toggle-'+key);
            $controller.click(function(){
                $component.toggle();
            });
            // is it shown?
            var shown = false;
            for(var i=0; i<shown_keys.length; i++){
                if(shown_keys[i] == key){
                    shown = true;
                    break;
                }
            }
            if(!shown){
                $component.hide();
            }
        });
    }
</script>
