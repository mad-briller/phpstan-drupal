<?php declare(strict_types=1);

namespace PHPStan\Rules\Deprecations;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;

final class PluginAnnotationContextDefinitionsRule extends DeprecatedAnnotationsRuleBase
{

    protected function getExpectedInterface(): string
    {
        return 'Drupal\Component\Plugin\ContextAwarePluginInterface';
    }

    protected function doProcessNode(ClassReflection $reflection, Node\Stmt\Class_ $node, Scope $scope): array
    {
        $annotation = $reflection->getResolvedPhpDoc();
        // Plugins should always be annotated, but maybe this class is missing its
        // annotation since it swaps an existing one.
        if ($annotation === null) {
            return [];
        }
        $hasMatch = strpos($annotation->getPhpDocString(), 'context = {') !== false;
        if ($hasMatch) {
            return [
                'Providing context definitions via the "context" key is deprecated in Drupal 8.7.x and will be removed before Drupal 9.0.0. Use the "context_definitions" key instead.',
            ];
        }
        return [];
    }
}