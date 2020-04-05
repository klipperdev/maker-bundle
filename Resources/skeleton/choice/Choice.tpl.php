<?php echo "<?php\n"; ?>

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace <?php echo $namespace; ?>;

use Klipper\Component\Choice\ChoiceInterface;
use Klipper\Component\Choice\Util\ChoiceUtil;

/**
 * <?php echo $class_name; ?> choice.
 *
 * @author <?php echo $author; ?>
 */
final class <?php echo $class_name; ?> implements ChoiceInterface
{
    /**
     * {@inheritdoc}
     */
    public static function listIdentifiers()
    {
        return [
            'Group label' => [
                'choice_key' => 'Choice label',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getValues()
    {
        return ChoiceUtil::getValues(static::listIdentifiers());
    }

    /**
     * {@inheritdoc}
     */
    public static function getTranslationDomain()
    {
        return '<?php echo $translation_domain; ?>';
    }
}
