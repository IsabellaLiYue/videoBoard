<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Video;

/**
 * @property integer $id
 * @property string $video_id
 * @property date-time $created_at
 * @property string $content
 * @property string $commented_at
 */
class Comment extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%comment}}';
    }

    public static function createComment($videoId, $commentTime, $content)
    {
        $video = Video::findIdentity($videoId);
        $video->comment_count = $video->comment_count + 1;
        $video->all_count = $video->all_count + 1;
        $video->save();

        $comment = new Comment();
        $comment['created_at'] = date("Y-m-d", time() + 8 * 3600);
        $comment->commented_at = $commentTime;
        $comment->content = $content;
        $comment->video_id = $videoId;
        $comment->save();
    }

    public static function getComment($videoId)
    {
        $comments = Comment::find()
            ->where(['video_id' => $videoId])
            ->orderBy(['commented_at' => SORT_ASC])
            ->all();
        return $comments;
    }

    public static function getCommentCount($date, $videoId)
    {
        $commentCount = Comment::find()
            ->where(['video_id' => $videoId])
            ->andWhere(['like', 'created_at', $date])
            ->all();
        return count($commentCount);
    }
}