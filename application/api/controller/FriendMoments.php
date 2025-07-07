<?php
namespace app\admin\controller\api;

use app\common\controller\Backend;
use think\Db;

class FriendMoments extends Backend
{
    protected $noNeedLogin = [];
    protected $noNeedRight = '*';

    // 获取朋友圈信息列表
    public function getMoments()
    {
        $user_id = $this->request->param('user_id');
        $page = $this->request->param('page', 1, 'intval'); // 获取页码，默认为第1页
        $pageSize = 10; // 每页显示10条数据

        // 获取该用户的好友列表
        $friendIds = Db::name('chat_friend')
            ->where('current_id', $user_id)
            ->where('deleted', 0)
            ->column('user_id');

        // 广场可见信息（可见性为0-公开）和好友发布的信息
        $moments = Db::name('friend_moments')
            ->where(function ($query) use ($friendIds, $user_id) {
                $query->where('visibility', 0)
                    ->whereOr('user_id', 'in', $friendIds)
                    ->whereOr('user_id', $user_id);
            })
            ->where('is_deleted', 0)
            ->order('create_time', 'desc')
            ->page($page, $pageSize) // 根据页码和每页数量进行分页
            ->select();

        foreach ($moments as &$moment) {
            // 获取点赞信息
            $moment['likes'] = Db::name('friend_likes')
                ->where('moment_id', $moment['moment_id'])
                ->where('is_deleted', 0)
                ->select();

            // 获取评论信息
            $moment['comments'] = Db::name('friend_comments')
                ->where('moment_id', $moment['moment_id'])
                ->where('is_deleted', 0)
                ->select();

            // 获取相关附件信息
            $moment['media_resources'] = Db::name('friend_media_resources')
                ->where('moment_id', $moment['moment_id'])
                ->order('sort_order', 'asc')
                ->select();
        }
        unset($moment);

        $this->success('获取成功', null, $moments);
    }

    // 测试数据获取
    public function gettest(){
        $json=
        $jsonString = '{
            "total": 100,
            "page": 1,
            "limit": 10,
            "list": [
                {
                    "userName": "小石头1",
                    "userImgUrl": "https://example.com/avatar.jpg",
                    "saying": "听说Flutter很🔥，我也来凑热闹，github上建了一个仓库，欢迎来撩~✌✌✌",
                    "pic": "https://example.com/picture.jpg",
                    "createTime": "2小时前",
                    "comments": [
                        {
                            "source": true,
                            "fromUser": "若海",
                            "toUser": "小石头",
                            "content": "性能如何？"
                        },
                        {
                            "source": false,
                            "fromUser": "小石头",
                            "toUser": "若海",
                            "content": "性能不错，但是开发体验感觉差一点。。。"
                        }
                    ]
                },
                {
                    "userName": "小石头2",
                    "userImgUrl": "https://example.com/avatar2.jpg",
                    "saying": "周末出去玩啦！",
                    "pic": "https://example.com/picture2.jpg",
                    "createTime": "3小时前",
                    "comments": [
                        {
                            "source": true,
                            "fromUser": "大佬",
                            "toUser": "小石头",
                            "content": "玩得开心！"
                        }
                    ]
                }
            ]
        }';
        $this->success('获取成功', null, $jsonString);
    }
}