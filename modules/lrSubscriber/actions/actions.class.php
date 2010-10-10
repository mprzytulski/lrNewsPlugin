<?php
/**
 * Subscribers actions
 */
class lrSubscriberActions extends dmFrontBaseActions
{

  public function preExecute() {
        $subscribers = LrSubscriberTable::getInstance()->findBy('confirmed', false);
        
        if ($subscribers->count() > 0) {
            foreach ($subscribers->getData() as $subscriber) {
                $currentTime = time();
                $limit = sfConfig::get('app_news_wait4ConfirmationHours') * 60 * 60;
                $registered = strtotime($subscriber['updated_at']);
                
                if (($currentTime - $registered) >= $limit) {
                    $subscriber->delete();
                }
            }
        }
        
        $subscribers->free();

        parent::preExecute();
    }

  public function executeFormWidget(dmWebRequest $request)
  {
    if ($request->hasParameter('confirm')) {
        $this->confirm($request->getParameter('confirm'));
    }

    if ($request->hasParameter('remove')) {
        $this->remove($request->getParameter('remove'));
    }

    if ($request->hasParameter('edit')) {
        $editSubscriber = LrSubscriberTable::getInstance()->findOneById($request->getParameter('edit'));

        $form = new LrSubscriberForm($editSubscriber);
    } else {
        $form = new LrSubscriberForm();
    }
        
    if ($request->hasParameter($form->getName()) && $form->bindAndValid($request))
    {
      $subscriber = $form->save();

      $this->sendConfirmation(
              $subscriber->getFirstname(),
              $subscriber->getLastname(),
              $subscriber->getEmail(),
              $subscriber->getId()
              );

      $this->getUser()->setFlash('subscription_form_valid', true);

      $this->redirectBack();
    }
    
    $this->forms['LrSubscriber'] = $form;
  }

  protected function confirm($id) {
      if (!is_numeric($id)) {
          return;
      }

      $this->email = null;

      $subscriber = LrSubscriberTable::getInstance()->find($id);

      if ($subscriber === FALSE) {
          $this->getUser()->setFlash('confirm_mail_invalid', true);
      } else {
          $subscriber->set('confirmed', true);
          $subscriber->save();
          $this->getUser()->setFlash('confirm_mail_valid', true);
          $this->getUser()->setFlash('email', $subscriber->email);
      }

  }

  protected function remove($id) {
      if (!is_numeric($id)) {
          return;
      }

      $this->email = null;

      $subscriber = LrSubscriberTable::getInstance()->find($id);

      if ($subscriber === FALSE) {
          $this->getUser()->setFlash('remove_mail_invalid', true);
      } else {
          $this->getUser()->setFlash('remove_mail_valid', true);
          $this->getUser()->setFlash('email', $subscriber->email);
          $subscriber->delete();
      }
  }

  protected function sendConfirmation($firstname, $lastname, $email, $id) {
      $confirm = '?confirm=' . $id;

      $this->getService('mail')
              ->setTemplate('confirm_newsletter_subscription')
              ->addValues(array(
                  'firstname'   => $firstname,
                  'lastname'    => $lastname,
                  'email'       => $email,
                  'confirm_parameter' => $confirm,
                  'confirm_limit'=> sfConfig::get('app_news_wait4ConfirmationHours')
              ))
              ->send();
  }

}
