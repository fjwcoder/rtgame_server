<?php
/*
 * @Descripttion: 机器人数据逻辑
 * @Author: FengQiMan
 * @Date: 2019-07-15 15:14:27
 */
namespace app\admin\logic;
use think\Db;

class Robot extends AdminBase
{
    
    // 获取所有机器人数据 FengQiMan 2019-07-15
    public function getRobotData($where, $field = '', $order = 'id', $paginate=false)
    {
        $this->modelRobotOrder->alias('a');//设置当前数据表的别名

        $this->modelRobotOrder->join = [

            [SYS_DB_PREFIX."game_list v", "a.game_id = v.id", "left"],
            [SYS_DB_PREFIX."game_plantform j", " a.plantform_id = j.id", "left"],
            [SYS_DB_PREFIX."robot_order_detail d", "a.id=d.oid", "left"],
        ];       

        $field = 'a.id, a.order_id, v.cname as game_name, j.name as plantform_name, 
            a.area_name, a.pay_money, a.step, a.status, a.create_time, a.order_type,
            d.begin_info, d.end_info
            ';
        return $this->modelRobotOrder->getList($where, $field, $order, $paginate);
    }

    //  FengQiMan 2019-07-15 机器人添加数据
    public function addRobotData($data = [])
    {
        $url = url('robotList');
        if($data['num'] == '' || !ctype_digit($data['num'])){
            return ['code'=>400,'msg'=>'请输入正确的正整数'];
        }
        $num = (int)$data['num'];// 添加的数量

        if($data['type'] == 1){
            // return ['code'=>200,'msg'=>'代练'];
            for ($i=0; $i < $num; $i++) { 
                // 随机数据
                $order_info = $this->rRandomData($data['type']);
                // dd($order_info);
                $order_id = 'GP'.setOrderID(); // 游戏代练订单号
                $res[$i] = [
                    'order_id'      =>  $order_id,
                    // 虚假数据
                    'user_id'       =>  $order_info['user_id'],
                    'game_id'       =>  $order_info['game_id'],
                    'plantform_id'  =>  $order_info['game_info']['plantform_id'],
                    'area_name'     =>  $order_info['game_info']['area_name'],
                    'user_mobile'   =>  createMobile(),
                    'game_account'  =>  $order_info['game_account'],
                    'game_password' =>  $order_info['game_password'],
                    'game_user'     =>  $order_info['game_user'], // 游戏角色名称
                    'pay_money'     =>  $order_info['game_info']['server_price'],
                    'game_info'     =>  $order_info['game_info']['game_info'],
                    'special_info'  =>  $order_info['special_info'],
                    // 'server_id'     =>  $order_info['game_info']['server_id'],
                    'step'          =>  2,
                    'waiter_id'     =>  0,
                    'create_time'   =>  $order_info['create_time'],
                    'pay_time'      =>  $order_info['pay_time'],
                    'finish_time'   =>  '',
                    'status'        =>  1,
                    'order_type'    =>  $data['type'],
                ];
                $dres[$i] = [
                    'order_id'      =>  $order_id,
                    'user_id'       =>  $order_info['user_id'],
                    'server_id'     =>  $order_info['game_info']['server_id'],
                    'begin_info'    =>  $order_info['game_info']['begin_info'],
                    'end_info'      =>  $order_info['game_info']['end_info'],
                    'server_price'  =>  $order_info['game_info']['server_price'],
                    'server_img'    =>  '',
                ];
            }
            Db::startTrans();
            try{
                $add = Db::name('robot_order')->insertAll($res);
                $oids = Db::name('robot_order')->limit($add)->order('id desc')->column('id');
                sort($oids);
                foreach($dres as $k => $v){
                    $dres[$k]['oid'] = $oids[$k];
                }
                $addD = Db::name('robot_order_detail')->insertAll($dres);

                if($add && $addD){ 
                    Db::commit(); 
                    return ['code'=>200,'msg'=>'创建成功'];
                }
            }catch(\Exception $e){
                // dump($e); 
                Db::rollback();
                return ['code'=>200,'msg'=>'创建失败'];
            }
            
        }else{
            // return ['code'=>200,'msg'=>'陪玩'];
            for ($i=0; $i < $num; $i++) { 
                // 随机数据
                $order_info = $this->getHPCountPrice();
                $order_id = 'GP'.setOrderID(); // 游戏代练订单号
                $res[$i] = [
                    'order_id'      =>  $order_id,
                    // 虚假数据
                    'user_id'       =>  $order_info['user_id'],
                    'game_id'       =>  $order_info['game_id'],
                    'plantform_id'  =>  $order_info['plantform_id'],
                    // 'area_name'     =>  $order_info['game_info']['area_name'],
                    'user_mobile'   =>  createMobile(),
                    // 'game_account'  =>  $order_info['game_account'],
                    // 'game_password' =>  $order_info['game_password'],
                    // 'game_user'     =>  $order_info['game_user'], // 游戏角色名称
                    'pay_money'     =>  $order_info['server_price'],
                    // 'game_info'     =>  $order_info['game_info']['game_info'],
                    'special_info'  =>  $order_info['special_info'],
                    // 'server_id'     =>  $order_info['game_info']['server_id'],
                    'step'          =>  $order_info['step'],
                    'waiter_id'     =>  $order_info['waiter_id'],
                    'create_time'   =>  $order_info['create_time'],
                    'pay_time'      =>  $order_info['pay_time'],
                    'finish_time'   =>  '',
                    'status'        =>  1,
                    'order_type'    =>  $data['type'],
                ];
                $dres[$i] = [
                    'order_id'      =>  $order_id,
                    'user_id'       =>  $order_info['user_id'],
                    // 'server_id'     =>  $order_info['game_info']['server_id'],
                    // 'begin_info'    =>  $order_info['game_info']['begin_info'],
                    // 'end_info'      =>  $order_info['game_info']['end_info'],
                    'server_price'  =>  $order_info['server_price'],
                    'server_type'  =>  $order_info['server_type'],
                    'server_con'  =>  $order_info['server_con'],
                    'server_img'    =>  '',
                ];
            }
            Db::startTrans();
            try{
                $add = Db::name('robot_order')->insertAll($res);
                $oids = Db::name('robot_order')->limit($add)->order('id desc')->column('id');
                sort($oids);
                foreach($dres as $k => $v){
                    $dres[$k]['oid'] = $oids[$k];
                }
                $addD = Db::name('robot_order_detail')->insertAll($dres);

                if($add && $addD){ 
                    Db::commit(); 
                    return ['code'=>200,'msg'=>'创建成功'];
                }
            }catch(\Exception $e){
                // dump($e); 
                Db::rollback();
                return ['code'=>400,'msg'=>'创建失败'];
            }
            

        }
    }

