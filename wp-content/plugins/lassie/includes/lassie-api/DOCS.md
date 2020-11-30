# API Instance

    $instance = new Lassie\Instance($api_host, $api_key, $api_secret);

    $isValidInstance = $instance->validate();

# Lassie App

    $appKey = Lassie\App::getKey([ 'api_host' => ..., 'auth_token' => ...]);

# Model
_Requires Model API keys_

    $data = Lassie\Model\<ModelName>Model::<MethodName>($instance, [ params... ]);

See [model-docs.lassie.cloud](https://model-docs.lassie.cloud/) for model and method names.

# Person Auth
_Requires Person Auth API keys_

- `$data = Lassie\PersonAuth::create($instance, [ params... ]);`
- `$data = Lassie\PersonAuth::reset($instance, [ params... ]);`
- `$data = Lassie\PersonAuth::check($instance, [ params... ]);`

- `$personInstance = Lassie\PersonAuth::getPerson($instance, [ 'username' => ..., 'password' => ...]);`

Note: this is a helper function; the callback returns a `Lassie\Instance` object with the Person API keys.

# Person
_Requires Person API keys (valid Instance object can be retrieved using `Lassie\PersonAuth::getPerson`)_

- `$data = Lassie\Person::getGroups($instance, [ params... ]);`
- `$data = Lassie\Person::getPayments($instance, [ params... ]);`
- `$data = Lassie\Person::getInformation($instance, [ params... ]);`
- `$data = Lassie\Person::update($instance, [ params... ]);`

- `$data = Lassie\Person\Account::create($instance, [ params... ]);`
- `$data = Lassie\Person\Account::deactivate($instance, [ params... ]);`
- `$data = Lassie\Person\Account::invite($instance, [ params... ]);`
- `$data = Lassie\Person\Account::accept($instance, [ params... ]);`
- `$data = Lassie\Person\Account::revoke($instance, [ params... ]);`
- `$data = Lassie\Person\Account::select($instance, [ params... ]);`
- `$data = Lassie\Person\Account::transfer($instance, [ params... ]);`
- `$data = Lassie\Person\Account::upgrade($instance, [ params... ]);`

- `$data = Lassie\Person\Event::pay($instance, [ params... ]);`
- `$data = Lassie\Person\Membership::pay($instance, [ params... ]);`

- `$data = Lassie\Person\API::revoke($instance, [ params... ]);`

# Person Management
_Requires Person Management API keys_

- `$data = Lassie\Management\Person::create($instance, [ params... ]);`
- `$data = Lassie\Management\Person::update($instance, [ params... ]);`

# Transaction
_Requires Transaction API keys_

- `$data = Lassie\Transaction::getTransaction($instance, [ params... ]);`
- `$data = Lassie\Transaction::postTransaction($instance, [ params... ]);`

- `$data = Lassie\Transaction::getAccount($instance, [ params... ]);`
- `$data = Lassie\Transaction::getProductCategories($instance, [ params... ]);`
- `$data = Lassie\Transaction::getProducts($instance, [ params... ]);`
- `$data = Lassie\Transaction::getTransactionTypes($instance, [ params... ]);`

Note: `transaction/account/upgrade` is a shorthand and will not be implemented.
