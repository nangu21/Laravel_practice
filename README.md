## Laravelの練習
### Unitテストに関するメモ
### ¶前提事項
```
tests
├ Feature
│   ├ DbHelloTest.php
│   ├ ExampleTest.php
│   └ HelloTest.php
└ Unit
│   └ ExampleTest.php
├ CreatesApplication.php
└ TestCase.php
```
このうち、`$ vendor/bin/phpunit`で実行されるテストは、オプションの`--list-tests`で確認できる。
```console
$ vendor/bin/phpunit --list-tests

PHPUnit 9.5.4 by Sebastian Bergmann and contributors.

Available test(s):
 - Tests\Unit\ExampleTest::testBasicTest
 - Tests\Feature\ExampleTest::testBasicTest
 - Tests\Feature\HelloTest::testHello
```
### ¶Laravel8へのバージョンアップによるエラー
指定アドレスへのアクセステストを実行したところ、エラーが発生した。
```HelloTest.php
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class HelloTest extends TestCase
{
 use RefreshDataBase;
 
 public function testHello() {
  $this->assertTrue(true);
  
  $response = $this->get('/');
  $response->assertStatus(200);

  $response = $this->get('/home');
  $response->assertStatus(302);
  
  $user = factory(User::class)->create();  //ここが問題
  $response = $this->actingAs($user)->get('/home');
  $response->assertStatus(200);
  
  $response = $this->get('/no_route');
  $response->assertStatus(404);
 }
}
```
上記ファイルの実行結果が以下。
```console
$ vendor/bin/phpunit tests/Feature/HelloTest.php

PHPUnit 9.5.4 by Sebastian Bergmann and contributors.

E                                                                   1 / 1 (100%)

Time: 00:00.218, Memory: 24.00 MB

There was 1 error:

1) Tests\Feature\HelloTest::testHello
Error: Call to undefined function Tests\Feature\factory()
```
調べたところ、laravel8ではfactory()の表記の仕方が変更になっていた。したがって
```HelloTest.php
$user = factory(User::class)->create();
```
の部分を以下のように変更。
```HelloTest.php
$user = User::factory()->create();
```
修正後の実行結果が以下。無事テストを通過。
```console
$ vendor/bin/phpunit tests/Feature/HelloTest.php

PHPUnit 9.5.4 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 00:00.310, Memory: 26.00 MB

OK (1 test, 5 assertions)
```

データベーステストでも同様。`factory(Person::class)->create()`は`Person::factory()->create()`に修正して実行する。
```DataHelloTest.php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Person;

class DataHelloTest extends TestCase
{
    public function test_data()
    {
        Person::factory()->create([  //ここを変更
            'name' => 'XXX',
            'mail' => 'YYY@ZZZ.com',
            'age' => '123',
        ]);

        $this->assertDatabaseHas('people', [
            'name' => 'XXX',
            'mail' => 'YYY@ZZZ.com',
            'age' => '123',
        ]);
    }
}
```
問題なくテストを通過。
```console
$ vendor/bin/phpunit tests/Feature/DataHelloTest.php

PHPUnit 9.5.4 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 00:00.137, Memory: 20.00 MB

OK (1 test, 1 assertion)
```
テスト用のsqliteデータベースに無事格納されていることも確認できた。
![テスト用データベース](db_test_result.png)
### ¶その他注意事項
#### モデルファイルのパス変更
Laravel8バージョンアップにより、これまで/appの直下に配置されていたモデルファイルが/app/Modelsのなかに格納されることになった。したがってファイルパスも`use App\User;`ではなく`use App\Models\User;`としなければならないことに注意。
