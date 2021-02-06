<?php

/*
 * This file is part of PHP-FFmpeg.
 *
 * (c) Alchemy <dev.team@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Exception\RuntimeException;
use FFMpeg\FFProbe\DataMapping\Stream;
use FFMpeg\Filters\Frame\FrameFilterInterface;
use FFMpeg\Media\Frame;

class ImageResolutionFilter implements FrameFilterInterface
{
    /** @var integer */
    private $priority;

    public function __construct($priority = 0)
    {
        $this->priority = $priority;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(Frame $frame)
    {
        $dimensions = null;
        $commands = array();

	    /**
	     * @var Stream $stream
	     */
        foreach ($frame->getVideo()->getStreams() as $stream) {
            if ($stream->isVideo()) {
                try {
                  //  $dimensions = $stream->getDimensions();
	                $dimensions = new Dimension(640, 360);

                    $commands[] = '-s';
                    $commands[] = $dimensions->getWidth() . 'x' . $dimensions->getHeight();
                    break;
                } catch (RuntimeException $e) {

                }
            }
        }

        return $commands;
    }
}
