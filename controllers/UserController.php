<?php

class UserController {
    public function test (Request $request, User $modelInstance) {
        return $modelInstance->test();
    }
}