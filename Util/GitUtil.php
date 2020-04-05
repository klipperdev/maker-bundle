<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Bundle\MakerBundle\Util;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
abstract class GitUtil
{
    public static function getAuthor(): ?string
    {
        return self::getLastLine(self::exec('git config user.name'));
    }

    public static function getAuthorEmail(): ?string
    {
        return self::getLastLine(self::exec('git config user.email'));
    }

    public static function getAuthorVars(): array
    {
        $authorVars = [];

        if (!empty($authorName = self::getAuthor())) {
            $authorVars['author_name'] = $authorName;
        }

        if (!isset($authorVars['author_name'])) {
            $authorVars['author_name'] = get_current_user();
        }

        $authorVars['author'] = $authorVars['author_name'];

        if (!empty($authorEmail = self::getAuthorEmail())) {
            $authorVars['author_email'] = $authorEmail;
            $authorVars['author'] = $authorVars['author'].' <'.$authorEmail.'>';
        }

        $authorVars['author'] .= PHP_EOL;

        return $authorVars;
    }

    protected static function exec($command): array
    {
        exec(sprintf('%s 2>&1', $command), $output, $result);

        return 0 === $result ? $output : [];
    }

    protected static function getLastLine(array $output): ?string
    {
        return array_pop($output);
    }
}
