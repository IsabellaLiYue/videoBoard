<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\Pagination;
use backend\util\Token;
use backend\util\Constant;

/**
 * @property integer $id
 * @property string $title
 * @property string $created_at
 * @property integer $updated_at
 * @property integer $user_id
 * @property integer $is_deleted
 * @property integer $video_path
 * @property integer $description
 * @property integer $description
 * @property integer $thumbnail_path
 * @property integer $comment_count
 * @property integer $view_count
 * @property integer $all_count
 */
class Video extends ActiveRecord
{
    const IS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public static function tableName()
    {
        return '{{%video}}';
    }

    public function rules()
    {
        return [
            ['is_deleted', 'default', 'value' => self::STATUS_ACTIVE],
            ['is_deleted', 'in', 'range' => [self::STATUS_ACTIVE, self::IS_DELETED]],
        ];
    }
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public static function UpLoad($title, $description, $videoPath, $thumbnailPath)
    {
        $token = $_COOKIE["sessionToken"];
        $user = Token::getTokenValue($token);
        $video = new Video();
        $video->user_id = $user->id;
        $video->title = $title;
        $video->description = $description;
        $video->video_path = $videoPath;
        $video->thumbnail_path = $thumbnailPath;
        $video->created_at = date("Y-m-d H:i:s", time() + 8 * 3600);
        $video->updated_at = date("Y-m-d H:i:s", time() + 8 * 3600);
        $video->save();
    }
    public static function show($searchType, $searchMine, $currentPage, $searchKey)
    {

        if (isset($_COOKIE[Constant::$SESSION_TOKEN])) {
            $token = $_COOKIE[Constant::$SESSION_TOKEN];
            $user = Token::getTokenValue($token);
        }

        $query = Video::find()->joinWith('user');
        if ($searchMine == 'no') {
            $query->where(['video.is_deleted' => '1'])->andWhere(['like', 'title', $searchKey]);
            if ($searchType == 'latest') {
                $query->orderBy(['updated_at' => SORT_DESC]);
            }
            if ($searchType == 'hotest') {
                $query->orderBy(['all_count' => SORT_DESC]);
            }
        } else {
            $query->where(['video.is_deleted' => '1', 'video.user_id' => $user->id])->andWhere(['like', 'title', $searchKey]);
            if ($searchType == 'latest') {
                $query->orderBy(['updated_at' => SORT_DESC]);
            }
            if ($searchType == 'hotest') {
                $query->orderBy(['all_count' => SORT_DESC]);
            }
        }
        $pagination = new Pagination(['totalCount' => $query->count(), 'pageSize' => 8]);
        $videos = $query->offset(($currentPage - 1) * 8)->limit($pagination->limit)->asArray()->all();
        return $videos;
    }

    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'is_deleted' => self::STATUS_ACTIVE]);
    }

    public static function deletes($videoId)
    {
        $video = Video::findIdentity($videoId);
        $token = $_COOKIE[Constant::$SESSION_TOKEN];
        $user = Token::getTokenValue($token);
        if ($user->id == $video->user_id) {
            $video->is_deleted = 0;
            $video->updated_at = date("Y-m-d H:i:s", time() + 8 * 3600);
            $video->save();
            return ['code' => "1", 'msg' => "Delete successfully"];
        }
        return ['code' => "0", 'msg' => "Fail to delete"];
    }

    public static function updateVideo($videoId)
    {
        $video = Video::findIdentity($videoId);
        $video->view_count = $video->view_count + 1;
        $video->all_count = $video->all_count + 1;
        $video->save();
    }

    public static function detail($videoId)
    {

        View::createdView($videoId);
        Video::updateVideo($videoId);
        $video = Video::findIdentity($videoId);
        $query = Video::find()
            ->where(['video.is_deleted' => '1'])
            ->orderBy(['all_count' => SORT_DESC]);
        $pagination = new Pagination(['totalCount' => $query->count(), 'pageSize' => 3]);
        $videos = $query->limit($pagination->limit)->all();
        if (isset($_COOKIE[Constant::$SESSION_TOKEN])) {
            $token = $_COOKIE[Constant::$SESSION_TOKEN];
            $user = Token::getTokenValue($token);
            $videoUser = User::findIdentity($video->user_id);
            return ['videos' => $videos, 'video' => $video, 'user' => $user, 'videoUser' => $videoUser];
        }
        return ['videos' => $videos, 'video' => $video];
    }

    public static function getVideoById($videoId)
    {
        $video = Video::findIdentity($videoId);
        $token = $_COOKIE[Constant::$SESSION_TOKEN];
        $user = Token::getTokenValue($token);
        if ($user->id == $video->user_id) {
            return $video;
        }
        return ['code' => "0", 'msg' => "video not find"];
    }

    public static function UpdateVideoById($title, $description, $thumbnailPath, $videoId)
    {
        $video = Video::findIdentity($videoId);
        $video->title = $title;
        $video->description = $description;
        if ($thumbnailPath) {
            $video->thumbnail_path = Constant::$PRE_DOMAIN . $thumbnailPath;
          }
        $video->updated_at = date("Y-m-d H:i:s", time() + 8 * 3600);
        $video->save();
    }

    public static function getVideos($date)
    {
        $sql = "SELECT video.*, p.* , user.username FROM
                    (SELECT v.video_id, IFNULL(c.c_count, 0) AS c_count, IFNULL(v.v_count, 0) AS v_count, (IFNULL(c.c_count, 0) + IFNULL(v.v_count, 0)) AS popu
                    FROM (SELECT comment.video_id, count(1) AS c_count
                        FROM comment
                        WHERE created_at like '$date'
                        GROUP BY comment.video_id) c
                        RIGHT OUTER JOIN
                        (SELECT view.video_id, COUNT(1) AS v_count
                        FROM view
                        WHERE created_at like '$date'
                        GROUP BY view.video_id) v
                    ON c.video_id = v.video_id
                    ORDER BY popu DESC)p, video, user
                WHERE p.video_id = video.id and video.user_id = user.id
                ORDER BY p.popu DESC
                limit 10";
        $videos = Yii::$app->db->createCommand($sql)->queryAll();
        return $videos;
    }
}