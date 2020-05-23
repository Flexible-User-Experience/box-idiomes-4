<?php

namespace App\Manager;

use App\Entity\NewsletterContact;
use App\Service\NotificationService;
use DrewM\MailChimp\MailChimp;
use Exception;

/**
 * Class MailchimpManager.
 *
 * @category Manager
 *
 * @author   Anton Serra <aserratorta@gmail.com>
 */
class MailchimpManager
{
    const SUBSCRIBED = 'subscribed';

    /**
     * @var MailChimp
     */
    private $mailChimp;

    /**
     * @var NotificationService
     */
    private $messenger;

    /**
     * Methods.
     */

    /**
     * MailchimpManager constructor.
     *
     * @param NotificationService $messenger
     * @param string              $mak       Mailchimp API key
     *
     * @throws Exception
     */
    public function __construct(NotificationService $messenger, $mak)
    {
        $this->mailChimp = new MailChimp($mak);
        $this->messenger = $messenger;
    }

    /**
     * Mailchimp Manager.
     *
     * @param NewsletterContact $newsletterContact
     * @param string            $listId
     *
     * @return bool $result = false if everything goes well
     */
    public function subscribeContactToList(NewsletterContact $newsletterContact, $listId)
    {
        // make HTTP API request
        $result = $this->mailChimp->post('lists/'.$listId.'/members', array(
            'email_address' => $newsletterContact->getEmail(),
            'status' => self::SUBSCRIBED,
        ));

        // check error
        if (is_array($result) && array_key_exists('status', $result) && ((!array_key_exists('title', $result) && self::SUBSCRIBED === $result['status']) || ((array_key_exists('title', $result) && 'Member Exists' === $result['title'] && 400 === $result['status'])))) {
            $this->messenger->sendCommonNewsletterUserNotification($newsletterContact);
            $result['status'] = self::SUBSCRIBED;
        } else {
            $this->messenger->sendFailureNewsletterSubscriptionAdminNotification($newsletterContact);
        }

        return $result;
    }
}
