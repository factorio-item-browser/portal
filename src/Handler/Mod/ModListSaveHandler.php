<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Mod;

use Psr\Http\Message\ServerRequestInterface;

/**
 * The request handler for saving the list of enabled mods.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ModListSaveHandler extends AbstractModListChangeHandler
{
    /**
     * Returns the list of enabled mods from the request.
     * @param ServerRequestInterface $request
     * @return array|string[]
     */
    protected function getEnabledModNamesFromRequest(ServerRequestInterface $request): array
    {
        $enabledModNames = $request->getParsedBody()['enabledMods'] ?? [];
        if (!is_array($enabledModNames)) {
            $enabledModNames = [];
        }
        return $enabledModNames;
    }
}
