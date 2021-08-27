const params = {
    env: process.env.NODE_ENV || 'development'
};

const path = require('path');
const webpack = require('webpack');

const plugins = {
    extText: require("extract-text-webpack-plugin"),
    svgStore: require('webpack-svgstore-plugin'),
    cssOptimize: require('optimize-css-assets-webpack-plugin'),
    jsOptimize: require('uglifyjs-webpack-plugin')
};

const VueLoaderPlugin = require('vue-loader/lib/plugin');

const postCss = {
    postcssPresetEnv: require('postcss-preset-env'),
    import: require('postcss-import'),
    nested: require('postcss-nested'),
    flexbugs: require("postcss-flexbugs-fixes"),
    inputStyle: require("postcss-input-style"),
    objectFit: require("postcss-object-fit-images"),
    gradientFix: require('postcss-gradient-transparency-fix'),
    extend: require('postcss-extend'),
};

module.exports = {
    context: __dirname + "/app",
    entry: {
        c: './app-crit.js',
        m: './app.js'
    },
    stats: {children: false},
    
    mode: params.env,
    watch: params.env == 'development',
    
    watchOptions: {
        aggregateTimeout: 50
    },
    
    performance: {
        hints: false
    },
    
    resolve: {
        modules: [
            (path.resolve(__dirname)),
            (path.resolve(__dirname) + '/vendor'),
            (path.resolve(__dirname) + '/node_modules')
        ],
        extensions: ['*', '.js', '.css', '.vue'],
        alias: {
            'vue': 'vue/dist/vue.common.js',
        },
    },
    
    resolveLoader: {
        modules: [
            path.resolve(__dirname),
            path.resolve(__dirname) + '/node_modules',
            path.resolve(__dirname) + '/node_modules'
        ]
    },
    
    output: {
        path: path.resolve(__dirname + '/build'),
        publicPath: '/local/client/build/',
        filename: '[name].js',
        library: 'A[name]'
    },
    
    module: {
        rules: [
            {
                test: /\.css$/,
                use: plugins.extText.extract({
                    fallback: "style-loader",
                    use: [
                        {
                            loader: 'css-loader',
                            options: {importLoaders: 1}
                        },
                        
                        {
                            loader: 'postcss-loader',
                            options: {
                                plugins: () => [
                                    postCss.import(),
                                    postCss.nested(),
                                    postCss.flexbugs(),
                                    postCss.inputStyle(),
                                    postCss.objectFit(),
                                    postCss.extend(),
                                    postCss.gradientFix(),
                                    postCss.postcssPresetEnv({
                                        stage: 0,
                                        preserve: false
                                    })
                                ]
                            }
                        }
                    ]
                })
            },

            {
                test: /\.vue$/,
                loader: 'vue-loader',
                exclude: /(node_modules|vendor)/,

            },
            
            {
                test: /\.js$/,
                exclude: /(node_modules|vendor)/,
                loader: 'babel-loader',
                query: {
                    cacheDirectory: true,
                    presets: [
                      'babel-preset-env',
                      'babel-preset-stage-0'
                    ]
                }
            },
            
            {
                test: /\.(svg)$/,
                use: [
                    {
                        loader: 'url-loader',
                        options: {
                            limit: 100000
                        }
                    }
                ]
            }
        ]
    },
    
    plugins: [
        new plugins.extText('[name].css',
            {
                allChunks: true
            }),
        
        new plugins.svgStore(
            {
                svgoOptions: {
                    plugins: [
                        {removeTitle: true}
                    ]
                }
            }
        ),


        new webpack.ProvidePlugin({
            _: "underscore",
            'vanillaTextMask': "vanilla-text-mask/dist/vanillaTextMask.js"
        }),

        new VueLoaderPlugin(),

        
        new webpack.ProvidePlugin({
            Swiper: 'swiper/dist/js/swiper.min.js',
            vanillaTextMask: "vanilla-text-mask/dist/vanillaTextMask.js"
        }),
        
        new webpack.ProvidePlugin({
            $: "jquery",
            jQuery: "jquery"
        })
    ]
};

if (params.env == 'production') {
    
    module.exports.plugins.push(
        new plugins.jsOptimize()
    );
    
    module.exports.plugins.push(
        new plugins.cssOptimize({
            cssProcessor: require('cssnano'),
        })
    );
}
