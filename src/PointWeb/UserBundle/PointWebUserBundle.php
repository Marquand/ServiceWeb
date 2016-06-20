<?php

namespace PointWeb\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PointWebUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
