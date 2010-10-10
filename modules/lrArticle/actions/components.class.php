<?php
/*

 * components.class.php
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
 * Articles components
 * 
 * No redirection nor database manipulation ( insert, update, delete ) here
 */
class lrArticleComponents extends myFrontModuleComponents
{

  public function executeList()
  {
    $query = $this->getListQuery();
    
    $this->lrArticlePager = $this->getPager($query);

    $this->lrArticlePager->setOption('ajax', 'true');
  }

  public function executeListSide()
  {
    $query = $this->getListQuery();
    
    $this->lrArticlePager = $this->getPager($query);
  }

  public function executeShow()
  {
    $query = $this->getShowQuery();
    
    $this->lrArticle = $this->getRecord($query);
  }


}
