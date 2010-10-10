<?php
/*

 * actions.class.php
 *
 * Copyright (c) 2010 Thomas Ohms <http://www.lokarabia.de>.
 *
 * This file is part of lrNewsPlugin.
 *
 * lrNewsPlugin is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * lrNewsPlugin is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with lrNewsPlugin.  If not, see <http ://www.gnu.org/licenses/>.
 */

/**
 * Articles actions
 */
class lrArticleActions extends myFrontModuleActions
{
    public function executeFeed($request) {
        $articles = Doctrine_Query::create()->from('LrArticle a')
                ->withI18n()
                ->where('aTranslation.is_active = ?', true)
                ->orderBy('aTranslation.created_at DESC')
                ->limit(sfConfig::get('app_news_maxFeedItems'))
                ->execute();

        $this->feed = new sfRssFeed();

        $this->feed->setAuthorName(sfConfig::get('app_news_feedAuthor'));

        $articleUrl = $this->getHelper()->link('lrArticle/list')->getAbsoluteHref();

        $this->feed->setLink($articleUrl);

        foreach ($articles as $article) {
            $item = new sfFeedItem();

            $item->setTitle($article->title);
            $item->setLink($this->getHelper()->link($article)->getAbsoluteHref());

            if (sfConfig::get('app_news_showAuthor')) {
                $item->setAuthorName($article->Author);
            }

            $item->setUniqueId($article->title . ' (' . $article->id . ')');

            $dateObject = new DateTime($article->createdAt);
            $item->setPubdate($dateObject->format('U'));

            $item->setDescription(
                    $this->getHelper()->media($article->Image)->size(300, 200) .
                    $this->getService('markdown')->toHtml($article->body)
                    );

            $this->feed->addItem($item);
        }

        $this->setLayout(false);
    }

}
