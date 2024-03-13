<?php

declare(strict_types=1);

use Contao\EasyCodingStandard\Fixer\CommentLengthFixer;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;
use PhpCsFixer\Fixer\Whitespace\MethodChainingIndentationFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->sets([__DIR__.'/vendor/contao/easy-coding-standard/config/contao.php']);

    $ecsConfig->paths([
        __DIR__.'/src',
        __DIR__.'/contao',
    ]);

    $ecsConfig->ruleWithConfiguration(HeaderCommentFixer::class, [
        'header' => "This file is part of the Contao IP Login extension.\n\n(c) INSPIRED MINDS",
    ]);

    $ecsConfig->skip([CommentLengthFixer::class, MethodChainingIndentationFixer::class]);

    $ecsConfig->parallel();
    $ecsConfig->lineEnding("\n");
    $ecsConfig->cacheDirectory(sys_get_temp_dir().'/ecs_default_cache');
};
