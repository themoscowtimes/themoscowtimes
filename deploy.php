<?php
namespace Deployer;

use Deployer\Task\Context;
use Symfony\Component\Console\Input\InputOption;
use Deployer\Exception\Exception;
use Deployer\Exception\GracefulShutdownException;
use function Deployer\Support\str_contains;
use Deployer\Type\Csv;



/**
 * Target Settings
 */
inventory('hosts.yml');



/**
 * Default options
 */
option('tag', null, InputOption::VALUE_REQUIRED, 'Tag to deploy');
option('revision', null, InputOption::VALUE_REQUIRED, 'Revision to deploy');
option('branch', null, InputOption::VALUE_REQUIRED, 'Branch to deploy');



/**
 * Configuration
 */
set('keep_releases', 5);
set('repository', ''); // Repository to deploy.
set('shared_dirs', []);
set('shared_files', ['config/env.php']);
set('copy_dirs', []);
set('writable_dirs', []);
set('writable_mode', 'acl'); // chmod, chown, chgrp or acl.
set('writable_use_sudo', false); // Using sudo in writable commands?
set('writable_recursive', true); // Common for all modes
set('writable_chmod_mode', '0755'); // For chmod mode
set('writable_chmod_recursive', true); // For chmod mode only (if is boolean, it has priority over `writable_recursive`)
set('http_user', false);
set('http_group', false);
set('clear_paths', []); // Relative path from deploy_path
set('clear_use_sudo', false); // Using sudo in clean commands?
set('cleanup_use_sudo', false);// Using sudo in cleanup commands?
set('use_relative_symlink', function () {
    return commandSupportsOption('ln', '--relative');
});
set('use_atomic_symlink', function () {
    return commandSupportsOption('mv', '--no-target-directory');
});
set('composer_action', 'install');
set('composer_options', '{{composer_action}} --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader --no-suggest');
set('env', []); // Run command environment (for example, SYMFONY_ENV=prod)




/**
 * Custom bins
 */
set('bin/php', function () {
    return locateBinaryPath('php');
});
set('bin/git', function () {
    return locateBinaryPath('git');
});
set('bin/composer', function () {
    if (commandExist('composer')) {
        $composer = locateBinaryPath('composer');
    }
    if (empty($composer)) {
        run("cd {{release_path}} && curl -sS https://getcomposer.org/installer | {{bin/php}}");
        $composer = '{{release_path}}/composer.phar';
    }

	// Run PHP while allowing url_fopen, running script from outside the base and allowing proc_open
    return '{{bin/php}} -d allow_url_fopen=On -d open_basedir=/ -d disable_functions= ' . $composer;
});
set('bin/symlink', function () {
    return get('use_relative_symlink') ? 'ln -nfs --relative' : 'ln -nfs';
});




/**
 * Helpers
 */

// Get the hostname
set('hostname', function () {
    return Context::get()->getHost()->getHostname();
});

// Get the current target
set('target', function () {
    return input()->getArgument('stage') ?: get('hostname');
});

// Get the current release path
set('current_path', function () {
    $link = run("readlink {{deploy_path}}/current");
    return substr($link, 0, 1) === '/' ? $link : get('deploy_path') . '/' . $link;
});