    // 2019-07-17 FengQiMan 机器人随机数据 - 代练
    public function rRandomData()
    {
        //用户id usei_id
        $user_id = mt_rand('8888','9999');

        // 游戏id game_id
        $game_id = rRomdomD('1,2');//所有ID

        // 根据游戏类型 判断英雄 段位 等信息
        $order_info = $this->getHeroDInfo($game_id);

        // 游戏账号 game_account 随机生成8-10位账号
        $game_account = mt_rand(1000,99999).mt_rand(1000,99999);

        // 游戏密码 game_password 随机生成10位密码
        $game_password = '';
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        for ( $i = 0; $i < 10; $i++ ){
            $game_password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }

        // 游戏昵称 game_user 随机生成5位密码
        $game_user = '';
        for ( $i = 0; $i < 5; $i++ ){
            $game_user .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }

        // 支付金额 pay_money
        $pay_money = '';

        // 特殊说明 special_info
        $special_info = '';

        // 创建时间 create_time
        $create_time = time() - mt_rand(300,9999);

        // 支付时间 pay_time
        $pay_time = $create_time + mt_rand(100,300);

        $data = [
            'user_id' => $user_id,
            'game_id' => $game_id,
            'game_account' => $game_account,
            'game_password' => $game_password,
            'game_user' => $game_user,
            'special_info' => $special_info,
            'create_time' => $create_time,
            'pay_time' => $pay_time,
            'game_info' => $order_info,
        ];
        // dd($data);
        return $data;
    }

