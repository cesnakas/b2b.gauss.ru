<?php

/*
 * This file is part of the Studio Fact package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Citfact\Tools\Event;

interface DispatcherInterface
{
    /**
     * @param  string $moduleName
     * @return void
     */
    public function registerByModule($moduleName);
}
