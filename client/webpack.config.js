function getEntrySources(sources) {
    if (process.env.NODE_ENV !== 'production') {
        sources.push('webpack-dev-server/client?http://localhost:8888');
    }
    return sources;
}

module.exports = {
    entry: {
        bundle: './entry'
    },
    output: {
        path: __dirname,
        filename: '[name].js'
    },
    devServer: {
        port: 8888
    },
    module: {       // modules - options affecting the normal modules
        loaders: [  // loaders - array of automatically applied loaders
            {
                test: /\.css$/, 
                loader: ["style!css"]
            },
            {
                test: /\.json$/, 
                loader: 'json-loader',
            },
            {
                test: /\.js$/,
                exclude: /(node_modules)/,
                loader: 'babel-loader',
                query: {
                    presets: ['es2015']
                }
            }
        ]
    },
    resolve: {
        extensions: ['', '.webpack.js', '.web.js', '.js']
    },
    node: {
        // console: 'empty',            // causes a 'console.log is not a function errr'
        fs: 'empty',
        net: 'empty',
        tls: 'empty'
    }
};