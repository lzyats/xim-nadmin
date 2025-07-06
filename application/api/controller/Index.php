<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 首页接口
 */
class Index extends Api
{
    protected $noNeedLogin = ['upinfo'];
    protected $noNeedRight = ['*'];



    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 首页
     *
     */
    public function index()
    {
        $this->success('请求成功');
    }

    /**
     * 首页
     *  $id APP的配置ID
     */
    public function upinfo($id){
        $data=[
            'upurl'=>'https://api.amoa.cn',
            'upversion'=>'1.3.2',
            'updurl'=>'https://app.amoa.cn',
            'apiurl'=>'https://app.amoa.cn',
        ];
        //对数据进行加密
        $data=json_encode($data);
        $data=$this->handleEncryptedData($data);
        return $data;
    }

}
