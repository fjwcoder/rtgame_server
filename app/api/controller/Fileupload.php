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

namespace app\api\controller;

use app\common\controller\ControllerBase;

/**
 * 文件上传
 */
class Fileupload extends ControllerBase
{
    /**
     * create by fjw in 19.4.1
     * 图片上传
     */
    public function imgUpload(){
        $get = request()->get();
        $img = $get['img'];
        // return $img;
        // $img = 'idfront';
        $path_info = ROOT_PATH . 'public' . DS . 'static' . DS . 'upload' . DS . 'picture' . DS . $img;


        // try{
            $file = request()->file($img);

        //     file_put_contents('up1.txt', var_export($file, true));
        // }catch(\Exception $e){
        //     file_put_contents('up1.txt', var_export($e, true));
        // }
        // 获取表单上传文件 例如上传了001.jpg
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->validate(['ext'=>'jpg,png,jpeg'])->move($path_info); 
            if($info){
                // 成功上传后 获取上传信息
                // 输出 jpg
                // echo $info->getExtension();
                // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
                $savename = $info->getSaveName();
                // echo $info->getSaveName();
                // 输出 42a79759f284b767dfcb2a0197904287.jpg
                $filename = $info->getFilename(); 
                // echo $info->getFilename(); 
// return $savename; 

                return json_encode(['code'=>200, 'path'=> 'static'.DS.'upload'.DS.'picture'.DS.$img.DS.$savename, 'error'=>[]]);
            }else{
                // 上传失败获取错误信息
                return json_encode(['code'=>500, 'path'=>'', 'error'=>$file->getError()]);
            }
        }else{
            return json_encode(['code'=>500, 'path'=>'', 'error'=>['error']]);
        }



    }

    
    
    
    
}
