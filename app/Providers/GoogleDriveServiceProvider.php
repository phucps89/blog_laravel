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
        \Storage::extend('google', function($app, $config) {
            $client = new \Google_Client();
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);

            $googleDrive = new \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter(
                new \Google_Service_Drive($client), // Client service
                $config['folderId'],                             // Folder ID as root ('root' or Folder ID)
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
            return new \League\Flysystem\Filesystem($adapter);
        });

        config([
            'elfinder.roots' => [
                [
                    'driver'       => 'FlysystemExt',
                    'filesystem'   => Helpers::getStorage()->getDriver(),
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
