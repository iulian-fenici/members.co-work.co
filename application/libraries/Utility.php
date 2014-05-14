<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Utility
{
    public function draw_search_form($section = '', $action = '')
    {
        $data='';
        $CI=get_instance();
        $options=array('-1'=>'Select...');
        $options += $CI->config->item($section, 'form_filters');
        $selected=-1;
        $data['search_for']='';
        foreach($options as $k=>$v)
        {
            if($CI->input->get($k, false))
            {
                $data['search_for']=$CI->input->get($k, false);
                $selected = $k;
            }
        }
        $CI->load->helper('form');
        $data['list_url']=$action;
        $data['drop_down']=form_dropdown('search_by', $options, $selected,'class="medium" id="search_by" style="margin-top: 11px"');
        return $CI->load->view('merchant/controls/search_form',$data,false);
    }
    public function draw_extended_search_form($section = '', $action = '')
    {
        $data='';
        $CI=get_instance();
        $options=array();
        $options += $CI->config->item($section, 'form_filters');
        $selected=-1;
        $data['search_for']='';
        foreach($options as $k=>$v)
        {
            if($CI->input->get($k, false))
            {
                $data['search_for']=$CI->input->get($k, false);
                $selected = $k;
            }
        }
        $CI->load->helper('form');
        $data['list_url']=$action;
        $data['options']=$options;
        $data['selected']=$selected;
        return $CI->load->view('merchant/controls/search_form_extended',$data,false);
    }
    public function draw_table_header($list_url,$columns)
    {
        $CI=get_instance();
        $data=array(
            'list_url'=>$list_url,
            'columns'=>$columns,
        );
        return $CI->load->view('merchant/controls/list_header',$data,false);
    }

    function draw_breadcrumbs()
    {
        $CI = get_instance();
        
        $section = '/' . $CI->uri->segment(1) . '/' . $CI->uri->segment(2);
        
        if($CI->config->item($section, 'breadcrumbs'))
        {
            return $CI->load->view('merchant/main/breadcrumbs', array('links' => $CI->config->item($section, 'breadcrumbs')), true);
        }
    }
    
    
    function build_filter_href($base, $filter=null, $order=null)
    {
        $CI=get_instance();
        $link = '';
        $params = $CI->input->get();
        $order_str = '';
        $filter_str = '';
        $non_filters = array('order', 'direction');
        if($filter)
        {
            if(isset($params['page']))
                unset($params['page']);
            foreach($filter as $k => $v)
            {
                if(isset($params[$k]) && !$v)
                    unset($params[$k]);
                else
                    $params[$k] = $v;
            }
        }
        if($order)
        {
            if(isset($params['page']))
                unset($params['page']);
            $params['order'] = $order;
            if($CI->input->get('order') == $order && $CI->input->get('direction') == 'asc')
                $params['direction'] = 'desc';
            else
                $params['direction'] = 'asc';
        }
        $link = $base . '?' . http_build_query($params, '', '&');
        return $link;
    }
    
    function get_sorted_state($column)
    {
        if(isset($_GET['order']) && $_GET['order'] == $column)
        {
            if(isset($_GET['direction']) && $_GET['direction'] == 'desc')
                return 'headerSortUp green';
            else
                return 'headerSortDown green';
        }
        else
            return '';
    }

}
?>