// Get a list of all the releases
set('releases_list', function () {
    cd('{{deploy_path}}');

    // If there is no releases return empty list.
    if (!test('[ -d releases ] && [ "$(ls -A releases)" ]')) {
        return [];
    }

    // Will list only dirs in releases.
    $list = explode("\n", run('cd releases && ls -t -1 -d */'));

    // Prepare list.
    $list = array_map(function ($release) {
        return basename(rtrim(trim($release), '/'));
    }, $list);

    $releases = []; // Releases list.

    // Collect releases based on .dep/releases info.
    // Other will be ignored.
    if (test('[ -f .dep/releases ]')) {
        $keepReleases = get('keep_releases');
        if ($keepReleases === -1) {
            $csv = run('cat .dep/releases');
        } else {
            // Instead of `tail -n` call here can be `cat` call,
            // but on hosts with a lot of deploys (more 1k) it
            // will output a really big list of previous releases.
            // It spoils appearance of output log, to make it pretty,
            // we limit it to `n*2 + 5` lines from end of file (15 lines).
            // Always read as many lines as there are release directories.
            $csv = run("tail -n " . max(count($releases), ($keepReleases * 2 + 5)) . " .dep/releases");
        }

        $metainfo = Csv::parse($csv);

        for ($i = count($metainfo) - 1; $i >= 0; --$i) {
            if (is_array($metainfo[$i]) && count($metainfo[$i]) >= 2) {
                list(, $release) = $metainfo[$i];
                $index = array_search($release, $list, true);
                if ($index !== false) {
                    $releases[] = $release;
                    unset($list[$index]);
                }
            }
        }
    }
    return $releases;
});

// Get the current release name
set('release_name', function () {
    $list = get('releases_list');

    // Filter out anything that does not look like a release.
    $list = array_filter($list, function ($release) {
        return preg_match('/^[\d\.]+$/', $release);
    });
    $nextReleaseNumber = 1;
    if (count($list) > 0) {
        $nextReleaseNumber = (int)max($list) + 1;
    }
    return (string)$nextReleaseNumber;
});

// Get the release path
set('release_path', function () {
    $releaseExists = test('[ -h {{deploy_path}}/release ]');
    if ($releaseExists) {
        $link = run("readlink {{deploy_path}}/release");
        return substr($link, 0, 1) === '/' ? $link : get('deploy_path') . '/' . $link;
    } else {
        return get('current_path');
    }
});

// Whether Git cache is usable
set('git_cache', function () {
    $gitVersion = run('{{bin/git}} version');
    $regs = [];
    if (preg_match('/((\d+\.?)+)/', $gitVersion, $regs)) {
        $version = $regs[1];
    } else {
        $version = "1.0.0";
    }
    return version_compare($version, '2.3', '>=');
});





/**
 * Tasks
 */
task('deploy:info', function () {
    $what = '';
    $branch = get('branch');

    if (!empty($branch)) {
        $what = "<fg=magenta>$branch</fg=magenta>";
    }

    if (input()->hasOption('tag') && !empty(input()->getOption('tag'))) {
        $tag = input()->getOption('tag');
        $what = "tag <fg=magenta>$tag</fg=magenta>";
    } elseif (input()->hasOption('revision') && !empty(input()->getOption('revision'))) {
        $revision = input()->getOption('revision');
        $what = "revision <fg=magenta>$revision</fg=magenta>";
    }

    if (empty($what)) {
        $what = "<fg=magenta>HEAD</fg=magenta>";
    }

    writeln("✈︎ Deploying $what on <fg=cyan>{{hostname}}</fg=cyan>");
})
->shallow()
->setPrivate();


desc('Preparing host for deploy');
task('deploy:prepare', function () {
    // Check if shell is POSIX-compliant
    $result = run('echo $0');
    if (!str_contains($result, 'bash') && !str_contains($result, 'sh')) {
        throw new \RuntimeException(
            'Shell on your server is not POSIX-compliant. Please change to sh, bash or similar.'
        );
    }
    run('if [ ! -d {{deploy_path}} ]; then mkdir -p {{deploy_path}}; fi');
    // Check for existing /current directory (not symlink)
    $result = test('[ ! -L {{deploy_path}}/current ] && [ -d {{deploy_path}}/current ]');
    if ($result) {
        throw new Exception('There already is a directory (not symlink) named "current" in ' . get('deploy_path') . '. Remove this directory so it can be replaced with a symlink for atomic deployments.');
    }
    // Create metadata .dep dir.
    run("cd {{deploy_path}} && if [ ! -d .dep ]; then mkdir .dep; fi");
    // Create releases dir.
    run("cd {{deploy_path}} && if [ ! -d releases ]; then mkdir releases; fi");
    // Create shared dir.
    run("cd {{deploy_path}} && if [ ! -d shared ]; then mkdir shared; fi");
});


