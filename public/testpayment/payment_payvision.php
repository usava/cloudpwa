<?php
define('PAYVISION_DYNAMIC_DESCRIPTOR',		'OptimalPlay UniqueCasino');
print_r ($_GET);

	$use3ds      = true;
	$use3ds_only = false;
       // test
        $url		 = 'https://testprocessor.payvisionservices.com/GatewayV2/BasicOperationsService.svc';
        $url_3ds	 = 'https://testprocessor.payvisionservices.com/GatewayV2/ThreeDSecureService.svc';
        $memberId	 = '100270156';
        $memberGuid	 = '325374E8-3DE2-470A-84A1-46C896DD2B49';
        $merchantAccountType = 1;

		$country_iso_code_numeric = 376;
		$currency_code_numeric = 978;

		$transaction_id = 'uniq-'.time();

        $amount_send 	 =	25;
        $cc_number 	= $_GET['cc_number'];
        $cc_exp_month=$_GET["cc_exp_month"];
        $cc_exp_year=$_GET["cc_exp_year"];
        $cc_cvv=$_GET["cc_cvv"];
        $cc_cardholder = $_GET['first_name'].' '.$_GET['last_name'];

 //   PayvisionPayment ();
        $url_Payment = $url . '/json/Payment';

	$request = [
		'memberId'				=> $memberId,								// Long,			This value is provided by Payvision and is used to authenticate a merchant.
		'memberGuid'			=> $memberGuid,								// String,			This value is provided by Payvision and is used to authenticate a merchant.
		'countryId'				=> $country_iso_code_numeric,				// Int,				ISO 3166 Country Code (numeric) that indicates the country where the transaction took place
		'amount'				=> $amount_send,							// Decimal,			The transaction amount. The decimal separator must be a point, i.e.:“.”
		'currencyId'			=> $currency_code_numeric,					// Int,				The ISO 4217 Currency Code (numeric) that indicates the currency of the transaction.
		'trackingMemberCode'	=> $transaction_id,							// String,			This value is the merchant’s order number or tracking code. Max. length of 100 chars. Must be unique within the preceding 24 hours.
		'cardNumber'			=> $cc_number,								// String,			Card holder account number.
		'cardholder'			=> $cc_cardholder,							// String,			Card holder name as it appears on the card.
		'cardExpiryMonth'		=> $cc_exp_month,							// unsignedByte,	The card expiration month. Valid values are numbers from 1 to 12 inclusive.
		'cardExpiryYear'		=> $cc_exp_year,							// Short,			The card expiration year expressed in 4 digits, i.e. 2007.
		'cardCvv'				=> $cc_cvv,									// String,			Visa (CVV2), MasterCard (CVC2) and AmEx (CID)
		'merchantAccountType'	=> $merchantAccountType,					// Int,				Type of merchant account: 1 – E-Commerce; 2 – Mail Order/Telephone order; 4 – Recurring;
		'dbaName'				=> PAYVISION_DYNAMIC_DESCRIPTOR,			// String,			The first part of the dynamic descriptor.
		'dbaCity'				=> PAYVISION_DYNAMIC_DESCRIPTOR,			// String,			The second part of the dynamic descriptor.
		'avsAddress'			=> 'Address cardholder', 								// String,			The street address for AVS verification.
		'avsZip'				=> '22045', 								// String,			The ZIP Code or postal code for AVS verification.
		'additionalInfo'		=> ''										// String,			In this parameter you can send any additionally required information in JSON format.
	];

	$data_string = json_encode($request);

print_r($data_string);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_URL, $url_Payment);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Accept: application/json',
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string))
	);

	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);

print_r($response);

