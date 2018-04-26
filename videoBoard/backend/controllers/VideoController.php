<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use common\models\Video;
use common\models\Comment;
use common\models\View;
use backend\util\UploadUtil;
use backend\util\Constant;
use backend\util\Token;

class VideoController extends ActiveController
{
    public $modelClass = 'common\models\Video';

    public function behaviors()
    {
        return ArrayHelper::merge([
            [
                'class' => Cors::className(),
            ],
        ], parent::behaviors());
    }

    public function actionIsGuest()
    {
        if (isset($_COOKIE[Constant::$SESSION_TOKEN])) {
            $token = $_COOKIE[Constant::$SESSION_TOKEN];
            $user = Token::getTokenValue($token);
            return ['code' => "1", 'msg' => "user already login"];
        }
        return ['code' => "0", 'msg' => "user not login"];
    }
    public function actionUpload()
    {
        $Model = Yii::$app->request->post();
        $fileName = UploadUtil::upload('pic/', Constant::$NUMBER, 'image');
        $videoName = UploadUtil::upload('pic/', Constant::$NUMBER, 'video');
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $videoPath = Constant::$PRE_DOMAIN . $videoName;
        $thumbnailPath = Constant::$PRE_DOMAIN . $fileName;
        return Video::UpLoad($title, $description, $videoPath, $thumbnailPath);
    }

    public function actionShow()
    {
        $data = Yii::$app->request->get();
        $searchKey = !empty($data['searchKey']) ? $data['searchKey'] : '';
        $currentPage = !empty($data['currentPage']) ? $data['currentPage'] : 1;

        $searchType = $data['searchType'];
        $searchMine = $data['searchMine'];

        $videos = Video::show($searchType, $searchMine, $currentPage, $searchKey);
        if (isset($_COOKIE[Constant::$SESSION_TOKEN])) {
            $token = $_COOKIE[Constant::$SESSION_TOKEN];
            $user = Token::getTokenValue($token);
            return ['videos' => $videos, 'id' => $user->id, 'username' => $user->username];
        }
        return ['videos' => $videos];
    }

    public function actionDeletes()
    {
        $data = Yii::$app->request->get();
        $videoId = $data['videoId'];
        return Video::deletes($videoId);
    }

    public function actionDetail()
    {
        $data = Yii::$app->request->get();
        $videoId = $data['videoId'];
        return Video::detail($videoId);
    }

    public function actionGetVideo()
    {
        $data = Yii::$app->request->get();
        $videoId = $data['videoId'];
        return Video::getVideoById($videoId);
    }

    public function actionUpdateVideo()
    {
        $Model = Yii::$app->request->post();
        $fileName = UploadUtil::upload('pic/', Constant::$NUMBER, 'image');
        $title = isset($_POST['title']) ? $_POST['title'] : '';
        $videoId = $_POST['videoId'];
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        return Video::UpdateVideoById($title, $description, $fileName, $videoId);
    }

    public function actionCreateComment()
    {
        $data = Yii::$app->request->get();
        $videoId = $data['videoId'];
        $commentTime = $data['commentTime'];
        $content = $data['comment'];
        return Comment::createComment($videoId, $commentTime, $content);
    }

    public function actionGetComments()
    {
        $data = Yii::$app->request->get();
        $videoId = $data['videoId'];
        return Comment::getComment($videoId);
    }

    public function actionGetVideos()
    {
        $date = date("Y-m-d", time() + 8 * 3600) . "%%";
        return Video::getVideos($date);
    }

    public function actionGetDetail()
    {
        $data = Yii::$app->request->get();
        $videoId = $data['videoId'];

        $data = Yii::$app->request->get();
        $date = date("Y-m-d", time() + 8 * 3600);
        $sevenView = array();
        $sevenComment = array();
        $sevenDate = array();

        for ($i = 0; $i < 7; $i ++) {
            $commentCount = Comment::getCommentCount($date, $videoId);
            $viewCount = View::getViewCount($date, $videoId);
            $sevenComment[] = $commentCount;
            $sevenView[] = $viewCount;
            $date = date('Y-m-d', strtotime($date) - 3600 * 24);
        }

        $date = date("m/d", time() + 8 * 3600);
        for ($i = 0; $i < 7; $i ++) {
            $sevenDate[] = $date;
            $date = date('m/d', strtotime($date) - 3600 * 24);
        }
        $sevenComment = array_reverse($sevenComment);
        $sevenView = array_reverse($sevenView);
        $sevenDate = array_reverse($sevenDate);
        return ['sevenComment' => $sevenComment, 'sevenView' => $sevenView, 'sevenDate' => $sevenDate];
    }
}