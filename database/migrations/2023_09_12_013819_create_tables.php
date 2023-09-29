<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('room');
        Schema::dropIfExists('player');
        Schema::dropIfExists('asset');
        Schema::dropIfExists('work');
        Schema::dropIfExists('history');

        Schema::create('room', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('部屋名')->unique();
            $table->integer('period')->default(0)->comment('決算ターン');
            $table->decimal('interest')->default(0)->comment('金利');
            $table->datetime('ins')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->datetime('upd')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
        });

        Schema::create('player', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('roomid')->comment('部屋ID');
            $table->string('name')->default('')->comment('プレイヤー名');
            $table->string('pass')->default('')->comment('パスワード');
            $table->string('token')->default('')->comment('トークン');
            $table->string('sex')->default('')->comment('性別');
            $table->integer('img')->default(0)->comment('キャラクターイメージ');
            $table->integer('money')->default(0)->comment('資金');
            $table->integer('workid')->default(0)->comment('職業ID');
            $table->integer('stress')->default(0)->comment('ストレス');
            $table->integer('turn')->default(0)->comment('ターン');
            $table->integer('lifelevel')->default(0)->comment('生活水準');
            $table->integer('flgFire')->default(0)->comment('ファイヤーゾーンかどうか');
            $table->datetime('ins')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->datetime('upd')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
        });

        Schema::create('asset', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('roomid')->comment('部屋ID');
            $table->integer('playerid')->comment('プレイヤーID');
            $table->integer('turn')->default(0)->comment('ターン');
            $table->string('type')->default('')->comment('資産タイプ');
            $table->integer('buy')->default(0)->comment('購入価格(借入残高)');
            $table->integer('sell')->default(0)->comment('売却価格(既返済額)');
            $table->decimal('return')->default(0)->comment('リターン');
            $table->integer('has')->default(0)->comment('所有数');
            $table->datetime('ins')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->datetime('upd')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
        });

        Schema::create('work', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->default('')->comment('職業名');
            $table->string('img')->default('')->comment('イメージ');
            $table->integer('disalbed')->default(0)->comment('無効フラグ');
            $table->integer('salary')->default(0)->comment('リターン');
            $table->integer('lifelevelMax')->default(0)->comment('最高生活水準');
            $table->integer('lifelevelMin')->default(0)->comment('最低生活水準');
            $table->integer('vanity')->default(0)->comment('決算時の生活水準の上がり方');
            $table->datetime('ins')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->datetime('upd')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
        });

        \DB::table('work')->insert([
            'id' => 1,
            'type' => '医者',
            'img' => 'doctor',
            'salary' => 1000,
            'lifelevelMax' => 10,
            'lifelevelMin' => 5,
            'vanity' => 150,
        ]);
        DB::table('work')->insert([
            'id' => 2,
            'type' => 'ITエンジニア',
            'img' => 'engineer',
            'salary' => 750,
            'lifelevelMax' => 9,
            'lifelevelMin' => 4,
            'vanity' => 120,
        ]);
        DB::table('work')->insert([
            'id' => 3,
            'type' => '公務員',
            'img' => 'business',
            'salary' => 600,
            'lifelevelMax' => 8,
            'lifelevelMin' => 3,
            'vanity' => 80,
        ]);
        DB::table('work')->insert([
            'id' => 4,
            'type' => '工場労務',
            'img' => 'worker',
            'salary' => 400,
            'lifelevelMax' => 7,
            'lifelevelMin' => 2,
            'vanity' => 40,
        ]);
        DB::table('work')->insert([
            'id' => 5,
            'type' => 'フリーター',
            'img' => 'freeter',
            'salary' => 250,
            'lifelevelMax' => 5,
            'lifelevelMin' => 1,
            'vanity' => 0,
        ]);


        Schema::create('history', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('roomid')->comment('部屋ID');
            $table->integer('playerid')->comment('プレイヤーID');
            $table->integer('turn')->comment('ターン');
            $table->string('action')->default('')->comment('アクション');
            $table->string('parameter')->default('')->comment('パラメータ');
            $table->datetime('ins')->default(DB::raw('CURRENT_TIMESTAMP'))->comment('作成日時');
            $table->datetime('upd')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'))->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room');
        Schema::dropIfExists('player');
        Schema::dropIfExists('asset');
        Schema::dropIfExists('work');
        Schema::dropIfExists('history');
    }
};
