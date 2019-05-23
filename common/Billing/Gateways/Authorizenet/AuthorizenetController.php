<?php namespace Common\Billing\Gateways\Authorizenet;

use Common\Auth\User;
use Common\Billing\BillingPlan;
use Common\Billing\Subscription;
use Illuminate\Http\Request;
use Common\Core\Controller;
use Omnipay\Common\Exception\InvalidCreditCardException;

class AuthorizenetController extends Controller
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var BillingPlan
     */
    private $billingPlan;

    /**
     * @var Subscription
     */
    private $subscription;

    /**
     * @var authorizenetGateway
     */
    private $authorizenet;

    /**
     * SubscriptionsController constructor.
     *
     * @param Request $request
     * @param BillingPlan $billingPlan
     * @param Subscription $subscription
     * @param authorizenetGateway $authorizenet
     */
    public function __construct(
        Request $request,
        BillingPlan $billingPlan,
        Subscription $subscription,
        AuthorizenetGateway $authorizenet
    )
    {
        $this->authorizenet = $authorizenet;
        $this->request = $request;
        $this->billingPlan = $billingPlan;
        $this->subscription = $subscription;

        $this->middleware('auth');
    }

    /**
     * Create a new subscription on authorizenet.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Common\Billing\GatewayException
     */
    public function createSubscription()
    {
        $this->validate($this->request, [
            'plan_id' => 'required|integer|exists:billing_plans,id',
            'start_date' => 'string'
        ]);

        /** @var User $user */
        $user = $this->request->user();
        $plan = $this->billingPlan->findOrFail($this->request->get('plan_id'));

        $sub = $this->authorizenet->subscriptions()->create($plan, $user, $this->request->get('start_date'));
        $user->subscribe('authorizenet', $sub['reference'], $plan);

        return $this->success(['user' => $user]);
    }

    /**
     * Add a new bank card to user using authorizenet token.
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Common\Billing\GatewayException
     */
    public function addCard()
    {
        $this->validate($this->request, [
            'token' => 'required|string',
        ]);

        try {
            $user = $this->authorizenet->addCard($this->request->user(), $this->request->get('token'));
        } catch (InvalidCreditCardException $e) {
            return $this->error(['general' => $e->getMessage()]);
        }

        return $this->success(['user' => $user]);
    }
}