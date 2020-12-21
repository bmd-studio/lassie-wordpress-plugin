# Lassie Wordpress Plugin
This repository demonstrates how to integrate the Lassie API in Wordpress for people to be able to login with their Lassie account and manage their data. It also allows for full model, transaction and person management API access for administrative tasks that individuals should not be able to perform.

This plugin is tested with Wordpress 5.3+.

## Prerequisites
1. A wordpress installation.

## Installation
1. Upload the plugin folder `wp-content/plugins/lassie` from this repository through the Wordpress administration interface (for this ZIP it first), or place it inside the `wp-content/plugins` directory directly when you have access to the source code.
2. When the plugin is installed correctly, a menu item will be added to the administration menu called `Lassie`.
3. Fill in your `host url` in the `API settings`, this is de main url of your lassie instance (f.e. `https://YOURNAME.lassie.cloud/api/v2`). Please note that you add the `/api/v2` path suffix as well to identify the correct API version.
4. Fill in your `API Key` and `API Secret` as you have received from the `Lassie Apps Module` (f.e. at `https://YOURNAME.lassie.cloud/apps/applications/`). If you don't have access to this module, please contact the person responsible for Lassie or BMD Studio to upgrade your subscription.
5. Once the API keys are filled in you can make API calls to Lassie via the methods explained below. The full API of the PHP library used by this Wordpress plugin can be found at: [Lassie API PHP](https://github.com/bmd-studio/lassie-api-php).

### Further reading
1. Lassie API PHP implementation: https://github.com/bmd-studio/lassie-api-php
2. Lassie API Documentation (without SDK): https://api-docs.lassie.cloud/
3. Lassie Model Documentation (for the Model API): https://model-docs.lassie.cloud/ 

## Authentication
Users are authenticated through the original Wordpress login process. When the user fills his credentials in the default wp-login form, these credentials are checked in your Lassie-instance. If the result is a valid user, the user will be logged in. When a user logs in with his credentials a new Wordpress-user will be created with the Lassie user-ID as login name to reference later login-actions. When a user logs in for the second time, this same user will be used as login-object.

You have to provide your own logic to redirect a user to the preferred page after login. 

## Model API
The model API fetches Lassie-functions and outputs the results in an array or object. A Lassie-administrator is able to provide the correct access to the API in the Rights-module, which can be found at: https://YOURNAME.lassie.cloud/rights/api_rights/. Every method you are using through the API should be allowed in this module individually.

Documentation on all modules and methods can be found in the [Model API Docs](https://model-docs.lassie.cloud/).

### Examples
List all events that are open for subscription ([Model API Reference](https://model-docs.lassie.cloud/class-Event_model.html#_get_open_events)):
```php
$lassieInstance = Lassie::getLassieApi();
$events = Lassie\Model\EventModel::getOpenEvents($lassieInstance);

foreach($events as $event) {
  echo $event->name."<br />";
}
```

List all active bar products ([Model API Reference](https://model-docs.lassie.cloud/class-Transaction_model.html#_get_bar_products)):
```php
$lassieInstance = Lassie::getLassieApi();
$products = Lassie\Model\TransactionModel::getBarProducts($lassieInstance, array(
  'active_only' => true,
));
 
foreach($products as $product) {
  echo $product->name." - &euro;".$product->retail_price."<br />";
}
```

## Person API
The person API fetches person-specific data from the logged-in user and outputs the results in an array or object. This information can only be retrieved through API-keys that are loaded during login. You should check if there is an existing user before running this API to avoid errors.

The API connects to two functions to retrieve either personal data like addresses and event subscriptions, or all the transactions that are linked to the user.

### Examples

#### Personal information
Get personal information of the logged in user ([API Reference](https://api-docs.lassie.cloud/#api-Person-Get_Person_Information)):
```php
if(is_user_logged_in()){
  $personInstance = Lassie::getPersonApi();
  $personInfo = Lassie\Person::getInformation($personInstance);
  echo $personInfo->first_name."<br />";
}
```

#### Paying for event
Pay for an event via Mollie ([API Reference](https://api-docs.lassie.cloud/#api-Person-Person_Pay_Event___Membership)):
```php
if(is_user_logged_in()){
  $personInstance = Lassie::getPersonApi();
  $paymentResult = Lassie\Person\Event::pay($personInstance, [
    'activity_id' => 2,
    'mollie_redirect_url' => 'https://www.link-to-integration-application.com/',
  ]);
}
```

#### Update information
Updating personal information ([API Reference](https://api-docs.lassie.cloud/#api-Person-Post_Update_Person)):
```php
if(is_user_logged_in()){
  $personInstance = Lassie::getPersonApi();
  $updateResult = Lassie\Person::update($personInstance, [
    'first_name' => 'Pieter',
    'last_name' => 'Jansen',
  ]);
}
```

## Person Auth API
The Person Auth API allows you to login users and with that fetch Person API keys that can access personal information. Please be make sure that any API keys with Person Auth API capabilities are not leaked as they can be used for efficient user login attempts.
### Examples

#### GDPR data request using username and password directly
Note that this is not recommended as this invites you to store the username and password locally. It can come in handy for one-time requests after the user filled in their username and password (e.g. with GDPR requests, [API Reference](https://api-docs.lassie.cloud/#api-Person-Get_Person_Data)).
```php
$lassieInstance = Lassie::getLassieApi();
$personInstance = Lassie\PersonAuth::getPerson($lassieInstance, [
	'username' => 'admin', 
	'password' => 'adminadmin',
]);
$gdprDataResult = Lassie\Person::getData($personInstance);
var_dump($gdprDataResult); exit;
```

#### Getting person API details
You can also get the person API keys manually if you require so using:
```php
$personApiDetails = Lassie::getPersonKeys('admin', 'adminadmin');
var_dump($personApiDetails); exit;
```

## Testing
If you would like to use this repository to test the plugin directly in a Wordpress instance you can use NodeJS, Docker and Docker Compose to do so.

First install all dependencies:
```shell
yarn setup
```

If you would like to change the database password and username you need to update the created `.env` file after `yarn setup`.

Now you can startup the containers:
```
yarn start
```

If you would like to run the container in detached mode:
```
yarn start -d
```

Note that in development the `wp-content/themes` directory is mounted to the local filesystem for quick editing of themes. However, Wordpress sometimes has the tendency to empty this directory when its mounted. It is recommended to go to the `Appearance` tab and re-install one of the themes manually.

#### Updating PHP Lassie API
If you want to update the [Lassie PHP API](https://github.com/bmd-studio/lassie-api-php) to the latest version available you can execute:
```shell
yarn sdk:update
``` 