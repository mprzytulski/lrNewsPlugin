<?php // Vars: $lrArticlePager

echo $lrArticlePager->renderNavigationTop();

echo _open('ul.elements');

foreach ($lrArticlePager as $lrArticle)
{
  echo _open('li.element');

    echo _link($lrArticle);

  echo _close('li');
}

echo _close('ul');

echo $lrArticlePager->renderNavigationBottom();