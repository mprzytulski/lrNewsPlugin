<?php // Vars: $lrArticle

use_helper('Date');

$author = null;
if (sfConfig::get('app_news_showAuthor')) {
    $author =  _tag('span', ' | ' . $lrArticle->Author);
}

echo _open('div.clearfix');

echo _tag('h1.t_big', $lrArticle->title);

echo _tag('p.lr_article_infos',
        _tag('span', format_date($lrArticle->createdAt, 'D'))  .
        $author
        );

echo _media($lrArticle->Image)->size(200, 200)->set('.image');

echo markdown($lrArticle->body);

echo _close('div');