    // 2019-07-17 FengQiMan  根据游戏类型 判断英雄 段位 等信息 - 代练
    public function getHeroDInfo($game_id = 0)
    {
        // 服务id plantform_id
        $plantform_id = '';
        // 什么区 area_name
        $area_name = '';
        // 游戏信息 game_info
        $game_info = '';
        // 服务id server_id
        $server_id = '';
        // 开始段位 begin_info
        $begin_info = '';
        // 结束段位 end_info
        $end_info = '';
        // 服务金额 server_price
        $server_price = '';
        // 开始段位 结束段位 价格
        $benginEnd = '';
        // 1王者荣耀
        if($game_id == 1){
            // 平台id
            $plantform_id = rRomdomD('1,2,3,4');
            // 游戏信息 game_info
            $yx_num = mt_rand(15,92);// 英雄数量
            $mw_num = mt_rand(90,150);// 铭文等级
            $xx_sm = '';
            $yxnames = "云中君,瑶,盘古,猪八戒,嫦娥,上官婉儿,李信,沈梦溪,伽罗,盾山,司马懿,孙策,元歌,米莱狄,狂铁,弈星,裴擒虎,杨玉环,公孙离,明世隐,女娲,梦奇,苏烈,百里玄策,百里守约,铠,鬼谷子,干将莫邪,东皇太一,大乔,黄忠,诸葛亮,哪吒,太乙真人,蔡文姬,雅典娜,杨戬,成吉思汗,钟馗,虞姬,李元芳,张飞,刘备,后羿,牛魔,孙悟空,亚瑟,橘右京,娜可露露,不知火舞,张良,花木兰,兰陵王,王昭君,韩信,刘邦,姜子牙,露娜,程咬金,安琪拉,貂蝉,关羽,老夫子,武则天,项羽,达摩,狄仁杰,马可波罗,李白,宫本武藏,典韦,曹操,甄姬,夏侯惇,周瑜,吕布,芈月,白起,扁鹊,孙膑,钟无艳,阿轲,高渐离,刘禅,庄周,鲁班七号,孙尚香,嬴政,妲己,墨子,赵云,小乔,廉颇,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,"; // 所有英雄名称 添加空白 减少指定英雄概率
            $zd_yx = rRomdomD($yxnames);// 指定英雄
            $game_info = '英雄数量：'.$yx_num.',铭文等级：'.$mw_num.',详细说明：'.$xx_sm.',指定英雄->'.$zd_yx;
            // 服务id 1排位赛
            $server_id = 1;
            $q_f = mt_rand(1,200);
            $area_name = '第'.$q_f.'区';
            $benginEnd = $this->getWZCountPrice($game_id);
            // 指定英雄加价 百分之三十
            if($zd_yx != ''){
                // $money_t = floatval($benginEnd['server_price'] + ($benginEnd['server_price']*0.3));
                $benginEnd['server_price'] = floatval($benginEnd['server_price'] + ($benginEnd['server_price']*0.3));
            }
        }elseif($game_id == 2){
            // 平台id
            $plantform_id = rRomdomD('5,6,7,8');
            // 游戏信息 game_info
            $yx_num = mt_rand(20,142);// 英雄数量
            $dq_sd = mt_rand(10,90);// 当前胜点
            $xx_sm = '';
            $yxnames = "吸血鬼,轮子妈,蒙多,树人,光辉女郎,奶妈,琴瑟仙女,稻草人,鳄鱼,酒桶,凯撒丁,提莫,皇子,铁男,雪人,船长,小炮,狼人赏金猎人,库奇,火人,阿卡丽,天启者,盲僧,凯南,盖伦,寒冰,蛮王,奶爸,维迦,武器大师,贾克斯,暗夜猎手,薇恩,堕天使,玛尔扎哈,基兰,时光老头,兰博,特朗德尔,辛吉德,炼金,乌鸦,蛇女,卡尔萨斯,安妮,皮城女警,凯特琳,伊芙琳老鼠,天使,虫子,奥拉夫,狂战,阿木木,木木,加里奥,蝙蝠侠,布里茨,机器人,黑默丁格,大头,崔斯特,卡牌,石头人,狗头,,大嘴,剑圣,赵信,豹女,老牛,龙龟,厄加特,螃蟹,瑞兹,瑞兹,艾尼维亚,凤凰,卡特琳娜,KT,乌迪尔,德鲁伊,赛恩,斧头哥,萨科,小丑,乐芙兰,妖姬,魔腾,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,"; // 所有英雄名称 添加空白 减少指定英雄概率
            $zd_yx = rRomdomD($yxnames);// 指定英雄
            $game_info = '英雄数量：'.$yx_num.',详细说明：'.$xx_sm.',指定英雄：'.$zd_yx;
            $begin_info = $dq_sd.'胜点';
            // 服务id 5灵活排位
            $server_id = 5;
            $area_name = $this->getGameArea($server_id);
            $benginEnd = $this->getYXCountPrice();
            // 指定英雄加价 百分之三十
            if($zd_yx != ''){
                $benginEnd['server_price'] = $benginEnd['server_price'] + ($benginEnd['server_price']*0.3);
            }
            $benginEnd['begin_info'] = $benginEnd['begin_info'].','.$begin_info;
        }

        $data = [
            'plantform_id' => $plantform_id,
            'area_name' => $area_name,
            'game_info' => $game_info,
            'server_id' => $server_id,
            'begin_info' => $benginEnd['begin_info'],
            'end_info' => $benginEnd['end_info'],
            'server_price' => $benginEnd['server_price'],
        ];
        // dd($data);
        return $data;
    }

