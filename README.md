# Lassie Wordpress Plugin

## Installation
- Upload the plugin folder through the administration interface, or place it inside the wp-content/plugins folder and extract it.
- When the plugin is installed correctly, a menu item will be added to the administration menu called 'Lassie'.
- Fill in your host url in the API settings, this is de main url of your lassie instance (f.e. 'https://YOURNAME.lassie.cloud').
- Create API-keys for a model-API in Lassie if you want to fetch Lassie-wide information such as events and products
- Create API-keys for a person-auth-API in Lassie if you want to have users login to your website with their Lassie credentials, and fetch personal information such as transactions and subscriptions.
- Fill in your API-keys in he API settings for the parts you are planning to use.
- Implement your own functionality throughout the website using the provided functions.

## Authentication
Users are authenticated through the original Wordpress login process. When the user fills his credentials in the default wp-login form, these credentials are checked in your Lassie-instance. If the result is a valid user, the user will be logged in. When a user logs in with his credentials a new Wordpress-user will be created with the Lassie user-ID as loginname to reference later login-actions. When a user logs in for the second time, this same user will be used as login-object.

You have to provide your own logic to redirect a user to the preferred page after login. 

## Model API
The model API fetches Lassie-functions and outputs the results in an array or object. A Lassie-administrator is able to provide the correct access to the API in the Rights-module. Every method you are using through the API should be allowed in this module individually.

Documentation on all modules and methods can be found in the [Model API Docs](https://model-docs.lassie.cloud/).

### Examples

List all events that are open for subscription
```php
$events = Lassie::getModel('event_model', 'get_open_events');
 
foreach($events as $event) {
     echo $event->name."<br />";
}
```

List all bar products
```php
$products = Lassie::getModel('transaction_model', 'get_bar_products', array('active_only' => true));
 
foreach($products as $product) {
     echo $product->name." - &euro;".$product->external_price."<br />";
}
```

## Person API
The person API fetches person-specific data from the logged-in user and outputs the results in an array or object. This information can only be retrieved through API-keys that are loaded during login. You should check if there is an existing user before running this API to avoid errors.

The API connects to two functions to retrieve either personal data like addresses and event subscriptions, or all the transactions that are linked to the user.

### Examples

#### Personal information
Get personal information of the logged in user
```php
if(is_user_logged_in()){
    $person = Lassie::getPerson();
    echo $person->first_name."<br />";
}
```

#### Payments
$selection determines the account from which the associated payments should be returned. The total amount of transactions that will be returned is capped by a setting in the Lassie administrative panel.

'first' - Payments from the first account balance of the user

'second' - Payments from the second account balance of the user

'other' - Remaining payments such as events and memberships that belong to this user

'all' (default) - All payments that are associated with this user

Get all transactions of the first account
```php
if(is_user_logged_in()){
    $transactions = Lassie::getTransactions('first');
    foreach($transactions as $transaction) {
        echo $transaction->product_name."<br />";
    }
}
```

## Person Management API
The person update API uses the existing Person API keys to update the records of the logged in user. The result is a boolean whether the update was successful or not, and a corresponding error message in case the update failed. Again, you should check if there is an existing user before running this API to avoid errors. 

### Examples

#### Update person
```php
// Update the 'website'-field of the logged in person
$update = Lassie::updatePerson(array('website' => 'www.moeilijkedingen.nl'));
 
// Check if the update was successful
if ($update->status == true){
    echo "Website-field updated!";
} else {
    echo "Something went wrong. Error: ".$update->error;
}
```
