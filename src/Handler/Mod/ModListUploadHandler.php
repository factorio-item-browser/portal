<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Mod;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\UploadedFile;

/**
 * The request handler for processing the uploaded mod-list.json file.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ModListUploadHandler extends AbstractModListChangeHandler
{
    /**
     * Returns the list of enabled mods from the request.
     * @param ServerRequestInterface $request
     * @return array|string[]
     */
    protected function getEnabledModNamesFromRequest(ServerRequestInterface $request): array
    {
        $enabledModNames = [];
        $uploadedFile = $request->getUploadedFiles()['modListFile'] ?? null;
        if (!$uploadedFile instanceof UploadedFile) {
            $this->modListSessionContainer->setUploadErrorMessage('missingFile');
        } else {
            $json = json_decode($uploadedFile->getStream()->getContents(), true);
            if (!is_array($json) || !isset($json['mods']) || !is_array($json['mods'])) {
                $this->modListSessionContainer->setUploadErrorMessage('invalidFile');
            } else {
                foreach ($json['mods'] as $mod) {
                    $modName = $mod['name'] ?? '';
                    if (($mod['enabled'] ?? false) && strlen($modName) > 0) {
                        $enabledModNames[] = $modName;
                    }
                }
            }
        }
        return $enabledModNames;
    }
}
