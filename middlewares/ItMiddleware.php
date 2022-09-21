<?php
interface ItMiddleware {
    // public function execute(Request $request);
    public function execute(Request &$request, User $user);
}