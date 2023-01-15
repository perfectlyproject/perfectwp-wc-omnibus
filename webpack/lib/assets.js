let md5 = require('js-md5');

class WpAssets {
    apply(compiler) {
        compiler.hooks.emit.tapAsync(
            'WpAssets',
            (compilation, callback) => {
                let phpScript = [`<?php 
                    use PerfectWPWCO\\Plugin;
                    use PerfectWPWCO\\Assets\\Style;
                    use PerfectWPWCO\\Assets\\Script;
                    use PerfectWPWCO\\Assets\\AbstractAsset;
                    
                    $assets = [
                        'scripts' => [],
                        'styles' => []
                    ];  
                `];

                let enqueue = [];
                compilation.chunks.forEach((chunk, key) => {
                    let viewType = 'AbstractAsset::VIEW_TYPE_FRONT';

                    if (/admin/.test(chunk.name)) {
                        viewType = 'AbstractAsset::VIEW_TYPE_ADMIN';
                    }
                    if (/blocks/.test(chunk.name)) {
                        viewType = 'AbstractAsset::VIEW_TYPE_GUTENBERG';
                    }

                    chunk.files.forEach((filename) => {

                        if (/\.js$/.test(filename)) {
                            enqueue.push(`
                                $assets['scripts'][] = (new Script)
                                    ->setViewType(${viewType})
                                    ->setHandle('${md5(filename)}')
                                    ->setSrc(Plugin::getInstance()->publicUrl('${filename}'));
                            `);
                        } else if (/\.css$/.test(filename)) {
                            enqueue.push(`
                                $assets['styles'][] = (new Style)
                                    ->setViewType(${viewType})
                                    ->setHandle('${md5(filename)}')
                                    ->setSrc(Plugin::getInstance()->publicUrl('${filename}'));
                            `);
                        }
                    });
                });

                phpScript = phpScript.concat(enqueue);
                phpScript.push(`return $assets;`);

                compilation.assets['../public/plugin-assets.php'] = {
                    source: function () {
                        return phpScript.join('\n');
                    },
                    size: function () {
                        return phpScript.join('\n').length;
                    }
                };

                callback();
            },
        );
    }
}

module.exports = WpAssets;