desc('Lock deploy');
task('deploy:lock', function () {
    $locked = test("[ -f {{deploy_path}}/.dep/deploy.lock ]");
    if ($locked) {
        $stage = input()->hasArgument('stage') ? ' ' . input()->getArgument('stage') : '';
        throw new GracefulShutdownException(
            "Deploy locked.\n" .
            sprintf('Execute "dep deploy:unlock%s" to unlock.', $stage)
        );
    } else {
        run("touch {{deploy_path}}/.dep/deploy.lock");
    }
});


desc('Prepare release. Clean up unfinished releases and prepare next release');
task('deploy:release', function () {
    cd('{{deploy_path}}');

    // Clean up if there is unfinished release
    $previousReleaseExist = test('[ -h release ]');

    if ($previousReleaseExist) {
        run('rm -rf "$(readlink release)"'); // Delete release
        run('rm release'); // Delete symlink
    }

    // We need to get releases_list at same point as release_name,
    // as standard release_name's implementation depends on it and,
    // if user overrides it, we need to get releases_list manually.
    $releasesList = get('releases_list');
    $releaseName = get('release_name');

    // Fix collisions
    $i = 0;
    while (test("[ -d {{deploy_path}}/releases/$releaseName ]")) {
        $releaseName .= '.' . ++$i;
        set('release_name', $releaseName);
    }

    $releasePath = parse("{{deploy_path}}/releases/{{release_name}}");

    // Metainfo.
    $date = run('date +"%Y%m%d%H%M%S"');

    // Save metainfo about release
    run("echo '$date,{{release_name}}' >> .dep/releases");

    // Make new release
    run("mkdir $releasePath");
    run("{{bin/symlink}} $releasePath {{deploy_path}}/release");

    // Add to releases list
    array_unshift($releasesList, $releaseName);
    set('releases_list', $releasesList);

    // Set previous_release
    if (isset($releasesList[1])) {
        set('previous_release', "{{deploy_path}}/releases/{$releasesList[1]}");
    }
});


desc('Update code');
task('deploy:update_code', function () {
    $repository = get('repository');
    $branch = get('branch');
    $git = get('bin/git');
    $gitCache = get('git_cache');
    $recursive = get('git_recursive', true) ? '--recursive' : '';
    $dissociate = get('git_clone_dissociate', true) ? '--dissociate' : '';
    $quiet = isQuiet() ? '-q' : '';
    $depth = $gitCache ? '' : '--depth 1';
    $options = [
        'tty' => get('git_tty', false),
    ];

    $at = '';
    if (!empty($branch)) {
        $at = "-b $branch";
    }

    // If option `tag` is set
    if (input()->hasOption('tag')) {
        $tag = input()->getOption('tag');
        if (!empty($tag)) {
            $at = "-b $tag";
        }
    }

    // If option `tag` is not set and option `revision` is set
    if (empty($tag) && input()->hasOption('revision')) {
        $revision = input()->getOption('revision');
        if (!empty($revision)) {
            $depth = '';
        }
    }

    // Enter deploy_path if present
    if (has('deploy_path')) {
        cd('{{deploy_path}}');
    }

    if ($gitCache && has('previous_release')) {
        try {
            run("$git clone $at $recursive $quiet --reference {{previous_release}} $dissociate $repository  {{release_path}} 2>&1", $options);
        } catch (\Throwable $exception) {
            // If {{deploy_path}}/releases/{$releases[1]} has a failed git clone, is empty, shallow etc, git would throw error and give up. So we're forcing it to act without reference in this situation
            run("$git clone $at $recursive $quiet $repository {{release_path}} 2>&1", $options);
        }
    } else {
        // if we're using git cache this would be identical to above code in catch - full clone. If not, it would create shallow clone.
        run("$git clone $at $depth $recursive $quiet $repository {{release_path}} 2>&1", $options);
    }

    if (!empty($revision)) {
        run("cd {{release_path}} && $git checkout $revision");
    }
});


