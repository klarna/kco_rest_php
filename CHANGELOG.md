## v4.1.4 - 2019-03-15
- Add full support of Instant Shopping API;
- Repo now has an Apache 2.0 LICENSE file on its root;
- HTTP Transport: Add support of PUT method;
- HTTP Transport: Stop throwing an exception when an API service return a bad structured Error;
- Examples: Add Instant Shopping examples.


## v4.1.3 - 2019-01-23
- HPP API: Add support for disabling an HPP session;
- Customer Token API: Add ability to use Klarna-Idempotency-Key when creating order;
- Customer Token API: Add new feature: Update token status;
- Examples: Update all example files. Add more information about getting credentials;
- Examples: Add example of changing the User-Agent.


## v4.1.2 - 2018-11-22
- Fix: Order management API threw Error Notice when fetching an order with refunds.

## v4.1.1 - 2018-10-31
- HPP: HPP service changed API completely without backward compatibility. Adopt SDK to the new changes.
    Mark getSessionStatus as @deprecated. Replaced by fetch function.
    Return data was changed by HPP API service.
    **[partial-backward-compatibility]**
- Add support of Merchant Card Service API

## v4.0.0 - 2018-08-27 (Major release)
- OrderManagement:
    * Add ability to fetch Captures;
    * Add support of Refunds **[partial-backward-compatibility]**;
- Add full support of Customer Token API;
- Add full support of Settlements API;
- Add full support of Payments API;
- Add full support of Hosted Payment Page API;
- Add 'Debug Mode' to be able to debug requests and responses;
- Put SDK References documentation to a GH Pages:
    https://klarna.github.io/kco_rest_php/
- Fix: Settlements API [Unexpected Header #15](https://github.com/klarna/kco_rest_php/issues/15);
- More Examples for all Klarna Services.

**BACKWARD COMPATIBILITY NOTES**
- OrderManagementAPI: Changed `refund` function. Before returned `$this`, now returns - `Refund` object;
- OrderManagementAPI: Order object now has an `array` of `Refund` objects instead of just array of data.
    **[backward-compatible]**


## v3.0.1 - 2017-01-16
- smaller fixes

## v3.0.0 - 2017-12-12

- support for guzzle >6.0

## v2.2.0 - 2015-12-7
- **NEW META-13** Allow for 201 response on refund - *Joakim.L*

## v2.1.0 - 2015-07-29
- **NEW MINT-2262** Support Guzzle 5.x versions - *Omer.K, Joakim.L*

## v2.0.0 - 2015-06-10
- **NEW MINT-2203** Use order id instead of URL for checkout orders - *Joakim.L*
- **NEW MINT-2214** Add base URLs for North America - *Joakim.L*

## v1.0.1 - 2015-03-30
- **FIX MINT-2002** Handle errors with an empty payload - *David.K*
- **NEW MINT-2097** Add apigen and custom styling - *Petros.G*

## v1.0.0 - 2014-10-16
- **NEW MINT-1804** Support checkout v3 and ordermanagement v1 APIs - *Joakim.L*
