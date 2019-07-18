<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | fjwcoder<fjwcoder@gmail.com>                           |
// +---------------------------------------------------------------------+
// | Repository |   |
// +---------------------------------------------------------------------+

namespace app\index\controller;
use think\Db;
/**
 * 前端首页控制器
 */
class Index extends IndexBase
{

    public function test_level(){

        $level_list = []; // 段位数组，包含：名称（name），当前段位共获得的星数（stars），价格分级对应的层级（star_level）
        $name_list = []; //  放到picker里显示
        // 以上两个方法是需要放到data里的，最好是重命名一下

        $star = 1;
        $king_star = 0;
        $big_num = [1=>'一',  2=>'二', 3=>'三', 4=>'四',  5=>'五'];
        $level_name = [
            ['name'=>'倔强青铜', 'level'=>3, 'star'=>3, 'star_level'=>1],
            ['name'=>'秩序白银', 'level'=>3, 'star'=>3, 'star_level'=>1],
            ['name'=>'荣耀黄金', 'level'=>4, 'star'=>4, 'star_level'=>1],
            ['name'=>'尊贵铂金', 'level'=>4, 'star'=>4, 'star_level'=>2],
            ['name'=>'永恒钻石', 'level'=>5, 'star'=>5, 'star_level'=>2],
            ['name'=>'至尊星耀', 'level'=>5, 'star'=>5, 'star_level'=>3],

            ['name'=>'王者1-10星', 'level'=>1, 'star'=>10, 'star_level'=>4],
            ['name'=>'王者11-20星', 'level'=>1, 'star'=>10, 'star_level'=>5],
            ['name'=>'王者21-30星', 'level'=>1, 'star'=>10, 'star_level'=>6],
            ['name'=>'王者31-40星', 'level'=>1, 'star'=>10, 'star_level'=>7],
            ['name'=>'王者41-50星', 'level'=>1, 'star'=>10, 'star_level'=>8],
            ['name'=>'王者50星以上', 'level'=>1, 'star'=>100, 'star_level'=>9]
        ];

        
        foreach($level_name as $k=>$v){
            if($k<6){
                for($i=$v['level']; $i>0; $i--){
                    $l = $big_num[$i];
                    for($j=1; $j<=$v['star']; $j++){
                        $name = $v['name'].$l.$j.'星';
                        $name_list[] = $name;
                        $temp = ['name'=>$name, 'stars'=>$star, 'star_level'=>$v['star_level']];
                        $level_list[] = $temp;
                        $star ++;
                    }
                }
            }else{
                for($i=0; $i<$v['star']; $i++){
                    $king_star += 1;
                    $name = '最强王者'.$king_star.'星';
                    $name_list[] = $name;
                    $temp = ['name'=>$name, 'stars'=>$star, 'star_level'=>$v['star_level']];
                    $level_list[] = $temp;
                    $star ++;
                }
            }
        }

        // 这里把level_list  和 name_list 放到data中

        // ...

    }

    public function test_price(){
        // $server_price = [];
        $a_data = [

            1 => ['total_stars'=>34, 'level_stars'=>34, 'star_price'=>1], // 青铜-黄金
            2 => ['total_stars'=>75, 'level_stars'=>41, 'star_price'=>3], // 铂金-钻石
            3 => ['total_stars'=>100, 'level_stars'=>25, 'star_price'=>5], // 星耀

            4 => ['total_stars'=>110, 'level_stars'=>10, 'star_price'=>6], // 王者1-10
            5 => ['total_stars'=>120, 'level_stars'=>10, 'star_price'=>8], // 王者11-20
            6 => ['total_stars'=>130, 'level_stars'=>10, 'star_price'=>10], // 王者21-30
            7 => ['total_stars'=>140, 'level_stars'=>10, 'star_price'=>12], // 王者31-40
            8 => ['total_stars'=>150, 'level_stars'=>10, 'star_price'=>15], // 王者41-50
            9 => ['total_stars'=>160, 'level_stars'=>10, 'star_price'=>20], // 王者51以上

        ];



        // foreach($a_data as $k=>$v){
        //     $temp = ['gid'=>1, 'sid'=>1, 'level_id'=>$k];
        //     $temp = array_merge($temp, $v);
        //     $server_price[] = $temp;
        // }
// dump($server_price); die;
        // $res = Db::name('server_price') -> insertAll($server_price);
        // dump($res); die;
        

    }

    // 首页
    public function index($cid = 0)
    {
        echo 'index'; 
    }
    
    // 详情
    public function details($id = 0)
    {
        
        $where = [];
        
        !empty((int)$id) && $where['a.id'] = $id;
        
        $data = $this->logicArticle->getArticleInfo($where);
        
        $this->assign('article_info', $data);
        
        $this->assign('category_list', $this->logicArticle->getArticleCategoryList([], true, 'create_time asc', false));
        
        return $this->fetch('details');
    }
}
