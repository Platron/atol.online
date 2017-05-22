<?php

namespace Platron\Atol\clients;

interface iClient {
    
    /**
     * Послать запрос
     * @param \Platron\Atol\BaseService $service
     */
    public function sendRequest(\Platron\Atol\services\BaseServiceRequest $service);
}
