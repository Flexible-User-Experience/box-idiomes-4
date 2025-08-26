<?php

namespace App\Manager;

use App\Entity\NewsletterContact;
use App\Service\NotificationService;
use DrewM\MailChimp\MailChimp;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class MailchimpManager
{
    public const SUBSCRIBED = 'subscribed';

    private MailChimp $mailChimp;
    private NotificationService $messenger;

    public function __construct(NotificationService $messenger, ParameterBagInterface $pb)
    {
        $this->mailChimp = new MailChimp($pb->get('mailchimp_api_key'));
        $this->messenger = $messenger;
    }

    public function subscribeContactToList(NewsletterContact $newsletterContact, $listId)
    {
        // make HTTP API request
        $result = $this->mailChimp->post('lists/'.$listId.'/members', [
            'email_address' => $newsletterContact->getEmail(),
            'status' => self::SUBSCRIBED,
        ]);

        // check error
        if (is_array($result) && array_key_exists('status', $result) && ((!array_key_exists('title', $result) && self::SUBSCRIBED === $result['status']) || (array_key_exists('title', $result) && 'Member Exists' === $result['title'] && 400 === $result['status']))) {
            $this->messenger->sendCommonNewsletterUserNotification($newsletterContact);
            $result['status'] = self::SUBSCRIBED;
        } else {
            $this->messenger->sendFailureNewsletterSubscriptionAdminNotification($newsletterContact);
        }

        return $result;
    }
}
