<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/12/2018
 * Time: 6:16 PM
 */

namespace App\Libraries;


class MyCachedStrageAdapter extends \League\Flysystem\Cached\Storage\Adapter
{
    use \Hypweb\Flysystem\Cached\Extra\Hasdir;
    use \Hypweb\Flysystem\Cached\Extra\DisableEnsureParentDirectories;
}
