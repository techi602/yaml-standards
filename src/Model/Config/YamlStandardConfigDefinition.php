<?php

declare(strict_types=1);

namespace YamlStandards\Model\Config;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class YamlStandardConfigDefinition implements ConfigurationInterface
{
    public const CONFIG_PATHS_TO_CHECK = 'pathsToCheck';
    public const CONFIG_EXCLUDED_PATHS = 'excludedPaths';
    public const CONFIG_CHECKERS = 'checkers';
    public const CONFIG_PATH_TO_CHECKER = 'pathToChecker';
    public const CONFIG_PARAMETERS_FOR_CHECKER = 'parameters';
    public const CONFIG_PARAMETERS_DEPTH = 'depth';
    public const CONFIG_PARAMETERS_INDENTS = 'indents';
    public const CONFIG_PARAMETERS_LEVEL = 'level';
    public const CONFIG_PARAMETERS_SERVICE_ALIASING_TYPE = 'serviceAliasingType';
    public const CONFIG_PARAMETERS_INDENTS_COMMENTS_WITHOUT_PARENT = 'indentsCommentsWithoutParent';
    public const CONFIG_PARAMETERS_ALPHABETICAL_PRIORITIZED_KEYS = 'prioritizedKeys';
    public const CONFIG_PARAMETERS_IGNORE_COMMENTS_INDENT = 'ignoreCommentsIndent';

    public const CONFIG_PARAMETERS_SERVICE_ALIASING_TYPE_VALUE_SHORT = 'short';
    public const CONFIG_PARAMETERS_SERVICE_ALIASING_TYPE_VALUE_LONG = 'long';

    public const CONFIG_PARAMETERS_INDENTS_COMMENTS_WITHOUT_PARENT_VALUE_DEFAULT = 'default';
    public const CONFIG_PARAMETERS_INDENTS_COMMENTS_WITHOUT_PARENT_VALUE_PRESERVED = 'preserved';

    private const REGEX_FILE_EXTENSION = '/\..+$/';

    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('yaml_standards_config');

        // fix for Symfony 4.2 and newer versions
        if (method_exists($treeBuilder, 'getRootNode')) {
            /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode */
            $rootNode = $treeBuilder->getRootNode();
        } else {
            /** @var \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $rootNode */
            $rootNode = /** @scrutinizer ignore-deprecated */ $treeBuilder->root('yaml_standards_config');
        }

        $this->buildItemsNode($rootNode->arrayPrototype());

        return $treeBuilder;
    }

    /**
     * @param \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition $node
     * @return \Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition
     */
    private function buildItemsNode(ArrayNodeDefinition $node): ArrayNodeDefinition
    {
        return $node
            ->children()
                ->arrayNode(self::CONFIG_PATHS_TO_CHECK)->isRequired()->cannotBeEmpty()
                    ->validate()
                        ->ifTrue(function (array $patterns) {
                            foreach ($patterns as $pattern) {
                                if (preg_match(self::REGEX_FILE_EXTENSION, $pattern) === 0) {
                                    return true;
                                }
                            }

                            return false;
                        })
                        ->thenInvalid('Invalid pattern: %s. Pattern must have to suffix defined.')
                    ->end()
                    ->/** @scrutinizer ignore-call */prototype('scalar')->end()
                ->/** @scrutinizer ignore-call */end()
                ->arrayNode(self::CONFIG_EXCLUDED_PATHS)
                    ->validate()
                        ->ifTrue(function (array $patterns) {
                            foreach ($patterns as $pattern) {
                                if (preg_match(self::REGEX_FILE_EXTENSION, $pattern) === 0) {
                                    return true;
                                }
                            }

                            return false;
                        })
                        ->thenInvalid('Invalid pattern: %s. Pattern must have to suffix defined.')
                    ->end()
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode(self::CONFIG_CHECKERS)
                    ->isRequired()
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode(self::CONFIG_PATH_TO_CHECKER)->defaultNull()->end()
                            ->arrayNode(self::CONFIG_PARAMETERS_FOR_CHECKER)
                                ->addDefaultsIfNotSet()
                                    ->children()
                                        ->scalarNode(self::CONFIG_PARAMETERS_DEPTH)->defaultValue(4)->end()
                                        ->scalarNode(self::CONFIG_PARAMETERS_INDENTS)->defaultValue(4)->end()
                                        ->scalarNode(self::CONFIG_PARAMETERS_LEVEL)->defaultValue(2)->end()
                                        ->enumNode(self::CONFIG_PARAMETERS_SERVICE_ALIASING_TYPE)->defaultValue(self::CONFIG_PARAMETERS_SERVICE_ALIASING_TYPE_VALUE_SHORT)->values([
                                            self::CONFIG_PARAMETERS_SERVICE_ALIASING_TYPE_VALUE_SHORT,
                                            self::CONFIG_PARAMETERS_SERVICE_ALIASING_TYPE_VALUE_LONG,
                                        ])->end()
                                        ->enumNode(self::CONFIG_PARAMETERS_INDENTS_COMMENTS_WITHOUT_PARENT)->defaultValue(self::CONFIG_PARAMETERS_INDENTS_COMMENTS_WITHOUT_PARENT_VALUE_DEFAULT)->values([
                                            self::CONFIG_PARAMETERS_INDENTS_COMMENTS_WITHOUT_PARENT_VALUE_DEFAULT,
                                            self::CONFIG_PARAMETERS_INDENTS_COMMENTS_WITHOUT_PARENT_VALUE_PRESERVED,
                                        ])->end()
                                        ->booleanNode(self::CONFIG_PARAMETERS_IGNORE_COMMENTS_INDENT)->defaultValue(false)->end()
                                        ->arrayNode(self::CONFIG_PARAMETERS_ALPHABETICAL_PRIORITIZED_KEYS)->prototype('scalar')->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }
}
