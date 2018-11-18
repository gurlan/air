<?php
namespace app\home\controller;
use think\Db;
use clt\Lunar;
use think\facade\Env;
class Index extends Common{
    public function initialize(){
        parent::initialize();
    }
    public function index(){
        $order = input('order','createtime');
        $time = time();
        $list=db('article')->alias('a')
            ->join(config('database.prefix').'category c','a.catid = c.id','left')
            ->field('a.id,c.catdir,c.catname')
            ->order($order.' desc')
            ->where('createtime', '>', $time)
            ->limit('15')
            ->select();
        foreach ($list as $k=>$v){
            $list[$k]['time'] = toDate($v['createtime']);
            $list[$k]['url'] = url('home/'.$v['catdir'].'/info',array('id'=>$v['id'],'catId'=>$v['catid']));
        }
        $this->assign('list', $list);
        if(!isMobile()){
            $m= $thisDate = date("m");
            $d= $thisDate = date("d");
            $y= $thisDate = date("Y");
            $Lunar=new Lunar();
            //获取农历日期
            $nonliData = $Lunar->convertSolarToLunar($y,$m,$d);
            $nonliData = $nonliData[1].'-'.$nonliData[2];
            $feastId = db('feast')->where(array('feast_date'=>$nonliData,'type'=>2))->value('id');
            $style='';
            $js='';
            if($feastId){
                $element = db('feast_element')->where('pid',$feastId)->select();
                $style = '<style>';
                $js = '';
                foreach ($element as $k=>$v){
                    $style .= $v['css'];
                    $js .= $v['js'];
                }
                $style .= '</style>';

            }else{
                $feastId = db('feast')->where(array('feast_date'=>$m.'-'.$d,'type'=>1))->value('id');
                if($feastId){
                    $element = db('feast_element')->where('pid',$feastId)->select();
                    $style = '<style>';
                    $js = '';
                    foreach ($element as $k=>$v){
                        $style .= $v['css'];
                        $js .= $v['js'];
                    }
                    $style .= '</style>';
                }
            }
            $this->assign('style', $style);
            $this->assign('js', $js);
        }
        return $this->fetch();
    }
    public function senmsg(){
        $data['tel'] = input('tel');
        $data['name'] = input('name');
        $data['company'] = input('company');
        $data['email'] = input('email');
        $data['content'] = input('content');
        $data['addtime'] = time();
        $data['ip'] = getIp();
        db('message')->insert($data);
        $result['status'] = 1;
        return $result;
    }
    public function down($id=''){
        $map['id'] = $id;
        $files = Db::name('download')->where($map)->find();
        return download(Env::get('root_path').'public'.$files['files'], $files['title']);
    }
    public function search()
    {
        $map = ' ';
        $keyword = input('keyword');
        $map .= 'title like "%'.$keyword.'%"';
        $map .= ' and (status = 1 or (status = 0 and createtime <' . time() . '))';
        $list = db('article')->alias('a')
            ->where($map)
            ->order('sort asc,createtime desc')
            ->paginate($this->pagesize);
        // 获取分页显示
        $page = $list->render();
        $list = $list->toArray();
        foreach ($list['data'] as $k => $v) {
            $list['data'][$k]['controller'] = $v['catdir'];
            if (isset($v['thumb'])) {
                $list['data'][$k]['title_thumb'] = imgUrl($v['thumb'], '/static/home/images/portfolio-thumb/p' . ($k + 1) . '.jpg');
            } else {
                $list['data'][$k]['title_thumb'] = '/static/home/images/portfolio-thumb/p' . ($k + 1) . '.jpg';
            }
        }
        $this->assign('list', $list['data']);
        $this->assign('page', $page);

        $cattemplate = db('category')->where('id', input('catId'))->value('template_list');

        return $this->fetch('search');
    }
}