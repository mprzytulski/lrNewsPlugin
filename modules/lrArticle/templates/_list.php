<?php

// Vars: $lrArticlePager

use_helper('Date');

echo $lrArticlePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($lrArticlePager as $lrArticle) {
    $author = null;
    if (sfConfig::get('app_news_showAuthor')) {
        $author = _tag('span', $lrArticle->Author) . ' | ';
    }

    echo _open('li.element');

    echo _tag('h2.t_medium', _link($lrArticle));

    echo markdown($lrArticle->summary, '.summary');

    echo _tag('p.lr_article_infos', _tag('span', format_date($lrArticle->createdAt, 'D')) . ' | ' .
            $author .
            _link($lrArticle)->text(__('Read more') . ' ...')
    );

    echo _close('li');
}

echo _close('ul');

echo $lrArticlePager->renderNavigationBottom();