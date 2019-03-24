<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/stripe", name="stripe")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $cu = null;

        try {
            \Stripe::setApiKey("sk_test_2P2be7eX0UgrFkr0lYnxgj09");

            $cu = $this->retrieveCustomer('cus_9kzXMTHWFRd0oO');

        } catch (\Stripe_CardError $e) {
            var_dump($e);
        }


        return array(
            'cu' => $cu
        );
    }

    /**
     * @Route("/stripe/card/add", name="stripe_add_card")
     * @Method("GET")
     * @Template()
     */
    public function addCardAction()
    {
        $cu = null;

        try {
            \Stripe::setApiKey("sk_test_2P2be7eX0UgrFkr0lYnxgj09");

            $cu = $this->retrieveCustomer('cus_9kzXMTHWFRd0oO');

        } catch (\Stripe_CardError $e) {
            var_dump($e);
        }


        return array(
            'cu' => $cu
        );
    }

    /**
     * @Route("/stripe/charge/{card}", name="stripe_charge")
     * @Method("GET")
     * @Template()
     */
    public function createCharge($card)
    {
        try {
            \Stripe::setApiKey("sk_test_C9UvuMeDNd6ETeHT2ENQVP4t");

            \Stripe_Charge::create(array(
                "amount" => 2000,
                "currency" => "gbp",
                "customer" => "cus_9kzXMTHWFRd0oO",
                "card" => $card,
                "description" => "ORDER ID: 128979870"
            ));

        } catch (\Stripe_CardError $e) {
        }

    }

    public function createToken()
    {
        \Stripe::setApiKey("sk_test_2P2be7eX0UgrFkr0lYnxgj09");

        $token = \Stripe_Token::create(array(
            "card" => array(
                "number" => "5555555555554444",
                "exp_month" => 12,
                "exp_year" => 2018,
                "cvc" => "314"
            )
        ));

        var_dump($token);
    }


    /**
     * @Route("/stripe/create", name="stripe_customer")
     */
    public function createCustomerWithTokenAction()
    {

        $token = "tok_19S59GF6rY2WfK3gFqhvXMXQ";
        $card = "card_19S59GF6rY2WfK3gbAv8PKG6";

       // $this->createToken();

        try {
            \Stripe::setApiKey("sk_test_2P2be7eX0UgrFkr0lYnxgj09");


//            $cu = $this->retrieveCustomer('cus_9kzXMTHWFRd0oO');

            $this->createCustomer('a@a.com');

           // $this->makeDefaultCard($cu, 'card_19S5KsF6rY2WfK3gTYeqsTpw');

            //$cu->description = "Customer for aiden.williams@example.com";
           // $cu->sources->create(array("source" => $token));
          //  $cu->save();

        } catch (\Stripe_CardError $e) {
            var_dump($e);
        }

        //echo '<pre>';
        //var_dump($cu->sources->data[1]->exp_month);
    }



    public function createCustomer($email)
    {
        $cu = \Stripe_Customer::create(array(
            "description" => $email,
            "email" => $email
        ));

        return $cu;
    }

    public function retrieveCustomer($cus_id)
    {
        $cu = \Stripe_Customer::retrieve($cus_id);

        return $cu;
    }

    public function deleteCustomer($cu)
    {
        $cu->delete();
    }

    public function addCard($cu, $token)
    {
        $cu->sources->create(array("source" => $token));
    }


    public function deleteCard($cu, $card_id)
    {
        $cu->sources->retrieve($card_id)->delete();
    }


    public function makeDefaultCard($cu, $card_id)
    {
        $cu->default_source = $card_id;
        $cu->save();
    }
}
