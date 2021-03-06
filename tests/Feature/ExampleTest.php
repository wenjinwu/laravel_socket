<?php

namespace Tests\Feature;

use App\Model\AdminUser;
use App\Services\CustomerService;
use Barryvdh\Debugbar\Twig\Extension\Debug;
use DebugBar\DebugBar;
use GatewayClient\Gateway;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {


//        DebugBar::disable();
        $response = $this->post('/testva', [
            'brand_id' => 1,
            'modelpn_id' => 1,
            'country_id' =>1,
        ]);
        dump($response->getContent());
        dump($response->getStatusCode());
    }

    /**
     * 添加管理员
     */
    public function testAdminadd()
    {
        $response = $this->get('/admin/dev/users/adduser',[
            'username' => 'dongodng',
            'password' => 'sdfdsf',
            'type' => 2,
        ]);
        viewtest($response->getContent());
        $response->assertStatus(200);
    }

    public function testatest()
    {
//        $admin = AdminUser::query()->first();
//        $admin->load('roles');
//        $data = app(CustomerService::class)->isFreeCustomer();
//        dump($data);
//        echo  Gateway::isUidOnline(3);
        # 2018102350505255
        #7f0000010b5500000003
//        $data = Gateway::getAllUidList();
//        $data =  Gateway::getAllClientIdList();
        $data = Gateway::getUidByClientId('7f0000010b5500000019');
        #2018102356564848
        dump($data);
    }
}
