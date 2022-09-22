<?php

class UserController {
    public function test (Request $request, ModelModule $modelInstance) {
        return $modelInstance->test();
    }
}