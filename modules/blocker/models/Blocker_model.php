<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Blocker_model extends CMS_Model{
    
    public function get_all(){
        $white_ip = $this->get_white_ip();
        $black_ip = $this->get_black_ip();
        $content = '';
        $content .= $this->get_white_referer();
        $content .= $this->get_black_referer();
        $content .= $this->get_white_useragent();
        $content .= $this->get_black_useragent();
        if($white_ip.$black_ip != ''){
            $content .= '    order deny,allow'.PHP_EOL;
            $content .= $white_ip.$black_ip;
        }
        return $content;
    }
    public function get_white_referer(){
        return '';
    }
    
    public function get_black_referer(){
        $query = $this->db->select('content')
            ->from($this->t('leech').' as leech')
            ->join($this->t('label').' as label', 'leech.id_label = label.id')
            ->where('label.status','black list')
            ->get();
        $content = array();
        foreach($query->result() as $row){
            $content[] = '    RewriteCond %{HTTP_REFERER} '.$row->content;
        }
        $content = implode(' [NC,OR]'.PHP_EOL, $content);
        if($content != ''){
            $content .= PHP_EOL.'    RewriteRule .* - [F]'.PHP_EOL;
        }else{
            $content .= PHP_EOL;
        }
        return $content;
    }
    
    public function get_white_useragent(){
        return '';
    }
    
    public function get_black_useragent(){
        $query = $this->db->select('content')
            ->from($this->t('useragent').' as useragent')
            ->join($this->t('label').' as label', 'useragent.id_label = label.id')
            ->where('label.status','black list')
            ->get();
        $content = array();
        foreach($query->result() as $row){
            $content[] = '    RewriteCond %{HTTP_USER_AGENT} '.$row->content;
        }
        $content = implode(' [OR]'.PHP_EOL, $content);
        if($content != ''){
            $content .= ' [NC]'.PHP_EOL.'    RewriteRule ^(.*)$ http://goaway.nevercomeback.com/'.PHP_EOL;
        }else{
            $content .= PHP_EOL;
        }
        return $content;
    }
    
    public function get_black_ip(){
        $query = $this->db->select('content')
            ->from($this->t('ip').' as ip')
            ->join($this->t('label').' as label', 'ip.id_label = label.id')
            ->where('label.status','black list')
            ->get();
        $content = array();
        foreach($query->result() as $row){
            $content = array_merge($content, explode(' ',$row->content));
        }
        for($i=0; $i<count($content); $i++){
            $content[$i] = 'deny from '.$content[$i];
        }
        $content = implode(PHP_EOL.'    ', $content);
        // sometime silly user use "," even if we told them to use " "
        $content = str_replace(',', ' ', $content);
        if($content != ''){
            $content = '    '.$content.PHP_EOL;
        }
        return $content;
    }
    
    public function get_white_ip(){
        $query = $this->db->select('content')
            ->from($this->t('ip').' as ip')
            ->join($this->t('label').' as label', 'ip.id_label = label.id')
            ->where('label.status','white list')
            ->get();
        $content = array();
        foreach($query->result() as $row){
            $content[] = $row->content;
        }
        $content = implode(' ', $content);
        // sometime silly user use "," even if we told them to use " "
        $content = str_replace(',', ' ', $content);
        if($content != ''){
            $content = '    allow from '.$content.PHP_EOL;
        }
        return $content;
    }
}
