<?php
namespace Beech\Socket\Event;

/*                                                                        *
 * Copyright (c) 2013 Robert Lemke and Beech Applications B.V.            *
 *                                                                        *
 * This is free software; you can redistribute it and/or modify it under  *
 * the terms of the MIT license.                                          *
 *                                                                        *
 * Based on ReactPHP by Igor Wiedler                                      *
 *                                                                        */

use React\EventLoop\StreamSelectLoop;

/**
 * A wrapper class for the React StreamSelectLoop
 */
class Loop extends StreamSelectLoop implements LoopInterface {
}

?>