desc('Cleaning up files and/or directories');
task('deploy:clear_paths', function () {
    $paths = get('clear_paths');
    $sudo  = get('clear_use_sudo') ? 'sudo' : '';

    foreach ($paths as $path) {
        run("$sudo rm -rf {{release_path}}/$path");
    }
});


desc('Creating symlinks for shared files and dirs');
task('deploy:shared', function () {
    $sharedPath = "{{deploy_path}}/shared";

    // Validate shared_dir, find duplicates
    foreach (get('shared_dirs') as $a) {
        foreach (get('shared_dirs') as $b) {
            if ($a !== $b && strpos(rtrim($a, '/') . '/', rtrim($b, '/') . '/') === 0) {
                throw new Exception("Can not share same dirs `$a` and `$b`.");
            }
        }
    }

    foreach (get('shared_dirs') as $dir) {
        // Check if shared dir does not exist.
        if (!test("[ -d $sharedPath/$dir ]")) {
            // Create shared dir if it does not exist.
            run("mkdir -p $sharedPath/$dir");

            // If release contains shared dir, copy that dir from release to shared.
            if (test("[ -d $(echo {{release_path}}/$dir) ]")) {
                run("cp -rv {{release_path}}/$dir $sharedPath/" . dirname(parse($dir)));
            }
        }

        // Remove from source.
        run("rm -rf {{release_path}}/$dir");

        // Create path to shared dir in release dir if it does not exist.
        // Symlink will not create the path and will fail otherwise.
        run("mkdir -p `dirname {{release_path}}/$dir`");

        // Symlink shared dir to release dir
        run("{{bin/symlink}} $sharedPath/$dir {{release_path}}/$dir");
    }

    foreach (get('shared_files') as $file) {
        $dirname = dirname(parse($file));

        // Create dir of shared file if not existing
        if (!test("[ -d {$sharedPath}/{$dirname} ]")) {
            run("mkdir -p {$sharedPath}/{$dirname}");
        }

        // Check if shared file does not exist in shared.
        // and file exist in release
        if (!test("[ -f $sharedPath/$file ]") && test("[ -f {{release_path}}/$file ]")) {
            // Copy file in shared dir if not present
            run("cp -rv {{release_path}}/$file $sharedPath/$file");
        }

        // Remove from source.
        run("if [ -f $(echo {{release_path}}/$file) ]; then rm -rf {{release_path}}/$file; fi");

        // Ensure dir is available in release
        run("if [ ! -d $(echo {{release_path}}/$dirname) ]; then mkdir -p {{release_path}}/$dirname;fi");

        // Touch shared
        run("touch $sharedPath/$file");

        // Symlink shared dir to release dir
        run("{{bin/symlink}} $sharedPath/$file {{release_path}}/$file");
    }
});


