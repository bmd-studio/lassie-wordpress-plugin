# API Instances

In order to facilitate the use of different versions/keys with the Lassie API, the request configs are decoupled from the rest of the API functionality. To use this library, you should initialize a `Lassie\Instance` object and pass this object to every library method.

    new Lassie\Instance($api_host, $api_key, $api_secret)

- `$api_host` The base API endpoint.
- `$api_key` Your application API key.
- `$api_secret` Your application API secret. 

Remember, specific API modules require different authorized API keys within Lassie.

#### Example

    $lassieDev = new Lassie\Instance(
        'http://lassie.example.com/dev/api',
        '2fd373d1eb3df287dbe433d517ad9d47',
        'a6b7099bb08bcb4b716803a6438ca7de',
    );

    $lassieProd = new Lassie\Instance(
        'http://lassie.example.com/api',
        'b73a0134391bfdab0303ec8e564986a0',
        '761f2eeb8703eff38e8e8d2649a6da40',
    );

    // will return response from DEV instance
    Lassie\Model\MembershipModel::getMemberships($lassieDev, ...);

    // will return response from PROD instance
    Lassie\Model\MembershipModel::getMemberships($lassieProd, ...);

### Validate API Instance

You can check that an instance has a valid endpoint and authorized API keys by calling `$instance->validate()`, which will return a boolean value.


#### Example

    $instance = new Lassie\Instance(...);
    $isValid = $instance->validate();
    if (!$isValid) {
        echo 'Oh no!';
    }

# Lassie\App

The App class allows you to access app-related methods. Currently, only the `getKey` method is available. Unlike the rest of the library, the `getKey` method does not require any API keys or instance object, as it retrieves these.

    `Lassie\App::getKey([ 'api_host' => ..., 'auth_token' => ... ])`

- `api_host` The base API endpoint.
- `auth_token` The Lassie App authentication token.

More details can be found [here](https://api-docs.lassie.cloud/#api-Lassie).

#### Example

    $data = Lassie\App::getKey([
        'api_host' => 'http://lassie.example.com/api',
        'auth_token' => 'SECRET'
    ]);
    var_dump($data);

# Lassie\Model

The Model class allows you to access Lassie model methods. Remember to use a model API key in your `Lassie\Instance` and also to grant individual method permissions within Lassie (Rights → API Rights).

To access the model API, you will need the _model name_, _model method_ and any related _model arguments_. A full model API reference can be found [here](https://model-docs.lassie.cloud/). HTTP request type will be determined automatically based on the model method.

    Lassie\Model\<ModelName>::<methodName>(<$lassieInstance>, [...params])

- `ModelName` Model name in PascalCase (will be converted to snake_case automatically).
- `methodName` Method name in camelCase (will be converted to snake_case automatically).
- `lassieInstance` Reference to a `Lassie\Instance` object (see above).
- `params` _optional_ Array containing additional params for the request.

#### Example

	// transaction_model::get_new_generation_transactions
	$transactions = Lassie\Model\TransactionModel::getNewGenerationTransactions(
		$transactionInstance,
		[ 'module_name' => 'bar' ]
	)
	var_dump($transactions);

# Lassie\PersonAuth

The PersonAuth class allows you to access authentication-related methods. Remember to use a Person Auth API key in your `Lassie\Instance`.

    Lassie\PersonAuth::<methodName>(<$lassieInstance>, [...params])

- `methodName` Method name in camelCase (will be converted to snake_case automatically).
- `lassieInstance` Reference to a `Lassie\Instance` object (see above).
- `params` _optional_ Array containing additional params for the request.

The full API reference can be found [here](https://api-docs.lassie.cloud/#api-Person_Auth).

**Note:** The PersonAuth class also includes a helper `Lassie\PersonAuth::getPerson` method, expanded upon in the following section.

#### Example

    $data = Lassie\PersonAuth::check($authInstance, [
        'username' => 'admin',
        'password' => 'SECRET'
    ]);
    var_dump($data);

# Lassie\Person

Using the Lassie Person API requires generating user-specific API keys. The full list of Person API methods, including sub-classes can be found in `DOCS.md`. You can access the Person API in two manners; directly with Person API keys, or with a username and password (which will automatically take care of API key generation).

### If you already have Person API keys

If you already have Person API keys generated, simply create a new `Lassie\Instance` object with the keys and pass it to the `Lassie\Person` methods.
Similarly to the Model API, you can call `Lassie\Person` methods based on the API endpoint, camelCased.

#### Example

    $JohnDoe = new Lassie\Instance($api_host, $person_api_key, $person_api_secret);

    // => GET api/person/groups
	$groups = Lassie\Person::getGroups($JohnDoe);
	var_dump($groups);

    // => POST api/person/membership/pay
    $payment_result = Lassie\Person\Membership::pay($JohnDoe, [
        'activity_id' => 2,
        'mollie_redirect_url' => 'https://www.link-to-integration-application.com/',
    ]);
    var_dump($payment_result);

### If you have a username & password

The Person API keys can also be automatically generated if you have a username and password, using the `Lassie\Person::getPerson(...)` method.

    $person = Lassie\Person::getPerson($instance, [ 'username' => ..., 'password' => ...])

- `$instance` Reference to a `Lassie\Instance` object. Make sure it has `Person Auth API` keys.
- `$username` Self explanatory.
- `$password` Self explanatory. Does not need to be encrypted, as we should be working over HTTPS.

- `$person` A person-specific `Lassie\Instance` object.

#### Example

    $person = Lassie\Person::getPerson($instance, [
        'username' => 'admin',
        'password' => 'SECRET'
    ]);
    $groups = Lassie\Person::getGroups($person);
    var_dump($groups);

# Lassie\Management\\*

The Management class allows you to access management-related methods, currently limited to Person management. Remember to use a Management API key in your `Lassie\Instance`.

    Lassie\Management\<Module>::<methodName>(<$lassieInstance>, [...params])

- `Module` Management module; currently only `Person` is available.
- `methodName` Method name in camelCase.
- `lassieInstance` Reference to a `Lassie\Instance` object (see above).
- `params` _optional_ Array containing additional params for the request.

The full API reference can be found [here](https://api-docs.lassie.cloud/#api-Person_Management).

#### Example

    $response = Lassie\Management\Person::update($managementInstance, [...]);
    var_dump($response);

# Lassie\Transaction

The Transaction class allows you to access transaction-related methods. Remember to use a Transaction API key in your `Lassie\Instance`.

    Lassie\Transaction::<methodName>($lassieInstance, [ ...params ])

- `methodName` Method name in camelCase.
- `$lassieInstance` Reference to a `Lassie.Instance` object (see above).
- `$params` _optional_ Array containing additional params for the request.

The full API reference can be found [here](https://api-docs.lassie.cloud/#api-Transaction).

**Note:** `transaction/account/upgrade` is a shorthand and is not implemented in this library.

#### Example

    $types = Lassie\Transaction::getTypes($instance);
	var_dump($types);
