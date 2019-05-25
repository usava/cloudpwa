import {
    AfterViewInit,
    Component,
    EventEmitter,
    Input,
    NgZone,
    OnDestroy,
    Output,
    ViewEncapsulation
} from '@angular/core';
import {finalize} from 'rxjs/operators';
import {User} from '../../core/types/models/User';
import {Settings} from '../../core/config/settings.service';
import {LazyLoaderService} from '../../core/utils/lazy-loader.service';
import {CurrentUser} from '../../auth/current-user';
import {Subscriptions} from '../../shared/billing/subscriptions.service';
import {Toast} from '../../core/ui/toast.service';

@Component({
    selector: 'credit-card-form',
    templateUrl: './credit-card-form.component.html',
    styleUrls: ['./credit-card-form.component.scss'],
    encapsulation: ViewEncapsulation.None
})
export class CreditCardFormComponent implements OnDestroy, AfterViewInit {

    /**
     * Event fired when form is submitted and card is added successfully on active gateway.
     */
    @Output() created: EventEmitter<User> = new EventEmitter();

    /**
     * Display text for form submit button.
     */
    @Input() submitButtonText = 'Submit';

    /**
     * Whether form submit button should be shown.
     */
    @Input() showSubmitButton = true;

    /**
     * Whether order summary should be shown in the template.
     */
    @Input() showOrderSummary = false;

    /**
     * Whether backend request is in progress.
     */
    public loading = false;

    /**
     * Errors returned from backend.
     */
    public error: string;

    /**
     * Mounted stripe elements.
     */
    private stripeElements: stripe.elements.Element[] = [];

    /**
     * Stripe.js instance.
     */
    private stripe: stripe.Stripe;

    /**
     * Mounted stripe elements.
     */
    private anetElements = [];

    /**
     * Stripe.js instance.
     */
    private anet;

    public paymentGateway: string;

    /**
     * CreditCardFormComponent Constructor.
     */
    constructor(
        private subscriptions: Subscriptions,
        private currentUser: CurrentUser,
        private settings: Settings,
        private zone: NgZone,
        private lazyLoader: LazyLoaderService,
        private toast: Toast,
    ) {
        this.resetForm();
        this.paymentGateway = 'anet';
        if (this.settings.get('billing.stripe.enable')) {
            this.paymentGateway = 'stripe';
        }
    }

    ngAfterViewInit() {

        if (this.settings.get('billing.anet.enable')) {
            this.initAnet();
        } else if (this.settings.get('billing.stripe.enable')) {

            this.initStripe();
        }

    }

    ngOnDestroy() {
        this.destroyStripe();
    }

    /**
     * Submit stripe elements credit card form.
     */
    public async submitForm() {
        // prevent all subscriptions on demo site.
        if (this.settings.get('common.site.demo')) {
            return this.toast.open('You can\'t do that on demo site.');
        }

        // console.log(this, this.settings);
        // debugger;
        this.loading = true;

        if (this.settings.get('billing.anet.enable')) {
            this.sendPaymentDataToAnet();
        } else if (this.settings.get('billing.stripe.enable')) {
            const {token, error} = await this.stripe.createToken(this.stripeElements[0]);

            if (error) {
                this.error = error.message;
                this.loading = false;
            } else {
                this.addCardToUser(token);
            }
        }
    }

    public addCardToUser(stripeToken: stripe.Token) {
        this.loading = true;

        this.subscriptions.addCard(stripeToken.id)
            .pipe(finalize(() => this.loading = false))
            .subscribe(response => {
                this.resetForm();
                this.currentUser.assignCurrent(response.user);
                this.created.emit(response.user);
            }, response => {
                this.error = response.messages.general;
            });
    }

    /**
     * Initiate stripe elements credit card form.
     */
    private initStripe() {
        this.lazyLoader.loadScript('https://js.stripe.com/v3').then(() => {
            const fields = ['cardNumber', 'cardExpiry', 'cardCvc'] as stripe.elements.elementsType[];
            this.stripe = Stripe(this.settings.get('billing.stripe_public_key'));
            const elements = this.stripe.elements();

            fields.forEach(field => {
                const el = elements.create(field, {classes: {base: 'base'}});
                el.mount('#' + field);
                el.on('change', this.onChange.bind(this));
                this.stripeElements.push(el);
            });
        });
    }

    /**
     * Destroy all stripe elements instances.
     */
    private destroyStripe() {
        this.stripeElements.forEach(el => {
            el.unmount();
            el.destroy();
        });
    }

    /**
     * Fired on stripe element "change" event.
     */
    private onChange(change: stripe.elements.ElementChangeResponse) {
        this.zone.run(() => {
            this.error = change.error ? change.error.message : null;
        });
    }

    /**
     * Reset credit card form.
     */
    private resetForm() {
        this.error = null;
    }

    private initAnet() {
        this.lazyLoader.loadScript('https://jstest.authorize.net/v1/Accept.js');
        this.lazyLoader.loadScript('https://jstest.authorize.net/v3/AcceptUI.js');
            // .then(() => {
                // const fields = ['cardNumber', 'cardExpiry', 'cardCvc'] as stripe.elements.elementsType[];
                // this.stripe = Stripe(this.settings.get('billing.stripe_public_key'));
                // const elements = this.stripe.elements();
                //
                // fields.forEach(field => {
                //     const el = elements.create(field, {classes: {base: 'base'}});
                //     el.mount('#' + field);
                //     el.on('change', this.onChange.bind(this));
                //     this.stripeElements.push(el);
                // });
            // });
    }

    private sendPaymentDataToAnet() {
        const authData = {
            clientKey: '' as string,
            apiLoginID: '' as string
        };
        authData.clientKey = this.settings.get('billing.anet_public_client_key');
        authData.apiLoginID = this.settings.get('billing.anet_api_login_id');

        const cardData = {
            cardNumber: '' as string,
            month: '' as string,
            year: '' as string,
            cardCode: '' as string
        };
        cardData.cardNumber = document.getElementById("cardNumber")['value'];
        cardData.month = document.getElementById("expMonth")['value'];
        cardData.year = document.getElementById("expYear")['value'];
        cardData.cardCode = document.getElementById("cardCode")['value'];

        const secureData = {
            authData: '' as any,
            cardData: '' as any
        };
        secureData.authData = authData;
        secureData.cardData = cardData;

        // @ts-ignore
        Accept.dispatchData(secureData, responseHandler);

        function responseHandler(response) {
            if (response.messages.resultCode === "Error") {
                var i = 0;
                while (i < response.messages.message.length) {
                    console.log(
                        response.messages.message[i].code + ": " +
                        response.messages.message[i].text
                    );
                    i = i + 1;
                }
            } else {
                this.paymentFormUpdate(response.opaqueData);
            }
        }
    }

    private paymentFormUpdate(opaqueData) {
        document.getElementById("dataDescriptor")['value'] = opaqueData.dataDescriptor;
        document.getElementById("dataValue")['value'] = opaqueData.dataValue;

        // If using your own form to collect the sensitive data from the customer,
        // blank out the fields before submitting them to your server.
        // document.getElementById("cardNumber").value = "";
        // document.getElementById("expMonth").value = "";
        // document.getElementById("expYear").value = "";
        // document.getElementById("cardCode").value = "";

        // document.getElementById("paymentForm").submit();
    }
}
