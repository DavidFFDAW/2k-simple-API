<?php
class AuthMiddleware extends FatherMiddleware implements ItMiddleware {

    public function __construct() {
        parent::__construct();
    }

    private function checkUserAPIToken(User $user, string $token) {
        $userWithToken = $user->getUserTokenIfAny($token);

        if (!isset($userWithToken) ||empty($userWithToken) || !$userWithToken) 
            $error = $this->setError(401, 'Unauthorized: Token not valid');

        return $this->hasError() ? $error : $userWithToken;
    }

    public function execute(Request &$request, User $user) {
        if (!isset($request->headers['Authorization'])) return $this->setError(401, 'Unauthorized: Token not found');

        $token = $request->bearerToken();        
        $userWithToken = $this->checkUserAPIToken($user, $token);
        if ($this->hasError()) return $userWithToken;
        $request->user = $userWithToken;

        return true;
    }
}