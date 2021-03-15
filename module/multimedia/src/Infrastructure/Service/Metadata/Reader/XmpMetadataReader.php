<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Infrastructure\Service\Metadata\Reader;

use Ergonode\Multimedia\Infrastructure\Service\Metadata\MetadataReaderInterface;

class XmpMetadataReader implements MetadataReaderInterface
{
    /**
     * @param resource $file
     *
     * @return array
     */
    public function read($file): array
    {
        $result = [];
        $xmp = $this->getProfile($file);

        if ($xmp) {
            foreach ($this->getData() as $key => $regex) {
                $value = preg_match("/$regex/is", $xmp, $match) ? $match[1] : null;
                if ($value) {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }

    /**
     * @return array|string[]
     */
    private function getData(): array
    {
        return [
            'profile' => 'photoshop:ICCProfile="([^"]*)"',
            'create_date' => 'xmp:CreateDate="([^"]*)"',
            'modify_date' => 'xmp:ModifyDate="([^"]*)"',
        ];
    }

    /**
     * @param resource $pointer
     */
    private function getProfile($pointer): ?string
    {
        rewind($pointer);
        $chunkSize = 1024;

        $tag = '<x:xmpmeta';
        $buffer = false;

        while ($buffer === false && ($chunk = fread($pointer, $chunkSize)) !== false) {
            if (strlen($chunk) <= 10) {
                break;
            }
            if (($position = strpos($chunk, $tag)) === false) {
                fseek($pointer, -10, SEEK_CUR);
            } else {
                $buffer = substr($chunk, $position);
            }
        }

        if (false === $buffer) {
            fclose($pointer);

            return null;
        }

        $tag = '</x:xmpmeta>';
        $offset = 0;
        while (($position = strpos($buffer, $tag, $offset)) === false && ($chunk = fread(
            $pointer,
            $chunkSize
        )) !== false && !empty($chunk)) {
            $offset = strlen($buffer) - 12;
            $buffer .= $chunk;
        }

        fclose($pointer);

        if (false === $position) {
            throw new \RuntimeException('No close tag found. Possibly corrupted file.');
        }

        return substr($buffer, 0, $position + 12);
    }
}
