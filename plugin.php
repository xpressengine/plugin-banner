<?php
namespace Xpressengine\Plugins\Banner;

use Illuminate\Database\Schema\Blueprint;
use Route;
use Schema;
use Xpressengine\Plugin\AbstractPlugin;

class Plugin extends AbstractPlugin
{
    /**
     * 이 메소드는 활성화(activate) 된 플러그인이 부트될 때 항상 실행된다.
     *
     * @return void
     */
    public function register()
    {
        app()->singleton(
            ['banner::handler' => Handler::class],
            function ($app) {
                $proxyClass = app('xe.interception')->proxy(Handler::class, 'Banner');
                return new $proxyClass($this);
            }
        );
    }


    /**
     * 이 메소드는 활성화(activate) 된 플러그인이 부트될 때 항상 실행됩니다.
     *
     * @return void
     */
    public function boot()
    {
        // implement code

        $this->route();
    }

    /**
     * route
     *
     * @return void
     */
    protected function route()
    {
        Route::settings($this->getId(), function(){
            Route::group(
                ['prefix' => 'groups', 'namespace' => 'Xpressengine\Plugins\Banner\Controllers'],
                function() {

                    // 그룹리스트 출력
                    // GET settings/banner/groups
                    Route::get('/', [
                        'as' => 'banner::group.index',
                        'uses' => 'GroupController@index',
                    ]);

                    // 그룹 생성 페이지
                    // GET settings/banner/groups/create
                    Route::get('create', [
                        'as' => 'banner::group.create',
                        'uses' => 'GroupController@create'
                    ]);

                    // 그룹 생성
                    // POST settings/banner/groups
                    Route::post('/', [
                        'as' => 'banner::group.store',
                        'uses' => 'GroupController@store'
                    ]);

                    // 그룹 삭제
                    // DEL settings/banner/groups/GROUP_ID
                    //Route::delete('{group_id}', [
                    //    'as' => 'banner::group.delete',
                    //    'uses' => 'GroupController@destroy'
                    //]);

                    // 그룹 수정
                    // PUT settings/banner/groups/GROUP_ID
                    Route::put('{group_id}', [
                        'as' => 'banner::group.update',
                        'uses' => 'GroupController@update'
                    ]);

                    // 편집기 출력(그룹 편집)
                    // GET settings/banner/groups/GROUP_ID/edit
                    
                    Route::get('{group_id}/edit', [
                        'as' => 'banner::group.edit',
                        'uses' => 'GroupController@edit'
                    ]);

                    Route::group(
                        ['prefix' => '{group_id}/items'],
                        function () {

                            // 아이템 생성
                            // POST settings/banner/groups/GROUP_ID/items
                            Route::post('/', [
                                'as' =>  'banner::item.store',
                                'uses' => 'ItemController@store'
                            ]);

                            // 아이템 편집
                            // GET settinngs/banner/groups/GROUP_ID/items/ITEM_ID/edit
                            Route::get('{item_id}/edit', [
                                'as' => 'banner::item.edit',
                                'uses' => 'ItemController@edit'
                            ]);

                            // 아이템 삭제
                            // DEL settings/banner/groups/GROUP_ID/items/ITEM_ID
                            Route::delete('{item_id}', [
                                'as' => 'banner::item.delete',
                                'uses' => 'ItemController@destroy'
                            ]);

                            // 아이템 수정
                            // PUT settings/banner/groups/GROUP_ID/items/ITEM_ID
                            Route::put('{item_id}', [
                                'as' => 'banner::item.update',
                                'uses' => 'ItemController@update'
                            ]);
                        }
                    );
                }
            );
        });
    }

    /**
     * 플러그인이 활성화될 때 실행할 코드를 여기에 작성한다.
     *
     * @param string|null $installedVersion 현재 XpressEngine에 설치된 플러그인의 버전정보
     *
     * @return void
     */
    public function activate($installedVersion = null)
    {
        // implement code
    }

    /**
     * 플러그인을 설치한다. 플러그인이 설치될 때 실행할 코드를 여기에 작성한다
     *
     * @return void
     */
    public function install()
    {
        if (!Schema::hasTable('banner_item')) {
            Schema::create(
                'banner_item',
                function (Blueprint $table) {
                    $table->engine = "InnoDB";

                    $table->string('id', 36)->primary();
                    $table->string('group_id', 36);
                    $table->string('title');
                    $table->string('content');
                    $table->string('link', 1000);
                    $table->string('link_target', 20);
                    $table->string('image', 1000);
                    $table->string('status', 100);
                    $table->integer('order')->default(0);
                    $table->boolean('use_timer')->default(false);
                    $table->timestamp('startedAt')->nullable();
                    $table->timestamp('endedAt')->nullable();
                    $table->timestamp('createdAt');
                    $table->timestamp('updatedAt');
                    $table->index('group_id', 'order');
                }
            );
        }

        if (!Schema::hasTable('banner_group')) {
            Schema::create(
                'banner_group',
                function (Blueprint $table) {
                    $table->engine = "InnoDB";

                    $table->string('id', 36)->primary();
                    $table->string('title')->unique();
                    $table->string('skin', 1000);
                    $table->timestamp('createdAt');
                    $table->timestamp('updatedAt');
                }
            );
        }
    }

    /**
     * 해당 플러그인이 설치된 상태라면 true, 설치되어있지 않다면 false를 반환한다.
     * 이 메소드를 구현하지 않았다면 기본적으로 설치된 상태(true)를 반환한다.
     *
     * @return boolean 플러그인의 설치 유무
     */
    public function checkInstalled()
    {
        return parent::checkInstalled();
    }

    /**
     * 플러그인을 업데이트한다.
     *
     * @return void
     */
    public function update()
    {
        // implement code
    }

    /**
     * 해당 플러그인이 최신 상태로 업데이트가 된 상태라면 true, 업데이트가 필요한 상태라면 false를 반환함.
     * 이 메소드를 구현하지 않았다면 기본적으로 최신업데이트 상태임(true)을 반환함.
     *
     * @return boolean 플러그인의 설치 유무,
     */
    public function checkUpdated()
    {
        // implement code

        return parent::checkUpdated();
    }

    /**
     * 플러그인의 설정페이지 주소를 반환한다.
     * 플러그인 목록에서 플러그인의 '관리' 버튼을 누를 경우 이 페이지에서 반환하는 주소로 연결된다.
     *
     * @return string
     */
    public function getSettingsURI()
    {
        return route('banner::group.index');
    }


}
