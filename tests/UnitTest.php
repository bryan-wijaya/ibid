<?php

class UnitTest extends TestCase
{
    /**
     *
     * @test
     */
    public function readMongoTest()
    {
        $this->get("users");
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'result' => ['*' =>
                [
                    'name',
                    'email',
                    'password',
                ]
            ]
        ]);
    }

    /**
     *
     * @test
     */
    public function readFirebaseTest()
    {
        $this->get("firebase");
        $this->seeStatusCode(201);
        $this->seeJsonStructure([
            'result' => ['*' =>
                [
                    'name',
                    'email',
                    'password',
                ]
            ]
        ]);
    }

    public function createJwtTest()
    {
        $parameters = ['email' => 'test@gmail.com','name' => 'test', 'password' => 'test123'];
        $this->post("jwt/create", $parameters);
        $this->seeStatusCode(201);
    }
}
