<?php

require_once 'Lassie.php';

// var_dump(Lassie\App::getKey([
//     'api_host' => 'https://demo.lassie.cloud/api/v2',
//     'auth_token' => 'SECRET'
// ]));
// $modelInstance = new Lassie\Instance(
// 	'https://demo.lassie.cloud/api/v2',
// 	'208d8278e794ab4477c26ca7dfa0c1cf',
// 	'3c7ce7c4311a47cf4392a215a02ab2ca'
// );
// var_dump(Lassie\Model\TransactionModel::getNewGenerationTransactions($modelInstance, [
// 	'module_name' => 'bar',
// 	'last_transaction_id' => 10
// ]));


// $authInstance = new Lassie\Instance(
//    'https://demo.lassie.cloud/api/v2',
//    '8c88597e5f115573d67c06ed2d03c79a',
//    'f039ea1fd94263df6e97a94c6a10deb8',
//    true
// );
// $person = Lassie\PersonAuth::check($authInstance, [
//     'username' => 'admin',
//     'password' => 'adminadmin'
// ]);
// var_dump($person);
// var_dump(Lassie\Person::getPayments($person));

// $managementInstance = new Lassie\Instance(
//    'https://demo.lassie.cloud/api/v2',
//    'f15d520e79a76d034a480397cc3e3f89',
//    'c8b91a5c9d360693507abf8e9675be70',
//    true
// );
// var_dump($managementInstance->validate());
// var_dump(Lassie\Management\Person::create($managementInstance));

// $transactionInstance = new Lassie\Instance(
//    'https://demo.lassie.cloud/api/v2',
//    '08bd4ea9273a4c1fb4c0ce37712421ff',
//    '34ef9b72f4d22a0b3849c1c2464fcacc',
//    true
// );
// var_dump(Lassie\Transaction::getTypes($transactionInstance));

// $LassieAdmin = new Lassie\Instance(
// 	'https://demo.lassie.cloud/api/v2',
// 	'c00eb08826a18a43f5b2f0e48e9a2277',
//     '57f9cde3ff7a3dc1c40b5de24644eae0',
//     true
// );
// var_dump(Lassie\Person::getPayments($LassieAdmin));
// var_dump(Lassie\Person\Account::accept($LassieAdmin));
// var_dump(Lassie\Person\Membership::pay($LassieAdmin, [
//   'activity_id' => 2,
//   'mollie_redirect_url' => 'https://demo.lassie.cloud/',
// ]));