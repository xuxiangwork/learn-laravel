<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    $esValuePerms = [];
    $query = json_decode('{"should":[{"qt":"match_phrase","value":"com.cmbchina.ccd.pluto.cmbActivity"},{"qt":"match_phrase","value":"cmb.pb"},{"qt":"match_phrase","value":"com.cmb.ubank.UBUI"}]}', true);
//    var_dump($query);
    list($ignore0, $ignore1, $ignore2, $fieldId) = explode(':', 'es:value:106:1773');
    foreach ($query as $type => $value) {
//        var_dump($type);
//        var_dump($value);
        $esValuePerms[$fieldId][$type] = array_merge($value, array_get($esValuePerms, $fieldId.'.'.$type, []));
    }
//    var_dump($esValuePerms);

    $esBoolQuery = [
        'must' => [],
        'must_not' => [],
        'should' => []
    ];

    foreach ($esValuePerms as $fieldId => $query) {
        $fieldName = 'xuxiang';
        foreach ($query as $boolType => $values) {
            foreach ($values as $v) {
                $value = $v['value'];
                $qt = $v['qt'];
                /*
                if ($v['qt'] === 'rtd') {
                    $value = $this->_transRtd2Range($value);
                    $qt = 'range';
                }*/

                $e = [
                    $qt => [
                        $fieldName => $value
                    ]
                ];
                $esBoolQuery[$boolType][] = $e;
            }
        }
    }

    var_dump($esBoolQuery);
    echo 'hello world!';
});

Route::group(['namespace' => 'Auth'], function() {

    Route::post('auth/login', 'AuthController@login');
    Route::post('auth/register', 'AuthController@register');
    Route::post('auth/user/info', 'UserInfoController@userInfo');
    Route::post('auth/user/verify', 'UserInfoController@verifyToken');

    Route::post('auth/user/create', 'UserInfoController@createUser');

});

Route::group(['namespace' => 'Test'], function() {

    Route::post('test/index', 'TestController@index');

});


