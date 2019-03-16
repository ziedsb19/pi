<?php

namespace techEventsBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class techEventsBundle extends Bundle
{
    public function getParent(){
        return 'FOSUserBundle';
    }
}
