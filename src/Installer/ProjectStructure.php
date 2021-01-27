<?php

namespace Cratespace\Preflight\Installer;

use Illuminate\Filesystem\Filesystem;

class ProjectStructure
{
    /**
     * Perform project file structure restructuring.
     *
     * @return void
     */
    public static function restructureProjectDirectory(): void
    {
        if (file_exists($phpunit = base_path('phpunit.xml'))) {
            unlink($phpunit);

            rename(__DIR__ . '/../../stubs/phpunitconfig.xml', $phpunit);
        }

        if (file_exists($envExample = base_path('.env.example'))) {
            unlink($envExample);

            copy(__DIR__ . '/../../stubs/.env.example', $envExample);
        }

        if (file_exists($envFile = base_path('.env'))) {
            unlink($envFile);

            copy($envExample, $envFile);
        }

        if (file_exists($styleCi = base_path('.styleci.yml'))) {
            unlink($styleCi);
        }

        if (file_exists(resource_path('views/welcome.blade.php'))) {
            chmod(resource_path('views/welcome.blade.php'), 0644);

            unlink(resource_path('views/welcome.blade.php'));
        }

        // Remove README...
        if (file_exists(base_path('README.md'))) {
            unlink(base_path('README.md'));
        }

        static::replaceInFile(
            '// \Illuminate\Session\Middleware\AuthenticateSession::class',
            '\Cratespace\Preflight\Http\Middleware\AuthenticateSession::class',
            app_path('Http/Kernel.php')
        );

        static::replaceInFile('auth:api', 'auth:sanctum', base_path('routes/api.php'));

        (new Filesystem())->deleteDirectory(resource_path('sass'));
    }

    /**
     * Replace a given string within a given file.
     *
     * @param string $search
     * @param string $replace
     * @param string $path
     *
     * @return void
     */
    public static function replaceInFile(string $search, string $replace, string $path): void
    {
        file_put_contents($path, str_replace($search, $replace, file_get_contents($path)));
    }
}