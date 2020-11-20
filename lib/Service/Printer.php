<?php

namespace Service;
namespace OCA\Printer\Service;

use Symfony\Component\Process\Process;

/**
 * class Printer.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class Printer
{
    public function print(string $file, string $orientation)
    {
        $options = [
            'landscape' => [
                'lpr',
                $file,
            ],
            'portrait' => [
                'lpr',
                '-o',
                'orientation-requested=4',
                $file,
            ],
        ];

        $process = new Process($options[$orientation]);
        $process->mustRun();
    }

    /**
     * Validates an orientation.
     */
    public function isValidOrientation(string $orientation): bool
    {
        return in_array($orientation, [
            'landscape',
            'portrait',
        ]);
    }
}
