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
 * Marker interface for all exceptions regarding the panda api.
 *
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
interface PandaException
{
    /**
     * Returns the exception code.
     *
     * @return int The code
     */
    public function getCode();

    /**
     * Returns the exception message.
     *
     * @return string The message
     */
    public function getMessage();
}
