<?php

namespace Tests\Feature\Concerns;


use Dingo\Api\Routing\UrlGenerator;

trait TestingHelpers{

    public function actingAs($user)
    {
        $this->app['api.auth']->setUser($user);

        return $this;
    }

    public function urlGenerate()
    {
        return app(UrlGenerator::class)->version('v1');
    }
}
