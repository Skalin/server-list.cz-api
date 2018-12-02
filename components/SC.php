<?php
/**
 * Project: hicoria-hosting
 * User: Dominik
 * Date: 21.10.2018
 * Time: 15:00
 */

namespace app\components;


use yii\helpers\FileHelper;
use yii\web\Controller;

class SC extends Controller
{

    public static function getBaseUrl()
    {
        return \Yii::$app->urlManager->baseUrl;
    }

    public static function getThemeFile($file)
    {
            return FileHelper::normalizePath(\Yii::$app->urlManager->baseUrl.($file ? '/'.$file : ''));
    }
}