    // 2019-07-17 FengQiMan 英雄联盟 区服  - 代练
    public function getGameArea($pid = 0)
    {
        $data = Db::name('game_area')->where(['pid'=>$pid])->column('name');
        $res = implode(',',$data);
        return rRomdomD($res);
    }
    
    // 2019-07-17 FengQiMan 英雄联盟生成段位选择数据 并计算价格 - 代练
    public function getYXCountPrice()
    {
        $total_level = 1;
        $level_list = []; // 段位数组，包含：名称（name），当前段位共获得的星数（stars），价格分级对应的层级（star_level）
        $name_list = []; //  放到picker里显示
        $big_num = [
            1 => '一',
            2 => '二',
            3 => '三',
            4 => '四',
            5 => '五',
        ];
        $level_name = [
            0 => [
                'name' => '坚韧黑铁',
                'level' => 4,
                'star_level' => 1,
                'level_price' => 2.5
            ],
            1 => [
                'name' => '英勇黄铜',
                'level' => 4,
                'star_level' => 2,
                'level_price' => 15
            ],
            2 => [
                'name' => '不屈白银',
                'level' => 4,
                'star_level' => 3,
                'level_price' => 20
            ],
            3 => [
                'name' => '荣耀黄金',
                'level' => 4,
                'star_level' => 4,
                'level_price' => 30
            ],
            4 => [
                'name' => '华贵铂金',
                'level' => 4,
                'star_level' => 5,
                'level_price' => 50
            ],
            5 => [
                'name' => '璀璨钻石',
                'level' => 4,
                'star_level' => 6,
                'level_price' => 100
            ]
        ];

        $objLength = 0;
        $num = 0;
        for ($i=0; $i < count($level_name); $i++) { 
            $objLength++;
        }
        for ($i=0; $i < $objLength; $i++) { 
            $now_lever = 1;
            for ($j = $level_name[$i]['level']; $j>0; $j--) { 
                $name_l = $big_num[$j];
                $name = $level_name[$i]["name"] . $name_l;
                
                $name_list[] = array_push($name_list,$name);
                $temp = [
                    "name"          =>  $name,
                    'star_level'    =>  $level_name[$i]["star_level"], //价格区间
                    'level_price'   =>  $level_name[$i]["level_price"], //价格
                    'total_level'   =>  $total_level, //总排列段位数
                    'now_lever'     =>  $now_lever, //当前项在此大段中的数量排列
                    'level_num'     =>  $level_name[$i]["level"] //当前大段的小段数量
                ];
                // dd($temp);
                $level_list[] = array_push($level_list,$temp);
                $total_level++;
                $now_lever++;
                $num++;
            }
        }
        // 将 $level_list 删除无用数据 并设置下标从 0 开始
        $level_list = array_values(array_filter($level_list, function($var) { return(!($var & 1)); },  ARRAY_FILTER_USE_KEY));
        // dd($level_list);
        $arrId = array_rand($level_list);
        // 确保开始段位不是最后一个
        if($arrId == 23){ $arrId -= 2;}
        // 开始段位信息
        $begin_info = $level_list[$arrId];
        // 确保结束段位不在开始段位之前 不和开始段位相同
        $end_id = $arrId + intval((($num - 1) - $arrId)/2);
        if($end_id == $arrId){$end_id = $end_id + 1;}
        // 结束段位信息
        $end_info = $level_list[$end_id];

        $server_price = 0; // 价格

        $num_i = $end_id - $arrId;
        
        for ($i=0; $i < $num_i; $i++) { 
            $server_price += $level_list[$arrId]['level_price'];
            $arrId++;
        }
        $data = [
            'begin_info' => $begin_info['name'],
            'end_info' => $end_info['name'],
            'server_price' => $server_price
        ];
        // dd($data);
        return $data;
        
    }

