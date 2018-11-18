<?php
namespace Deployer;

require 'recipe/laravel.php';

set('application', 'deployer.prod');

set('repository', 'git@github.com:francescomalatesta/deployer-test.git');

set('git_tty', true);

set('allow_anonymous_stats', false);

set('dotenv_path', '{{release_path}}/.env');

host('deployer.prod')
    ->user('forge')
    ->forwardAgent()
    ->set('deploy_path', '~/{{application}}');

task('build', function () {
    run('cd {{release_path}} && build');
});

task('dotenv:upload', function () {
    $src = "shared/.env";
    if (!file_exists($src)) {
        throw new \Exception("File not found: $src");
    }

    $dest = '{{dotenv_path}}';
    upload($src, $dest);
});

// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');


before('deploy:symlink', 'artisan:migrate');

before('artisan:config:cache', 'dotenv:upload');
