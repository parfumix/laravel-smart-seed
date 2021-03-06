<?php namespace LaravelSeed\Providers;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use LaravelSeed\Contracts\ProviderInterface;
use LaravelSeed\Exceptions\SeederException;
use Symfony\Component\Yaml\Parser;

class YamlProvider extends AbstractProvider implements ProviderInterface {

    protected $ext = 'yaml';

    /**
     * Return an array of data to be parsed ...
     *
     * @param string $source
     * @param Collection $seeded
     * @return array|mixed
     */
    public function getData($source = '', Collection $seeded) {
        $files = $source ? getFullPathSource($source, $this) : getFilesFromPathByEnv( $this );

        $diffFiles = getDiffFiles($files , $seeded, $this);

        return self::parseYamlFiles($diffFiles);
    }

    /**
     * Make source file ..
     *
     * @param Command $command
     * @throws SeederException
     * @return bool|mixed
     */
    public function create(Command $command) {
        $path = self::getConfig('path');

        if( ! File::isWritable( $path  ) )
            throw new SeederException('Path are not writable. Please chmod!');

        $source = explode(',', self::getSource());

        array_walk($source, function($name) use($path, $command) {
            $model = 'App\\' . ucfirst(strtolower( $name ));

            if( ! isEloquentExists($model) ) {
                $command->error(sprintf('Model %s not exists. Skipped!', $model));
                return false;
            }

            $fullPath = getFullPathSource($name, $this);

            if( File::exists($fullPath)) {
                $command->error(sprintf('Model %s already exists. Skipped!', $name));
                return false;
            }

            File::put( $fullPath, arrayToYaml([
                    'class'  => ucfirst($name),
                    'source' => arrayToYaml(
                        getTableSchema($model), 1
                    )
                ], 1
            ));

            $command->info(sprintf('File %s created successfully!', $name));
        });
    }

    /**
     * Parse yaml files in path ..
     *
     * @param array $files
     * @param string $path
     * @return \Illuminate\Support\Collection
     */
    private function parseYamlFiles(array $files = [], $path = '') {
        if(! $path)
            $path = self::getConfig('path');

        $yaml      = new Parser;

        return collect(
            array_map(function($file) use($yaml, $path) {
                if( File::exists($file ))
                    return $yaml->parse( File::get($file) ) + ['hash' => filemtime($file)];
            }, $files)
        );
    }
}