    // 2019-07-17 FengQiMan 王者荣耀生成段位选择数据 并计算价格 - 代练
    public function getWZCountPrice()
    {
        $level_list = []; // 段位数组，包含：名称（name），当前段位共获得的星数（stars），价格分级对应的层级（star_level）,单价star_price
        $name_list = []; //  放到picker里显示
        $star = 1;
        $king_star = 0;
        $big_num = [
            1=>'一',
            2=>'二',
            3=>'三',
            4=>'四',
            5=>'五',
        ];
        $level_name = [
            0 => [
                'name'=>'倔强青铜',
                'level'=>3,
                'star'=>3,
                'star_level'=>1,
                "star_price" => 1
            ],
            1 => [
                'name'=>'秩序白银',
                'level'=>3,
                'star'=>3,
                'star_level'=>1,
                "star_price" => 1
            ],
            2 => [
                'name'=>'荣耀黄金',
                'level'=>4,
                'star'=>4,
                'star_level'=>2,
                "star_price" => 2
            ],
            3 => [
                'name'=>'尊贵铂金',
                'level'=>4,
                'star'=>4,
                'star_level'=>3,
                "star_price" => 3
            ],
            4 => [
                'name'=>'永恒钻石',
                'level'=>5,
                'star'=>5,
                'star_level'=>4,
                "star_price" => 5
            ],
            5 => [
                'name'=>'至尊星耀',
                'level'=>5,
                'star'=>5,
                'star_level'=>5,
                "star_price" => 6
            ],
            6 => [
                'name'=>'王者1-10星',
                'level'=>1,
                'star'=>10,
                'star_level'=>6,
                "star_price" => 7
            ],
            7 => [
                'name'=>'王者11-20星',
                'level'=>1,
                'star'=>10,
                'star_level'=>7,
                "star_price" => 8
            ],
            8 => [
                'name'=>'王者21-30星',
                'level'=>1,
                'star'=>10,
                'star_level'=>8,
                "star_price" => 13
            ],
            9 => [
                'name'=>'王者31-40星',
                'level'=>1,
                'star'=>10,
                'star_level'=>9,
                "star_price" => 15
            ],
            10 => [
                'name'=>'王者41-50星',
                'level'=>1,
                'star'=>10,
                'star_level'=>10,
                "star_price" => 18
            ],
            11 => [
                'name'=>'王者50星以上',
                'level'=>1,
                'star'=>100,
                'star_level'=>11,
                "star_price" => 25
            ],
        ];

        $ks = [];
        $num = 0;
        foreach ($level_name as $k => $v) {
            $ks[$k] = $k;
            if($k < 6){
                // $k = $big_num[$k];
                for ($i = $level_name[$k]["level"]; $i > 0; $i--) {
                    $k1 = $big_num[$i];
                    for ($j = 1; $j <= $level_name[$k]["level"]; $j++) {
                        $name = $level_name[$k]["name"] . $k1 . $j . "星";
                        $name_list[] = array_push($name_list,$name);
                        $temp = [
                            "name"=> $name,
                            'stars'=> $star,
                            'star_level'=> $level_name[$k]["star_level"],
                            'star_price'=>$level_name[$k]["star_price"]
                        ];
                        $level_list[] = array_push($level_list,$temp);
                        $star++;
                        $num++;
                    }
                }
                
            }else{
                for ($i = 0; $i < $level_name[$k]["star"]; $i++) {
                    $king_star += 1;
                    $name = "最强王者" . $king_star . "星";
                    $name_list[] = array_push($name_list,$name);
                    $temp = [
                        "name"=> $name,
                        'stars'=> $star,
                        'star_level'=> $level_name[$k]["star_level"],
                        'star_price'=>$level_name[$k]["star_price"]
                    ];
                    $level_list[] = array_push($level_list,$temp);
                    $star++;
                    $num++;
                }
                
            }
            
        }
        
        // 将 $level_list 删除无用数据 并设置下标从 0 开始
        $level_list = array_values(array_filter($level_list, function($var) { return(!($var & 1)); },  ARRAY_FILTER_USE_KEY));
        // dd($level_list);
        $arrId = array_rand($level_list);
        
        // 确保开始段位不是最后一个
        if($arrId >= 100){ $arrId -= 50; }
        // 开始段位信息
        $begin_info = $level_list[$arrId];
        // 确保结束段位不在开始段位之前 不和开始段位相同
        $end_id = $arrId + intval((($num - 5) - $arrId)/2);
        if($end_id == $arrId){$end_id = $end_id + 1;}
        // 结束段位信息
        // $end_id = 20;
        $end_info = $level_list[$end_id];

        $server_price = 0; // 价格

        $num_i = $end_id - $arrId;
        
        for ($i=0; $i < $num_i; $i++) { 
            $arrId++;
            $server_price += $level_list[$arrId]['star_price'];
        }

        $data = [
            'begin_info' => $begin_info['name'],
            'end_info' => $end_info['name'],
            'server_price' => $server_price
        ];
        // d($data);
        return $data;


    }

