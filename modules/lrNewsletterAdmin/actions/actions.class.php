<?php

require_once dirname(__FILE__) . '/../lib/lrNewsletterAdminGeneratorConfiguration.class.php';
require_once dirname(__FILE__) . '/../lib/lrNewsletterAdminGeneratorHelper.class.php';

/**
 * lrNewsletterAdmin actions.
 *
 * @package    LRCMS
 * @subpackage lrNewsletterAdmin
 * @author     Thomas Ohms <http://www.lokarabia.de>
 * @version    SVN: $Id: actions.class.php 26 2010-10-02 18:35:29Z tohms $
 */
class lrNewsletterAdminActions extends autoLrNewsletterAdminActions {

    public function executeSend(sfWebRequest $request) {
        $content = null;
        $this->subscriberCount = 0;
        $this->articles = array();

        $newsletter = LrNewsletterTable::getInstance()->findOneById($request->getParameter('id'));

        $this->subject = $newsletter->subject;

        $subscribers = LrSubscriberTable::getInstance()->findBy('confirmed', true);
        $this->subscriberCount = $subscribers->count();

        foreach ($newsletter->LrArticles->getData() as $article) {
            $this->articles[] = $article->title;
            $content .= $this->renderArticle($article->title, $article->summary);
        }

        $mail = $this->getService('mail')->setTemplate('newsletter');
        $recipients = array();

        foreach ($subscribers as $subscriber) {
            $mail->addValues(array(
                'firstname' => $subscriber->firstname,
                'lastname' => $subscriber->lastname,
                'email' => $subscriber->email,
                'content' => $content,
                'unsubscribe_parameter' => '?remove=' . $subscriber->id,
                'edit_parameter' => '?edit=' . $subscriber->id
            ));

            $recipients[$subscriber->email] = $subscriber->firstname . " " . $subscriber->lastname;
        }

        $mail = $mail->render();
        $mail->getMessage()->setTo($recipients);
        $mail->getMessage()->setSubject($newsletter->subject);

        $mail->send();

        $newsletter->set('sent_at', date("Y-m-d H:i"));
        $newsletter->save();
    }

    protected function renderArticle($title, $summary) {
        $nl = "\r\n";

        $title = "*== " . $title . "==*" . $nl;
        $content = $summary . $nl . $nl;

        return $title . $content;
    }

}
