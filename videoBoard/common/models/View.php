<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer $id
 * @property string $video_id
 * @property string $created_at
 */
class View extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%view}}';
    }

    public static function createdView($videoId)
    {
        $view = new View();
        $view->video_id = $videoId;
        $view->created_at = date("Y-m-d", time() + 8 * 3600);
        $view->save();
    }

    public static function getViewCount($date, $videoId)
    {
        $viewtCount = View::find()
            ->where(['video_id' => $videoId])
            ->andWhere(['like', 'created_at', $date])
            ->all();
        return count($viewtCount);
    }
}