    // 2019-07-18 FengQiMan 和平精英生成数据 价格 时间等 - 陪玩
    public function getHPCountPrice()
    {
        //用户id usei_id
        $user_id = mt_rand('8888','9999');
        // 游戏id game_id
        $game_id = 4;//rRomdomD('1,2');//和平精英的ID
        // 区服 plantform_id
        $plantform_id = rRomdomD('9,10,11,12');

        // 特殊说明 special_info
        $special_info = '';

        // 创建时间 create_time
        $create_time = time() - mt_rand(300,9999);

        // 支付时间 pay_time
        $pay_time = $create_time + mt_rand(100,300);
        // 支付金额 server_price
        $server_price = 0;

        $server_type = rRomdomD('1,2');// 服务类型 1：按时间 2按局数
        $server_con = mt_rand(1,10);// n 小时 或 n 局

        if($server_type == 1){
            $server_price = $server_con * 5; // 一小时五元
        }elseif($server_type == 2){
            $server_price = $server_con * 3; // 一局三元
        }
        $data = [
            'user_id'       =>  $user_id,
            'game_id'       =>  $game_id,
            'plantform_id'  =>  $plantform_id,
            'special_info'  =>  $special_info,
            'create_time'   =>  $create_time,
            'pay_time'      =>  $pay_time,
            'server_price'  =>  $server_price,
            'server_type'   =>  $server_type,
            'server_con'    =>  $server_con,
            'waiter_id'     =>  0,
            'step'          =>  2,
        ];

        return $data;
        

    }



}
