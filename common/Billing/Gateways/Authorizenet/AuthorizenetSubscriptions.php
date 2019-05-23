<?php namespace Common\Billing\Gateways\Authorizenet;

use Carbon\Carbon;
use Common\Billing\BillingPlan;
use Common\Auth\User;
use Common\Billing\Subscription;
use Omnipay\Authorizenet\CIMGateway as Gateway;
use Common\Billing\GatewayException;
use Common\Billing\Gateways\Contracts\GatewaySubscriptionsInterface;

class AuthorizenetSubscriptions implements GatewaySubscriptionsInterface
{
    /**
     * @var Gateway
     */
    private $gateway;

    /**
     * AuthorizenetSubscriptions constructor.
     *
     * @param Gateway $gateway
     */
    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Fetch specified subscription's details from gateway.
     *
     * @param Subscription $subscription
     * @return array
     * @throws GatewayException
     */
    public function find(Subscription $subscription)
    {
        $response = $this->gateway->fetchSubscription([
            'subscriptionReference' => $subscription->gateway_id,
            'customerReference' => $subscription->user->stripe_id,
        ])->send();

        if ( ! $response->isSuccessful()) {
            throw new GatewayException("Could not find authorizenet subscription: {$response->getMessage()}");
        }

        return [
            'renews_at' => Carbon::parse($response->getData()['current_period_end']),
        ];
    }

    /**
     * Create a new subscription on authorizenet using specified plan.
     *
     * @param BillingPlan $plan
     * @param User $user
     * @param null $startDate
     * @return array
     * @throws GatewayException
     */
    public function create(BillingPlan $plan, User $user, $startDate = null)
    {
        if ($user->subscribedTo($plan, 'authorizenet')) {
            throw new \LogicException("User already subscribed to '{$plan->name}' plan.");
        }

        $request = $this->gateway->createSubscription([
            'customerReference' => $user->stripe_id,
            'plan' => $plan->uuid,
        ]);

        $data = $request->getData();
        $data['trial_end'] = $startDate ? Carbon::parse($startDate)->getTimestamp() : 'now';
        $response = $request->sendData($data);

        if ( ! $response->isSuccessful()) {
            throw new GatewayException("Authorizenet subscription creation failed: {$response->getMessage()}");
        }

        return [
            'reference' => $response->getSubscriptionReference(),
            'end_date' => $response->getData()['current_period_end']
        ];
    }

    /**
     * Cancel specified subscription on authorizenet.
     *
     * @param Subscription $subscription
     * @param bool $atPeriodEnd
     * @return bool
     * @throws GatewayException
     */
    public function cancel(Subscription $subscription, $atPeriodEnd = true)
    {
        $response = $this->gateway->cancelSubscription([
            'subscriptionReference' => $subscription->gateway_id,
            'customerReference' => $subscription->user->stripe_id,
            'at_period_end' => $atPeriodEnd,
        ])->send();

        if ( ! $response->isSuccessful()) {
            throw new GatewayException("Authorizenet subscription cancel failed: {$response->getMessage()}");
        }

        return true;
    }

    /**
     * Resume specified subscription on authorizenet.
     *
     * @param Subscription $subscription
     * @param array $params
     * @return bool
     * @throws GatewayException
     */
    public function resume(Subscription $subscription, $params)
    {
        $response = $this->gateway->updateSubscription(array_merge([
            'plan' => $subscription->plan->uuid,
            'customerReference' => $subscription->user->stripe_id,
            'subscriptionReference' => $subscription->gateway_id,
        ], $params))->send();

        if ( ! $response->isSuccessful()) {
            throw new GatewayException("Authorizenet subscription resume failed: {$response->getMessage()}");
        }

        return true;
    }

    /**
     * Change billing plan of specified subscription.
     *
     * @param Subscription $subscription
     * @param BillingPlan $newPlan
     * @return boolean
     * @throws GatewayException
     */
    public function changePlan(Subscription $subscription, BillingPlan $newPlan)
    {
        $response = $this->gateway->updateSubscription([
            'plan' => $newPlan->uuid,
            'customerReference' => $subscription->user->stripe_id,
            'subscriptionReference' => $subscription->gateway_id,
        ])->send();

        if ( ! $response->isSuccessful()) {
            throw new GatewayException("Authorizenet subscription plan change failed: {$response->getMessage()}");
        }

        return true;
    }
}