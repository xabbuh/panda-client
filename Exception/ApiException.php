<?php

/*
* This file is part of the XabbuhPandaClient package.
*
* (c) Christian Flothmann <christian.flothmann@xabbuh.de>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Xabbuh\PandaClient\Exception;

/**
 * Indicate errors regarding the panda api.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ApiException extends \Exception implements PandaException
{
}