desc('Make writable dirs');
task('deploy:writable', function () {
    $dirs = join(' ', get('writable_dirs'));
    $mode = get('writable_mode');
    $sudo = get('writable_use_sudo') ? 'sudo' : '';
    $httpUser = get('http_user', false);
    $runOpts = [];
    if ($sudo) {
        $runOpts['tty'] = get('writable_tty', false);
    }

    if (empty($dirs)) {
        return;
    }

    if ($httpUser === false && ! in_array($mode, ['chgrp', 'chmod'], true)) {
        // Attempt to detect http user in process list.
        $httpUserCandidates = explode("\n", run("ps axo comm,user | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | sort | awk '{print \$NF}' | uniq"));
        if (count($httpUserCandidates)) {
            $httpUser = array_shift($httpUserCandidates);
        }

        if (empty($httpUser)) {
            throw new \RuntimeException(
                "Can't detect http user name.\n" .
                "Please setup `http_user` config parameter."
            );
        }
    }

    try {
        cd('{{release_path}}');

        // Create directories if they don't exist
        run("mkdir -p $dirs");

        $recursive = get('writable_recursive') ? '-R' : '';

        if ($mode === 'chown') {
            // Change owner.
            // -R   operate on files and directories recursively
            // -L   traverse every symbolic link to a directory encountered
            run("$sudo chown -L $recursive $httpUser $dirs", $runOpts);
        } elseif ($mode === 'chgrp') {
            // Change group ownership.
            // -R   operate on files and directories recursively
            // -L   if a command line argument is a symbolic link to a directory, traverse it
            $httpGroup = get('http_group', false);
            if ($httpGroup === false) {
                throw new \RuntimeException("Please setup `http_group` config parameter.");
            }
            run("$sudo chgrp -H $recursive $httpGroup $dirs", $runOpts);
        } elseif ($mode === 'chmod') {
            // in chmod mode, defined `writable_chmod_recursive` has priority over common `writable_recursive`
            if (is_bool(get('writable_chmod_recursive'))) {
                $recursive = get('writable_chmod_recursive') ? '-R' : '';
            }
            run("$sudo chmod $recursive {{writable_chmod_mode}} $dirs", $runOpts);
        } elseif ($mode === 'acl') {
            if (strpos(run("chmod 2>&1; true"), '+a') !== false) {
                // Try OS-X specific setting of access-rights

                run("$sudo chmod +a \"$httpUser allow delete,write,append,file_inherit,directory_inherit\" $dirs", $runOpts);
                run("$sudo chmod +a \"`whoami` allow delete,write,append,file_inherit,directory_inherit\" $dirs", $runOpts);
            } elseif (commandExist('setfacl')) {
                if (!empty($sudo)) {
                    run("$sudo setfacl -L $recursive -m u:\"$httpUser\":rwX -m u:`whoami`:rwX $dirs", $runOpts);
                    run("$sudo setfacl -dL $recursive -m u:\"$httpUser\":rwX -m u:`whoami`:rwX $dirs", $runOpts);
                } else {
                    // When running without sudo, exception may be thrown
                    // if executing setfacl on files created by http user (in directory that has been setfacl before).
                    // These directories/files should be skipped.
                    // Now, we will check each directory for ACL and only setfacl for which has not been set before.
                    $writeableDirs = get('writable_dirs');
                    foreach ($writeableDirs as $dir) {
                        // Check if ACL has been set or not
                        $hasfacl = run("getfacl -p $dir | grep \"^user:$httpUser:.*w\" | wc -l");
                        // Set ACL for directory if it has not been set before
                        if (!$hasfacl) {
                            run("setfacl -L $recursive -m u:\"$httpUser\":rwX -m u:`whoami`:rwX $dir");
                            run("setfacl -dL $recursive -m u:\"$httpUser\":rwX -m u:`whoami`:rwX $dir");
                        }
                    }
                }
            } else {
                throw new \RuntimeException("Can't set writable dirs with ACL.");
            }
        } else {
            throw new \RuntimeException("Unknown writable_mode `$mode`.");
        }
    } catch (\RuntimeException $e) {
        $formatter = Deployer::get()->getHelper('formatter');

        $errorMessage = [
            "Unable to setup correct permissions for writable dirs.                  ",
            "You need to configure sudo's sudoers files to not prompt for password,",
            "or setup correct permissions manually.                                  ",
        ];
        write($formatter->formatBlock($errorMessage, 'error', true));

        throw $e;
    }
});



desc('Installing vendors');
task('deploy:vendors', function () {
    if (!commandExist('unzip')) {
        writeln('<comment>To speed up composer installation setup "unzip" command with PHP zip extension https://goo.gl/sxzFcD</comment>');
    }
    run('cd {{release_path}} && {{bin/composer}} {{composer_options}}');
});



