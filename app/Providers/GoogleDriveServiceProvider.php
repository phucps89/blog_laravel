<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/12/2018
 * Time: 10:25 AM
 */

namespace App\Providers;

use App\Libraries\Helpers;
use App\Libraries\MyCachedStrageAdapter;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use Illuminate\Support\ServiceProvider;


class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $client = new \Google_Client();
        $client->setClientId(env('GOOGLE_DRIVE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_DRIVE_CLIENT_SECRET'));
        $client->refreshToken(env('GOOGLE_DRIVE_REFRESH_TOKEN'));

        \Storage::extend('google', function($app, $config) use ($client) {
            $service = new \Google_Service_Drive($client);
            $options = [];
            if(isset($config['teamDriveId'])) {
                $options['teamDriveId'] = $config['teamDriveId'];
            }
            $adapter = new GoogleDriveAdapter($service, $config['folderId'], $options);

            return new \League\Flysystem\Filesystem($adapter);
        });

        $googleDrive = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter(
            new \Google_Service_Drive($client), // Client service
            env('GOOGLE_DRIVE_FOLDER_ID'),                             // Folder ID as root ('root' or Folder ID)
            [ 'useHasDir' => true ]             // options (elFinder need hasDir method)
        );

        $cache = new MyCachedStrageAdapter (
            new \League\Flysystem\Adapter\Local('flycache'),
            'gdcache',
            300
        );

        // Flysystem cached adapter
        $adapter = new \League\Flysystem\Cached\CachedAdapter(
            $googleDrive,
            $cache
        );

        config([
            'elfinder.roots' => [
                [
                    'driver'       => 'FlysystemExt',
                    'filesystem'   => new \League\Flysystem\Filesystem($adapter),
                    'fscache'      => null,
                    'separator'    => '/',
                    // optional
                    'alias'        => 'GoogleDrive',
                    'rootCssClass' => 'elfinder-navbar-root-googledrive'
                ]
            ]
        ]);
    }
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
