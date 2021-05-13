# 🍕Laravelの練習
## 🧁Jetstream導入に関するメモ
**環境**
```
macOS Big Sur 11.3.1
PHP 7.4.19
Composer 2.0.13
Laravel Framework 8.40.0 (確認方法: $ php artisan ―version)
```

**DB**
- [DB Browser for SQLite](https://sqlitebrowser.org/dl/)を使う
- GUIを使って直感的にDB操作ができるSQLiteツール

**認証ライブラリ**
- [Jetstream](https://jetstream.laravel.com/2.x/installation.html)を使う
- Laravel/UI：バージョン8で非推奨。今後のサポートにも不安あり
- Breeze：必要最低限の認証機能があればいい時はコレ
- Fortify：いろんな認証機能を試してみたいがJetstreamでは過剰な時はコレ

**導入**
- 注意点：Jetstreamは新しいアプリケーションにのみインストールする。既存のものにインストールすると予期せぬ動作、問題が発生する。(マニュアルより)
- livewire vs inertia：フロントエンドをPHP(Blade.php)で実装するならlivewire、vue.jsならinertiaを使う
```console
├ $ laravel new [アプリケーション名] //アプリケーションの雛形作成とJetstreamのインストールを別々に行う場合
|	├> $ composer require Laravel/jetstream //Jetstreamのインストール
|	└> $ php artisan jetstream:install livewire
|		又は $ php artisan jetstream:install livewire ―teams
|		又は $ php artisan jetstream:install inertia
|		又は $ php artisan jetstream:install inertia ―teams
|
└ $ laravel new [アプリケーション名] ―jet  //雛形作成と同時にJetstreamインストールする場合
	├>
	|	    |     |         |
	|	    |,---.|--- ,---.|--- ,---.,---.,---.,-.-.
	|	    ||---'|    `---.|    |    |---',---|| | |
	|	`---'`---'`---'`---'`---'`    `---'`---^` ' '
	|	Which Jetstream stack do you prefer?
 	|	[0] livewire
 	| 	[1] inertia
	|	> livewireを使う場合は0, inertiaを使う場合は1
	|
	└>	Will your application use teams? (yes/no) [no]:
	   	> team機能(各ユーザを任意のチームに割り当てることができる)を使う場合はyes
$ npm install //npmの依存関係をインストール(npmコマンドがない場合、node.jsをインストールする)
$ npm run dev
```

**Usersテーブルを生成する**
- DB設定情報が用意されている`/config/database.php`を開いてデフォルトの`DB_CONNECTION`を変更
```database.php
変更前:
‘default’ => env(‘DB_CONNECTION’, ‘mysql’),
変更後:
’default’ => env(‘DB_CONNECTION’, ‘sqlite’),
```
- .envファイルの環境変数を変更する。
```.env
変更前:
DB_CONNECTION=mysql
DB_HOST=***
DB_PORT=***
DB_DATABASE=***
DB_USERNAME=***
DB_PASSWORD=
変更後:
DB_CONNECTION=sqlite
```
```console
$ php artisan migrate //usersテーブルが自動生成される
```

- サーバーを実行してトップページを表示する
```console
$ php artisan serve
```
認証機能が実装されていることが確認できる。やったね！
![トップページ](app_top.jpg)
![新規登録画面](app_register.jpg)

## 🍫Unitテストに関するメモ
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
指定アドレスへの**アクセステスト**を実行したところ、エラーが発生した。
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

**データベーステスト**でも同様。`factory(Person::class)->create()`は`Person::factory()->create()`に修正して実行する。
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


## 🍰エラーログとSlack連携に関するメモ
LaravelのエラーログとSlack連携は以下の3ステップで簡単に実装できた。
- SlackにてIncoming WebHooksを追加
- アプリケーションのenvファイルを修正
- アプリケーションのlogging.phpファイルを修正

### ¶実際の流れ
①Slack側でIncoming WebHooksを追加し、インテグレーション用のURlを発行して

![IncomingWebHooks](add_app.png)

②アプリケーション側の.envファイルに`LOG_SLACK_WEBHOOK_URL=インテグレーション用URL`を追加する
```.env
LOG_CHANNEL=stack
LOG_LEVEL=debug
LOG_SLACK_WEBHOOK_URL=https://hooks.slack.com/... //ここを追加
```
③configフォルダのlogging.phpファイルを編集して完了！

```logging.php
'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single', 'slack'], //ここを追加
            'ignore_exceptions' => false,
        ],
        
        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log', //Slackメッセージで表示される発言者名
            'emoji' => ':space_invader:', //Slackメッセージで表示される絵文字
            'level' => env('LOG_LEVEL', 'critical'), //通知するエラーレベル
        ],
```

無事に通知を受け取ることができた。

![エラーログ通知](slack_message.png)