desc('Creating symlink to release');
task('deploy:symlink', function () {
    if (get('use_atomic_symlink')) {
        run("mv -T {{deploy_path}}/release {{deploy_path}}/current");
    } else {
        // Atomic symlink does not supported.
        // Will use simple≤ two steps switch.
        run("cd {{deploy_path}} && {{bin/symlink}} {{release_path}} current"); // Atomic override symlink.
        run("cd {{deploy_path}} && rm release"); // Remove release link.
    }
});


desc('Unlock deploy');
task('deploy:unlock', function () {
    run("rm -f {{deploy_path}}/.dep/deploy.lock");//always success
});

desc('Copy assets');
task('deploy:assets', function () {
	foreach(get('assets') as $dir) {
		$backup = $dir . '.previous';
		// remove previous dir backup
		run("if [ -f $(echo {{assets_path}}/$backup) ]; then rm -rf {{assets_path}}/$backup; fi");
		// copy each dir as backup
		run("cd {{assets_path}} && cp -rpf {{assets_path}}/$dir {{assets_path}}/$backup");
		// copy/ owerwrite with newest release
		run("cd {{assets_path}} && cp -rpf {{release_path}}/public/$dir {{assets_path}}");
	}
})->desc('Updating assets');


desc('Cleaning up old releases');
task('cleanup', function () {
    $releases = get('releases_list');
    $keep = get('keep_releases');
    $sudo  = get('cleanup_use_sudo') ? 'sudo' : '';
    $runOpts = [];
    if ($sudo) {
        $runOpts['tty'] = get('cleanup_tty', false);
    }

    if ($keep === -1) {
        // Keep unlimited releases.
        return;
    }

    while ($keep > 0) {
        array_shift($releases);
        --$keep;
    }

    foreach ($releases as $release) {
        run("$sudo rm -rf {{deploy_path}}/releases/$release", $runOpts);
    }

    run("cd {{deploy_path}} && if [ -e release ]; then $sudo rm release; fi", $runOpts);
    run("cd {{deploy_path}} && if [ -h release ]; then $sudo rm release; fi", $runOpts);
});


desc('Copy directories');
task('deploy:copy_dirs', function () {
    if (has('previous_release')) {
        foreach (get('copy_dirs') as $dir) {
            if (test("[ -d {{previous_release}}/$dir ]")) {
                run("mkdir -p {{release_path}}/$dir");
                run("rsync -av {{previous_release}}/$dir/ {{release_path}}/$dir");
            }
        }
    }
});


desc('Rollback to previous release');
task('rollback', function () {
    $releases = get('releases_list');

    if (isset($releases[1])) {
        $releaseDir = "{{deploy_path}}/releases/{$releases[1]}";

        // Symlink to old release.
        run("cd {{deploy_path}} && {{bin/symlink}} $releaseDir current");

        // Remove release
        run("rm -rf {{deploy_path}}/releases/{$releases[0]}");

        if (isVerbose()) {
            writeln("Rollback to `{$releases[1]}` release was successful.");
        }
    } else {
        writeln("<comment>No more releases you can revert to.</comment>");
    }
});


task('success', function () {
    writeln('<info>Successfully deployed!</info>');
})
->local()
->shallow()
->setPrivate();


task('deploy:failed', function () {
	writeln('<info>Deploy failed</info>');
})
->setPrivate();



desc('Deploy project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:writable',
		'deploy:symlink',
		'deploy:assets',
    'deploy:unlock',
    'cleanup',
]);
fail('deploy', 'deploy:failed');
after('deploy', 'success');

// task('deploy', [
// 	'deploy:prepare',
// 	'deploy:release',
// 	'deploy:update_code',
// 	'deploy:vendors',
// 	'deploy:shared',
// 	'deploy:writable',
// 	'deploy:executable',
// 	'deploy:symlink',
// 	'deploy:assets',
// 	'cleanup',
// 	'deploy:resetcache',
// ])->desc('Deploy your project');