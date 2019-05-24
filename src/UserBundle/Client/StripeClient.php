<?php

namespace UserBundle\Client;

use UserBundle\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Stripe\Charge;
use Stripe\Error\Base;
use Stripe\Stripe;
use Stripe\Customer;

class StripeClient
{
    private $config;
    private $em;
    private $logger;

    public function __construct($secretKey, array $config, EntityManagerInterface $em, LoggerInterface $logger)
    {
        Stripe::setApiKey($secretKey);
        $this->config = $config;
        $this->em = $em;
        $this->logger = $logger;
    }

    /**
     * @param Utilisateur $user
     * @param $token
     * @throws Base
     */
    public function createPremiumCharge(Utilisateur $user, $token)
    {
        try {

            $charge = Charge::create([
                'amount' => $this->config['decimal'] ? $this->config['premium_amount'] * 100 : $this->config['premium_amount'],
                'currency' => $this->config['currency'],
                'description' => 'Payment',
                'source' => $token,
                'receipt_email' => $user->getEmail(),
            ]);
            $customer = Customer::create(array(
                "description" => "Customer TechEvent",
                "email" => $user->getEmail(),
                "source" => $token,
            ));

        } catch (Base $e) {
            $this->logger->error(sprintf('%s exception encountered when creating a premium payment: "%s"', get_class($e), $e->getMessage()), ['exception' => $e]);

            throw $e;
        }

        $user->setChargeId($charge->id);
        $user->setPremium($charge->paid);

        $this->em->flush();
    }

}
