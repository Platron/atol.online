<?php

namespace Platron\Atol\clients;

use Platron\Atol\services\BaseServiceRequest;

interface iClient {
    
    /**
     * Послать запрос
     * @param \Platron\Atol\BaseService $service
     */
    public function sendRequest(BaseServiceRequest $service);
}
