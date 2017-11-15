<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace common\modules\article\helpers;

use common\modules\user\models\BlogUser;
use common\modules\article\models\Article;
use common\modules\comment\models\Comment;

/**
 * Description of ArticleHelper
 *
 * @author makszipeter
 */
class ArticleHelper {

    /**
     * 
     * @param int $statusId
     * @param int $userId
     * @return int
     * returns with the number of the articles with specified status. It will be used at the statistic page
     * status could be draft or deleted
     */
    public static function getNumberOfArticlesWithSpecifiedStatus($statusId, $userId) {
        return Article::find()->where(['user_id' => $userId])->andWhere(['status' => $statusId])->count();
    }

    /**
     * 
     * @param int $userId
     * @return int
     */
    public static function getNumberOfActiveArticles($userId) {
        return Article::find()->where(['user_id' => $userId])->andWhere(['not in', 'status', [Article::DRAFT, Article::DELETED]])->andWhere(['<', 'publicated_at', time()])->count();
    }

    /**
     * 
     * @param int $userId
     * @return int
     */
    public static function getNumberOfUnpublicatedArticles($userId) {
        return Article::find()->where(['user_id' => $userId])->andWhere(['>', 'publicated_at', time()])->count();
    }

    /**
     * 
     * @param int $userId
     * @return Article[]
     */
    public static function getTopFiveMostReadedArticles($userId) {
        $userModel = BlogUser::findOne($userId);
        return $userModel->getArticles()->orderBy(['visits' => SORT_DESC])->limit(5)->all();
    }

    /**
     * 
     * @param int $userId
     * @return Article[]
     */
    public static function getTopFiveMostCommentedArticles($userId) {
        $userModel = BlogUser::find()->where(['id' => $userId])->with(['articles'])->one();
        $articleIds = [];
        foreach ($userModel->articles as $article) {
            $articleIds[] = $article->id;
        }

        $sortedArticleIds = Comment::getMostCommentedArticleIdsForSpecifiedUser($articleIds);
        $articleNamesCommentNumberArray = [];
        foreach ($sortedArticleIds as $value) {
            $articleNamesCommentNumberArray[] = ['title' => Article::findOne($value['article_id'])->attributes['title'], 'coms' => $value['cnt']];
        }

        return $articleNamesCommentNumberArray;
    }

    /**
     * 
     * @param int $limit
     * @return Article[]
     */
    public static function getNewestArticles($limit = 5) {
        return Article::find()->orderBy(['publicated_at' => SORT_DESC])->where(['status' => Article::ACTIVE])->limit($limit)->all();
    }

    /**
     * 
     * @return yii\db\Query
     */
    public static function getActiveArticleQuery() {
        return Article::find()->where(['<', 'publicated_at', time()])->
                        andWhere(['status' => Article::ACTIVE])->
                        orderBy(['publicated_at' => SORT_DESC])->with(['image', 'user', 'comments']);
    }

    /**
     * 
     * @param int $userid
     * @return  yii\db\Query
     */
    public static function getArticleQueryFromUser($userid) {
        return Article::find()->where(['user_id' => $userid])->andWhere(['<', 'publicated_at', time()])->
                        andWhere(['status' => Article::ACTIVE])->orderBy(['publicated_at' => SORT_DESC])->
                        with(['image', 'user', 'comments']);
    }

    /**
     * 
     * @param int[] $userIdList
     * @return  yii\db\Query
     */
    public static function getSubbedArticleQuery($userIdList) {
        return Article::find()->where(['in', 'user_id', $userIdList])->andWhere(['=', 'status', Article::ACTIVE])->
                        orderBy(['publicated_at' => SORT_DESC])->with(['image', 'user', 'comments']);
    }

}
